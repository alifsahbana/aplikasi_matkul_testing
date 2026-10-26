<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'WebGIS UMKM Tembakau Pamekasan' }} - Potensi Ekonomi Geospasial</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen text-slate-800 font-sans selection:bg-forest-500 selection:text-white">
    <!-- Navbar Publik -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 rounded-xl bg-forest-800 flex items-center justify-center text-white shadow-md shadow-forest-900/10 group-hover:bg-forest-700 transition">
                            <svg class="w-6 h-6 text-tobacco-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="block text-base font-bold text-forest-950 leading-tight">WebGIS Tembakau</span>
                            <span class="block text-xs font-medium text-slate-500">Kabupaten Pamekasan</span>
                        </div>
                    </a>
                </div>

                <!-- Nav Menu Desktop -->
                <nav class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('home') }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition {{ request()->routeIs('home') ? 'bg-forest-50 text-forest-800' : 'text-slate-600 hover:text-forest-900 hover:bg-slate-100' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('map.index') }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition {{ request()->routeIs('map.index') ? 'bg-forest-50 text-forest-800' : 'text-slate-600 hover:text-forest-900 hover:bg-slate-100' }}">
                        Peta Interaktif
                    </a>
                    <a href="{{ route('public.reports') }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition {{ request()->routeIs('public.reports') ? 'bg-forest-50 text-forest-800' : 'text-slate-600 hover:text-forest-900 hover:bg-slate-100' }}">
                        Direktori UMKM
                    </a>
                </nav>

                <!-- Auth Buttons / Profile -->
                <div class="flex items-center space-x-3">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-lg text-white bg-forest-800 hover:bg-forest-700 shadow-sm transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                                Panel Admin
                            </a>
                        @else
                            <span class="text-xs font-medium text-slate-600 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
                                {{ auth()->user()->name }}
                            </span>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-slate-100 rounded-lg transition" title="Keluar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-lg text-forest-900 bg-forest-50 hover:bg-forest-100 border border-forest-200 transition">
                            <svg class="w-4 h-4 mr-1.5 text-forest-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            Masuk Petugas
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Akademik -->
    <footer class="bg-forest-950 text-slate-300 border-t border-forest-900 text-sm mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2 space-y-3">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-lg bg-forest-800 flex items-center justify-center text-white">
                            <svg class="w-5 h-5 text-tobacco-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                        </div>
                        <span class="font-bold text-white text-base">WebGIS UMKM Tembakau Pamekasan</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-md">
                        Sistem Informasi Geospasial pemetaan rantai pasok dan potensi ekonomi komoditas tembakau di 13 kecamatan Kabupaten Pamekasan, Madura, Jawa Timur.
                    </p>
                    <p class="text-[11px] text-tobacco-400 font-medium">
                        Mata Kuliah: Manajemen dan Proyek Sistem Informasi / Software Quality Assurance (SQA)
                    </p>
                </div>
                <div>
                    <h5 class="text-xs font-bold uppercase tracking-wider text-white mb-3">Tautan Navigasi</h5>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Beranda & Dashboard</a></li>
                        <li><a href="{{ route('map.index') }}" class="hover:text-white transition">Peta Persebaran GIS</a></li>
                        <li><a href="{{ route('public.reports') }}" class="hover:text-white transition">Laporan & Ekspor Data</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Portal Masuk Petugas</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-xs font-bold uppercase tracking-wider text-white mb-3">Rantai Nilai Tembakau</h5>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li>• Pembibitan & Petani Tembakau</li>
                        <li>• Pengeringan & Rajangan</li>
                        <li>• Pengepul & Perdagangan</li>
                        <li>• Sigaret Kretek Tangan (SKT)</li>
                        <li>• Olahan & Turunan Nabati</li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t border-forest-900/60 text-center text-xs text-slate-400 flex flex-col sm:flex-row justify-between items-center gap-3">
                <span>&copy; {{ date('Y') }} WebGIS UMKM Tembakau Kabupaten Pamekasan. Hak Cipta Terpelihara.</span>
                <span class="text-[11px] bg-forest-900 px-3 py-1 rounded-full text-forest-200">Data demo untuk keperluan akademik</span>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
