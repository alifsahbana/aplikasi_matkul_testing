@extends('layouts.app', ['title' => 'Peta WebGIS Tembakau Pamekasan'])

@section('content')
<div class="h-[calc(100vh-4rem)] flex flex-col md:flex-row relative overflow-hidden bg-slate-100" x-data="webgisApp()">
    <!-- Sidebar Filter & Kontrol Pencarian (Kiri) -->
    <div class="w-full md:w-80 lg:w-96 bg-white border-r border-slate-200 flex flex-col z-20 shadow-md">
        <!-- Header Filter -->
        <div class="p-4 border-b border-slate-100 bg-forest-950 text-white flex items-center justify-between">
            <div>
                <h2 class="font-bold text-sm tracking-tight">Eksplorasi Spasial GIS</h2>
                <p class="text-[11px] text-tobacco-400">Peta Sebaran UMKM Tembakau</p>
            </div>
            <button @click="resetFilters()" class="text-[11px] font-semibold text-slate-300 hover:text-white px-2 py-1 rounded bg-forest-900 border border-forest-800 transition">
                Reset
            </button>
        </div>

        <!-- Filter Controls -->
        <div class="p-4 space-y-4 border-b border-slate-200 bg-slate-50/50">
            <!-- Pencarian Real-time -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Cari Nama UMKM / Desa
                </label>
                <div class="relative">
                    <input type="text" x-model="searchQuery" @input.debounce.300ms="loadMarkers()"
                        placeholder="Contoh: Barurambat, SKT, Kadur..."
                        class="w-full pl-9 pr-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <!-- Filter Kecamatan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Kecamatan
                </label>
                <select x-model="selectedDistrict" @change="loadMarkers()"
                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none bg-white">
                    <option value="">Semua Kecamatan (13 Wilayah)</option>
                    @foreach ($districts as $dist)
                        <option value="{{ $dist->id }}">{{ $dist->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Kategori Rantai Pasok -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Kategori Rantai Pasok
                </label>
                <select x-model="selectedCategory" @change="loadMarkers()"
                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none bg-white">
                    <option value="">Semua Kategori (6 Rantai Pasok)</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Toggle Layer Batas Kecamatan -->
            <div class="flex items-center justify-between pt-1">
                <label class="text-xs font-semibold text-slate-700 flex items-center cursor-pointer">
                    <input type="checkbox" x-model="showDistrictsLayer" @change="toggleDistrictsLayer()"
                        class="w-4 h-4 text-forest-700 rounded border-slate-300 focus:ring-forest-500 mr-2">
                    Batas Wilayah Kecamatan
                </label>
                <span class="text-[11px] font-bold text-forest-700" x-text="markersCount + ' Marker'"></span>
            </div>
        </div>

        <!-- Daftar Hasil Pencarian Cepat -->
        <div class="flex-1 overflow-y-auto divide-y divide-slate-100 p-2">
            <template x-if="loading">
                <div class="p-6 text-center text-xs text-slate-400 space-y-2">
                    <div class="w-6 h-6 border-2 border-forest-600 border-t-transparent rounded-full animate-spin mx-auto"></div>
                    <p>Memuat data spasial...</p>
                </div>
            </template>

            <template x-if="!loading && markersList.length === 0">
                <div class="p-6 text-center text-xs text-slate-400">
                    Tidak ada UMKM yang cocok dengan kriteria filter.
                </div>
            </template>

            <template x-for="item in markersList" :key="item.properties.id">
                <div @click="focusMarker(item)"
                    class="p-3 hover:bg-forest-50/60 rounded-xl cursor-pointer transition flex items-start space-x-3 text-xs">
                    <div class="w-3.5 h-3.5 rounded-full mt-0.5 flex-shrink-0" :style="'background-color: ' + item.properties.marker_color"></div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-slate-900 truncate" x-text="item.properties.name"></h4>
                        <p class="text-[11px] text-slate-500" x-text="item.properties.category_name"></p>
                        <p class="text-[11px] text-slate-400" x-text="item.properties.district_name + (item.properties.village ? ' - ' + item.properties.village : '')"></p>
                    </div>
                </div>
            </template>
        </div>

        <!-- Legenda Kategori Marker -->
        <div class="p-3 border-t border-slate-200 bg-white">
            <h5 class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">Legenda Kategori Marker</h5>
            <div class="grid grid-cols-2 gap-1.5 text-[10px]">
                @foreach ($categories as $cat)
                    <div class="flex items-center space-x-1.5 truncate">
                        <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $cat->marker_color }}"></span>
                        <span class="text-slate-600 truncate" title="{{ $cat->name }}">{{ $cat->name }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Peta Leaflet Utama (Kanan) -->
    <div class="flex-1 relative h-full w-full">
        <div id="map" class="h-full w-full z-10"></div>

        <!-- Tombol Floating Reset Center -->
        <div class="absolute bottom-6 right-6 z-20 flex flex-col space-y-2">
            <button @click="resetMapView()"
                class="px-3.5 py-2 rounded-xl bg-white text-forest-900 text-xs font-bold shadow-xl border border-slate-200 hover:bg-forest-50 transition flex items-center">
                <svg class="w-4 h-4 mr-1 text-forest-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                Pusatkan Pamekasan
            </button>
        </div>
    </div>

    <!-- Modal Rincian UMKM -->
    <div x-show="selectedUmkmModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
        <div @click.away="selectedUmkmModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-4">
            <div class="flex items-start justify-between pb-3 border-b border-slate-100">
                <div>
                    <span class="inline-block px-2 py-0.5 text-[11px] font-bold rounded-md bg-forest-50 text-forest-800" x-text="activeModalData?.code"></span>
                    <h3 class="text-lg font-bold text-slate-900 mt-1" x-text="activeModalData?.name"></h3>
                    <p class="text-xs text-slate-500" x-text="activeModalData?.category"></p>
                </div>
                <button @click="selectedUmkmModal = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold p-1">&times;</button>
            </div>

            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Kecamatan</span>
                    <span class="font-bold text-slate-800" x-text="activeModalData?.district"></span>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Desa</span>
                    <span class="font-bold text-slate-800" x-text="activeModalData?.village || '-'"></span>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Kapasitas Produksi</span>
                    <span class="font-bold text-slate-800" x-text="new Intl.NumberFormat('id-ID').format(activeModalData?.production_capacity || 0) + ' ' + (activeModalData?.production_unit || '')"></span>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Produksi Riil</span>
                    <span class="font-bold text-slate-800" x-text="new Intl.NumberFormat('id-ID').format(activeModalData?.actual_production || 0) + ' ' + (activeModalData?.production_unit || '')"></span>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Utilisasi Panen</span>
                    <span class="font-bold text-emerald-700" x-text="(activeModalData?.utilization_rate || 0) + '%'"></span>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Tenaga Kerja</span>
                    <span class="font-bold text-slate-800" x-text="(activeModalData?.worker_count || 0) + ' Orang'"></span>
                </div>
            </div>

            <div class="p-3 bg-slate-50 rounded-xl text-xs space-y-1">
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Alamat Fisik</span>
                <p class="text-slate-700 font-medium" x-text="activeModalData?.address"></p>
                <div class="pt-1 flex items-center justify-between text-[11px] text-slate-500 font-mono">
                    <span x-text="'Koordinat: ' + activeModalData?.latitude + ', ' + activeModalData?.longitude"></span>
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button @click="selectedUmkmModal = false" class="px-5 py-2 text-xs font-bold rounded-xl bg-forest-800 text-white hover:bg-forest-700 transition">
                    Tutup Rincian
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function webgisApp() {
    return {
        map: null,
        markersLayer: null,
        districtsLayer: null,
        searchQuery: '{{ $searchQuery ?? "" }}',
        selectedDistrict: '{{ $selectedDistrict ?? "" }}',
        selectedCategory: '{{ $selectedCategory ?? "" }}',
        showDistrictsLayer: true,
        loading: false,
        markersCount: 0,
        markersList: [],
        markersMap: {},
        selectedUmkmModal: false,
        activeModalData: null,

        init() {
            this.$nextTick(() => {
                this.initMap();
                this.loadDistrictsBoundary();
                this.loadMarkers();
            });
        },

        initMap() {
            // Centroid Kabupaten Pamekasan: -7.1610, 103.4800
            this.map = L.map('map', {
                center: [-7.1610, 103.4800],
                zoom: 11,
                zoomControl: true,
            });

            // Basemap OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> kontributor | WebGIS Pamekasan'
            }).addTo(this.map);

            this.markersLayer = L.layerGroup().addTo(this.map);
            this.districtsLayer = L.layerGroup().addTo(this.map);
        },

        resetMapView() {
            if (this.map) {
                this.map.setView([-7.1610, 103.4800], 11);
            }
        },

        resetFilters() {
            this.searchQuery = '';
            this.selectedDistrict = '';
            this.selectedCategory = '';
            this.loadMarkers();
            this.resetMapView();
        },

        loadDistrictsBoundary() {
            fetch('{{ route("api.geojson.districts") }}')
                .then(res => res.json())
                .then(data => {
                    this.districtsLayer.clearLayers();
                    L.geoJSON(data, {
                        style: {
                            color: '#15803d',
                            weight: 1.5,
                            opacity: 0.65,
                            fillColor: '#86efac',
                            fillOpacity: 0.12,
                            dashArray: '3',
                        },
                        onEachFeature: (feature, layer) => {
                            if (feature.properties) {
                                layer.bindTooltip(`<strong>Kec. ${feature.properties.name}</strong><br>Luas: ${feature.properties.area_km2} km²<br>UMKM: ${feature.properties.umkm_count} Unit`, {
                                    sticky: true,
                                    className: 'text-xs rounded-lg px-2 py-1 shadow-md bg-white text-slate-800'
                                });
                            }
                        }
                    }).addTo(this.districtsLayer);
                })
                .catch(err => console.error("Gagal memuat batas kecamatan:", err));
        },

        toggleDistrictsLayer() {
            if (this.showDistrictsLayer) {
                this.map.addLayer(this.districtsLayer);
            } else {
                this.map.removeLayer(this.districtsLayer);
            }
        },

        loadMarkers() {
            this.loading = true;
            let url = new URL('{{ route("api.geojson.umkms") }}', window.location.origin);
            if (this.selectedDistrict) url.searchParams.set('district_id', this.selectedDistrict);
            if (this.selectedCategory) url.searchParams.set('category_id', this.selectedCategory);
            if (this.searchQuery) url.searchParams.set('q', this.searchQuery);

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    this.markersLayer.clearLayers();
                    this.markersList = data.features || [];
                    this.markersCount = this.markersList.length;
                    this.markersMap = {};

                    this.markersList.forEach(feature => {
                        const [lng, lat] = feature.geometry.coordinates;
                        const props = feature.properties;

                        // Custom SVG Marker Pin dengan warna dinamis per kategori
                        const markerHtml = `
                            <div style="background-color: ${props.marker_color}; width: 24px; height: 24px; border-radius: 50%; border: 3px solid #ffffff; box-shadow: 0 3px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;">
                                <div style="width: 6px; height: 6px; background-color: #ffffff; border-radius: 50%;"></div>
                            </div>
                        `;

                        const customIcon = L.divIcon({
                            html: markerHtml,
                            className: 'custom-div-icon',
                            iconSize: [24, 24],
                            iconAnchor: [12, 12],
                            popupAnchor: [0, -12],
                        });

                        const marker = L.marker([lat, lng], { icon: customIcon });

                        // Konten Popup Interaktif
                        const popupContent = `
                            <div class="p-3.5 max-w-xs space-y-2">
                                <div class="border-b border-slate-100 pb-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-forest-700 bg-forest-50 px-2 py-0.5 rounded">${props.category_name}</span>
                                    <h4 class="font-bold text-sm text-slate-900 mt-1">${props.name}</h4>
                                    <p class="text-xs text-slate-500">Kec. ${props.district_name} ${props.village ? '- ' + props.village : ''}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-1.5 text-[11px] text-slate-600">
                                    <div>Kapasitas: <strong>${new Intl.NumberFormat('id-ID').format(props.production_capacity)} ${props.production_unit}</strong></div>
                                    <div>Tenaga Kerja: <strong>${props.worker_count} Org</strong></div>
                                </div>
                                <div class="pt-1">
                                    <button onclick="window.openDetail(${props.id})" class="w-full text-center py-1.5 px-3 rounded-lg text-xs font-bold text-white bg-forest-800 hover:bg-forest-700 transition">
                                        Rincian Lengkap
                                    </button>
                                </div>
                            </div>
                        `;

                        marker.bindPopup(popupContent);
                        marker.addTo(this.markersLayer);
                        this.markersMap[props.id] = marker;
                    });

                    this.loading = false;
                })
                .catch(err => {
                    console.error("Gagal memuat markers GeoJSON:", err);
                    this.loading = false;
                });
        },

        focusMarker(item) {
            const marker = this.markersMap[item.properties.id];
            if (marker && this.map) {
                const [lng, lat] = item.geometry.coordinates;
                this.map.flyTo([lat, lng], 15, { duration: 1 });
                marker.openPopup();
            }
        },

        openDetailById(id) {
            fetch(`/api/umkms/${id}`)
                .then(res => res.json())
                .then(data => {
                    this.activeModalData = data;
                    this.selectedUmkmModal = true;
                });
        }
    };
}

// Handler global untuk tombol di dalam popup leaflet
window.openDetail = function(id) {
    const appEl = document.querySelector('[x-data="webgisApp()"]');
    if (appEl && appEl._x_dataStack) {
        appEl._x_dataStack[0].openDetailById(id);
    }
};
</script>
@endpush
@endsection
