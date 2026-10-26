<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\MapController;
use App\Http\Controllers\Public\PublicReportController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UmkmController as AdminUmkmController;
use App\Http\Controllers\Admin\ImportController as AdminImportController;
use App\Http\Controllers\Admin\AnalysisController as AdminAnalysisController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;

/*
|--------------------------------------------------------------------------
| Rute Publik (WebGIS & Direktori Terbuka)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/peta', [MapController::class, 'index'])->name('map.index');
Route::get('/laporan', [PublicReportController::class, 'index'])->name('public.reports');
Route::get('/laporan/export-csv', [PublicReportController::class, 'exportCsv'])->name('public.reports.export');

// API GeoJSON Spasial untuk Peta Leaflet
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/geojson/umkms', [MapController::class, 'geojsonUmkms'])->name('geojson.umkms');
    Route::get('/geojson/districts', [MapController::class, 'geojsonDistricts'])->name('geojson.districts');
    Route::get('/umkms/{umkm}', [MapController::class, 'showUmkm'])->name('umkms.show');
});

/*
|--------------------------------------------------------------------------
| Rute Autentikasi Sistem
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Rute Administrasi (Khusus Role Administrator)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD UMKM Tembakau
    Route::resource('umkm', AdminUmkmController::class);

    // Pengelolaan & Impor CSV
    Route::get('/import', [AdminImportController::class, 'index'])->name('import.index');
    Route::post('/import/preview', [AdminImportController::class, 'preview'])->name('import.preview');
    Route::post('/import/process', [AdminImportController::class, 'import'])->name('import.process');
    Route::get('/import/template', [AdminImportController::class, 'downloadTemplate'])->name('import.template');

    // Analisis Potensi Ekonomi & Scoring CPI
    Route::get('/analisis', [AdminAnalysisController::class, 'index'])->name('analysis.index');

    // Laporan Manajerial & Rekapitulasi
    Route::get('/laporan', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/export-csv', [AdminReportController::class, 'exportCsv'])->name('reports.export');
});
