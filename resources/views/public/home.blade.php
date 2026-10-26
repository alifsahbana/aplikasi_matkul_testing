@extends('layouts.app', ['title' => 'Dashboard Publik'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <!-- Hero Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-forest-950 via-forest-900 to-forest-800 text-white p-8 sm:p-12 shadow-2xl shadow-forest-950/20">
        <div class="relative z-10 max-w-3xl space-y-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-forest-800/80 text-tobacco-300 border border-forest-700">
                Sistem Informasi Geospasial Kabupaten Pamekasan
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white leading-tight">
                Pemetaan & Potensi Ekonomi UMKM Tembakau Pamekasan
            </h1>
            <p class="text-sm sm:text-base text-slate-300 leading-relaxed">
                Platform terpadu untuk pemantauan sebaran spasial, analisis kapasitas produksi, utilisasi panen, dan penyerapan tenaga kerja di 13 kecamatan Kabupaten Pamekasan, Madura.
            </p>
            <div class="pt-2 flex flex-wrap gap-3">
                <a href="{{ route('map.index') }}" class="inline-flex items-center px-5 py-3 rounded-xl font-bold text-xs sm:text-sm text-forest-950 bg-tobacco-400 hover:bg-tobacco-300 transition shadow-lg shadow-tobacco-400/20">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    Jelajahi Peta GIS Interaktif
                </a>
                <a href="{{ route('public.reports') }}" class="inline-flex items-center px-5 py-3 rounded-xl font-bold text-xs sm:text-sm text-white bg-forest-800/90 hover:bg-forest-700 border border-forest-700 transition">
                    Lihat Direktori Lengkap
                </a>
            </div>
        </div>
        <!-- Decorative Glow -->
        <div class="absolute -right-16 -bottom-16 w-80 h-80 rounded-full bg-forest-600/20 blur-3xl pointer-events-none"></div>
    </div>

    <!-- Metrik Statistik Utama (Dihitung Real-time dari Basis Data) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Kartu 1: Total UMKM -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total UMKM Terdaftar</span>
                <span class="p-2 rounded-xl bg-forest-50 text-forest-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </span>
            </div>
            <div class="mt-4">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ number_format($metrics['total_umkm']) }}</span>
                <span class="text-xs text-slate-500 block mt-1">Unit usaha binaan & mandiri</span>
            </div>
        </div>

        <!-- Kartu 2: Koordinat Valid -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Koordinat Spasial Valid</span>
                <span class="p-2 rounded-xl bg-emerald-50 text-emerald-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </span>
            </div>
            <div class="mt-4">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ number_format($metrics['valid_coordinates_count']) }}</span>
                <span class="text-xs text-emerald-600 font-medium block mt-1">
                    {{ $metrics['total_umkm'] > 0 ? round(($metrics['valid_coordinates_count'] / $metrics['total_umkm']) * 100, 1) : 0 }}% terpetakan di WebGIS
                </span>
            </div>
        </div>

        <!-- Kartu 3: Total Tenaga Kerja -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tenaga Kerja Terserap</span>
                <span class="p-2 rounded-xl bg-tobacco-50 text-tobacco-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </span>
            </div>
            <div class="mt-4">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ number_format($metrics['total_workers']) }}</span>
                <span class="text-xs text-slate-500 block mt-1">Pekerja lokal di 13 kecamatan</span>
            </div>
        </div>

        <!-- Kartu 4: Utilisasi Kapasitas -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-Rata Utilisasi</span>
                <span class="p-2 rounded-xl bg-amber-50 text-amber-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </span>
            </div>
            <div class="mt-4">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $metrics['average_utilization'] }}%</span>
                <span class="text-xs text-slate-500 block mt-1">Realisasi terhadap kapasitas</span>
            </div>
        </div>
    </div>

    <!-- Bagian Grafik & Visualisasi Dinamis (Chart.js) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grafik 1: Distribusi UMKM per Kecamatan -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Sebaran UMKM per Kecamatan</h3>
                    <p class="text-xs text-slate-500">Jumlah unit usaha tembakau terdata di 13 kecamatan</p>
                </div>
                <span class="text-xs font-semibold text-forest-700 bg-forest-50 px-2.5 py-1 rounded-lg border border-forest-200">
                    Pamekasan
                </span>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="districtChart"></canvas>
            </div>
        </div>

        <!-- Grafik 2: Komposisi Kategori Rantai Pasok -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col">
            <div class="pb-4 border-b border-slate-100 mb-4">
                <h3 class="font-bold text-slate-900 text-base">Komposisi Rantai Pasok</h3>
                <p class="text-xs text-slate-500">Proporsi kategori komoditas tembakau</p>
            </div>
            <div class="relative h-56 w-full flex-grow flex items-center justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 text-center">
                <span class="text-xs font-medium text-slate-500">Total Kategori Rantai Nilai: 6 Jenis Usaha</span>
            </div>
        </div>
    </div>

    <!-- Tabel UMKM Terverifikasi Terbaru -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-base">UMKM Tembakau Terbaru</h3>
                <p class="text-xs text-slate-500">Pembaruan data profil usaha terverifikasi</p>
            </div>
            <a href="{{ route('public.reports') }}" class="text-xs font-bold text-forest-700 hover:text-forest-900 transition flex items-center">
                Lihat Semua &rarr;
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">Kode</th>
                        <th class="px-6 py-3.5">Nama UMKM</th>
                        <th class="px-6 py-3.5">Kategori Rantai Pasok</th>
                        <th class="px-6 py-3.5">Kecamatan</th>
                        <th class="px-6 py-3.5">Kapasitas Produksi</th>
                        <th class="px-6 py-3.5">Tenaga Kerja</th>
                        <th class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($latestUmkms as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-mono font-bold text-forest-800">{{ $item->code }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ $item->name }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium"
                                      style="background-color: {{ $item->category?->marker_color }}15; color: {{ $item->category?->marker_color }};">
                                    {{ $item->category?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-700">{{ $item->district?->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-700">
                                {{ number_format($item->production_capacity) }} {{ $item->production_unit }}
                            </td>
                            <td class="px-6 py-4 text-slate-700 font-medium">{{ $item->worker_count }} orang</td>
                            <td class="px-6 py-4">
                                @if($item->business_status === 'aktif')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                        Aktif
                                    </span>
                                @elseif($item->business_status === 'musiman')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                                        Musiman
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">
                                        Non-Aktif
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                                Belum ada data UMKM yang tersimpan di sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Inisialisasi Grafik Kecamatan
    const districtData = @json($districts);
    const ctxDistrict = document.getElementById('districtChart');
    if (ctxDistrict) {
        new Chart(ctxDistrict, {
            type: 'bar',
            data: {
                labels: districtData.map(d => d.name),
                datasets: [{
                    label: 'Jumlah UMKM',
                    data: districtData.map(d => d.umkm_count),
                    backgroundColor: '#15803d',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 }
                    },
                    x: {
                        ticks: { maxRotation: 45, minRotation: 0 }
                    }
                }
            }
        });
    }

    // 2. Inisialisasi Grafik Kategori
    const categoryData = @json($categories);
    const ctxCategory = document.getElementById('categoryChart');
    if (ctxCategory) {
        new Chart(ctxCategory, {
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
                        labels: {
                            boxWidth: 12,
                            font: { size: 10 }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
