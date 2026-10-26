@extends('layouts.admin', [
    'title' => 'Laporan Rekapitulasi',
    'header' => 'Rekapitulasi Laporan Manajerial & Ekspor Data'
])

@section('content')
<div class="space-y-6">
    <!-- Header & Ekspor Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h2 class="text-base sm:text-lg font-bold text-slate-900">Rekapitulasi Data Administratif UMKM</h2>
            <p class="text-xs text-slate-500">Agregasi terpadu per kecamatan dan kategori rantai pasok tembakau</p>
        </div>
        <div>
            <a href="{{ route('admin.reports.export', request()->query()) }}"
                class="inline-flex items-center px-4 py-2.5 text-xs font-bold rounded-xl text-forest-950 bg-tobacco-400 hover:bg-tobacco-300 transition shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Ekspor CSV Administratif Lengkap
            </a>
        </div>
    </div>

    <!-- Ringkasan Rekap Wilayah & Kategori -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Rekap Kecamatan -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50/60">
                <h3 class="font-bold text-xs uppercase tracking-wider text-slate-700">Rekapitulasi per Kecamatan (13 Wilayah)</h3>
            </div>
            <div class="max-h-64 overflow-y-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 text-[10px] uppercase font-bold sticky top-0">
                        <tr>
                            <th class="px-4 py-2.5">Kecamatan</th>
                            <th class="px-4 py-2.5 text-center">UMKM</th>
                            <th class="px-4 py-2.5 text-right">Produksi Riil</th>
                            <th class="px-4 py-2.5 text-right">Pekerja</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($districtRecap as $d)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-2.5 font-bold text-slate-800">{{ $d['name'] }}</td>
                                <td class="px-4 py-2.5 text-center font-bold text-forest-700">{{ $d['umkm_count'] }}</td>
                                <td class="px-4 py-2.5 text-right text-slate-600">{{ number_format($d['total_production']) }}</td>
                                <td class="px-4 py-2.5 text-right font-medium text-slate-800">{{ $d['total_workers'] }} Org</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Rekap Kategori -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50/60">
                <h3 class="font-bold text-xs uppercase tracking-wider text-slate-700">Rekapitulasi per Rantai Pasok (6 Kategori)</h3>
            </div>
            <div class="max-h-64 overflow-y-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 text-[10px] uppercase font-bold sticky top-0">
                        <tr>
                            <th class="px-4 py-2.5">Kategori</th>
                            <th class="px-4 py-2.5 text-center">UMKM</th>
                            <th class="px-4 py-2.5 text-right">Pekerja</th>
                            <th class="px-4 py-2.5 text-right">Transaksi Agregat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($categoryRecap as $c)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-2.5 font-bold text-slate-800 flex items-center space-x-2">
                                    <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $c['marker_color'] }}"></span>
                                    <span>{{ $c['name'] }}</span>
                                </td>
                                <td class="px-4 py-2.5 text-center font-bold text-forest-700">{{ $c['umkm_count'] }}</td>
                                <td class="px-4 py-2.5 text-right font-medium text-slate-800">{{ $c['total_workers'] }} Org</td>
                                <td class="px-4 py-2.5 text-right text-slate-600">IDR {{ number_format($c['total_transactions'] / 1000000, 1) }} Jt</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Kecamatan</label>
                <select name="district_id" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none bg-white">
                    <option value="">Semua Wilayah</option>
                    @foreach ($districts as $d)
                        <option value="{{ $d->id }}" {{ request('district_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Kategori Usaha</label>
                <select name="category_id" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Status Usaha</label>
                <select name="status" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none bg-white">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="musiman" {{ request('status') == 'musiman' ? 'selected' : '' }}>Musiman</option>
                    <option value="non-aktif" {{ request('status') == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 py-2 text-xs font-bold rounded-xl text-white bg-forest-800 hover:bg-forest-700 transition">
                    Saring Laporan
                </button>
                <a href="{{ route('admin.reports.index') }}" class="py-2 px-3 text-xs font-semibold rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Rincian Data Administratif -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5">Kode</th>
                        <th class="px-5 py-3.5">Nama UMKM</th>
                        <th class="px-5 py-3.5">Pemilik</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">Kecamatan</th>
                        <th class="px-5 py-3.5">Kapasitas</th>
                        <th class="px-5 py-3.5">Produksi Riil</th>
                        <th class="px-5 py-3.5">Utilisasi</th>
                        <th class="px-5 py-3.5">Tenaga Kerja</th>
                        <th class="px-5 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($umkms as $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-3.5 font-mono font-bold text-forest-800">{{ $item->code }}</td>
                            <td class="px-5 py-3.5 font-bold text-slate-900">{{ $item->name }}</td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $item->owner_name ?: '-' }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                      style="background-color: {{ $item->category?->marker_color }}15; color: {{ $item->category?->marker_color }};">
                                    {{ $item->category?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-700">Kec. {{ $item->district?->name }}</td>
                            <td class="px-5 py-3.5 text-slate-700">{{ number_format($item->production_capacity) }} {{ $item->production_unit }}</td>
                            <td class="px-5 py-3.5 text-slate-700">{{ number_format($item->actual_production) }} {{ $item->production_unit }}</td>
                            <td class="px-5 py-3.5 font-bold text-emerald-700">{{ $item->capacity_utilization ?? 0 }}%</td>
                            <td class="px-5 py-3.5 font-medium text-slate-800">{{ $item->worker_count }} Org</td>
                            <td class="px-5 py-3.5">
                                @if($item->business_status === 'aktif')
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Aktif</span>
                                @elseif($item->business_status === 'musiman')
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Musiman</span>
                                @else
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">Non-Aktif</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-5 py-8 text-center text-slate-400">
                                Tidak ada data yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($umkms->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $umkms->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
