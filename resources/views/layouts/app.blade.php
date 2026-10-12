<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>@yield('title', 'Reservasi Unit Tanjung Api')</title>
    <meta name="description" content="Sistem Informasi Reservasi Unit Tanjung Api - Objek Wisata dan Konservasi Penyu di Desa Sebubus, Paloh, Sambas.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS & Vite -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Flatpickr Datepicker (Sesuai format Indonesia DD/MM/YYYY) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/emerald.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>


    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        .text-gradient {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>

    @yield('styles')
</head>
<body class="flex flex-col min-h-screen text-slate-800 antialiased selection:bg-emerald-500 selection:text-white overflow-x-hidden">

    <!-- Header / Translucent Sticky Navbar -->
    <header class="sticky top-0 z-40 glass-nav shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <img src="{{ asset('images/logo.jpg') }}" class="w-9 h-9 rounded-full object-cover shadow-sm border border-emerald-100" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;" alt="Logo Tanjung Api">
                        <span class="font-extrabold text-xl tracking-tight text-gradient sm:text-2xl">Tanjung Api</span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="font-semibold text-sm transition-colors {{ request()->routeIs('home') ? 'text-emerald-600' : 'text-slate-600 hover:text-emerald-600' }}">Beranda</a>
                    <a href="{{ route('units.index') }}" class="font-semibold text-sm transition-colors {{ request()->routeIs('units.index') || request()->routeIs('units.show') ? 'text-emerald-600' : 'text-slate-600 hover:text-emerald-600' }}">Unit</a>
                    <a href="{{ route('about') }}" class="font-semibold text-sm transition-colors {{ request()->routeIs('about') ? 'text-emerald-600' : 'text-slate-600 hover:text-emerald-600' }}">Tentang Kami</a>
                    <a href="{{ route('contact') }}" class="font-semibold text-sm transition-colors {{ request()->routeIs('contact') ? 'text-emerald-600' : 'text-slate-600 hover:text-emerald-600' }}">Kontak</a>
                </nav>

                <!-- Auth Action Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    @if(session()->has('tamu_id'))
                        <!-- User Profile Dropdown -->
                        <div class="relative" id="user-dropdown-container">
                            <button type="button" id="user-dropdown-btn" class="flex items-center gap-2.5 py-1.5 px-2 rounded-full hover:bg-slate-100/80 transition-all duration-200 group">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #059669, #0d9488); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 14px; box-shadow: 0 4px 6px -1px rgba(5,150,105,0.2); outline: 2px solid #fff; outline-offset: 0px; transition: all 0.2s;">
                                    {{ strtoupper(substr(session('tamu_nama'), 0, 1)) }}
                                </div>
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-transform duration-200" id="dropdown-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="user-dropdown-menu" class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 py-2 opacity-0 invisible translate-y-2 transition-all duration-200 z-50">
                                <!-- User Info Header -->
                                <div class="px-4 py-3 border-b border-slate-100">
                                    <div class="flex items-center gap-3">
                                        <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #059669, #0d9488); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 14px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                            {{ strtoupper(substr(session('tamu_nama'), 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800">{{ session('tamu_nama') }}</p>
                                            <p class="text-xs text-slate-500">Pengguna Terdaftar</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Menu Items -->
                                <div class="py-1.5">
                                    <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors {{ request()->routeIs('profile') ? 'text-emerald-600 bg-emerald-50/50' : '' }}">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        Profil Saya
                                    </a>
                                    <a href="{{ route('bookings.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors {{ request()->routeIs('bookings.index') ? 'text-emerald-600 bg-emerald-50/50' : '' }}">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                            </svg>
                                        </div>
                                        Riwayat Pesanan
                                    </a>
                                </div>

                                <!-- Logout -->
                                <div class="border-t border-slate-100 pt-1.5 mt-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                                            <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                </svg>
                                            </div>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-sm text-slate-600 hover:text-emerald-600 transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-emerald-600/10 hover:shadow-lg hover:shadow-emerald-600/20 active:scale-95">Daftar</a>
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center md:hidden">
                    <button type="button" id="mobile-menu-btn" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white/95 border-b border-slate-100 transition-all duration-300">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('home') }}" class="block px-3 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('home') ? 'text-emerald-600 bg-emerald-50' : 'text-slate-700 hover:bg-emerald-50 hover:text-emerald-600' }}">Beranda</a>
                <a href="{{ route('units.index') }}" class="block px-3 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('units.index') || request()->routeIs('units.show') ? 'text-emerald-600 bg-emerald-50' : 'text-slate-700 hover:bg-emerald-50 hover:text-emerald-600' }}">Unit</a>
                <a href="{{ route('about') }}" class="block px-3 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('about') ? 'text-emerald-600 bg-emerald-50' : 'text-slate-700 hover:bg-emerald-50 hover:text-emerald-600' }}">Tentang Kami</a>
                <a href="{{ route('contact') }}" class="block px-3 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('contact') ? 'text-emerald-600 bg-emerald-50' : 'text-slate-700 hover:bg-emerald-50 hover:text-emerald-600' }}">Kontak</a>
                <hr class="border-slate-100 my-2">
                @if(session()->has('tamu_id'))
                    <a href="{{ route('bookings.index') }}" class="block px-3 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('bookings.index') ? 'text-emerald-600 bg-emerald-50' : 'text-slate-700 hover:bg-emerald-50 hover:text-emerald-600' }}">Riwayat Pesanan</a>
                    <a href="{{ route('profile') }}" class="block px-3 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('profile') ? 'text-emerald-600 bg-emerald-50' : 'text-slate-700 hover:bg-emerald-50 hover:text-emerald-600' }}">Profil Saya</a>
                    <div class="px-3 py-2 text-sm text-slate-500">Masuk sebagai: <strong>{{ session('tamu_nama') }}</strong></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2.5 rounded-xl text-base font-semibold text-red-600 hover:bg-red-50">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2.5 rounded-xl text-base font-semibold text-slate-700 hover:bg-emerald-50">Masuk</a>
                    <a href="{{ route('register') }}" class="block mx-3 my-2 text-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-base rounded-xl">Daftar</a>
                @endif
            </div>
        </div>
    </header>

    <!-- Success & Error Alert Messages -->
    @if(session('success') || session('error') || $errors->any())
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 shadow-sm animate-fade-in">
                <svg class="w-5 h-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm font-semibold">{{ session('success') }}</div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 shadow-sm animate-fade-in">
                <svg class="w-5 h-5 flex-shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm font-semibold">{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any() && !request()->routeIs('login') && !request()->routeIs('register'))
            <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 shadow-sm">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-bold text-sm">Terjadi beberapa kesalahan:</span>
                </div>
                <ul class="list-disc pl-5 text-sm space-y-1 font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    @endif

    @if(session()->has('tamu_id'))
        @php
            $activeTamu = \App\Models\Tamu::find(session('tamu_id'));
        @endphp
        @if($activeTamu && !$activeTamu->hasVerifiedEmail())
            <div class="bg-amber-500 text-white text-xs sm:text-sm font-semibold py-2.5 px-4 text-center flex items-center justify-center gap-2 shadow-inner">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span>Email Anda belum diverifikasi! Harap periksa Gmail ({{ $activeTamu->email }}) & verifikasi dalam 3 hari agar akun tidak terhapus otomatis.</span>
                <form action="{{ route('verify.resend') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="underline hover:text-amber-100 font-bold ml-1">Kirim Ulang Email</button>
                </form>
            </div>
        @endif
    @endif


    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>


    <!-- Footer Section -->
    <footer class="bg-slate-900 text-slate-400 pt-16 pb-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
                <!-- Info Column -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-2 text-white">
                        <img src="{{ asset('images/logo.jpg') }}" class="w-8 h-8 rounded-full object-cover border border-emerald-500/20" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover;" alt="Logo">
                        <span class="font-extrabold text-lg tracking-wider text-emerald-400">TANJUNG API</span>
                    </div>
                    <p class="text-sm leading-relaxed max-w-md">
                        Menyediakan layanan unit nyaman berupa villa eksklusif dan tenda camping di kawasan wisata alam dan konservasi penyu Pantai Tanjung Api, Desa Sebubus, Kecamatan Paloh, Kabupaten Sambas.
                    </p>
                </div>
                
                <!-- Links Column -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-white tracking-widest uppercase">Tautan Penting</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-400 transition-colors">Beranda</a></li>
                        <li><a href="{{ route('units.index') }}" class="hover:text-emerald-400 transition-colors">Daftar Unit</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-emerald-400 transition-colors">Tentang Kami</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-emerald-400 transition-colors">Kontak Pengelola</a></li>
                    </ul>
                </div>

                <!-- Contact Column -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-white tracking-widest uppercase">Hubungi Kami</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex gap-2">
                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Pantai Tanjung Api, Desa Sebubus, Kec. Paloh, Kab. Sambas, Kalimantan Barat</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span>+62 823-5566-7788</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>info@pantaitanjungapi.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="border-slate-800 my-8">
            
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-xs">
                <span>&copy; {{ date('Y') }} Rindi Indriani - Teknik Informatika POLNEP. Hak Cipta Dilindungi.</span>
                <a href="{{ route('admin.login') }}" class="text-slate-500 hover:text-emerald-400 font-semibold transition-colors flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Panel Pengelola
                </a>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu & Dropdown Toggle Script -->
    <script>
        // Mobile menu toggle
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });

        // User profile dropdown toggle
        const dropdownBtn = document.getElementById('user-dropdown-btn');
        const dropdownMenu = document.getElementById('user-dropdown-menu');
        const dropdownChevron = document.getElementById('dropdown-chevron');

        if (dropdownBtn && dropdownMenu) {
            dropdownBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = !dropdownMenu.classList.contains('invisible');
                
                if (isOpen) {
                    // Close
                    dropdownMenu.classList.add('opacity-0', 'invisible', 'translate-y-2');
                    dropdownMenu.classList.remove('opacity-100', 'visible', 'translate-y-0');
                    if (dropdownChevron) dropdownChevron.style.transform = 'rotate(0deg)';
                } else {
                    // Open
                    dropdownMenu.classList.remove('opacity-0', 'invisible', 'translate-y-2');
                    dropdownMenu.classList.add('opacity-100', 'visible', 'translate-y-0');
                    if (dropdownChevron) dropdownChevron.style.transform = 'rotate(180deg)';
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                const container = document.getElementById('user-dropdown-container');
                if (container && !container.contains(e.target)) {
                    dropdownMenu.classList.add('opacity-0', 'invisible', 'translate-y-2');
                    dropdownMenu.classList.remove('opacity-100', 'visible', 'translate-y-0');
                    if (dropdownChevron) dropdownChevron.style.transform = 'rotate(0deg)';
                }
            });

            // Close on Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    dropdownMenu.classList.add('opacity-0', 'invisible', 'translate-y-2');
                    dropdownMenu.classList.remove('opacity-100', 'visible', 'translate-y-0');
                    if (dropdownChevron) dropdownChevron.style.transform = 'rotate(0deg)';
                }
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
