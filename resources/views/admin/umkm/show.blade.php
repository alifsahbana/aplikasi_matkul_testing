@extends('layouts.admin', [
    'title' => 'Detail UMKM ' . $umkm->name,
    'header' => 'Rincian Lengkap Data UMKM'
])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Rincian -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-md text-xs font-mono font-bold bg-forest-50 text-forest-800">{{ $umkm->code }}</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold"
                      style="background-color: {{ $umkm->category?->marker_color }}15; color: {{ $umkm->category?->marker_color }};">
                    {{ $umkm->category?->name }}
                </span>
            </div>
            <h2 class="text-xl font-extrabold text-slate-900 mt-1">{{ $umkm->name }}</h2>
            <p class="text-xs text-slate-500">Pemilik: <span class="font-bold text-slate-700">{{ $umkm->owner_name ?: 'Tidak Disebutkan' }}</span></p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.umkm.edit', $umkm) }}" class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-xl text-white bg-forest-800 hover:bg-forest-700 transition">
                Edit Data
            </a>
            <a href="{{ route('admin.umkm.index') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                Kembali
            </a>
        </div>
    </div>

    <!-- Grid Indikator Kunci -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Kapasitas Produksi</span>
            <span class="text-lg font-bold text-slate-900 block mt-1">{{ number_format($umkm->production_capacity) }} {{ $umkm->production_unit }}</span>
            <span class="text-[11px] text-slate-400 block mt-0.5">{{ $umkm->production_period }}</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Produksi Riil</span>
            <span class="text-lg font-bold text-slate-900 block mt-1">{{ number_format($umkm->actual_production) }} {{ $umkm->production_unit }}</span>
            <span class="text-[11px] text-emerald-600 font-bold block mt-0.5">Utilisasi: {{ $umkm->capacity_utilization ?? 0 }}%</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tenaga Kerja</span>
            <span class="text-lg font-bold text-slate-900 block mt-1">{{ $umkm->worker_count }} Orang</span>
            <span class="text-[11px] text-slate-400 block mt-0.5">Pekerja lokal</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Estimasi Transaksi</span>
            <span class="text-lg font-bold text-slate-900 block mt-1">IDR {{ number_format($umkm->transaction_value / 1000000, 1) }} Jt</span>
            <span class="text-[11px] text-slate-400 block mt-0.5">Nilai perputaran</span>
        </div>
    </div>

    <!-- Peta Lokasi & Informasi Geospasial -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Peta Lokasi Spasial UMKM</h3>
        <div id="showMap" class="h-64 w-full rounded-xl border border-slate-300 overflow-hidden shadow-inner"></div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-2">
            <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                <span class="text-[10px] font-bold uppercase text-slate-400">Wilayah Administratif</span>
                <p class="font-bold text-slate-800">Kecamatan {{ $umkm->district?->name }}</p>
                <p class="text-slate-600">Desa/Kelurahan: {{ $umkm->village ?: '-' }}</p>
                <p class="text-slate-600">Alamat: {{ $umkm->address }}</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                <span class="text-[10px] font-bold uppercase text-slate-400">Koordinat & Legalitas</span>
                <p class="font-mono text-slate-800">Lat: {{ $umkm->latitude }}, Lng: {{ $umkm->longitude }}</p>
                <p class="text-slate-600">Status Operasional: <strong class="capitalize">{{ $umkm->business_status }}</strong></p>
                <p class="text-slate-600">Sumber Data: {{ $umkm->data_source }}</p>
                <p class="text-slate-600">Kontak: {{ $umkm->contact_phone ?: '-' }}</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const lat = {{ $umkm->latitude }};
    const lng = {{ $umkm->longitude }};

    const map = L.map('showMap').setView([lat, lng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    L.marker([lat, lng])
        .addTo(map)
        .bindPopup("<strong>{{ $umkm->name }}</strong><br>{{ $umkm->address }}")
        .openPopup();
});
</script>
@endpush
@endsection
