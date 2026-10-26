@extends('layouts.app', ['title' => 'Direktori & Laporan UMKM'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <!-- Header Halaman -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Direktori & Laporan Publik UMKM Tembakau</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftar unit usaha tembakau resmi terverifikasi di Kabupaten Pamekasan</p>
        </div>
        <div>
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
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Ketik kata kunci..."
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
                        <th class="px-6 py-3.5">Kode</th>
                        <th class="px-6 py-3.5">Nama Usaha</th>
                        <th class="px-6 py-3.5">Kategori Rantai Pasok</th>
                        <th class="px-6 py-3.5">Wilayah</th>
                        <th class="px-6 py-3.5">Kapasitas Produksi</th>
                        <th class="px-6 py-3.5">Produksi Riil</th>
                        <th class="px-6 py-3.5">Pekerja</th>
                        <th class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($umkms as $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-6 py-4 font-mono font-bold text-forest-800">{{ $item->code }}</td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-900 block">{{ $item->name }}</span>
                                <span class="text-[11px] text-slate-400 block">{{ $item->address }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold"
                                      style="background-color: {{ $item->category?->marker_color }}15; color: {{ $item->category?->marker_color }};">
                                    {{ $item->category?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-800 block">Kec. {{ $item->district?->name }}</span>
                                <span class="text-[11px] text-slate-400 block">Desa: {{ $item->village ?: '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-700">
                                {{ number_format($item->production_capacity) }} {{ $item->production_unit }}
                            </td>
                            <td class="px-6 py-4 text-slate-700">
                                {{ number_format($item->actual_production) }} {{ $item->production_unit }}
                            </td>
                            <td class="px-6 py-4 text-slate-700 font-medium">{{ $item->worker_count }} Org</td>
                            <td class="px-6 py-4">
                                @if($item->business_status === 'aktif')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Aktif</span>
                                @elseif($item->business_status === 'musiman')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Musiman</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">Non-Aktif</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
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
