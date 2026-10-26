@extends('layouts.admin', [
    'title' => 'Edit UMKM',
    'header' => 'Pembaruan Data UMKM Tembakau'
])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
        <div class="border-b border-slate-100 pb-4 mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Perbarui Data UMKM: {{ $umkm->name }}</h2>
                <p class="text-xs text-slate-500">Kode Registrasi: <span class="font-mono font-bold text-forest-700">{{ $umkm->code }}</span></p>
            </div>
            <a href="{{ route('admin.umkm.show', $umkm) }}" class="text-xs font-semibold text-slate-600 hover:text-forest-900">
                &larr; Kembali ke Rincian
            </a>
        </div>

        <form action="{{ route('admin.umkm.update', $umkm) }}" method="POST" class="space-y-6" id="umkmEditForm">
            @csrf
            @method('PUT')

            <!-- Identitas Usaha -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-forest-800 border-b border-forest-100 pb-1">
                    1. Identitas Pokok Usaha
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kode Unik Registrasi *</label>
                        <input type="text" name="code" value="{{ old('code', $umkm->code) }}" required
                            class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Usaha / Kelompok Tani *</label>
                        <input type="text" name="name" value="{{ old('name', $umkm->name) }}" required
                            class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Pemilik / Pengelola</label>
                        <input type="text" name="owner_name" value="{{ old('owner_name', $umkm->owner_name) }}"
                            class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kategori Rantai Pasok *</label>
                        <select name="category_id" required class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none bg-white">
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}" {{ old('category_id', $umkm->category_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Wilayah & Spasial -->
            <div class="space-y-4 pt-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-forest-800 border-b border-forest-100 pb-1">
                    2. Wilayah Administratif & Koordinat Spasial
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kecamatan *</label>
                        <select name="district_id" id="districtSelect" required class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none bg-white">
                            @foreach ($districts as $d)
                                <option value="{{ $d->id }}" data-lat="{{ $d->latitude }}" data-lng="{{ $d->longitude }}" {{ old('district_id', $umkm->district_id) == $d->id ? 'selected' : '' }}>
                                    {{ $d->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Desa / Kelurahan</label>
                        <input type="text" name="village" value="{{ old('village', $umkm->village) }}"
                            class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Operasional Lengkap *</label>
                        <textarea name="address" rows="2" required
                            class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none">{{ old('address', $umkm->address) }}</textarea>
                    </div>
                </div>

                <!-- Mini Map Picker -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Geser Marker atau Klik Peta untuk Koreksi Koordinat</span>
                    </div>
                    <div id="miniMapPicker" class="h-64 w-full rounded-xl border border-slate-300 overflow-hidden shadow-inner mb-3"></div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Latitude (Lintang) *</label>
                            <input type="text" id="latInput" name="latitude" value="{{ old('latitude', $umkm->latitude) }}" required
                                class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none font-mono">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Longitude (Bujur) *</label>
                            <input type="text" id="lngInput" name="longitude" value="{{ old('longitude', $umkm->longitude) }}" required
                                class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none font-mono">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kapasitas & Produksi -->
            <div class="space-y-4 pt-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-forest-800 border-b border-forest-100 pb-1">
                    3. Indikator Kapasitas & Potensi Ekonomi
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Luas Lahan (Ha)</label>
                        <input type="number" step="0.01" name="land_area_ha" value="{{ old('land_area_ha', $umkm->land_area_ha) }}"
                            class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Kapasitas Produksi *</label>
                        <input type="number" step="0.01" name="production_capacity" value="{{ old('production_capacity', $umkm->production_capacity) }}" required
                            class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Satuan Produksi *</label>
                        <select name="production_unit" required class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none bg-white">
                            <option value="Kg" {{ old('production_unit', $umkm->production_unit) == 'Kg' ? 'selected' : '' }}>Kg</option>
                            <option value="Ton" {{ old('production_unit', $umkm->production_unit) == 'Ton' ? 'selected' : '' }}>Ton</option>
                            <option value="Batang" {{ old('production_unit', $umkm->production_unit) == 'Batang' ? 'selected' : '' }}>Batang</option>
                            <option value="Bal" {{ old('production_unit', $umkm->production_unit) == 'Bal' ? 'selected' : '' }}>Bal</option>
                            <option value="Liter" {{ old('production_unit', $umkm->production_unit) == 'Liter' ? 'selected' : '' }}>Liter</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Produksi Riil / Aktual</label>
                        <input type="number" step="0.01" name="actual_production" value="{{ old('actual_production', $umkm->actual_production) }}"
                            class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Periode Perhitungan *</label>
                        <input type="text" name="production_period" value="{{ old('production_period', $umkm->production_period) }}" required
                            class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Jumlah Tenaga Kerja (Orang)</label>
                        <input type="number" name="worker_count" value="{{ old('worker_count', $umkm->worker_count) }}" min="0" required
                            class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Nilai Transaksi (IDR)</label>
                        <input type="number" step="1000" name="transaction_value" value="{{ old('transaction_value', $umkm->transaction_value) }}"
                            class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Status Usaha *</label>
                        <select name="business_status" required class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none bg-white">
                            <option value="aktif" {{ old('business_status', $umkm->business_status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="musiman" {{ old('business_status', $umkm->business_status) == 'musiman' ? 'selected' : '' }}>Musiman</option>
                            <option value="non-aktif" {{ old('business_status', $umkm->business_status) == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Kontak Telepon / WhatsApp</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', $umkm->contact_phone) }}"
                            class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none">
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Sumber Data *</label>
                        <input type="text" name="data_source" value="{{ old('data_source', $umkm->data_source) }}" required
                            class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none">
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.umkm.index') }}" class="px-5 py-2.5 text-xs font-semibold rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 text-xs font-bold rounded-xl text-white bg-forest-800 hover:bg-forest-700 transition shadow-md">
                    Perbarui Data UMKM
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const latInput = document.getElementById('latInput');
    const lngInput = document.getElementById('lngInput');

    let currentLat = parseFloat(latInput.value) || -7.1610;
    let currentLng = parseFloat(lngInput.value) || 103.4800;

    const map = L.map('miniMapPicker').setView([currentLat, currentLng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let marker = L.marker([currentLat, currentLng], { draggable: true }).addTo(map);

    function updateMarker(lat, lng) {
        marker.setLatLng([lat, lng]);
        latInput.value = lat.toFixed(7);
        lngInput.value = lng.toFixed(7);
    }

    marker.on('dragend', function (e) {
        const coord = e.target.getLatLng();
        updateMarker(coord.lat, coord.lng);
    });

    map.on('click', function (e) {
        updateMarker(e.latlng.lat, e.latlng.lng);
    });
});
</script>
@endpush
@endsection
