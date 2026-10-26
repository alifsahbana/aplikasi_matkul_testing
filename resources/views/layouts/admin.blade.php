<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Panel Admin' }} - WebGIS Tembakau Pamekasan</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full font-sans antialiased text-slate-800" x-data="{ sidebarOpen: false }">
    <div class="min-h-full flex flex-col md:flex-row">
        <!-- Sidebar Mobile Backdrop -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm md:hidden" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <!-- Sidebar Utama -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" class="fixed inset-y-0 left-0 z-50 w-64 bg-forest-950 text-white flex flex-col transition-transform duration-300 ease-in-out md:static md:inset-auto md:min-h-screen">
            <!-- Brand Info -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-forest-900">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-forest-800 flex items-center justify-center text-white shadow-inner">
                        <svg class="w-5 h-5 text-tobacco-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    </div>
                    <div>
                        <span class="block text-sm font-bold tracking-tight text-white">Admin WebGIS</span>
                        <span class="block text-[11px] text-tobacco-400 font-medium">Kab. Pamekasan</span>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">&times;</button>
            </div>

            <!-- Navigasi Menu -->
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                <div class="px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-forest-400">Menu Utama</div>

                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3.5 py-2.5 text-xs font-semibold rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-forest-800 text-white shadow-sm' : 'text-slate-300 hover:bg-forest-900 hover:text-white' }}">
                    <svg class="w-4 h-4 mr-3 text-forest-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard Manajerial
                </a>

                <a href="{{ route('admin.umkm.index') }}" class="flex items-center px-3.5 py-2.5 text-xs font-semibold rounded-xl transition {{ request()->routeIs('admin.umkm.*') ? 'bg-forest-800 text-white shadow-sm' : 'text-slate-300 hover:bg-forest-900 hover:text-white' }}">
                    <svg class="w-4 h-4 mr-3 text-forest-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Data UMKM Tembakau
                </a>

                <a href="{{ route('admin.import.index') }}" class="flex items-center px-3.5 py-2.5 text-xs font-semibold rounded-xl transition {{ request()->routeIs('admin.import.*') ? 'bg-forest-800 text-white shadow-sm' : 'text-slate-300 hover:bg-forest-900 hover:text-white' }}">
                    <svg class="w-4 h-4 mr-3 text-forest-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Impor Data CSV
                </a>

                <a href="{{ route('admin.analysis.index') }}" class="flex items-center px-3.5 py-2.5 text-xs font-semibold rounded-xl transition {{ request()->routeIs('admin.analysis.*') ? 'bg-forest-800 text-white shadow-sm' : 'text-slate-300 hover:bg-forest-900 hover:text-white' }}">
                    <svg class="w-4 h-4 mr-3 text-forest-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Analisis Potensi Ekonomi
                </a>

                <a href="{{ route('admin.reports.index') }}" class="flex items-center px-3.5 py-2.5 text-xs font-semibold rounded-xl transition {{ request()->routeIs('admin.reports.*') ? 'bg-forest-800 text-white shadow-sm' : 'text-slate-300 hover:bg-forest-900 hover:text-white' }}">
                    <svg class="w-4 h-4 mr-3 text-forest-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Laporan Rekapitulasi
                </a>

                <div class="pt-6 px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-forest-400">Akses Publik</div>

                <a href="{{ route('map.index') }}" target="_blank" class="flex items-center px-3.5 py-2 text-xs font-semibold text-slate-300 hover:bg-forest-900 hover:text-white rounded-xl transition">
                    <svg class="w-4 h-4 mr-3 text-forest-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Buka Peta Publik
                </a>
            </nav>

            <!-- User Footer in Sidebar -->
            <div class="p-4 border-t border-forest-900 bg-forest-950/80">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-full bg-forest-800 text-white font-bold flex items-center justify-center text-xs">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-forest-300 truncate">Administrator</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Area Konten Utama -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Topbar Header -->
            <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex items-center space-x-3">
                    <button @click="sidebarOpen = true" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h1 class="text-base sm:text-lg font-bold text-slate-900">{{ $header ?? 'Panel Kontrol Administrator' }}</h1>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="text-xs font-semibold text-slate-600 hover:text-forest-900 px-3 py-1.5 rounded-lg hover:bg-slate-100 transition hidden sm:inline-block">
                        Lihat Beranda Publik
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-lg text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </header>

            <!-- Content Body -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
                <div class="max-w-7xl mx-auto">
                    @include('components.alert')
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
