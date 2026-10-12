@extends('layouts.app')

@section('title', 'Daftar Unit - Pantai Tanjung Api')

@section('content')
<!-- Header Page -->
<div class="bg-slate-900 text-white py-16 relative overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <img src="{{ asset('images/hero_beach.png') }}" class="w-full h-full object-cover" alt="Banner">
    </div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-3">
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight">Pilihan Akomodasi</h1>
        <p class="text-slate-300 max-w-2xl mx-auto text-sm sm:text-base">
            Temukan tempat peristirahatan terbaik Anda, mulai dari Villa bergaya tropis hingga pengalaman berkemah di bawah bintang-bintang.
        </p>
    </div>
</div>

<!-- Main Area -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Filter Bar -->
    <div class="flex justify-center mb-12">
        <div class="bg-slate-100 p-1.5 rounded-2xl flex flex-wrap justify-center gap-1 border border-slate-200">
            <a href="{{ route('units.index') }}" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all {{ !request()->filled('tipe') ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-600 hover:text-slate-950' }}">
                Semua Tipe
            </a>
            @if(isset($allTypes) && count($allTypes) > 0)
                @foreach($allTypes as $type)
                    <a href="{{ route('units.index', ['tipe' => $type]) }}" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all {{ request('tipe') === $type ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-600 hover:text-slate-950' }}">
                        {{ ucfirst($type) }}
                    </a>
                @endforeach
            @else
                <a href="{{ route('units.index', ['tipe' => 'villa']) }}" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all {{ request('tipe') === 'villa' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-600 hover:text-slate-950' }}">
                    Villa
                </a>
                <a href="{{ route('units.index', ['tipe' => 'tenda']) }}" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all {{ request('tipe') === 'tenda' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-600 hover:text-slate-950' }}">
                    Tenda
                </a>
            @endif
        </div>
    </div>

    <!-- Listings Grid -->
    @if($units->isEmpty())
        <div class="text-center py-16 bg-white rounded-3xl border border-slate-100 shadow-sm max-w-md mx-auto space-y-4">
            <svg class="w-12 h-12 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
            </svg>
            <h3 class="font-extrabold text-lg text-slate-800">Unit Tidak Ditemukan</h3>
            <p class="text-slate-500 text-sm">Maaf, jenis unit yang Anda cari belum tersedia saat ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($units as $unit)
                <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 flex flex-col h-full">
                    <!-- Image -->
                    <div class="relative h-56 bg-slate-100 overflow-hidden">
                        <img src="{{ asset('images/' . $unit->foto) }}" class="w-full h-full object-cover" alt="{{ $unit->nama_unit }}">
                        <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold text-white uppercase bg-slate-900/80 backdrop-blur-sm">
                            {{ $unit->tipe_unit }}
                        </span>
                    </div>
                    <!-- Info -->
                    <div class="p-6 flex flex-col flex-grow justify-between space-y-6">
                        <div class="space-y-3">
                            <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $unit->alamat }}
                            </div>
                            <h2 class="font-extrabold text-xl text-slate-900 leading-snug">{{ $unit->nama_unit }}</h2>
                            <p class="text-sm text-slate-600 leading-relaxed">{{ $unit->fasilitas }}</p>
                        </div>
                        
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-slate-500 block">Mulai dari</span>
                                <span class="font-extrabold text-emerald-600 text-xl">Rp {{ number_format($unit->harga, 0, ',', '.') }}</span>
                                <span class="text-xs text-slate-400">/malam</span>
                            </div>
                            <a href="{{ route('units.show', $unit->id_unit) }}" class="inline-flex items-center justify-center px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all shadow-md shadow-emerald-600/10 active:scale-95">
                                Detail & Pesan
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
