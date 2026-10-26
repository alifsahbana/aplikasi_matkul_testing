<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TobaccoUmkm;
use App\Models\District;
use App\Models\Category;
use App\Services\EconomicAnalysisService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function __construct(
        protected EconomicAnalysisService $analysisService
    ) {}

    /**
     * Halaman Rekapitulasi Laporan Lengkap Administrator
     */
    public function index(Request $request)
    {
        $districts = District::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        $districtRecap = $this->analysisService->getDistrictDistribution();
        $categoryRecap = $this->analysisService->getCategoryDistribution();

        $query = TobaccoUmkm::with(['district', 'category']);

        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('business_status', $request->status);
        }

        $umkms = $query->paginate(20)->withQueryString();

        return view('admin.reports.index', compact('umkms', 'districts', 'categories', 'districtRecap', 'categoryRecap'));
    }

    /**
     * Ekspor Data Administratif Lengkap ke CSV
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = TobaccoUmkm::with(['district', 'category']);

        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $filename = 'rekap_administratif_umkm_pamekasan_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // Header CSV Lengkap
            fputcsv($handle, [
                'ID',
                'Kode Registrasi',
                'Nama UMKM',
                'Nama Pemilik',
                'Kategori Rantai Pasok',
                'Kecamatan',
                'Desa / Kelurahan',
                'Alamat Operasional',
                'Latitude',
                'Longitude',
                'Luas Lahan (Ha)',
                'Kapasitas Produksi',
                'Satuan',
                'Produksi Aktual',
                'Periode Produksi',
                'Utilisasi Kapasitas (%)',
                'Tenaga Kerja (Orang)',
                'Estimasi Transaksi (IDR)',
                'Status Usaha',
                'Sumber Data',
                'Kontak',
                'Tanggal Ditambahkan',
            ]);

            $query->chunk(100, function ($records) use ($handle) {
                foreach ($records as $item) {
                    fputcsv($handle, [
                        $item->id,
                        $item->code,
                        $item->name,
                        $item->owner_name ?? '-',
                        $item->category?->name ?? '-',
                        $item->district?->name ?? '-',
                        $item->village ?? '-',
                        $item->address,
                        $item->latitude,
                        $item->longitude,
                        $item->land_area_ha,
                        $item->production_capacity,
                        $item->production_unit,
                        $item->actual_production,
                        $item->production_period,
                        $item->capacity_utilization ?? 0,
                        $item->worker_count,
                        $item->transaction_value,
                        $item->business_status,
                        $item->data_source,
                        $item->contact_phone ?? '-',
                        $item->created_at->format('Y-m-d H:i'),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
