@extends('layouts.app', ['title' => 'Direktori & Laporan UMKM'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <!-- Header Halaman -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xl">📋</span>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Direktori & Laporan Publik UMKM Tembakau</h1>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftar unit usaha tembakau resmi terverifikasi lengkap dengan kapasitas dan kontak di Kabupaten Pamekasan</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <button onclick="window.print()"
                class="inline-flex items-center px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition border border-slate-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak / Simpan PDF
            </button>
            <a href="{{ route('public.reports.export', request()->query()) }}"
                class="inline-flex items-center px-4 py-2.5 rounded-xl text-xs font-bold text-forest-950 bg-tobacco-400 hover:bg-tobacco-300 transition shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Unduh Berkas CSV
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('public.reports') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Cari Nama -->
            <div class="lg:col-span-2">
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Cari UMKM / Desa</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Ketik nama usaha atau desa..."
                    class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none">
            </div>

            <!-- Filter Kecamatan -->
            <div>
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Kecamatan</label>
                <select name="district_id" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none bg-white">
                    <option value="">Semua Kecamatan</option>
                    @foreach ($districts as $d)
                        <option value="{{ $d->id }}" {{ request('district_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Kategori -->
            <div>
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Kategori Usaha</label>
                <select name="category_id" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 py-2 px-3 text-xs font-bold rounded-xl text-white bg-forest-800 hover:bg-forest-700 transition">
                    Terapkan
                </button>
                <a href="{{ route('public.reports') }}" class="py-2 px-3 text-xs font-semibold rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Direktori -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5">Kode</th>
                        <th class="px-5 py-3.5">Nama Usaha & Pemilik</th>
                        <th class="px-5 py-3.5">Kategori Rantai Pasok</th>
                        <th class="px-5 py-3.5">Wilayah</th>
                        <th class="px-5 py-3.5 text-right">Kapasitas</th>
                        <th class="px-5 py-3.5 text-right">Realisasi</th>
                        <th class="px-5 py-3.5 text-center">Pekerja</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-center">Aksi / Navigasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($umkms as $item)
                        @php
                            $rawPhone = $item->contact_phone;
                            $cleanPhone = $rawPhone ? preg_replace('/[^0-9]/', '', $rawPhone) : '';
                            if (str_starts_with($cleanPhone, '0')) {
                                $cleanPhone = '62' . substr($cleanPhone, 1);
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-4 font-mono font-bold text-forest-800 whitespace-nowrap">
                                {{ $item->code }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-bold text-slate-900">{{ $item->name }}</span>
                                    @if($item->is_verified)
                                        <span class="text-[10px] text-emerald-700 bg-emerald-50 px-1.5 py-0.2 rounded font-bold" title="Terverifikasi Resmi Dinas">✓</span>
                                    @endif
                                </div>
                                <span class="text-[11px] text-slate-400 block truncate max-w-xs">{{ $item->address }}</span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold"
                                      style="background-color: {{ $item->category?->marker_color }}15; color: {{ $item->category?->marker_color }};">
                                    {{ $item->category?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="font-medium text-slate-800 block">Kec. {{ $item->district?->name }}</span>
                                <span class="text-[11px] text-slate-400 block">Desa: {{ $item->village ?: '-' }}</span>
                            </td>
                            <td class="px-5 py-4 text-right font-medium text-slate-700 whitespace-nowrap">
                                {{ number_format($item->production_capacity) }} <span class="text-[10px] text-slate-400">{{ $item->production_unit }}</span>
                            </td>
                            <td class="px-5 py-4 text-right font-bold text-forest-800 whitespace-nowrap">
                                {{ number_format($item->actual_production) }} <span class="text-[10px] text-slate-400">{{ $item->production_unit }}</span>
                            </td>
                            <td class="px-5 py-4 text-center text-slate-700 font-medium whitespace-nowrap">
                                {{ $item->worker_count }} Org
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                @if($item->business_status === 'aktif')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Aktif</span>
                                @elseif($item->business_status === 'musiman')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Musiman</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Link ke Peta WebGIS -->
                                    <a href="{{ route('map.index', ['q' => $item->name]) }}"
                                       title="Buka titik di Peta GIS"
                                       class="p-1.5 rounded-lg text-slate-600 hover:text-forest-800 hover:bg-forest-50 transition border border-slate-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                                    </a>

                                    <!-- Link Rute Google Maps -->
                                    @if($item->latitude && $item->longitude)
                                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $item->latitude }},{{ $item->longitude }}"
                                           target="_blank" rel="noopener noreferrer"
                                           title="Buka rute navigasi Google Maps"
                                           class="p-1.5 rounded-lg text-blue-600 hover:text-blue-800 hover:bg-blue-50 transition border border-blue-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </a>
                                    @endif

                                    <!-- Link WhatsApp -->
                                    @if($cleanPhone)
                                        <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Halo, saya melihat profil usaha ' . $item->name . ' di Direktori Tembakau Pamekasan.') }}"
                                           target="_blank" rel="noopener noreferrer"
                                           title="Chat via WhatsApp"
                                           class="p-1.5 rounded-lg text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 transition border border-emerald-200">
                                            <span class="text-xs">💬</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                                <div class="max-w-xs mx-auto space-y-2">
                                    <svg class="w-8 h-8 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="font-bold text-slate-600">Tidak ada data ditemukan</p>
                                    <p class="text-xs text-slate-400">Coba ubah filter atau kata kunci pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($umkms->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $umkms->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
