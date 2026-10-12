@extends('layouts.app')

@section('title', 'Hubungi Kami - Pantai Tanjung Api')

@section('content')
<!-- Contact Hero Section -->
<div class="relative bg-slate-900 overflow-hidden min-h-[40vh] flex items-center">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/hero_beach.png') }}" class="w-full h-full object-cover opacity-50" alt="Hubungi Kami Pantai Tanjung Api">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/80 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-left">
        <div class="max-w-3xl space-y-6">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Hubungi Pengelola
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight leading-[1.2]">
                Mari Tetap Terhubung
            </h1>
            <p class="text-lg text-slate-300 max-w-2xl leading-relaxed">
                Punya pertanyaan mengenai akomodasi, kegiatan konservasi, atau rute perjalanan? Tim kami siap membantu Anda kapan saja.
            </p>
        </div>
    </div>
</div>

<!-- Main Contact Details & Form Section -->
<div class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Column 1 & 2: Contact Information Cards -->
            <div class="lg:col-span-1 space-y-8">
                <div class="space-y-4">
                    <span class="text-emerald-600 font-bold text-sm uppercase tracking-widest block">DETAIL HUBUNGAN</span>
                    <h2 class="text-3xl font-extrabold text-slate-950 tracking-tight leading-tight">Informasi Kontak</h2>
                    <p class="text-slate-600 leading-relaxed text-sm">
                        Anda dapat berkunjung ke kantor pengelola kami di area wisata atau menghubungi saluran layanan pelanggan kami berikut.
                    </p>
                </div>

                <div class="space-y-6">
                    <!-- Address Card -->
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 flex gap-4 hover:border-emerald-500/20 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm mb-1">Lokasi Kami</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Pantai Tanjung Api, Desa Sebubus, Kec. Paloh, Kab. Sambas, Kalimantan Barat, Indonesia
                            </p>
                        </div>
                    </div>

                    <!-- Phone Card -->
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 flex gap-4 hover:border-emerald-500/20 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm mb-1">Telepon & WhatsApp</h4>
                            <p class="text-xs text-slate-600 leading-relaxed mb-1">+62 823-5566-7788</p>
                            <span class="inline-block text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Tersedia Setiap Hari</span>
                        </div>
                    </div>

                    <!-- Email Card -->
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 flex gap-4 hover:border-emerald-500/20 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm mb-1">Email Resmi</h4>
                            <p class="text-xs text-slate-600 leading-relaxed mb-1">info@pantaitanjungapi.com</p>
                            <p class="text-xs text-slate-600 leading-relaxed">reservasi@pantaitanjungapi.com</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column 3: Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-100 shadow-xl shadow-slate-900/5 space-y-6">
                    <div class="space-y-2">
                        <h3 class="text-2xl font-extrabold text-slate-950">Kirimkan Kami Pesan</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            Isi formulir di bawah ini untuk mengirimkan saran, kritik, atau pertanyaan. Kami akan merespons dalam waktu 24 jam.
                        </p>
                    </div>

                    <!-- Internal alert within contact page if form errors exist -->
                    @if($errors->any() && (request()->routeIs('contact') || request()->routeIs('contact.send')))
                        <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-xs">
                            <div class="font-bold mb-1">Pengiriman gagal, periksa kesalahan berikut:</div>
                            <ul class="list-disc pl-4 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Nama -->
                            <div class="space-y-1.5">
                                <label for="nama" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Nama Lengkap</label>
                                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required placeholder="Contoh: Rindi Indriani"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all">
                            </div>
                            <!-- Email -->
                            <div class="space-y-1.5">
                                <label for="email" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Alamat Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="Contoh: rindi@domain.com"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all">
                            </div>
                        </div>

                        <!-- Subjek -->
                        <div class="space-y-1.5">
                            <label for="subjek" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Subjek Pesan</label>
                            <input type="text" name="subjek" id="subjek" value="{{ old('subjek') }}" required placeholder="Contoh: Pertanyaan Akomodasi Villa"
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all">
                        </div>

                        <!-- Pesan -->
                        <div class="space-y-1.5">
                            <label for="pesan" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Isi Pesan</label>
                            <textarea name="pesan" id="pesan" rows="5" required placeholder="Tuliskan pesan Anda secara detail di sini..."
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all">{{ old('pesan') }}</textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all shadow-md shadow-emerald-600/10 hover:shadow-lg hover:shadow-emerald-600/20 active:scale-95 text-sm">
                                Kirim Pesan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- Interactive Maps Section -->
<div class="bg-slate-50 border-t border-slate-100">
    <!-- Judul -->
    <div class="py-10 text-center space-y-2 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-emerald-600 font-bold text-sm uppercase tracking-widest block">Peta Lokasi</span>
        <h3 class="text-2xl font-extrabold text-slate-950 tracking-tight">Temukan Kami di Google Maps</h3>
        <p class="text-slate-500 text-sm max-w-xl mx-auto leading-relaxed">
            Gunakan peta interaktif di bawah ini untuk melihat rute perjalanan menuju Pantai Tanjung Api secara detail.
        </p>
    </div>

    <!-- Peta full-width, tinggi layar penuh -->
    <div class="w-full h-[500px]">
        <iframe
            class="w-full h-full border-none"
            src="https://maps.google.com/maps?q=Pantai%20Tanjung%20Api,%20Sebubus,%20Paloh,%20Sambas,%20Kalimantan%20Barat&t=&z=13&ie=UTF8&iwloc=&output=embed"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
</div>
@endsection


