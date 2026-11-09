@extends('layouts.app', ['title' => 'Peta WebGIS Tembakau Pamekasan'])

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    #map {
        width: 100% !important;
        height: 100% !important;
        min-height: 480px !important;
        background-color: #f1f5f9;
        z-index: 10;
    }
    .custom-div-icon {
        background: transparent !important;
        border: none !important;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 1rem !important;
        padding: 0 !important;
        overflow: hidden !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25) !important;
    }
    .leaflet-popup-content {
        margin: 0 !important;
        line-height: 1.4 !important;
    }
    .leaflet-container {
        font-family: inherit !important;
    }
</style>
@endpush

@section('content')
<!-- Script Definisi Aplikasi WebGIS (Didefinisikan sebelum komponen Alpine dirender) -->
<script>
function cleanPhone(phone) {
    if (!phone) return '';
    let cleaned = phone.replace(/[^0-9]/g, '');
    if (cleaned.startsWith('0')) {
        cleaned = '62' + cleaned.substring(1);
    }
    return cleaned;
}

function webgisApp() {
    return {
        map: null,
        markersLayer: null,
        districtsLayer: null,
        streetLayer: null,
        satelliteLayer: null,
        userLocationMarker: null,
        sidebarOpen: true,
        basemap: 'street',
        locatingUser: false,
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
            const startMap = () => {
                const leaflet = window.L || (typeof L !== 'undefined' ? L : null);
                const mapEl = document.getElementById('map');
                if (!leaflet || !mapEl) {
                    setTimeout(startMap, 40);
                    return;
                }

                this.initMap(leaflet);
                this.loadDistrictsBoundary(leaflet);
                this.loadMarkers(leaflet);

                setTimeout(() => {
                    if (this.map) this.map.invalidateSize();
                }, 200);
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', startMap);
            } else {
                this.$nextTick(startMap);
            }

            window.addEventListener('resize', () => {
                if (this.map) this.map.invalidateSize();
            });
        },

        initMap(L_obj) {
            if (this.map) return;
            const Leaflet = L_obj || window.L;

            // Centroid Wilayah Pamekasan: -7.1610, 103.4800
            this.map = Leaflet.map('map', {
                center: [-7.1610, 103.4800],
                zoom: 11,
                zoomControl: true,
            });

            // 1. Basemap CartoDB Voyager / OSM (Sangat cepat, kontras tinggi & stabil)
            this.streetLayer = Leaflet.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                maxZoom: 19,
                subdomains: 'abcd',
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/attributions">CARTO</a> | WebGIS Pamekasan'
            }).addTo(this.map);

            // 2. Basemap Esri Satellite (Citra Satelit)
            this.satelliteLayer = Leaflet.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: 'Tiles &copy; Esri &mdash; Citra Satelit'
            });

            this.districtsLayer = Leaflet.layerGroup().addTo(this.map);
            this.markersLayer = Leaflet.layerGroup().addTo(this.map);
        },

        switchBasemap(type) {
            if (this.basemap === type || !this.map) return;
            this.basemap = type;
            if (type === 'satellite') {
                this.map.removeLayer(this.streetLayer);
                this.satelliteLayer.addTo(this.map);
            } else {
                this.map.removeLayer(this.satelliteLayer);
                this.streetLayer.addTo(this.map);
            }
        },

        locateUser() {
            if (!navigator.geolocation) {
                alert("Peramban Anda tidak mendukung sensor geolokasi GPS.");
                return;
            }

            const Leaflet = window.L;
            this.locatingUser = true;
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;

                    if (this.userLocationMarker && this.map) {
                        this.map.removeLayer(this.userLocationMarker);
                    }

                    const userIcon = Leaflet.divIcon({
                        html: `
                            <div style="background-color: #2563eb; width: 22px; height: 22px; border-radius: 50%; border: 3px solid #ffffff; box-shadow: 0 0 0 6px rgba(37,99,235,0.3); display: flex; align-items: center; justify-content: center;">
                                <div style="width: 6px; height: 6px; background-color: #ffffff; border-radius: 50%;"></div>
                            </div>
                        `,
                        className: 'user-location-pin',
                        iconSize: [22, 22],
                        iconAnchor: [11, 11]
                    });

                    this.userLocationMarker = Leaflet.marker([lat, lng], { icon: userIcon })
                        .addTo(this.map)
                        .bindPopup('<div class="p-2 text-xs font-bold text-slate-800">📍 Posisi Anda Saat Ini</div>')
                        .openPopup();

                    this.map.flyTo([lat, lng], 14, { duration: 1.5 });
                    this.locatingUser = false;
                },
                (err) => {
                    alert("Tidak dapat mendeteksi lokasi GPS: " + err.message);
                    this.locatingUser = false;
                },
                { enableHighAccuracy: true, timeout: 8000 }
            );
        },

        resetMapView() {
            if (!this.map) return;
            const Leaflet = window.L;
            const markers = Object.values(this.markersMap);
            if (markers.length > 0 && Leaflet) {
                const group = Leaflet.featureGroup(markers);
                if (group.getBounds().isValid()) {
                    this.map.fitBounds(group.getBounds().pad(0.08));
                    return;
                }
            }
            this.map.setView([-7.1610, 103.4800], 11);
        },

        resetFilters() {
            this.searchQuery = '';
            this.selectedDistrict = '';
            this.selectedCategory = '';
            this.loadMarkers();
            this.resetMapView();
        },

        getDistrictColor(production) {
            if (production >= 200000) return '#064e3b';
            if (production >= 100000) return '#047857';
            if (production >= 30000) return '#059669';
            if (production > 0) return '#10b981';
            return '#6ee7b7';
        },

        loadDistrictsBoundary(L_obj) {
            const Leaflet = L_obj || window.L;
            fetch('{{ route("api.geojson.districts") }}')
                .then(res => res.json())
                .then(data => {
                    if (!this.districtsLayer || !Leaflet) return;
                    this.districtsLayer.clearLayers();
                    const self = this;

                    Leaflet.geoJSON(data, {
                        style: (feature) => {
                            const prod = feature.properties.total_production || 0;
                            return {
                                color: self.getDistrictColor(prod),
                                weight: 2,
                                fillOpacity: 0.22,
                                dashArray: '3, 4',
                            };
                        },
                        onEachFeature: (feature, layer) => {
                            const props = feature.properties;
                            const prodFormatted = new Intl.NumberFormat('id-ID').format(props.total_production || 0);

                            // Tooltip Interaktif saat hover
                            const tooltipContent = `
                                <div class="p-1 text-xs">
                                    <div class="font-extrabold text-forest-950 text-sm">Kec. ${props.name}</div>
                                    <div class="text-slate-600 mt-1 space-y-0.5">
                                        <div>🏭 <strong>${props.umkm_count || 0}</strong> Unit Usaha UMKM</div>
                                        <div>📦 <strong>${prodFormatted}</strong> Kg Tembakau</div>
                                        <div>👥 <strong>${props.total_workers || 0}</strong> Tenaga Kerja</div>
                                    </div>
                                    <div class="text-[10px] text-forest-700 font-semibold mt-1">Klik untuk memfilter kecamatan ini</div>
                                </div>
                            `;
                            layer.bindTooltip(tooltipContent, { sticky: true });

                            // Hover style
                            layer.on('mouseover', function () {
                                this.setStyle({
                                    weight: 3,
                                    fillOpacity: 0.45,
                                    color: '#022c22'
                                });
                            });
                            layer.on('mouseout', function () {
                                const prod = feature.properties.total_production || 0;
                                this.setStyle({
                                    weight: 2,
                                    fillOpacity: 0.22,
                                    color: self.getDistrictColor(prod)
                                });
                            });

                            // Klik poligon langsung filter kecamatan
                            layer.on('click', function () {
                                self.selectedDistrict = props.id.toString();
                                self.loadMarkers();
                            });
                        }
                    }).addTo(this.districtsLayer);
                })
                .catch(err => console.error("Gagal memuat batas kecamatan:", err));
        },

        toggleDistrictsLayer() {
            if (!this.map || !this.districtsLayer) return;
            if (this.showDistrictsLayer) {
                this.map.addLayer(this.districtsLayer);
            } else {
                this.map.removeLayer(this.districtsLayer);
            }
        },

        loadMarkers(L_obj) {
            const Leaflet = L_obj || window.L;
            this.loading = true;
            let url = new URL('{{ route("api.geojson.umkms") }}', window.location.origin);
            if (this.selectedDistrict) url.searchParams.set('district_id', this.selectedDistrict);
            if (this.selectedCategory) url.searchParams.set('category_id', this.selectedCategory);
            if (this.searchQuery) url.searchParams.set('q', this.searchQuery);

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (!this.markersLayer || !Leaflet) {
                        this.loading = false;
                        return;
                    }
                    this.markersLayer.clearLayers();
                    this.markersList = data.features || [];
                    this.markersCount = this.markersList.length;
                    this.markersMap = {};

                    this.markersList.forEach(feature => {
                        const [lng, lat] = feature.geometry.coordinates;
                        const props = feature.properties;

                        // Custom SVG Marker Pin dengan warna dinamis per kategori
                        const markerHtml = `
                            <div style="background-color: ${props.marker_color}; width: 26px; height: 26px; border-radius: 50%; border: 3px solid #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.35); display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                <div style="width: 7px; height: 7px; background-color: #ffffff; border-radius: 50%;"></div>
                            </div>
                        `;

                        const customIcon = Leaflet.divIcon({
                            html: markerHtml,
                            className: 'custom-div-icon',
                            iconSize: [26, 26],
                            iconAnchor: [13, 13],
                            popupAnchor: [0, -13],
                        });

                        const marker = Leaflet.marker([lat, lng], { icon: customIcon });

                        // Siapkan link WhatsApp jika ada nomor telepon
                        const cleanWa = cleanPhone(props.contact_phone);
                        const waButtonHtml = cleanWa ? `
                            <a href="https://wa.me/${cleanWa}?text=${encodeURIComponent('Halo, saya melihat informasi usaha ' + props.name + ' di WebGIS Tembakau Pamekasan.')}"
                               target="_blank" rel="noopener noreferrer"
                               class="flex-1 text-center py-1.5 px-2 rounded-lg text-[11px] font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition flex items-center justify-center gap-1 shadow-sm">
                                <span>💬</span> WA
                            </a>
                        ` : '';

                        // Konten Popup Interaktif Modern
                        const popupContent = `
                            <div class="p-3.5 max-w-xs space-y-2">
                                <div class="border-b border-slate-100 pb-2">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-forest-700 bg-forest-50 px-2 py-0.5 rounded">${props.category_name}</span>
                                        ${props.is_verified ? '<span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.2 rounded">✓ Resmi</span>' : ''}
                                    </div>
                                    <h4 class="font-extrabold text-sm text-slate-900">${props.name}</h4>
                                    <p class="text-xs text-slate-500">Kec. ${props.district_name} ${props.village ? '- ' + props.village : ''}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-1.5 text-[11px] text-slate-600">
                                    <div>Kapasitas: <strong>${new Intl.NumberFormat('id-ID').format(props.production_capacity)} ${props.production_unit}</strong></div>
                                    <div>Tenaga Kerja: <strong>${props.worker_count} Org</strong></div>
                                </div>
                                <div class="pt-1.5 flex gap-1.5">
                                    <a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}"
                                       target="_blank" rel="noopener noreferrer"
                                       class="flex-1 text-center py-1.5 px-2 rounded-lg text-[11px] font-bold text-white bg-blue-600 hover:bg-blue-700 transition flex items-center justify-center gap-1 shadow-sm">
                                        <span>🚗</span> Rute
                                    </a>
                                    ${waButtonHtml}
                                </div>
                                <div>
                                    <button onclick="window.openDetail(${props.id})" class="w-full text-center py-1.5 px-3 rounded-lg text-xs font-bold text-white bg-forest-800 hover:bg-forest-700 transition shadow-sm">
                                        Rincian Lengkap & Profil
                                    </button>
                                </div>
                            </div>
                        `;

                        marker.bindPopup(popupContent);
                        marker.addTo(this.markersLayer);
                        this.markersMap[props.id] = marker;
                    });

                    // Posisikan peta mencakup seluruh marker yang ditemukan
                    if (this.markersList.length > 0 && this.map) {
                        const markerList = Object.values(this.markersMap);
                        if (markerList.length > 0) {
                            const group = Leaflet.featureGroup(markerList);
                            if (group.getBounds().isValid()) {
                                this.map.fitBounds(group.getBounds().pad(0.08));
                            }
                        }
                    }

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

                // Di layar HP, tutup sidebar saat marker dipilih
                if (window.innerWidth < 768) {
                    this.sidebarOpen = false;
                }
            }
        },

        openDetailById(id) {
            fetch(`/api/umkms/${id}`)
                .then(res => res.json())
                .then(data => {
                    this.activeModalData = data;
                    this.selectedUmkmModal = true;
                })
                .catch(err => console.error("Gagal memuat detail UMKM:", err));
        },

        cleanPhoneNumber(phone) {
            return cleanPhone(phone);
        }
    };
}

// Global handler dispatch
window.openDetail = function(id) {
    window.dispatchEvent(new CustomEvent('show-umkm-modal', { detail: { id: id } }));
};
</script>

<div class="h-full flex-1 flex flex-col md:flex-row relative overflow-hidden bg-slate-100"
     x-data="webgisApp()"
     x-on:show-umkm-modal.window="openDetailById($event.detail.id)">

    <!-- Tombol Toggle Mobile (Ponsel) -->
    <div class="md:hidden absolute top-3 left-3 z-30">
        <button @click="sidebarOpen = !sidebarOpen"
            class="px-3 py-2 rounded-xl bg-forest-900 text-white text-xs font-bold shadow-xl border border-forest-800 flex items-center gap-1.5">
            <span x-text="sidebarOpen ? '✕ Tutup Filter' : '☰ Filter & Direktori'"></span>
            <span class="text-[10px] px-1.5 py-0.2 bg-forest-800 rounded-full font-mono" x-text="markersCount"></span>
        </button>
    </div>

    <!-- Sidebar Filter & Kontrol Pencarian (Kiri) -->
    <div :class="sidebarOpen ? 'flex' : 'hidden md:flex'"
         class="w-full md:w-80 lg:w-96 bg-white border-r border-slate-200 flex-col z-20 shadow-md h-full overflow-hidden absolute md:relative inset-y-0 left-0">
        
        <!-- Header Filter -->
        <div class="p-4 border-b border-slate-100 bg-forest-950 text-white flex items-center justify-between">
            <div>
                <h2 class="font-bold text-sm tracking-tight flex items-center gap-1.5">
                    <span>🗺️</span> Eksplorasi Spasial GIS
                </h2>
                <p class="text-[11px] text-tobacco-400">Peta Sebaran & Sentra UMKM Tembakau</p>
            </div>
            <div class="flex items-center gap-2">
                <button @click="resetFilters()" class="text-[11px] font-semibold text-slate-300 hover:text-white px-2.5 py-1 rounded-lg bg-forest-900 border border-forest-800 transition">
                    Reset
                </button>
                <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white text-lg font-bold p-1">&times;</button>
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="p-4 space-y-3.5 border-b border-slate-200 bg-slate-50/50">
            <!-- Pencarian Real-time -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Cari Nama UMKM / Desa
                </label>
                <div class="relative">
                    <input type="text" x-model="searchQuery" @input.debounce.300ms="loadMarkers()"
                        placeholder="Contoh: Barurambat, SKT, Kadur, Larangan..."
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

            <!-- Toggle Layer & Info Jumlah Marker -->
            <div class="flex items-center justify-between pt-1 border-t border-slate-200/60">
                <label class="text-xs font-semibold text-slate-700 flex items-center cursor-pointer">
                    <input type="checkbox" x-model="showDistrictsLayer" @change="toggleDistrictsLayer()"
                        class="w-4 h-4 text-forest-700 rounded border-slate-300 focus:ring-forest-500 mr-2">
                    Batas Wilayah Kecamatan
                </label>
                <span class="text-[11px] font-bold text-forest-700 px-2 py-0.5 bg-forest-50 border border-forest-200 rounded-full" x-text="markersCount + ' Titik'"></span>
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
                    class="p-3 hover:bg-forest-50/70 rounded-xl cursor-pointer transition flex items-start space-x-3 text-xs group">
                    <div class="w-3.5 h-3.5 rounded-full mt-0.5 flex-shrink-0 ring-2 ring-white shadow-sm" :style="'background-color: ' + item.properties.marker_color"></div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <h4 class="font-bold text-slate-900 truncate group-hover:text-forest-800" x-text="item.properties.name"></h4>
                            <span x-show="item.properties.is_verified" class="text-[10px] text-emerald-600 font-bold bg-emerald-50 px-1 rounded">✓</span>
                        </div>
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
    <div class="flex-1 relative h-full w-full min-h-[450px]">
        <div id="map" class="h-full w-full z-10"></div>

        <!-- Basemap Switcher (Peta Satelit vs Jalan) -->
        <div class="absolute top-4 right-4 z-20 flex bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-slate-200/80 p-1 text-xs font-bold">
            <button @click="switchBasemap('street')"
                :class="basemap === 'street' ? 'bg-forest-800 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                class="px-3 py-1.5 rounded-xl transition flex items-center gap-1.5">
                <span>🗺️</span> Peta Jalan
            </button>
            <button @click="switchBasemap('satellite')"
                :class="basemap === 'satellite' ? 'bg-forest-800 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                class="px-3 py-1.5 rounded-xl transition flex items-center gap-1.5">
                <span>🛰️</span> Citra Satelit
            </button>
        </div>

        <!-- Tombol Floating Aksi Peta (Kanan Bawah) -->
        <div class="absolute bottom-6 right-6 z-20 flex flex-col space-y-2">
            <!-- Lokasi Pengguna (GPS) -->
            <button @click="locateUser()" :disabled="locatingUser"
                class="px-3.5 py-2.5 rounded-xl bg-white text-slate-800 text-xs font-bold shadow-xl border border-slate-200 hover:bg-slate-50 transition flex items-center gap-1.5">
                <svg class="w-4 h-4 text-blue-600" :class="locatingUser ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span x-text="locatingUser ? 'Mendeteksi...' : 'Lokasi Saya (GPS)'"></span>
            </button>

            <!-- Reset Center -->
            <button @click="resetMapView()"
                class="px-3.5 py-2.5 rounded-xl bg-forest-900 text-white text-xs font-bold shadow-xl border border-forest-800 hover:bg-forest-800 transition flex items-center gap-1.5">
                <svg class="w-4 h-4 text-tobacco-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                Pusatkan Peta
            </button>
        </div>
    </div>

    <!-- Modal Rincian UMKM Super Lengkap -->
    <div x-show="selectedUmkmModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
        <div @click.away="selectedUmkmModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-4">
            <!-- Modal Header -->
            <div class="flex items-start justify-between pb-3 border-b border-slate-100">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="inline-block px-2 py-0.5 text-[11px] font-mono font-bold rounded-md bg-forest-50 text-forest-800 border border-forest-200" x-text="activeModalData?.code"></span>
                        <template x-if="activeModalData?.is_verified">
                            <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold rounded-md bg-emerald-100 text-emerald-800">
                                ✓ Terverifikasi Resmi
                            </span>
                        </template>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-900" x-text="activeModalData?.name"></h3>
                    <p class="text-xs font-semibold text-forest-700" x-text="activeModalData?.category"></p>
                </div>
                <button @click="selectedUmkmModal = false" class="text-slate-400 hover:text-slate-600 text-2xl font-bold p-1 leading-none">&times;</button>
            </div>

            <!-- Grid Data Rinci -->
            <div class="grid grid-cols-2 gap-2.5 text-xs">
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Wilayah</span>
                    <span class="font-bold text-slate-800 text-xs" x-text="'Kec. ' + (activeModalData?.district || '-')"></span>
                    <span class="text-[11px] text-slate-500 block truncate" x-text="'Desa: ' + (activeModalData?.village || '-')"></span>
                </div>
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Status Operasional</span>
                    <span class="font-bold capitalize text-slate-800 text-xs" x-text="activeModalData?.business_status || 'Aktif'"></span>
                    <span class="text-[10px] text-slate-400 block">Periode: <span class="capitalize" x-text="activeModalData?.production_period || 'Tahunan'"></span></span>
                </div>
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Kapasitas Potensial</span>
                    <span class="font-bold text-slate-900 text-xs" x-text="new Intl.NumberFormat('id-ID').format(activeModalData?.production_capacity || 0) + ' ' + (activeModalData?.production_unit || 'Kg')"></span>
                </div>
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Realisasi Produksi</span>
                    <span class="font-bold text-forest-700 text-xs" x-text="new Intl.NumberFormat('id-ID').format(activeModalData?.actual_production || 0) + ' ' + (activeModalData?.production_unit || 'Kg')"></span>
                </div>
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Tingkat Utilisasi</span>
                    <span class="font-bold text-emerald-700 text-xs" x-text="(activeModalData?.utilization_rate || 0) + '%'"></span>
                </div>
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Penyerapan Tenaga Kerja</span>
                    <span class="font-bold text-slate-800 text-xs" x-text="(activeModalData?.worker_count || 0) + ' Pekerja Lokal'"></span>
                </div>
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Luas Lahan</span>
                    <span class="font-bold text-slate-800 text-xs" x-text="(activeModalData?.land_area_ha || 0) + ' Hektar'"></span>
                </div>
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Estimasi Perputaran</span>
                    <span class="font-bold text-amber-700 text-xs" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(activeModalData?.transaction_value || 0)"></span>
                </div>
            </div>

            <!-- Alamat & Titik Koordinat -->
            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 text-xs space-y-1">
                <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Alamat & Koordinat Geospasial</span>
                <p class="text-slate-700 font-medium" x-text="activeModalData?.address"></p>
                <div class="pt-1 flex items-center justify-between text-[11px] text-slate-500 font-mono">
                    <span x-text="'Lat: ' + activeModalData?.latitude + ' | Long: ' + activeModalData?.longitude"></span>
                </div>
            </div>

            <!-- Tombol Interaksi Rute & WhatsApp -->
            <div class="grid grid-cols-2 gap-2 pt-1">
                <!-- Tombol Google Maps Direction -->
                <a :href="'https://www.google.com/maps/dir/?api=1&destination=' + activeModalData?.latitude + ',' + activeModalData?.longitude"
                   target="_blank" rel="noopener noreferrer"
                   class="py-2.5 px-3 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition flex items-center justify-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Rute Google Maps</span>
                </a>

                <!-- Tombol Hubungi WhatsApp -->
                <template x-if="activeModalData?.contact_phone">
                    <a :href="'https://wa.me/' + cleanPhoneNumber(activeModalData.contact_phone) + '?text=' + encodeURIComponent('Halo, saya melihat profil usaha ' + activeModalData.name + ' di WebGIS Tembakau Pamekasan.')"
                       target="_blank" rel="noopener noreferrer"
                       class="py-2.5 px-3 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition flex items-center justify-center gap-1.5 shadow-sm">
                        <span>💬</span> Chat WhatsApp
                    </a>
                </template>
                <template x-if="!activeModalData?.contact_phone">
                    <button disabled class="py-2.5 px-3 rounded-xl text-xs font-medium text-slate-400 bg-slate-100 cursor-not-allowed text-center">
                        Kontak Belum Terdata
                    </button>
                </template>
            </div>

            <!-- Tombol Footer -->
            <div class="pt-2 flex justify-between items-center border-t border-slate-100">
                <button onclick="window.print()" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Rincian
                </button>
                <button @click="selectedUmkmModal = false" class="px-5 py-2 text-xs font-bold rounded-xl bg-forest-800 text-white hover:bg-forest-700 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
