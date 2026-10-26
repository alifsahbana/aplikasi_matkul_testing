<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TobaccoUmkm;
use App\Models\District;
use App\Models\Category;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PublicReportController extends Controller
{
    /**
     * Laporan Publik Direktori UMKM
     */
    public function index(Request $request)
    {
        $districts = District::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        $query = TobaccoUmkm::with(['district', 'category'])
            ->where('is_verified', true);

        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('business_status', $request->status);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('village', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $umkms = $query->paginate(15)->withQueryString();

        return view('public.reports', compact('umkms', 'districts', 'categories'));
    }

    /**
     * Ekspor Laporan Publik ke Berkas CSV (Data Pemilik Disamarkan Demi Privasi)
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = TobaccoUmkm::with(['district', 'category'])
            ->where('is_verified', true);

        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $filename = 'laporan_publik_umkm_tembakau_pamekasan_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // Header CSV
            fputcsv($handle, [
                'Kode Registrasi',
                'Nama UMKM',
                'Kategori Usaha',
                'Kecamatan',
                'Desa / Kelurahan',
                'Alamat',
                'Kapasitas Produksi',
                'Satuan',
                'Produksi Aktual',
                'Tenaga Kerja',
                'Status Usaha',
                'Sumber Data',
            ]);

            $query->chunk(100, function ($records) use ($handle) {
                foreach ($records as $item) {
                    fputcsv($handle, [
                        $item->code,
                        $item->name,
                        $item->category?->name ?? '-',
                        $item->district?->name ?? '-',
                        $item->village ?? '-',
                        $item->address,
                        $item->production_capacity,
                        $item->production_unit,
                        $item->actual_production,
                        $item->worker_count,
                        $item->business_status,
                        $item->data_source,
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
