@extends('layouts.app')

@section('title', 'Tentang Kami - Pantai Tanjung Api')

@section('content')
<!-- About Hero Section -->
<div class="relative bg-slate-900 overflow-hidden min-h-[50vh] flex items-center">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/hero_beach.png') }}" class="w-full h-full object-cover opacity-50" alt="Pantai Tanjung Api">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/80 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-left">
        <div class="max-w-3xl space-y-6">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Profil Pengelola & Destinasi
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight leading-[1.2]">
                Mengenal Lebih Dekat <br><span class="text-gradient font-black bg-gradient-to-r from-emerald-400 to-teal-300">Pantai Tanjung Api</span>
            </h1>
            <p class="text-lg text-slate-300 max-w-2xl leading-relaxed">
                Kombinasi harmonis antara keindahan alam pesisir tropis Desa Sebubus dan pusat konservasi penyelamatan satwa penyu yang dilindungi di Kalimantan Barat.
            </p>
        </div>
    </div>
</div>

<!-- Main Description Section -->
<div class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="space-y-6">
                <span class="text-emerald-600 font-bold text-sm uppercase tracking-widest block">KONSERVASI & ALAM</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-950 tracking-tight leading-tight">
                    Menjaga Kehidupan, Melestarikan Habitat Penyu
                </h2>
                <p class="text-slate-600 leading-relaxed">
                    Pantai Tanjung Api bukan sekadar tempat wisata pantai biasa. Lokasi ini memegang peranan krusial sebagai salah satu sentra pendaratan alami bagi spesies penyu langka seperti **Penyu Hijau**, **Penyu Sisik**, **Penyu Lekang**, dan **Penyu Belimbing** di wilayah pesisir Kalimantan Barat. 
                </p>
                <p class="text-slate-600 leading-relaxed">
                    Melalui pengelolaan yang berdedikasi di Desa Sebubus, Kecamatan Paloh, Kabupaten Sambas, kami berupaya menciptakan lingkungan yang aman bagi indukan penyu untuk bertelur dan memastikan kelangsungan hidup anak penyu (tukik) hingga kembali ke laut lepas.
                </p>
                <div class="p-6 bg-slate-50 border-l-4 border-emerald-500 rounded-r-2xl">
                    <p class="text-sm font-semibold text-slate-700 italic">
                        "Dengan memilih menginap di akomodasi kami, Anda secara langsung berkontribusi pada pendanaan operasional dan kampanye perlindungan penangkaran penyu di Pantai Tanjung Api."
                    </p>
                </div>
            </div>

            <!-- Stats/Image Section -->
            <div class="relative">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl h-[450px]">
                    <img src="{{ asset('images/hero_beach.png') }}" class="w-full h-full object-cover" alt="Kawasan Pantai Tanjung Api">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                </div>
                <!-- Decorative absolute card -->
                <div class="absolute -bottom-6 -left-6 bg-emerald-900 text-white p-6 rounded-2xl shadow-xl max-w-xs border border-emerald-800">
                    <h3 class="font-bold text-emerald-400 mb-1">Pendaratan Penyu</h3>
                    <p class="text-xs text-emerald-100 leading-relaxed">Penyu bertelur terutama pada musim kemarau di malam hari sepanjang pantai berpasir halus Tanjung Api.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== SECTION: Pengetahuan Penyu ===== -->
<div class="py-20 bg-slate-50 border-y border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
            <span class="text-emerald-600 font-bold text-sm uppercase tracking-widest">Pengetahuan Penyu</span>
            <h2 class="text-3xl font-extrabold text-slate-950 tracking-tight">Mengenal Penyu di Pantai Tanjung Api</h2>
            <p class="text-slate-600 text-lg leading-relaxed">
                Pantai Tanjung Api adalah salah satu surga pendaratan penyu terpenting di Kalimantan Barat. Berbagai spesies penyu yang dilindungi secara rutin hadir untuk bertelur di sepanjang pantai berpasir halus ini.
            </p>
        </div>


        <!-- Turtle Species Cards -->
        <div class="mb-6">
            <h3 class="text-2xl font-extrabold text-slate-900 text-center mb-2">Jenis Penyu di Pantai Tanjung Api</h3>
            <p class="text-slate-500 text-center mb-10">Empat spesies penyu yang dilindungi ini secara rutin mendaratkan diri di Pantai Tanjung Api untuk bertelur dan dilestarikan.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Penyu Hijau -->
            <div class="group bg-white rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="h-3 bg-gradient-to-r from-emerald-400 to-teal-500"></div>
                <div class="relative h-60 w-full overflow-hidden bg-slate-100">
                    <img src="{{ asset('images/penyu_hijau.jpg') }}" alt="Penyu Hijau" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                    <span class="absolute bottom-4 right-4 px-3.5 py-1 rounded-full text-xs font-extrabold bg-amber-500/90 text-white backdrop-blur-sm shadow border border-white/20">Terancam Punah</span>
                </div>
                <div class="p-8 space-y-5">
                    <div class="flex items-center gap-4">
                        <div>
                            <h4 class="text-xl font-extrabold text-slate-900">Penyu Hijau</h4>
                            <p class="text-xs text-emerald-600 font-semibold italic">Chelonia mydas</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Penyu Hijau adalah spesies yang paling sering mendarat di Pantai Tanjung Api. Dinamai "hijau" bukan dari warna cangkangnya, melainkan dari warna lemak di dalam tubuhnya. Penyu ini merupakan satu-satunya penyu dewasa yang bersifat herbivora — memakan lamun dan alga laut.
                    </p>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-slate-50 rounded-xl p-3">
                            <div class="text-xs text-slate-500 mb-1">Ukuran Dewasa</div>
                            <div class="text-sm font-bold text-slate-800">80 – 150 cm</div>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <div class="text-xs text-slate-500 mb-1">Berat Dewasa</div>
                            <div class="text-sm font-bold text-slate-800">68 – 190 kg</div>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <div class="text-xs text-slate-500 mb-1">Musim Bertelur</div>
                            <div class="text-sm font-bold text-slate-800">Mei – Oktober</div>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <div class="text-xs text-slate-500 mb-1">Makanan Utama</div>
                            <div class="text-sm font-bold text-slate-800">Lamun & Alga</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        <p class="text-xs text-emerald-700">Spesies dominan di Pantai Tanjung Api — dilindungi penuh oleh hukum Indonesia dan IUCN.</p>
                    </div>
                </div>
            </div>

            <!-- Penyu Sisik -->
            <div class="group bg-white rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="h-3 bg-gradient-to-r from-orange-400 to-rose-500"></div>
                <div class="relative h-60 w-full overflow-hidden bg-slate-100">
                    <img src="{{ asset('images/penyu_sisik.jpg') }}" alt="Penyu Sisik" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                    <span class="absolute bottom-4 right-4 px-3.5 py-1 rounded-full text-xs font-extrabold bg-red-600/90 text-white backdrop-blur-sm shadow border border-white/20">Kritis</span>
                </div>
                <div class="p-8 space-y-5">
                    <div class="flex items-center gap-4">
                        <div>
                            <h4 class="text-xl font-extrabold text-slate-900">Penyu Sisik</h4>
                            <p class="text-xs text-orange-600 font-semibold italic">Eretmochelys imbricata</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Penyu Sisik dikenal dari paruhnya yang lancip menyerupai paruh elang dan sisik cangkang yang saling tumpang tindih dengan indah. Spesies ini memiliki peran ekologis penting dalam menjaga kesehatan terumbu karang dengan memakan spons laut yang menempel di karang.
                    </p>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-slate-50 rounded-xl p-3">
                            <div class="text-xs text-slate-500 mb-1">Ukuran Dewasa</div>
                            <div class="text-sm font-bold text-slate-800">60 – 95 cm</div>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <div class="text-xs text-slate-500 mb-1">Berat Dewasa</div>
                            <div class="text-sm font-bold text-slate-800">45 – 70 kg</div>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <div class="text-xs text-slate-500 mb-1">Musim Bertelur</div>
                            <div class="text-sm font-bold text-slate-800">April – September</div>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <div class="text-xs text-slate-500 mb-1">Makanan Utama</div>
                            <div class="text-sm font-bold text-slate-800">Spons Laut</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 p-3 bg-orange-50 rounded-xl border border-orange-100">
                        <svg class="w-4 h-4 text-orange-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        <p class="text-xs text-orange-700">Berstatus Kritis (Critically Endangered) — terancam oleh perdagangan ilegal cangkang dan perusakan habitat.</p>
                    </div>
                </div>
            </div>

            <!-- Penyu Lekang -->
            <div class="group bg-white rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="h-3 bg-gradient-to-r from-teal-400 to-cyan-500"></div>
                <div class="relative h-60 w-full overflow-hidden bg-slate-100">
                    <img src="{{ asset('images/penyu_lekang.jpg') }}" alt="Penyu Lekang" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                    <span class="absolute bottom-4 right-4 px-3.5 py-1 rounded-full text-xs font-extrabold bg-amber-500/90 text-white backdrop-blur-sm shadow border border-white/20">Rentan</span>
                </div>
                <div class="p-8 space-y-5">
                    <div class="flex items-center gap-4">
                        <div>
                            <h4 class="text-xl font-extrabold text-slate-900">Penyu Lekang</h4>
                            <p class="text-xs text-teal-600 font-semibold italic">Lepidochelys olivacea</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Penyu Lekang (Olive Ridley) adalah penyu terkecil di antara spesies penyu laut. Penyu ini dikenal dengan cangkang berwarna abu-abu zaitun dan fenomena pendaratan masal (arribada) yang unik saat bertelur.
                    </p>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-slate-50 rounded-xl p-3">
                            <div class="text-xs text-slate-500 mb-1">Ukuran Dewasa</div>
                            <div class="text-sm font-bold text-slate-800">60 – 70 cm</div>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <div class="text-xs text-slate-500 mb-1">Berat Dewasa</div>
                            <div class="text-sm font-bold text-slate-800">35 – 50 kg</div>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <div class="text-xs text-slate-500 mb-1">Musim Bertelur</div>
                            <div class="text-sm font-bold text-slate-800">Juni – November</div>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <div class="text-xs text-slate-500 mb-1">Makanan Utama</div>
                            <div class="text-sm font-bold text-slate-800">Kepiting & Kerang</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 p-3 bg-teal-50 rounded-xl border border-teal-100">
                        <svg class="w-4 h-4 text-teal-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        <p class="text-xs text-teal-700">Berstatus Rentan (Vulnerable) — dilindungi dari jaring nelayan dan pencemaran pesisir.</p>
                    </div>
                </div>
            </div>

            <!-- Penyu Belimbing -->
            <div class="group bg-white rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="h-3 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                <div class="relative h-60 w-full overflow-hidden bg-slate-100">
                    <img src="{{ asset('images/penyu_belimbing.jpg') }}" alt="Penyu Belimbing" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                    <span class="absolute bottom-4 right-4 px-3.5 py-1 rounded-full text-xs font-extrabold bg-red-600/90 text-white backdrop-blur-sm shadow border border-white/20">Kritis</span>
                </div>
                <div class="p-8 space-y-5">
                    <div class="flex items-center gap-4">
                        <div>
                            <h4 class="text-xl font-extrabold text-slate-900">Penyu Belimbing</h4>
                            <p class="text-xs text-indigo-600 font-semibold italic">Dermochelys coriacea</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Penyu Belimbing adalah penyu terbesar di dunia. Berbeda dari spesies lain, cangkangnya tidak berlapis sisik keras melainkan cangkang lunak bergaris memanjang menyerupai buah belimbing.
                    </p>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-slate-50 rounded-xl p-3">
                            <div class="text-xs text-slate-500 mb-1">Ukuran Dewasa</div>
                            <div class="text-sm font-bold text-slate-800">150 – 200 cm</div>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <div class="text-xs text-slate-500 mb-1">Berat Dewasa</div>
                            <div class="text-sm font-bold text-slate-800">250 – 700 kg</div>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <div class="text-xs text-slate-500 mb-1">Musim Bertelur</div>
                            <div class="text-sm font-bold text-slate-800">Mei – Agustus</div>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <div class="text-xs text-slate-500 mb-1">Makanan Utama</div>
                            <div class="text-sm font-bold text-slate-800">Ubur-ubur</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 p-3 bg-indigo-50 rounded-xl border border-indigo-100">
                        <svg class="w-4 h-4 text-indigo-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        <p class="text-xs text-indigo-700">Berstatus Sangat Kritis (Critically Endangered) — raksasa samudra purba yang sangat dilindungi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== SECTION: Festival & Kegiatan Budaya ===== -->
<div class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
            <span class="text-emerald-600 font-bold text-sm uppercase tracking-widest">Festival & Budaya</span>
            <h2 class="text-3xl font-extrabold text-slate-950 tracking-tight">Perayaan & Festival di Pantai Tanjung Api</h2>
            <p class="text-slate-600 text-lg leading-relaxed">
                Selain keindahan alam dan kekayaan ekologinya, Pantai Tanjung Api juga menjadi panggung berbagai festival dan kegiatan budaya unik yang mempertemukan tradisi lokal dengan semangat pelestarian.
            </p>
        </div>

        <!-- Featured Festival: Pelepasan Tukik -->
        <div class="relative bg-gradient-to-br from-teal-50 to-emerald-50 rounded-3xl border border-teal-200 overflow-hidden mb-12 shadow-lg">
            <div class="absolute top-0 right-0 w-64 h-64 opacity-10">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#10B981" d="M47.5,-61.5C61.1,-52.5,71.4,-38.4,75.2,-22.8C79.1,-7.2,76.4,9.9,69.4,24.6C62.4,39.4,51.1,51.8,37.4,59.6C23.7,67.4,7.6,70.7,-8.6,70.4C-24.8,70.1,-41.1,66.2,-53.8,57C-66.5,47.8,-75.5,33.3,-77.3,17.8C-79.1,2.3,-73.6,-14.3,-64.8,-28.3C-55.9,-42.2,-43.6,-53.5,-29.9,-62.2C-16.1,-70.9,-1,-76.9,13.7,-76.1C28.5,-75.2,34,-70.5,47.5,-61.5Z" transform="translate(100 100)"/>
                </svg>
            </div>
            <div class="relative z-10 p-8 md:p-12">
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-bold bg-emerald-600 text-white">
                        🐢 Kegiatan Unggulan
                    </span>
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-bold bg-white border border-emerald-300 text-emerald-700">
                        🌊 Konservasi Alam
                    </span>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                    <div class="space-y-5">
                        <h3 class="text-3xl font-extrabold text-slate-900">Pelepasan Tukik</h3>
                        <p class="text-slate-700 leading-relaxed">
                            <strong>Pelepasan Tukik</strong> adalah kegiatan konservasi yang menjadi daya tarik utama di Pantai Tanjung Api. Wisatawan dan warga lokal bersama-sama melepas ratusan tukik (anak penyu) ke laut lepas sebagai wujud nyata kepedulian terhadap kelestarian penyu.
                        </p>
                        <p class="text-slate-600 leading-relaxed">
                            Momen ini menjadi pengalaman yang sangat emosional dan edukatif — menyaksikan langsung tukik-tukik kecil berlari menuju ombak dan memulai perjalanan panjang mereka di lautan. Kegiatan ini berlangsung rutin sepanjang musim bertelur penyu.
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-emerald-200 shadow-sm">
                                <span class="text-lg">📅</span>
                                <div>
                                    <div class="text-xs text-slate-500">Waktu</div>
                                    <div class="text-sm font-bold text-slate-800">Mei – Oktober</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-emerald-200 shadow-sm">
                                <span class="text-lg">📍</span>
                                <div>
                                    <div class="text-xs text-slate-500">Lokasi</div>
                                    <div class="text-sm font-bold text-slate-800">Pantai Tanjung Api</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-emerald-200 shadow-sm">
                                <span class="text-lg">👥</span>
                                <div>
                                    <div class="text-xs text-slate-500">Peserta</div>
                                    <div class="text-sm font-bold text-slate-800">Warga & Wisatawan</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <p class="text-xs font-bold text-emerald-700 uppercase tracking-widest mb-2">📸 Galeri Foto</p>
                        <!-- Foto utama besar -->
                        <div class="rounded-2xl overflow-hidden shadow-md aspect-video w-full">
                            <img src="{{ asset('images/tukik_massal.jpg') }}"
                                 alt="Pelepasan tukik massal bersama warga"
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-500 cursor-pointer">
                        </div>
                        <!-- Dua foto kecil bawah -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-xl overflow-hidden shadow-sm aspect-square">
                                <img src="{{ asset('images/tukik_anak.jpg') }}"
                                     alt="Anak kecil melihat tukik di pantai"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-500 cursor-pointer">
                            </div>
                            <div class="rounded-xl overflow-hidden shadow-sm aspect-square">
                                <img src="{{ asset('images/galeri_tukik_3.png') }}"
                                     alt="Tukik di tangan relawan"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-500 cursor-pointer">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Other Festivals Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Festival 2: Pelepasan Tukik Massal -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 space-y-4">
                <div class="w-14 h-14 rounded-2xl bg-teal-50 flex items-center justify-center text-3xl">🌊</div>
                <div>
                    <span class="text-xs font-bold text-teal-600 uppercase tracking-wider">Konservasi</span>
                    <h4 class="text-lg font-extrabold text-slate-900 mt-1">Pelepasan Tukik Massal</h4>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Setiap musim bertelur, wisatawan dan warga lokal bersama-sama melepas ratusan tukik (anak penyu) ke laut lepas. Momen ini menjadi pengalaman emosional dan edukatif yang tidak terlupakan bagi seluruh peserta.
                </p>
                <div class="pt-2 border-t border-slate-100">
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <span>📅</span>
                        <span>Sepanjang musim bertelur (Mei – Oktober)</span>
                    </div>
                </div>
            </div>

            <!-- Festival 3: Malam Patroli Penyu -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 space-y-4">
                <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-3xl">🌙</div>
                <div>
                    <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Ekowisata</span>
                    <h4 class="text-lg font-extrabold text-slate-900 mt-1">Malam Patroli Penyu</h4>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Wisatawan diajak bergabung bersama tim ranger untuk melakukan patroli malam di sepanjang pantai — menyaksikan langsung indukan penyu naik ke darat dan bertelur di bawah cahaya bulan. Pengalaman langka yang memperdalam rasa cinta terhadap alam.
                </p>
                <div class="pt-2 border-t border-slate-100">
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <span>📅</span>
                        <span>Setiap malam saat musim pendaratan</span>
                    </div>
                </div>
            </div>

            <!-- Festival 4: Pesta Rakyat Pesisir -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 space-y-4">
                <div class="w-14 h-14 rounded-2xl bg-rose-50 flex items-center justify-center text-3xl">🎭</div>
                <div>
                    <span class="text-xs font-bold text-rose-600 uppercase tracking-wider">Budaya Lokal</span>
                    <h4 class="text-lg font-extrabold text-slate-900 mt-1">Pesta Rakyat Pesisir</h4>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Festival budaya pesisir yang menampilkan seni musik tradisional Melayu Sambas, tarian daerah, pameran kerajinan tangan lokal, dan bazaar kuliner khas pantai. Merayakan identitas budaya masyarakat Desa Sebubus yang kaya dan beragam.
                </p>
                <div class="pt-2 border-t border-slate-100">
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <span>📅</span>
                        <span>Hari-hari besar & akhir pekan khusus</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lodging Options CTA Section -->
<div class="bg-slate-900 text-white py-20 relative overflow-hidden">
    <div class="absolute inset-0 z-0 opacity-10">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
            <defs>
                <pattern id="grid-cta" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid-cta)" />
        </svg>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
        <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Tertarik Merasakan Keindahan Pantai Tanjung Api?</h2>
        <p class="text-slate-300 max-w-xl mx-auto text-lg">
            Pesan akomodasi nyaman Anda sekarang. Mulai dari Villa modern hingga Camping Tenda premium di bawah taburan bintang pantai.
        </p>
        <div class="pt-4">
            <a href="{{ route('units.index') }}" class="inline-flex items-center justify-center px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-emerald-600/20 active:scale-95">
                Lihat Pilihan Unit
            </a>
        </div>
    </div>
</div>
@endsection
