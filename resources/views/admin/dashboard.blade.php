@extends('layouts.admin', [
    'title' => 'Dashboard Manajemen',
    'header' => 'Ringkasan Eksekutif & Manajemen WebGIS'
])

@section('content')
<div class="space-y-8">
    <!-- Banner Selamat Datang & Quick Actions -->
    <div class="bg-gradient-to-r from-forest-900 via-forest-800 to-forest-700 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-forest-950/60 text-tobacco-300 border border-forest-600">
                Panel Kontrol Resmi • SQA & GIS Pamekasan
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                Halo, {{ auth()->user()->name }}!
            </h2>
            <p class="text-xs sm:text-sm text-slate-300 max-w-xl">
                Sistem aktif mengelola data rantai pasok tembakau di 13 kecamatan Kabupaten Pamekasan dengan integritas basis data terpadu.
            </p>
        </div>
        <div class="flex flex-wrap gap-2.5">
            <a href="{{ route('admin.umkm.create') }}"
                class="inline-flex items-center px-4 py-2.5 text-xs font-bold rounded-xl text-forest-950 bg-tobacco-400 hover:bg-tobacco-300 transition shadow-md">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah UMKM
            </a>
            <a href="{{ route('admin.import.index') }}"
                class="inline-flex items-center px-4 py-2.5 text-xs font-bold rounded-xl text-white bg-forest-950 hover:bg-forest-900 border border-forest-600 transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Impor CSV
            </a>
        </div>
    </div>

    <!-- Metrik Statistik Utama (Dihitung dari DB) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total UMKM</span>
            <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 block mt-2">{{ number_format($metrics['total_umkm']) }}</span>
            <span class="text-xs text-forest-700 font-semibold mt-1 block">{{ $metrics['active_count'] }} aktif, {{ $metrics['seasonal_count'] }} musiman</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Koordinat Terpetakan</span>
            <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 block mt-2">{{ number_format($metrics['valid_coordinates_count']) }}</span>
            <span class="text-xs text-emerald-600 font-semibold mt-1 block">Validasi Spasial 100% Lolos</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Penyerapan Tenaga Kerja</span>
            <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 block mt-2">{{ number_format($metrics['total_workers']) }}</span>
            <span class="text-xs text-slate-500 mt-1 block">Pekerja lokal terserap</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Perputaran Transaksi</span>
            <span class="text-xl sm:text-2xl font-extrabold text-slate-900 block mt-2 truncate">
                IDR {{ number_format($metrics['total_transactions'] / 1000000, 1) }} Jt
            </span>
            <span class="text-xs text-slate-500 mt-1 block">Estimasi transaksi agregat</span>
        </div>
    </div>

    <!-- Grafik Visualisasi Dinamis (Chart.js) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm sm:text-base">Sebaran UMKM di 13 Kecamatan</h3>
                    <p class="text-xs text-slate-500">Peringkat konsentrasi unit usaha tembakau per wilayah</p>
                </div>
                <a href="{{ route('admin.reports.index') }}" class="text-xs font-bold text-forest-700 hover:text-forest-900">
                    Lihat Rekap &rarr;
                </a>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="adminDistrictChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col">
            <div class="pb-4 border-b border-slate-100 mb-4">
                <h3 class="font-bold text-slate-900 text-sm sm:text-base">Distribusi Rantai Pasok</h3>
                <p class="text-xs text-slate-500">Klasifikasi 6 kategori usaha</p>
            </div>
            <div class="relative h-56 w-full flex-grow flex items-center justify-center">
                <canvas id="adminCategoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Grid Log Impor Terbaru & Aktivitas UMKM -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Log Impor CSV Terkini -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">Riwayat Impor Data CSV</h4>
                    <p class="text-xs text-slate-500">Pencatatan audit berkas migrasi data</p>
                </div>
                <a href="{{ route('admin.import.index') }}" class="text-xs font-bold text-forest-700 hover:text-forest-900">
                    Kelola Impor &rarr;
                </a>
            </div>
            <div class="divide-y divide-slate-100 text-xs">
                @forelse ($recentImports as $log)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50/70 transition">
                        <div>
                            <span class="font-bold text-slate-900 block truncate max-w-xs">{{ $log->original_filename }}</span>
                            <span class="text-[11px] text-slate-400 block">{{ $log->created_at->diffForHumans() }} oleh {{ $log->user?->name ?? 'Sistem' }}</span>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold
                                {{ $log->status === 'success' ? 'bg-emerald-100 text-emerald-800' : ($log->status === 'partial' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                {{ ucfirst($log->status) }}
                            </span>
                            <span class="text-[11px] text-slate-500 block mt-0.5">{{ $log->success_rows }}/{{ $log->total_rows }} baris</span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400">
                        Belum ada riwayat impor CSV yang dicatat.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- UMKM Terdaftar Terbaru -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">UMKM Terdaftar Terakhir</h4>
                    <p class="text-xs text-slate-500">Pembaruan direktori UMKM</p>
                </div>
                <a href="{{ route('admin.umkm.index') }}" class="text-xs font-bold text-forest-700 hover:text-forest-900">
                    Semua Data &rarr;
                </a>
            </div>
            <div class="divide-y divide-slate-100 text-xs">
                @forelse ($recentUmkms as $u)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50/70 transition">
                        <div>
                            <span class="font-bold text-slate-900 block">{{ $u->name }}</span>
                            <span class="text-[11px] text-slate-400 block">{{ $u->code }} • Kec. {{ $u->district?->name }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-slate-700 font-semibold block">{{ number_format($u->production_capacity) }} {{ $u->production_unit }}</span>
                            <span class="text-[11px] text-slate-400 block">{{ $u->worker_count }} pekerja</span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400">
                        Belum ada data UMKM.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const districtData = @json($districts);
    const ctxDist = document.getElementById('adminDistrictChart');
    if (ctxDist) {
        new Chart(ctxDist, {
            type: 'bar',
            data: {
                labels: districtData.map(d => d.name),
                datasets: [{
                    label: 'Jumlah UMKM',
                    data: districtData.map(d => d.umkm_count),
                    backgroundColor: '#166534',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } },
                    x: { ticks: { maxRotation: 45, minRotation: 0 } }
                }
            }
        });
    }

    const categoryData = @json($categories);
    const ctxCat = document.getElementById('adminCategoryChart');
    if (ctxCat) {
        new Chart(ctxCat, {
            type: 'doughnut',
            data: {
                labels: categoryData.map(c => c.name),
                datasets: [{
                    data: categoryData.map(c => c.umkm_count),
                    backgroundColor: categoryData.map(c => c.marker_color),
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, font: { size: 10 } }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
