@extends('layouts.admin', [
    'title' => 'Impor Data CSV',
    'header' => 'Pengelolaan Migrasi & Impor Data CSV'
])

@section('content')
<div class="space-y-6" x-data="csvImportApp()">
    <!-- Banner Panduan Impor & Unduh Template -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold text-slate-900">Impor Data Massal UMKM Tembakau</h2>
            <p class="text-xs text-slate-500 mt-1">
                Format berkas wajib berupa CSV dengan pemisah koma (,). Sistem akan memvalidasi koordinat dan mendeteksi data duplikat secara transaksional.
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.import.template') }}"
                class="inline-flex items-center px-4 py-2.5 text-xs font-bold rounded-xl text-forest-950 bg-tobacco-400 hover:bg-tobacco-300 transition shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Unduh Template CSV DEMO
            </a>
        </div>
    </div>

    <!-- Hasil Impor Terakhir Jika Ada -->
    @if(session('import_result'))
        @php $res = session('import_result'); @endphp
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-bold text-sm text-slate-900">Laporan Eksekusi Impor Terakhir</h3>
            <div class="grid grid-cols-3 gap-3 text-center text-xs">
                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Total Baris</span>
                    <span class="font-extrabold text-base text-slate-800">{{ $res['total'] }}</span>
                </div>
                <div class="p-3 bg-emerald-50 rounded-xl">
                    <span class="text-emerald-600 block text-[10px] uppercase font-bold">Berhasil Masuk</span>
                    <span class="font-extrabold text-base text-emerald-700">{{ $res['success'] }}</span>
                </div>
                <div class="p-3 bg-rose-50 rounded-xl">
                    <span class="text-rose-600 block text-[10px] uppercase font-bold">Gagal / Dilewati</span>
                    <span class="font-extrabold text-base text-rose-700">{{ $res['failed'] }}</span>
                </div>
            </div>

            @if(!empty($res['errors']))
                <div class="pt-2">
                    <h4 class="text-xs font-bold text-rose-700 uppercase tracking-wider mb-2">Rincian Baris Gagal / Defect:</h4>
                    <div class="max-h-48 overflow-y-auto border border-rose-200 rounded-xl divide-y divide-rose-100 bg-rose-50/50 text-xs">
                        @foreach ($res['errors'] as $err)
                            <div class="p-2.5 flex items-start space-x-2 text-rose-900">
                                <span class="font-mono font-bold text-rose-700 flex-shrink-0">Baris {{ $err['line'] }}:</span>
                                <span>{{ $err['reason'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Formulir Unggah & Pratinjau -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-6">
        <form action="{{ route('admin.import.process') }}" method="POST" enctype="multipart/form-data" id="importForm">
            @csrf

            <div class="space-y-3">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                    Pilih Berkas CSV Data UMKM (.csv)
                </label>
                <div class="flex items-center space-x-3">
                    <input type="file" name="csv_file" id="csvFileInput" accept=".csv,.txt" required
                        @change="handleFileSelect($event)"
                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-forest-50 file:text-forest-800 hover:file:bg-forest-100 cursor-pointer border border-slate-200 rounded-xl p-1">
                    
                    <button type="button" @click="previewCsv()" :disabled="!fileSelected || previewLoading"
                        class="px-4 py-2.5 text-xs font-bold rounded-xl text-slate-700 bg-slate-100 hover:bg-slate-200 disabled:opacity-50 transition flex-shrink-0">
                        <span x-show="!previewLoading">Pratinjau Data</span>
                        <span x-show="previewLoading" x-cloak>Memeriksa...</span>
                    </button>
                </div>
                <p class="text-[11px] text-slate-400">Batas ukuran berkas maksimal 5 MB.</p>
            </div>

            <!-- Tabel Pratinjau 5 Baris Pertama -->
            <div x-show="previewData" x-cloak class="pt-4 space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-forest-800">Pratinjau 5 Baris Pertama</h4>
                    <span class="text-[11px] text-slate-400" x-text="'Ditemukan ' + (previewData?.headers?.length || 0) + ' Kolom Header'"></span>
                </div>
                <div class="overflow-x-auto border border-slate-200 rounded-xl">
                    <table class="w-full text-left text-[11px]">
                        <thead class="bg-slate-50 text-slate-600 font-bold uppercase border-b border-slate-200">
                            <tr>
                                <template x-for="h in previewData?.headers" :key="h">
                                    <th class="px-3 py-2 text-nowrap" x-text="h"></th>
                                </template>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="(row, idx) in previewData?.preview_rows" :key="idx">
                                <tr class="hover:bg-slate-50">
                                    <template x-for="h in previewData?.headers" :key="h">
                                        <td class="px-3 py-2 text-nowrap text-slate-700" x-text="row[h] || '-'"></td>
                                    </template>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" :disabled="!fileSelected"
                    class="px-6 py-2.5 text-xs font-bold rounded-xl text-white bg-forest-800 hover:bg-forest-700 disabled:opacity-50 transition shadow-md">
                    Eksekusi Impor Transaksional
                </button>
            </div>
        </form>
    </div>

    <!-- Riwayat Log Impor CSV -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h3 class="font-bold text-slate-900 text-sm">Riwayat & Audit Log Impor Berkas</h3>
            <p class="text-xs text-slate-500">Rekapitulasi proses impor data massal yang telah dijalankan</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5">Waktu Impor</th>
                        <th class="px-5 py-3.5">Nama Berkas</th>
                        <th class="px-5 py-3.5">Pengunggah</th>
                        <th class="px-5 py-3.5">Total Baris</th>
                        <th class="px-5 py-3.5">Sukses</th>
                        <th class="px-5 py-3.5">Gagal</th>
                        <th class="px-5 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4 font-mono text-slate-600">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-4 font-bold text-slate-900">{{ $log->original_filename }}</td>
                            <td class="px-5 py-4 text-slate-700">{{ $log->user?->name ?? 'Sistem' }}</td>
                            <td class="px-5 py-4 font-semibold text-slate-800">{{ $log->total_rows }}</td>
                            <td class="px-5 py-4 font-bold text-emerald-700">{{ $log->success_rows }}</td>
                            <td class="px-5 py-4 font-bold text-rose-700">{{ $log->failed_rows }}</td>
                            <td class="px-5 py-4">
                                @if($log->status === 'success')
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Success</span>
                                @elseif($log->status === 'partial')
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Partial</span>
                                @else
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">Failed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-slate-400">
                                Belum ada riwayat berkas impor.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function csvImportApp() {
    return {
        fileSelected: false,
        previewLoading: false,
        previewData: null,

        handleFileSelect(event) {
            this.fileSelected = event.target.files.length > 0;
            this.previewData = null;
        },

        previewCsv() {
            const input = document.getElementById('csvFileInput');
            if (!input.files.length) return;

            this.previewLoading = true;
            const formData = new FormData();
            formData.append('csv_file', input.files[0]);

            fetch('{{ route("admin.import.preview") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                this.previewLoading = false;
                if (res.success) {
                    this.previewData = res.data;
                } else {
                    alert(res.message || 'Gagal memproses pratinjau berkas CSV.');
                }
            })
            .catch(err => {
                this.previewLoading = false;
                alert('Terjadi kesalahan jaringan atau berkas tidak valid.');
            });
        }
    };
}
</script>
@endpush
@endsection
