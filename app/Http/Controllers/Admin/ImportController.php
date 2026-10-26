<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CsvImportService;
use App\Models\ImportLog;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportController extends Controller
{
    public function __construct(
        protected CsvImportService $importService
    ) {}

    /**
     * Halaman Pengelolaan dan Riwayat Impor CSV
     */
    public function index()
    {
        $logs = ImportLog::with('user')->latest()->paginate(10);

        return view('admin.import.index', compact('logs'));
    }

    /**
     * Pratinjau 5 Baris Pertama CSV Sebelum Eksekusi Impor
     */
    public function preview(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'], // Max 5MB
        ], [
            'csv_file.required' => 'Silakan pilih berkas CSV untuk diunggah.',
            'csv_file.mimes' => 'Format berkas harus berupa CSV (.csv) atau teks pemisah koma (.txt).',
            'csv_file.max' => 'Ukuran berkas CSV maksimal 5 Megabytes.',
        ]);

        try {
            $previewData = $this->importService->previewCsv($request->file('csv_file'));
            return response()->json([
                'success' => true,
                'data' => $previewData,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Eksekusi Impor Berkas CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        try {
            $result = $this->importService->import($request->file('csv_file'), $request->user()?->id);

            $msg = "Impor selesai! Berhasil diimpor: {$result['success']} baris. Gagal/Dilewati: {$result['failed']} baris.";

            return redirect()->route('admin.import.index')
                ->with('success', $msg)
                ->with('import_result', $result);
        } catch (\Throwable $e) {
            return redirect()->route('admin.import.index')
                ->with('error', 'Terjadi kesalahan sistem saat proses impor: ' . $e->getMessage());
        }
    }

    /**
     * Unduh Berkas CSV Template DEMO Sintetis
     */
    public function downloadTemplate()
    {
        $path = database_path('data/demo_umkm_tembakau_pamekasan.csv');

        if (!file_exists($path)) {
            $content = $this->importService->generateDemoCsvContent();
            file_put_contents($path, $content);
        }

        return response()->download($path, 'template_demo_umkm_tembakau_pamekasan.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
