<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>@yield('title', 'Dashboard Pengelola - Tanjung Api')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS & Vite -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Chart.js for analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .text-gradient {
            background: linear-gradient(135deg, #34d399 0%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>

    @yield('styles')
</head>
<body class="h-full flex text-slate-800 antialiased">

    <!-- Desktop Sidebar -->
    <aside class="hidden md:flex md:flex-col md:w-64 bg-slate-900 text-slate-300 border-r border-slate-800 flex-shrink-0">
        <!-- Sidebar Header -->
        <div class="h-16 flex items-center px-6 border-b border-slate-800 gap-2 bg-slate-950">
            <img src="{{ asset('images/logo.jpg') }}" class="w-7 h-7 rounded-full object-cover border border-emerald-500/20" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover;" alt="Logo">
            <span class="font-extrabold text-sm sm:text-base tracking-widest text-emerald-400">ADMIN PANEL</span>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path>
                </svg>
                Dashboard
            </a>
            
            <a href="{{ route('admin.units.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.units.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Kelola Unit
            </a>

            <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.bookings.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                Kelola Pemesanan
            </a>

            <a href="{{ route('admin.walkin.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.walkin.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                Reservasi Langsung
            </a>

            <a href="{{ route('admin.guests.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.guests.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Kelola Tamu
            </a>

            <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.reports.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path>
                </svg>
                Laporan Keuangan
            </a>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-800/80 bg-slate-950 text-center">
            <span class="text-[11px] font-semibold text-slate-500">Tanjung Api Management</span>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col min-h-screen overflow-x-hidden">
        <!-- Top Nav Header -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 z-30 shadow-sm">
            <!-- Left Header -->
            <div class="flex items-center gap-3">
                <button type="button" id="mobile-sidebar-btn" class="md:hidden p-2 rounded-xl hover:bg-slate-100 text-slate-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h2 class="font-extrabold text-slate-900 text-base sm:text-lg">@yield('header_title', 'Administrasi')</h2>
            </div>

            <!-- Right Header Actions -->
            <div class="flex items-center gap-3 sm:gap-4">
                <a href="{{ route('home') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100/70 border border-emerald-200/60 py-2 px-3.5 rounded-xl transition-all flex items-center gap-1.5 shadow-sm">
                    <span>Lihat Web Utama</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                </a>

                <div class="h-6 w-px bg-slate-200"></div>

                <!-- Logged In User Profile Menu -->
                <div class="relative" id="user-menu-container">
                    <button type="button" id="user-menu-button" class="flex items-center gap-2.5 p-1.5 pr-2.5 rounded-xl hover:bg-slate-100 transition-all border border-transparent hover:border-slate-200 focus:outline-none group">
                        <div class="relative">
                            <img src="{{ asset('images/logo.jpg') }}" class="w-9 h-9 rounded-full object-cover border-2 border-emerald-500 shadow-sm" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;" alt="Profile Picture">
                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-white rounded-full"></span>
                        </div>
                        <div class="text-left hidden sm:block">
                            <span class="block text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition-colors leading-tight truncate max-w-[140px]">{{ session('admin_nama', 'Admin') }}</span>
                            <span class="block text-[11px] font-medium text-slate-500 leading-tight">Administrator</span>
                        </div>
                        <svg id="user-menu-chevron" class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu Card -->
                    <div id="user-menu-dropdown" class="hidden absolute right-0 mt-2 w-60 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 transition-all">
                        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                            <p class="text-sm font-extrabold text-slate-800 truncate">{{ session('admin_nama', 'Admin') }}</p>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Administrator</p>
                        </div>

                        <div class="py-1">
                            <a href="{{ route('admin.profile') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Edit Profil
                            </a>
                        </div>

                        <div class="border-t border-slate-100 pt-1">
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-red-600 hover:bg-red-50 transition-colors text-left cursor-pointer">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Mobile Sidebar Menu Backdrop -->
        <div id="mobile-sidebar" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm md:hidden">
            <div class="w-64 bg-slate-900 h-full text-slate-300 flex flex-col justify-between">
                <div>
                    <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800 bg-slate-950">
                        <span class="font-extrabold text-sm sm:text-base tracking-widest text-emerald-400">ADMIN PANEL</span>
                        <button type="button" id="mobile-sidebar-close" class="p-1 rounded-lg hover:bg-slate-800 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <nav class="px-4 py-6 space-y-1.5">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'hover:bg-slate-800 hover:text-white' }}">Dashboard</a>
                        <a href="{{ route('admin.units.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.units.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'hover:bg-slate-800 hover:text-white' }}">Kelola Unit</a>
                        <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.bookings.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'hover:bg-slate-800 hover:text-white' }}">Kelola Pemesanan</a>
                        <a href="{{ route('admin.walkin.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.walkin.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'hover:bg-slate-800 hover:text-white' }}">Reservasi Langsung</a>
                        <a href="{{ route('admin.guests.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.guests.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'hover:bg-slate-800 hover:text-white' }}">Kelola Tamu</a>
                        <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.reports.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'hover:bg-slate-800 hover:text-white' }}">Laporan Keuangan</a>
                    </nav>
                </div>
                <div class="p-4 border-t border-slate-800 bg-slate-950 flex flex-col gap-2">
                    <div class="flex items-center gap-3 px-2">
                        <a href="{{ route('admin.profile') }}" class="flex-shrink-0">
                            <img src="{{ asset('images/logo.jpg') }}" class="w-10 h-10 rounded-full object-cover border-2 border-emerald-500" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" alt="Profile Picture">
                        </a>
                        <div class="min-w-0 flex-1">
                            <a href="{{ route('admin.profile') }}" class="font-semibold text-white text-sm block truncate">{{ session('admin_nama') }}</a>
                            <span class="text-[11px] text-slate-500 block truncate">Administrator</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="mt-4 flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-red-600/10 hover:bg-red-600 text-red-500 hover:text-white rounded-xl text-sm font-bold transition-all">Keluar Panel</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Success & Error Alert Banners -->
        @if(session('success') || session('error') || $errors->any())
        <div class="max-w-7xl w-full mx-auto px-6 mt-6">
            @if(session('success'))
                <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm font-semibold">{{ session('success') }}</div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm font-semibold">{{ session('error') }}</div>
                </div>
            @endif
        </div>
        @endif

        <!-- Main Dashboard View Container -->
        <main class="flex-grow p-6">
            @yield('content')
        </main>
    </div>

    <!-- Toggle Mobile Sidebar & User Menu Scripts -->
    <script>
        const btnSidebarOpen = document.getElementById('mobile-sidebar-btn');
        const btnSidebarClose = document.getElementById('mobile-sidebar-close');
        const mobileSidebar = document.getElementById('mobile-sidebar');

        btnSidebarOpen?.addEventListener('click', () => {
            mobileSidebar?.classList.remove('hidden');
        });

        btnSidebarClose?.addEventListener('click', () => {
            mobileSidebar?.classList.add('hidden');
        });
        
        mobileSidebar?.addEventListener('click', (e) => {
            if (e.target === mobileSidebar) {
                mobileSidebar.classList.add('hidden');
            }
        });

        // User Menu Dropdown Toggle
        const userMenuBtn = document.getElementById('user-menu-button');
        const userMenuDropdown = document.getElementById('user-menu-dropdown');
        const userMenuChevron = document.getElementById('user-menu-chevron');

        if (userMenuBtn && userMenuDropdown) {
            userMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                userMenuDropdown.classList.toggle('hidden');
                if (userMenuChevron) {
                    userMenuChevron.classList.toggle('rotate-180');
                }
            });

            document.addEventListener('click', (e) => {
                if (!userMenuDropdown.contains(e.target) && !userMenuBtn.contains(e.target)) {
                    userMenuDropdown.classList.add('hidden');
                    if (userMenuChevron) {
                        userMenuChevron.classList.remove('rotate-180');
                    }
                }
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
