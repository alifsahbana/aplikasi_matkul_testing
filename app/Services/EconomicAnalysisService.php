<?php

namespace App\Services;

use App\Models\TobaccoUmkm;
use App\Models\District;
use App\Models\Category;
use Illuminate\Support\Collection;

class EconomicAnalysisService
{
    /**
     * Hitung metrik agregat utama untuk Dashboard
     */
    public function getSummaryMetrics(): array
    {
        $totalUmkm = TobaccoUmkm::count();
        $validCoords = TobaccoUmkm::whereBetween('latitude', [-7.3500000, -6.9500000])
            ->whereBetween('longitude', [103.3500000, 103.6500000])
            ->count();

        $activeUmkm = TobaccoUmkm::where('business_status', 'aktif')->count();
        $seasonalUmkm = TobaccoUmkm::where('business_status', 'musiman')->count();

        $totalCapacity = (float) TobaccoUmkm::sum('production_capacity');
        $totalProduction = (float) TobaccoUmkm::sum('actual_production');
        $totalWorkers = (int) TobaccoUmkm::sum('worker_count');
        $totalTransactions = (float) TobaccoUmkm::sum('transaction_value');

        $averageUtilization = $totalCapacity > 0
            ? round(($totalProduction / $totalCapacity) * 100, 2)
            : 0;

        return [
            'total_umkm' => $totalUmkm,
            'valid_coordinates_count' => $validCoords,
            'active_count' => $activeUmkm,
            'seasonal_count' => $seasonalUmkm,
            'total_capacity' => $totalCapacity,
            'total_production' => $totalProduction,
            'average_utilization' => $averageUtilization,
            'total_workers' => $totalWorkers,
            'total_transactions' => $totalTransactions,
        ];
    }

    /**
     * Data distribusi UMKM per kecamatan
     */
    public function getDistrictDistribution(): Collection
    {
        return District::withCount('umkms')
            ->with(['umkms' => function ($q) {
                $q->select('id', 'district_id', 'actual_production', 'production_capacity', 'worker_count', 'transaction_value');
            }])
            ->get()
            ->map(function ($district) {
                $count = $district->umkms_count;
                $totProd = (float) $district->umkms->sum('actual_production');
                $totCap = (float) $district->umkms->sum('production_capacity');
                $workers = (int) $district->umkms->sum('worker_count');
                $transactions = (float) $district->umkms->sum('transaction_value');

                $utilization = $totCap > 0 ? round(($totProd / $totCap) * 100, 2) : 0;

                return [
                    'id' => $district->id,
                    'code' => $district->code,
                    'name' => $district->name,
                    'area_km2' => $district->area_km2,
                    'umkm_count' => $count,
                    'total_production' => $totProd,
                    'total_capacity' => $totCap,
                    'capacity_utilization' => $utilization,
                    'total_workers' => $workers,
                    'total_transactions' => $transactions,
                ];
            })
            ->sortByDesc('umkm_count')
            ->values();
    }

    /**
     * Data komposisi per kategori usaha rantai pasok
     */
    public function getCategoryDistribution(): Collection
    {
        return Category::withCount('umkms')
            ->with(['umkms' => function ($q) {
                $q->select('id', 'category_id', 'actual_production', 'worker_count', 'transaction_value');
            }])
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'marker_color' => $cat->marker_color,
                    'icon' => $cat->icon,
                    'umkm_count' => $cat->umkms_count,
                    'total_production' => (float) $cat->umkms->sum('actual_production'),
                    'total_workers' => (int) $cat->umkms->sum('worker_count'),
                    'total_transactions' => (float) $cat->umkms->sum('transaction_value'),
                ];
            })
            ->sortByDesc('umkm_count')
            ->values();
    }

    /**
     * Analisis Potensi Ekonomi Mendalam:
     * Menghitung Indeks Potensi Komposit (CPI) dengan Formula Terbobot:
     * - Bobot Utilisasi Produksi: 30%
     * - Bobot Penyerapan Tenaga Kerja: 25%
     * - Bobot Perputaran Transaksi: 25%
     * - Bobot Diversifikasi Kategori: 20%
     *
     * Catatan Ilmiah SQA: Memisahkan FAKTA, ESTIMASI, dan REKOMENDASI.
     * Bila data UMKM pada suatu kecamatan < 2, diberikan status "Data Belum Memadai".
     */
    public function getEconomicAnalysis(): array
    {
        $districts = District::with(['umkms.category'])->get();

        $maxProd = TobaccoUmkm::groupBy('district_id')->selectRaw('SUM(actual_production) as total')->pluck('total')->max() ?: 1;
        $maxWorkers = TobaccoUmkm::groupBy('district_id')->selectRaw('SUM(worker_count) as total')->pluck('total')->max() ?: 1;
        $maxTrans = TobaccoUmkm::groupBy('district_id')->selectRaw('SUM(transaction_value) as total')->pluck('total')->max() ?: 1;
        $maxCats = Category::count() ?: 1;

        $rankings = $districts->map(function ($district) use ($maxProd, $maxWorkers, $maxTrans, $maxCats) {
            $umkms = $district->umkms;
            $count = $umkms->count();

            $totProd = (float) $umkms->sum('actual_production');
            $totCap = (float) $umkms->sum('production_capacity');
            $workers = (int) $umkms->sum('worker_count');
            $trans = (float) $umkms->sum('transaction_value');
            $distinctCategories = $umkms->pluck('category_id')->unique()->count();

            $utilization = $totCap > 0 ? round(($totProd / $totCap) * 100, 2) : 0;

            // Evaluasi Kuorum Validitas Statistik
            $isDataSufficient = ($count >= 2 && $totCap > 0);

            if (!$isDataSufficient) {
                return [
                    'district_id' => $district->id,
                    'district_name' => $district->name,
                    'is_sufficient' => false,
                    'status_label' => 'Data Belum Cukup',
                    'umkm_count' => $count,
                    'fact' => [
                        'umkm_count' => $count,
                        'total_workers' => $workers,
                        'total_production' => $totProd,
                        'actual_transactions' => $trans,
                    ],
                    'estimation' => [
                        'utilization_rate' => $utilization,
                        'potential_score' => 0,
                    ],
                    'recommendation' => 'Diperlukan survei pendataan dan pemetaan lapangan tambahan di Kecamatan ' . $district->name . ' untuk memenuhi batas minimum kuorum analitik.',
                    'score' => 0,
                ];
            }

            // Normalisasi Min-Max untuk 4 pilar indikator (skala 0 - 100)
            $normProd = min(100, ($totProd / $maxProd) * 100);
            $normWorkers = min(100, ($workers / $maxWorkers) * 100);
            $normTrans = min(100, ($trans / $maxTrans) * 100);
            $normDiv = min(100, ($distinctCategories / $maxCats) * 100);

            // Perhitungan Indeks Potensi Terbobot (Composite Potential Index)
            // 30% Produksi + 25% Tenaga Kerja + 25% Nilai Transaksi + 20% Diversifikasi
            $compositeScore = round(
                (0.30 * $normProd) +
                (0.25 * $normWorkers) +
                (0.25 * $normTrans) +
                (0.20 * $normDiv),
                2
            );

            // Rekomendasi Berbasis Rule Kebijakan Ilmiah
            $recommendations = [];
            if ($utilization < 70) {
                $recommendations[] = "Peningkatan efisiensi panen dan penyuluhan teknis penanganan pasca-panen daun tembakau karena utilisasi masih {$utilization}%.";
            } else {
                $recommendations[] = "Tingkat utilisasi produksi tinggi ({$utilization}%). Layak diusulkan bantuan sarana mekanisasi perajangan dan gudang penyimpanan modern.";
            }

            if ($distinctCategories <= 2) {
                $recommendations[] = "Rantai pasok terkonsentrasi pada segmen hulu. Perlu pembinaan diversifikasi ke industri hilir rokok linting (SKT) atau produk olahan turunan tembakau.";
            } else {
                $recommendations[] = "Ekosistem rantai pasok beragam ({$distinctCategories} segmen). Direkomendasikan pembentukan klaster terintegrasi hulu-hilir.";
            }

            return [
                'district_id' => $district->id,
                'district_name' => $district->name,
                'is_sufficient' => true,
                'status_label' => 'Memadai',
                'umkm_count' => $count,
                'fact' => [
                    'umkm_count' => $count,
                    'total_workers' => $workers,
                    'total_production' => $totProd,
                    'actual_transactions' => $trans,
                ],
                'estimation' => [
                    'utilization_rate' => $utilization,
                    'potential_score' => $compositeScore,
                ],
                'recommendation' => implode(' ', $recommendations),
                'score' => $compositeScore,
            ];
        })
        ->sortByDesc('score')
        ->values();

        return [
            'rankings' => $rankings,
            'formula_documentation' => [
                'title' => 'Formula Indeks Potensi Komposit (Composite Potential Index - CPI)',
                'formula' => 'Skor = (0.30 × Skor Produksi) + (0.25 × Skor Tenaga Kerja) + (0.25 × Skor Nilai Transaksi) + (0.20 × Skor Diversifikasi Rantai Pasok)',
                'criteria' => [
                    'Skor Produksi (Bobot 30%)' => 'Mengukur volume produksi riil yang dihasilkan terhadap kapasitas wilayah.',
                    'Skor Tenaga Kerja (Bobot 25%)' => 'Mengukur kemampuan penyerapan tenaga kerja lokal oleh UMKM.',
                    'Skor Nilai Transaksi (Bobot 25%)' => 'Mengukur estimasi kontribusi nilai perputaran uang terhadap ekonomi lokal.',
                    'Skor Diversifikasi (Bobot 20%)' => 'Mengukur variasi keberadaan 6 kategori rantai pasok industri tembakau.',
                ],
                'validity_threshold' => 'Kecamatan wajib memiliki minimal 2 UMKM terdata dengan data kapasitas valid agar status analitik dinyatakan Memadai.',
            ],
        ];
    }
}
