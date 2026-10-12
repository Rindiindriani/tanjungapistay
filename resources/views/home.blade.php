@extends('layouts.app')

@section('title', 'Beranda - Pantai Tanjung Api')

@section('content')
<!-- Hero Section -->
<div class="relative bg-slate-900 overflow-hidden min-h-[85vh] flex items-center">
    <!-- Background image with green gradient overlay -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/hero_beach.png') }}" class="w-full h-full object-cover opacity-60" alt="Pantai Tanjung Api">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/90 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32">
        <div class="max-w-2xl space-y-6">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Destinasi Wisata & Konservasi Penyu
            </span>
            <h1 class="text-4xl sm:text-6xl font-extrabold text-white tracking-tight leading-[1.1]">
                Temukan Keindahan <span class="text-emerald-400">Pantai Tanjung Api</span>
            </h1>
            <p class="text-lg text-slate-300 leading-relaxed">
                Nikmati kenyamanan beristirahat di tengah keasrian alam tropis Desa Sebubus, Paloh. Saksikan langsung kehidupan penyu langka yang dilindungi di habitat alaminya.
            </p>
            <div class="flex flex-wrap gap-4 pt-4">
                <a href="{{ route('units.index') }}" class="px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-emerald-600/20 active:scale-95">
                    Pesan Unit Sekarang
                </a>
                <a href="{{ route('about') }}" class="px-6 py-3.5 bg-slate-800/80 hover:bg-slate-700/80 text-white border border-slate-700 font-bold rounded-xl transition-all backdrop-blur-sm active:scale-95">
                    Pelajari Konservasi
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Info Cards Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-20">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl p-6 shadow-xl shadow-slate-900/5 border border-slate-100 flex gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-950 mb-1">Akomodasi Nyaman</h3>
                <p class="text-sm text-slate-600">Tersedia pilihan unit Villa eksklusif dan Tenda camping premium di tepi pantai.</p>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="bg-white rounded-2xl p-6 shadow-xl shadow-slate-900/5 border border-slate-100 flex gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-950 mb-1">Harga Terjangkau</h3>
                <p class="text-sm text-slate-600">Tarif unit bersaing dengan kualitas pelayanan terbaik dan fasilitas memadai.</p>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="bg-white rounded-2xl p-6 shadow-xl shadow-slate-900/5 border border-slate-100 flex gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-950 mb-1">Pembayaran Aman</h3>
                <p class="text-sm text-slate-600">Proses pemesanan terintegrasi dengan simulasi payment gateway otomatis.</p>
            </div>
        </div>
    </div>
</div>

<!-- Previews / Lodging Options -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-12">
        <div>
            <span class="text-emerald-600 font-bold text-sm uppercase tracking-widest">PILIHAN KAMAR</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-950 tracking-tight">Kamar & Tenda Terpopuler</h2>
        </div>
        <a href="{{ route('units.index') }}" class="font-bold text-emerald-600 hover:text-emerald-700 transition-colors flex items-center gap-1">
            Lihat Semua Unit
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($units->take(4) as $unit)
            <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 flex flex-col h-full">
                <!-- Room Image -->
                <div class="relative h-48 bg-slate-100 overflow-hidden">
                    <img src="{{ asset('images/' . $unit->foto) }}" class="w-full h-full object-cover" alt="{{ $unit->nama_unit }}">
                    <span class="absolute top-4 right-4 px-2.5 py-1 rounded-full text-xs font-bold text-white uppercase bg-slate-900/80 backdrop-blur-sm">
                        {{ $unit->tipe_unit }}
                    </span>
                </div>
                <!-- Card Info -->
                <div class="p-6 flex flex-col flex-grow justify-between space-y-4">
                    <div class="space-y-2">
                        <h3 class="font-extrabold text-lg text-slate-900 leading-snug">{{ $unit->nama_unit }}</h3>
                        <p class="text-xs text-slate-500 line-clamp-2">{{ $unit->fasilitas }}</p>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between items-baseline">
                            <span class="text-xs text-slate-500 font-medium">Harga / Malam</span>
                            <span class="font-extrabold text-emerald-600 text-lg">Rp {{ number_format($unit->harga, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('units.show', $unit->id_unit) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-slate-900 hover:bg-emerald-600 text-white font-bold text-sm rounded-xl transition-colors">
                            Detail Unit
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
