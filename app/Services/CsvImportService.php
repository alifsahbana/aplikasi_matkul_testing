<?php

namespace App\Services;

use App\Models\TobaccoUmkm;
use App\Models\District;
use App\Models\Category;
use App\Models\ImportLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CsvImportService
{
    /**
     * Header CSV yang valid dan didukung
     */
    public const REQUIRED_HEADERS = [
        'kode_umkm',
        'nama_umkm',
        'nama_pemilik',
        'kategori',
        'kecamatan',
        'desa',
        'alamat',
        'latitude',
        'longitude',
        'luas_lahan_ha',
        'kapasitas_produksi',
        'satuan_produksi',
        'produksi_aktual',
        'periode_produksi',
        'jumlah_tenaga_kerja',
        'nilai_transaksi',
        'status_usaha',
        'kontak',
    ];

    /**
     * Pratinjau 5 baris pertama CSV sebelum impor
     */
    public function previewCsv(UploadedFile $file): array
    {
        $rows = [];
        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            throw new \Exception("Gagal membuka berkas CSV.");
        }

        $header = fgetcsv($handle, 2000, ',');
        if (!$header) {
            fclose($handle);
            throw new \Exception("Berkas CSV kosong atau format header tidak valid.");
        }

        // Normalisasi header (lowercase, trim)
        $normalizedHeader = array_map(function ($h) {
            return trim(strtolower(str_replace([' ', '-'], '_', $h)));
        }, $header);

        $lineCount = 0;
        while (($data = fgetcsv($handle, 2000, ',')) !== false && $lineCount < 5) {
            if (count($data) === count($normalizedHeader)) {
                $rows[] = array_combine($normalizedHeader, $data);
                $lineCount++;
            }
        }

        fclose($handle);

        return [
            'headers' => $normalizedHeader,
            'preview_rows' => $rows,
            'sample_count' => count($rows),
        ];
    }

    /**
     * Proses Impor Berkas CSV dengan Transaksi dan Validasi Ketat
     */
    public function import(UploadedFile $file, ?int $userId = null): array
    {
        $handle = fopen($file->getRealPath(), 'r');
        if ($handle === false) {
            throw new \Exception("Gagal membuka berkas CSV.");
        }

        $rawHeader = fgetcsv($handle, 2000, ',');
        if (!$rawHeader) {
            fclose($handle);
            throw new \Exception("Berkas CSV kosong.");
        }

        $header = array_map(function ($h) {
            return trim(strtolower(str_replace([' ', '-'], '_', $h)));
        }, $rawHeader);

        // Ambil data referensi
        $districts = District::all()->keyBy(function ($d) {
            return strtolower(trim($d->name));
        });

        $categories = Category::all()->keyBy(function ($c) {
            return strtolower(trim($c->name));
        });

        $lineNumber = 1;
        $successCount = 0;
        $failedCount = 0;
        $errors = [];
        $recordsToInsert = [];

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle, 2000, ',')) !== false) {
                $lineNumber++;

                // Lewati baris kosong
                if (empty(array_filter($row))) {
                    continue;
                }

                if (count($row) !== count($header)) {
                    $failedCount++;
                    $errors[] = [
                        'line' => $lineNumber,
                        'reason' => "Jumlah kolom tidak cocok (ditemukan " . count($row) . ", diharapkan " . count($header) . ")",
                    ];
                    continue;
                }

                $data = array_combine($header, $row);

                // Validasi data baris
                $rowValidator = Validator::make($data, [
                    'kode_umkm' => 'required|string|max:50',
                    'nama_umkm' => 'required|string|max:200',
                    'kategori' => 'required|string',
                    'kecamatan' => 'required|string',
                    'latitude' => 'required|numeric',
                    'longitude' => 'required|numeric',
                    'kapasitas_produksi' => 'nullable|numeric|min:0',
                    'produksi_aktual' => 'nullable|numeric|min:0',
                    'jumlah_tenaga_kerja' => 'nullable|integer|min:0',
                    'nilai_transaksi' => 'nullable|numeric|min:0',
                ]);

                if ($rowValidator->fails()) {
                    $failedCount++;
                    $errors[] = [
                        'line' => $lineNumber,
                        'reason' => implode(', ', $rowValidator->errors()->all()),
                    ];
                    continue;
                }

                $lat = (float) $data['latitude'];
                $lng = (float) $data['longitude'];

                // Validasi spasial batas koordinat Pamekasan
                if (!TobaccoUmkm::isValidCoordinate($lat, $lng)) {
                    $failedCount++;
                    $errors[] = [
                        'line' => $lineNumber,
                        'reason' => "Koordinat [{$lat}, {$lng}] di luar batas geografis Kabupaten Pamekasan (Lintang: -7.35 s/d -6.95, Bujur: 103.35 s/d 103.65)",
                    ];
                    continue;
                }

                // Cek eksistensi Kecamatan
                $distKey = strtolower(trim($data['kecamatan']));
                $district = $districts->get($distKey);
                if (!$district) {
                    $failedCount++;
                    $errors[] = [
                        'line' => $lineNumber,
                        'reason' => "Kecamatan '{$data['kecamatan']}' tidak ditemukan dalam basis data Kabupaten Pamekasan.",
                    ];
                    continue;
                }

                // Cek eksistensi Kategori
                $catKey = strtolower(trim($data['kategori']));
                $category = $categories->get($catKey);
                if (!$category) {
                    // Cari alternatif parsial
                    $matchedCat = $categories->first(function ($c) use ($catKey) {
                        return str_contains($catKey, strtolower($c->name)) || str_contains(strtolower($c->name), $catKey);
                    });

                    if ($matchedCat) {
                        $category = $matchedCat;
                    } else {
                        $failedCount++;
                        $errors[] = [
                            'line' => $lineNumber,
                            'reason' => "Kategori rantai pasok '{$data['kategori']}' tidak terdaftar.",
                        ];
                        continue;
                    }
                }

                $code = trim($data['kode_umkm']);

                // Deteksi Duplikasi
                $exists = TobaccoUmkm::where('code', $code)->exists();
                if ($exists) {
                    $failedCount++;
                    $errors[] = [
                        'line' => $lineNumber,
                        'reason' => "Duplikat kode UMKM '{$code}' sudah ada di database.",
                    ];
                    continue;
                }

                $businessStatus = strtolower(trim($data['status_usaha'] ?? 'aktif'));
                if (!in_array($businessStatus, ['aktif', 'musiman', 'non-aktif'])) {
                    $businessStatus = 'aktif';
                }

                $newRecord = [
                    'code' => $code,
                    'name' => trim($data['nama_umkm']),
                    'owner_name' => trim($data['nama_pemilik'] ?? ''),
                    'category_id' => $category->id,
                    'district_id' => $district->id,
                    'address' => trim($data['alamat'] ?? '-'),
                    'village' => trim($data['desa'] ?? '-'),
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'land_area_ha' => (float) ($data['luas_lahan_ha'] ?? 0),
                    'production_capacity' => (float) ($data['kapasitas_produksi'] ?? 0),
                    'production_unit' => trim($data['satuan_produksi'] ?? 'Kg'),
                    'actual_production' => (float) ($data['produksi_aktual'] ?? 0),
                    'production_period' => trim($data['periode_produksi'] ?? 'Tahunan'),
                    'worker_count' => (int) ($data['jumlah_tenaga_kerja'] ?? 1),
                    'transaction_value' => (float) ($data['nilai_transaksi'] ?? 0),
                    'business_status' => $businessStatus,
                    'data_source' => 'Impor CSV: ' . $file->getClientOriginalName(),
                    'contact_phone' => trim($data['kontak'] ?? ''),
                    'is_verified' => true,
                    'created_by' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                TobaccoUmkm::create($newRecord);
                $successCount++;
            }

            fclose($handle);

            // Simpan audit log
            $status = $failedCount === 0 ? 'success' : ($successCount > 0 ? 'partial' : 'failed');
            $importLog = ImportLog::create([
                'user_id' => $userId,
                'original_filename' => $file->getClientOriginalName(),
                'total_rows' => $successCount + $failedCount,
                'success_rows' => $successCount,
                'failed_rows' => $failedCount,
                'error_details' => $errors,
                'status' => $status,
            ]);

            DB::commit();

            return [
                'log_id' => $importLog->id,
                'total' => $successCount + $failedCount,
                'success' => $successCount,
                'failed' => $failedCount,
                'errors' => $errors,
                'status' => $status,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            fclose($handle);
            throw $e;
        }
    }

    /**
     * Buat Berkas CSV Template DEMO Sintetis
     */
    public function generateDemoCsvContent(): string
    {
        $headers = self::REQUIRED_HEADERS;
        $rows = [
            [
                'DEMO-PMK-101',
                'Sentra Rajangan Tembakau Barurambat',
                'Ahmad Subandi',
                'Pengeringan & Pengolahan Daun',
                'Pamekasan',
                'Barurambat Timur',
                'Jl. Veteran No. 12',
                '-7.1590000',
                '103.4850000',
                '0.45',
                '8000',
                'Kg',
                '7200',
                'Musim 2026',
                '14',
                '450000000',
                'aktif',
                '081234567891',
            ],
            [
                'DEMO-KDR-102',
                'Kelompok Tani Tembakau Puncak Kadur',
                'K.H. Zainuddin',
                'Petani & Pembibitan Tembakau',
                'Kadur',
                'Kadur Timur',
                'Dusun Puncak RT 02/01',
                '-7.0940000',
                '103.5510000',
                '3.80',
                '7600',
                'Kg',
                '7100',
                'Musim 2026',
                '22',
                '530000000',
                'aktif',
                '081234567892',
            ],
            [
                'DEMO-PSN-103',
                'PR. Tembakau Pantura Segar (SKT)',
                'Mahfud Efendi',
                'Produsen Sigaret Kretek Tangan (SKT)',
                'Pasean',
                'Pasean Pesisir',
                'Jl. Pelabuhan Niaga No. 8',
                '-6.9140000',
                '103.5430000',
                '0.30',
                '500000',
                'Batang',
                '440000',
                'Bulanan',
                '20',
                '350000000',
                'aktif',
                '081234567893',
            ],
        ];

        $output = fopen('php://temp', 'r+');
        fputcsv($output, $headers);
        foreach ($rows as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }
}
