@extends('layouts.admin', [
    'title' => 'Analisis Potensi Ekonomi',
    'header' => 'Analisis Potensi & Rekomendasi Ekonomi Geospasial'
])

@section('content')
<div class="space-y-6">
    <!-- Header Modul Analitik -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <h2 class="text-base sm:text-lg font-bold text-slate-900">Model Analitik Potensi Ekonomi Tembakau Pamekasan</h2>
        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
            Modul ini menggunakan formula ilmiah terbobot (Composite Potential Index) dengan memisahkan secara ketat antara
            <strong class="text-slate-800">Fakta Riil</strong>, <strong class="text-slate-800">Estimasi Terukur</strong>, dan
            <strong class="text-slate-800">Rekomendasi Kebijakan</strong>.
        </p>
    </div>

    <!-- Dokumentasi Rumus & Formula Ilmiah -->
    <div class="bg-gradient-to-r from-forest-900 to-forest-800 rounded-2xl p-6 text-white shadow-md">
        <div class="flex items-start justify-between">
            <div>
                <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-tobacco-400 text-forest-950 mb-2">
                    Metodologi SQA & Analitika
                </span>
                <h3 class="font-extrabold text-base sm:text-lg text-white">
                    {{ $analysis['formula_documentation']['title'] }}
                </h3>
                <p class="font-mono text-xs text-tobacco-200 bg-forest-950/60 px-3.5 py-2 rounded-xl mt-2 border border-forest-700">
                    {{ $analysis['formula_documentation']['formula'] }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mt-4 text-xs">
            @foreach ($analysis['formula_documentation']['criteria'] as $name => $desc)
                <div class="bg-forest-950/40 p-3 rounded-xl border border-forest-700/60">
                    <span class="font-bold text-tobacco-300 block mb-1">{{ $name }}</span>
                    <span class="text-slate-300 text-[11px] leading-relaxed">{{ $desc }}</span>
                </div>
            @endforeach
        </div>

        <div class="mt-4 pt-3 border-t border-forest-700/50 text-[11px] text-slate-300 flex items-center space-x-2">
            <svg class="w-4 h-4 text-tobacco-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span><strong>Ambang Batas Keabsahan:</strong> {{ $analysis['formula_documentation']['validity_threshold'] }}</span>
        </div>
    </div>

    <!-- Tabel Peringkat Kecamatan & Rekomendasi Terarah -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-sm">Tabel Peringkat Potensi & Rekomendasi 13 Kecamatan</h3>
                <p class="text-xs text-slate-500">Urutan kecamatan berdasarkan skor komposit potensi ekonomi</p>
            </div>
            <span class="text-xs font-semibold text-slate-500">
                13 Kecamatan Teranalisis
            </span>
        </div>

        <div class="divide-y divide-slate-100">
            @foreach ($analysis['rankings'] as $index => $item)
                <div class="p-5 hover:bg-slate-50/60 transition space-y-3">
                    <!-- Baris Atas: Peringkat, Nama, Skor, Status -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center space-x-3">
                            <span class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs
                                {{ $index === 0 ? 'bg-amber-400 text-amber-950 shadow-sm' : ($index < 3 ? 'bg-forest-100 text-forest-800' : 'bg-slate-100 text-slate-600') }}">
                                #{{ $index + 1 }}
                            </span>
                            <div>
                                <h4 class="font-bold text-sm text-slate-900">Kecamatan {{ $item['district_name'] }}</h4>
                                <span class="text-xs text-slate-500">{{ $item['umkm_count'] }} UMKM terdaftar</span>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            @if($item['is_sufficient'])
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Skor CPI: {{ $item['score'] }} / 100
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                    Status: Data Belum Cukup
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Pemisahan Fakta vs Estimasi vs Rekomendasi -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs pt-1">
                        <!-- Kolom 1: Fakta Riil -->
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-1">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">1. Fakta Basis Data</span>
                            <p class="text-slate-700">Pekerja Terserap: <strong class="text-slate-900">{{ number_format($item['fact']['total_workers']) }} Org</strong></p>
                            <p class="text-slate-700">Produksi Riil: <strong class="text-slate-900">{{ number_format($item['fact']['total_production']) }}</strong></p>
                            <p class="text-slate-700">Nilai Transaksi: <strong class="text-slate-900">IDR {{ number_format($item['fact']['actual_transactions'] / 1000000, 1) }} Jt</strong></p>
                        </div>

                        <!-- Kolom 2: Estimasi Terukur -->
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-1">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">2. Estimasi Terukur</span>
                            <p class="text-slate-700">Utilisasi Kapasitas: <strong class="text-emerald-700">{{ $item['estimation']['utilization_rate'] }}%</strong></p>
                            <p class="text-slate-700">Indeks Komposit: <strong class="text-slate-900">{{ $item['estimation']['potential_score'] }} poin</strong></p>
                            <p class="text-[11px] text-slate-400">Dihitung otomatis via formula terbobot</p>
                        </div>

                        <!-- Kolom 3: Rekomendasi Ilmiah -->
                        <div class="p-3 bg-forest-50/60 rounded-xl border border-forest-100 space-y-1">
                            <span class="text-[10px] font-bold text-forest-800 uppercase tracking-wider block">3. Rekomendasi Kebijakan</span>
                            <p class="text-slate-700 leading-relaxed text-[11px]">
                                {{ $item['recommendation'] }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
