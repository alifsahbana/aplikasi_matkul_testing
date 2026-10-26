@extends('layouts.admin', [
    'title' => 'Manajemen UMKM',
    'header' => 'Kelola Data UMKM Tembakau Pamekasan'
])

@section('content')
<div class="space-y-6">
    <!-- Header Aksi & Filter -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h2 class="text-base sm:text-lg font-bold text-slate-900">Daftar Data Master UMKM</h2>
            <p class="text-xs text-slate-500">Total data tercatat dalam sistem: {{ $umkms->total() }} UMKM</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.umkm.create') }}"
                class="inline-flex items-center px-4 py-2.5 text-xs font-bold rounded-xl text-white bg-forest-800 hover:bg-forest-700 transition shadow-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah UMKM Baru
            </a>
            <a href="{{ route('admin.import.index') }}"
                class="inline-flex items-center px-4 py-2.5 text-xs font-bold rounded-xl text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition">
                <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Impor CSV
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.umkm.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div class="lg:col-span-2">
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Cari Kode / Nama / Pemilik</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Ketik kata kunci pencarian..."
                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Kecamatan</label>
                <select name="district_id" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-forest-600 focus:outline-none bg-white">
                    <option value="">Semua Kecamatan</option>
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

            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 py-2 px-3 text-xs font-bold rounded-xl text-white bg-forest-800 hover:bg-forest-700 transition">
                    Filter
                </button>
                <a href="{{ route('admin.umkm.index') }}" class="py-2 px-3 text-xs font-semibold rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Data UMKM -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" x-data="{ deleteModal: false, deleteUrl: '', deleteName: '' }">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5">Kode</th>
                        <th class="px-5 py-3.5">Nama Usaha / Pemilik</th>
                        <th class="px-5 py-3.5">Kategori Rantai Pasok</th>
                        <th class="px-5 py-3.5">Wilayah</th>
                        <th class="px-5 py-3.5">Kapasitas</th>
                        <th class="px-5 py-3.5">Pekerja</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($umkms as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-4 font-mono font-bold text-forest-800">{{ $item->code }}</td>
                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-900 block">{{ $item->name }}</span>
                                <span class="text-[11px] text-slate-400 block">Pemilik: {{ $item->owner_name ?: '-' }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold"
                                      style="background-color: {{ $item->category?->marker_color }}15; color: {{ $item->category?->marker_color }};">
                                    {{ $item->category?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-medium text-slate-800 block">Kec. {{ $item->district?->name }}</span>
                                <span class="text-[11px] text-slate-400 block">Desa: {{ $item->village ?: '-' }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-700">
                                <span class="font-semibold block">{{ number_format($item->production_capacity) }} {{ $item->production_unit }}</span>
                                <span class="text-[11px] text-emerald-600 block">Aktual: {{ number_format($item->actual_production) }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-700 font-medium">{{ $item->worker_count }} Org</td>
                            <td class="px-5 py-4">
                                @if($item->business_status === 'aktif')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Aktif</span>
                                @elseif($item->business_status === 'musiman')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Musiman</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right space-x-1">
                                <a href="{{ route('admin.umkm.show', $item) }}" class="p-1.5 text-slate-500 hover:text-forest-700 hover:bg-forest-50 rounded-lg inline-block transition" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('admin.umkm.edit', $item) }}" class="p-1.5 text-slate-500 hover:text-amber-700 hover:bg-amber-50 rounded-lg inline-block transition" title="Edit Data">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <button type="button" @click="deleteModal = true; deleteUrl = '{{ route('admin.umkm.destroy', $item) }}'; deleteName = '{{ $item->name }}'"
                                    class="p-1.5 text-slate-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg inline-block transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                Belum ada data UMKM yang terdaftar.
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

        <!-- Modal Konfirmasi Hapus -->
        <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div @click.away="deleteModal = false" class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-slate-200 text-center space-y-4">
                <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="font-bold text-base text-slate-900">Konfirmasi Hapus</h3>
                <p class="text-xs text-slate-500">
                    Apakah Anda yakin ingin menghapus data UMKM <strong class="text-slate-800" x-text="deleteName"></strong>? Tindakan ini tidak dapat dibatalkan.
                </p>
                <form :action="deleteUrl" method="POST" class="pt-2 flex space-x-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="deleteModal = false" class="flex-1 py-2 text-xs font-semibold rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-2 text-xs font-bold rounded-xl bg-rose-600 text-white hover:bg-rose-700 transition">
                        Hapus Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
