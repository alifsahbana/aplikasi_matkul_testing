@extends('layouts.app', ['title' => 'Masuk Petugas'])

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <div class="w-14 h-14 rounded-2xl bg-forest-800 flex items-center justify-center text-white shadow-lg shadow-forest-900/15">
                <svg class="w-8 h-8 text-tobacco-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
        </div>
        <h2 class="mt-4 text-center text-2xl font-extrabold text-slate-900 tracking-tight">
            Portal Masuk Petugas
        </h2>
        <p class="mt-1 text-center text-xs text-slate-500">
            Akses verifikasi data dan panel manajerial WebGIS Tembakau Pamekasan
        </p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-6 shadow-xl shadow-slate-200/60 rounded-2xl border border-slate-200 sm:px-10">
            @include('components.alert')

            <form action="{{ route('login') }}" method="POST" class="space-y-4" id="loginForm">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Alamat Email
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                            class="appearance-none block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600 text-sm"
                            placeholder="nama@pamekasan-gis.id">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Kata Sandi
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="appearance-none block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600 text-sm"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center text-slate-600">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-forest-700 border-slate-300 rounded focus:ring-forest-500">
                        <span class="ml-2">Ingat sesi saya</span>
                    </label>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-forest-800 hover:bg-forest-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-forest-600 transition">
                        Masuk ke Sistem
                    </button>
                </div>
            </form>

            <!-- Quick fill login demo -->
            <div class="mt-6 pt-6 border-t border-slate-100">
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center mb-3">
                    Akses Cepat Demo Pengujian (SQA)
                </p>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" onclick="fillAdmin()"
                        class="px-3 py-2 text-xs font-bold rounded-xl bg-forest-50 text-forest-900 border border-forest-200 hover:bg-forest-100 transition text-center">
                        Akun Admin
                    </button>
                    <button type="button" onclick="fillPengunjung()"
                        class="px-3 py-2 text-xs font-bold rounded-xl bg-slate-100 text-slate-800 border border-slate-200 hover:bg-slate-200 transition text-center">
                        Akun Pengunjung
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function fillAdmin() {
        document.getElementById('email').value = 'admin@pamekasan-gis.id';
        document.getElementById('password').value = 'admin12345';
    }
    function fillPengunjung() {
        document.getElementById('email').value = 'pengunjung@pamekasan-gis.id';
        document.getElementById('password').value = 'pengunjung123';
    }
</script>
@endsection
