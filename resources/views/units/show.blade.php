{{-- Menggunakan layout utama aplikasi --}}
@extends('layouts.app')

{{-- Menentukan judul halaman detail unit --}}
@section('title', $unit->nama_unit . ' - Detail')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Breadcrumb Navigasi Halaman --}}
    <nav class="flex mb-8 text-xs sm:text-sm text-slate-500 font-medium" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="hover:text-emerald-600 transition-colors">Beranda</a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-4.5 h-4.5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <a href="{{ route('units.index') }}" class="ml-1 md:ml-2 hover:text-emerald-600 transition-colors">Unit</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-4.5 h-4.5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 md:ml-2 text-slate-800 font-semibold">{{ $unit->nama_unit }}</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- Grid Layout Utama 2 Kolom --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        {{-- Kolom Detail Akomodasi (2/3 lebar layar desktop) --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Multi-Foto Galeri Unit Penginapan --}}
            <div class="space-y-3">
                {{-- Foto Main Preview --}}
                <div class="relative bg-slate-900 rounded-3xl overflow-hidden shadow-lg h-72 sm:h-96 group">
                    <img id="mainGalleryImage" src="{{ asset('images/' . $unit->semua_foto[0]) }}" class="w-full h-full object-cover transition-all duration-300 transform group-hover:scale-105" alt="{{ $unit->nama_unit }}">
                    
                    {{-- Badge Jumlah Foto --}}
                    <div class="absolute top-4 right-4 bg-slate-900/80 backdrop-blur-md text-white text-xs font-bold px-3 py-1.5 rounded-full border border-white/20 flex items-center gap-1.5 shadow-md">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Galeri {{ count($unit->semua_foto) }} Foto</span>
                    </div>

                    {{-- Overlay Caption --}}
                    <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-slate-950/80 via-slate-950/40 to-transparent p-6 text-white flex items-end justify-between">
                        <div>
                            <span class="text-xs font-semibold text-emerald-300 uppercase tracking-wider">Suasana Unit</span>
                            <h2 class="text-lg font-bold" id="mainGalleryCaption">Foto 1 dari {{ count($unit->semua_foto) }}</h2>
                        </div>
                        <button type="button" onclick="openLightbox()" class="px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-md rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                            </svg>
                            Perbesar Foto
                        </button>
                    </div>
                </div>

                {{-- Thumbnail Selector Bar --}}
                @if(count($unit->semua_foto) > 1)
                    <div class="grid grid-cols-4 sm:grid-cols-6 gap-3 pt-1">
                        @foreach($unit->semua_foto as $index => $fotoPath)
                            <button type="button" onclick="switchMainImage('{{ asset('images/' . $fotoPath) }}', {{ $index + 1 }}, this)" 
                                class="thumb-btn relative rounded-2xl overflow-hidden h-20 sm:h-24 border-2 transition-all group {{ $index === 0 ? 'border-emerald-500 ring-2 ring-emerald-500/20 scale-[1.02]' : 'border-slate-200 opacity-70 hover:opacity-100 hover:border-emerald-300' }}">
                                <img src="{{ asset('images/' . $fotoPath) }}" class="w-full h-full object-cover" alt="Foto {{ $index + 1 }}">
                                <span class="absolute bottom-1 right-1 text-[9px] font-extrabold bg-black/60 text-white px-1.5 py-0.5 rounded">
                                    {{ $index === 0 ? 'Sampul' : 'Kamar ' . ($index) }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
            
            {{-- Informasi Judul & Alamat Unit --}}
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 uppercase tracking-wide">
                        {{ $unit->tipe_unit }}
                    </span>
                    <span class="text-xs text-slate-500 font-medium">Tanjung Api Beach</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">{{ $unit->nama_unit }}</h1>
                <p class="text-slate-500 text-sm sm:text-base flex items-center gap-1.5">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ $unit->alamat }}
                </p>
            </div>

            {{-- Deskripsi Fasilitas Unit --}}
            <div class="space-y-4 bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm">
                <h3 class="font-bold text-lg text-slate-900">Fasilitas Unit</h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                    {{ $unit->fasilitas }}
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4 border-t border-slate-100 text-xs sm:text-sm text-slate-600 font-medium">
                    <span class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Akses Dekat Pantai
                    </span>
                    <span class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Kamar Mandi
                    </span>
                    <span class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Area Parkir
                    </span>
                </div>
            </div>
        </div>

        {{-- Kolom Form Pemesanan / Sidebar (1/3 lebar layar desktop) --}}
        <div class="space-y-6">
            {{-- Kartu Tampilan Tarif & Form Booking --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-900/5 p-6 sm:p-8 space-y-6">
                <div>
                    <span class="text-xs text-slate-500 font-medium block">Tarif Sewa</span>
                    <div class="flex items-baseline gap-1">
                        <span class="font-extrabold text-3xl text-emerald-600" id="header-price">Rp {{ number_format($unit->harga, 0, ',', '.') }}</span>
                        <span class="text-sm text-slate-400" id="header-price-unit">/malam</span>
                    </div>
                </div>

                {{-- Form Reservasi Tamu --}}
                <form action="{{ route('bookings.store') }}" method="POST" id="booking-form" class="space-y-4">
                    @csrf
                    <input type="hidden" name="id_unit" value="{{ $unit->id_unit }}">
                    
                    {{-- Pesan Error Ketersediaan --}}
                    @error('ketersediaan')
                        <div class="p-3 text-xs bg-red-50 text-red-600 border border-red-100 rounded-xl font-semibold">{{ $message }}</div>
                    @enderror

                    {{-- Tanggal Check-in --}}
                    <div>
                        <label for="tanggal_checkin" class="block text-xs font-bold text-slate-600 uppercase mb-1.5">
                            Tanggal Check-In
                        </label>
                        <input type="date" name="tanggal_checkin" id="tanggal_checkin" required min="{{ date('Y-m-d') }}" value="{{ old('tanggal_checkin', $bookingAttempt['tanggal_checkin'] ?? date('Y-m-d')) }}"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-emerald-500 transition-colors">
                        <p class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Check-in tersedia mulai <strong class="text-slate-600">pukul 14:00</strong>
                        </p>
                    </div>

                    {{-- Durasi Sewa (Ikon Stepper Tambah / Kurang Malam) --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-600 uppercase">
                                Durasi Sewa
                            </label>
                            <span class="text-xs font-extrabold text-emerald-700" id="durasi-subtotal">
                                Rp {{ number_format($unit->harga, 0, ',', '.') }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-1.5 bg-slate-50 border border-slate-200 rounded-2xl">
                            {{-- Tombol Kurang Malam --}}
                            <button type="button" id="btn-durasi-minus" 
                                class="w-12 h-11 rounded-xl bg-white border border-slate-200 text-slate-700 font-extrabold text-lg flex items-center justify-center hover:bg-slate-100 hover:text-emerald-600 transition-all shadow-sm active:scale-95 disabled:opacity-30 disabled:cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                            </button>

                            {{-- Input Jumlah Malam (Bisa Diketik Langsung & Diklik Tambah/Kurang) --}}
                            <div class="flex-1 flex items-center justify-center gap-2 px-2">
                                <input type="number" name="durasi_malam" id="durasi_malam" min="1" max="60" required
                                    value="{{ old('durasi_malam', 1) }}"
                                    class="w-16 h-11 text-center font-extrabold text-slate-900 text-lg bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 shadow-sm transition-all [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                <span class="font-extrabold text-slate-800 text-base tracking-tight select-none">Malam</span>
                            </div>

                            {{-- Tombol Tambah Malam --}}
                            <button type="button" id="btn-durasi-plus" 
                                class="w-12 h-11 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-lg flex items-center justify-center transition-all shadow-sm active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            </button>
                        </div>

                        {{-- Hidden Input untuk Tanggal Checkout ke Server --}}
                        <input type="hidden" name="tanggal_checkout" id="tanggal_checkout" value="{{ old('tanggal_checkout', $bookingAttempt['tanggal_checkout'] ?? date('Y-m-d', strtotime('+1 day'))) }}">
                    </div>

                    {{-- Jumlah Kamar & Jumlah Tamu --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="jumlah_kamar" class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Jumlah Unit</label>
                            <input type="number" name="jumlah_kamar" id="jumlah_kamar" required min="1" max="10" value="{{ old('jumlah_kamar', $bookingAttempt['jumlah_kamar'] ?? 1) }}"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-emerald-500 transition-colors">
                        </div>
                        <div>
                            <label for="jumlah_tamu" class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Jumlah Tamu</label>
                            <input type="number" name="jumlah_tamu" id="jumlah_tamu" required min="1" 
                                @if(strtolower($unit->tipe_unit) === 'villa') max="{{ 6 * (old('jumlah_kamar', $bookingAttempt['jumlah_kamar'] ?? 1)) }}" @endif 
                                value="{{ old('jumlah_tamu', $bookingAttempt['jumlah_tamu'] ?? 2) }}"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-emerald-500 transition-colors">
                        </div>
                    </div>
                    @if(strtolower($unit->tipe_unit) === 'villa')
                        <p class="text-[11px] text-amber-700 font-semibold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Kapasitas Villa maksimal 6 orang per unit.
                        </p>
                    @endif
                    @error('jumlah_tamu')
                        <div class="p-2.5 text-xs bg-red-50 text-red-600 border border-red-100 rounded-xl font-semibold">{{ $message }}</div>
                    @enderror


                    {{-- Opsi Pembayaran (DP 50% atau Full 100%) --}}
                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Skema Pembayaran</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <label class="relative flex items-center p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-emerald-400 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/40 transition-all">
                                <input type="radio" name="jenis_pembayaran" value="full" checked onchange="updatePrice()" class="text-emerald-600 focus:ring-emerald-500">
                                <div class="ml-2 text-xs">
                                    <span class="font-extrabold block text-slate-900">Bayar Full (100%)</span>
                                    <span class="text-[10px] text-slate-500 font-medium">Lunas sekaligus</span>
                                </div>
                            </label>

                            <label class="relative flex items-center p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-emerald-400 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/40 transition-all">
                                <input type="radio" name="jenis_pembayaran" value="dp" onchange="updatePrice()" class="text-emerald-600 focus:ring-emerald-500">
                                <div class="ml-2 text-xs">
                                    <span class="font-extrabold block text-slate-900">Uang Muka / DP (50%)</span>
                                    <span class="text-[10px] text-amber-700 font-medium">Sisa 50% saat check-in</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Kotak Respons Pengecekan Ketersediaan AJAX --}}
                    <div id="availability-status" class="hidden p-3 rounded-xl text-xs font-semibold"></div>

                    {{-- Estimasi Rincian Total Harga --}}
                    <div class="pt-4 border-t border-slate-100 space-y-2.5 text-sm text-slate-600">
                        <div class="flex justify-between">
                            <span id="nights-label">1 Malam x 1 Unit</span>
                            <span id="base-price">Rp {{ number_format($unit->harga, 0, ',', '.') }}</span>
                        </div>
                        <div id="dp-info-row" class="hidden justify-between text-xs text-amber-800 font-bold bg-amber-50 p-2.5 rounded-xl border border-amber-200">
                            <span>Sisa Bayar Saat Check-in (50%):</span>
                            <span id="dp-remaining-price">Rp 0</span>
                        </div>
                        <div class="flex justify-between font-extrabold text-slate-900 pt-2.5 border-t border-slate-100">
                            <span id="total-pay-label">Total Tagihan Awal</span>
                            <span id="total-price" class="text-emerald-600 text-lg">Rp {{ number_format($unit->harga, 0, ',', '.') }}</span>
                        </div>
                    </div>


                    {{-- Tombol Kirim Pemesanan --}}
                    <button type="submit" id="btn-submit-booking" class="w-full mt-2 inline-flex items-center justify-center px-6 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-base rounded-xl transition-all shadow-lg shadow-emerald-600/10 active:scale-95">
                        Pesan Unit Sekarang
                    </button>
                    
                    {{-- Catatan Peringatan jika Tamu Belum Login --}}
                    @if(!session()->has('tamu_id'))
                        <div class="mt-3 p-3.5 bg-emerald-50/50 rounded-2xl border border-emerald-100/50 text-center">
                            <p class="text-xs text-emerald-800 font-semibold leading-relaxed">
                                Anda belum masuk. Klik tombol di atas untuk masuk atau daftar akun terlebih dahulu untuk melanjutkan pemesanan ini.
                            </p>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Lightbox Perbesar Foto Galeri --}}
<div id="lightboxModal" class="fixed inset-0 z-50 bg-slate-950/95 backdrop-blur-md hidden items-center justify-center p-4">
    <button type="button" onclick="closeLightbox()" class="absolute top-6 right-6 p-3 bg-white/10 hover:bg-white/20 text-white rounded-full transition-all z-50">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <div class="max-w-5xl w-full max-h-[85vh] flex flex-col items-center justify-center relative">
        <img id="lightboxImage" src="{{ asset('images/' . $unit->semua_foto[0]) }}" class="max-w-full max-h-[75vh] object-contain rounded-2xl shadow-2xl border border-white/10">
        <p id="lightboxCaption" class="text-white text-sm font-bold mt-4 bg-slate-900/80 px-4 py-2 rounded-full border border-white/10">
            Foto 1 dari {{ count($unit->semua_foto) }}
        </p>
    </div>
</div>
@endsection

@section('scripts')
<style>
/* Styling tanggal yang sudah penuh di kalender Flatpickr */
.flatpickr-booked {
    background: #fee2e2 !important;
    color: #991b1b !important;
    text-decoration: line-through;
    opacity: 0.7;
    cursor: not-allowed;
    position: relative;
}
.flatpickr-booked::after {
    content: '●';
    position: absolute;
    bottom: 2px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 5px;
    color: #dc2626;
}
</style>

<script>
    // ===== Skrip Multi-Foto Galeri =====
    let currentPhotoIndex = 0;
    const galleryPhotos = @json(array_map(fn($p) => asset('images/' . $p), $unit->semua_foto));

    function switchMainImage(srcUrl, index, btnEl) {
        currentPhotoIndex = index - 1;
        const mainImg = document.getElementById('mainGalleryImage');
        const mainCaption = document.getElementById('mainGalleryCaption');
        const lbImg = document.getElementById('lightboxImage');
        const lbCap = document.getElementById('lightboxCaption');

        if (mainImg) {
            mainImg.style.opacity = '0.4';
            setTimeout(() => {
                mainImg.src = srcUrl;
                mainImg.style.opacity = '1';
            }, 150);
        }

        if (mainCaption) {
            mainCaption.textContent = `Foto ${index} dari ${galleryPhotos.length}`;
        }
        if (lbImg) lbImg.src = srcUrl;
        if (lbCap) lbCap.textContent = `Foto ${index} dari ${galleryPhotos.length}`;

        // Reset thumbnail styling
        document.querySelectorAll('.thumb-btn').forEach(btn => {
            btn.className = 'thumb-btn relative rounded-2xl overflow-hidden h-20 sm:h-24 border-2 transition-all group border-slate-200 opacity-70 hover:opacity-100 hover:border-emerald-300';
        });

        if (btnEl) {
            btnEl.className = 'thumb-btn relative rounded-2xl overflow-hidden h-20 sm:h-24 border-2 transition-all group border-emerald-500 ring-2 ring-emerald-500/20 scale-[1.02]';
        }
    }

    function openLightbox() {
        const modal = document.getElementById('lightboxModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeLightbox() {
        const modal = document.getElementById('lightboxModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    // ===== Skrip Kalkulasi Harga & Pengecekan Ketersediaan Real-Time (AJAX) =====
    const pricePerNight = {{ $unit->harga }};
    const checkinInput = document.getElementById('tanggal_checkin');
    const checkoutInput = document.getElementById('tanggal_checkout');
    const durasiInput = document.getElementById('durasi_malam');
    const durasiDisplay = document.getElementById('durasi-display');
    const durasiSubtotal = document.getElementById('durasi-subtotal');
    const btnDurasiMinus = document.getElementById('btn-durasi-minus');
    const btnDurasiPlus = document.getElementById('btn-durasi-plus');
    const roomsInput = document.getElementById('jumlah_kamar');
    
    const headerPrice = document.getElementById('header-price');
    const headerPriceUnit = document.getElementById('header-price-unit');
    const nightsLabel = document.getElementById('nights-label');
    const basePrice = document.getElementById('base-price');
    const totalPrice = document.getElementById('total-price');
    const statusDiv = document.getElementById('availability-status');
    const btnSubmit = document.getElementById('btn-submit-booking');

    // Format angka ke mata uang Rupiah
    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    // Mengambil jumlah malam yang dipilih
    function calculateNights() {
        return parseInt(durasiInput ? durasiInput.value : 1) || 1;
    }

    // Sinkronisasi tanggal checkout di background
    function syncCheckoutFromDuration() {
        const ciVal = checkinInput.value;
        const durasi = calculateNights();
        if (!ciVal) return;

        const ciDate = new Date(ciVal + 'T00:00:00');
        ciDate.setDate(ciDate.getDate() + durasi);

        const y = ciDate.getFullYear();
        const m = String(ciDate.getMonth() + 1).padStart(2, '0');
        const d = String(ciDate.getDate()).padStart(2, '0');
        const coStr = `${y}-${m}-${d}`;

        if (checkoutInput) checkoutInput.value = coStr;
    }

    // Mengubah durasi sewa (+ / - malam)
    function setDuration(newNights) {
        let n = parseInt(newNights) || 1;
        if (n < 1) n = 1;
        if (n > 60) n = 60;

        if (durasiInput) durasiInput.value = n;
        if (durasiDisplay) durasiDisplay.textContent = n + ' Malam';
        if (btnDurasiMinus) btnDurasiMinus.disabled = (n <= 1);

        syncCheckoutFromDuration();
        updatePrice();
        checkAvailability();
    }

    // Memperbarui tampilan rincian harga
    function updatePrice() {
        const nights = calculateNights();
        const rooms = parseInt(roomsInput.value) || 1;
        const selectedPayment = document.querySelector('input[name="jenis_pembayaran"]:checked')?.value || 'full';
        const dpInfoRow = document.getElementById('dp-info-row');
        const dpRemainingPrice = document.getElementById('dp-remaining-price');
        const totalPayLabel = document.getElementById('total-pay-label');

        const total = pricePerNight * rooms * nights;

        if (headerPrice) headerPrice.textContent = formatRupiah(total);
        if (headerPriceUnit) headerPriceUnit.textContent = nights > 1 ? `(${nights} malam)` : '/malam';
        if (durasiSubtotal) durasiSubtotal.textContent = formatRupiah(pricePerNight * nights);
        if (nightsLabel) nightsLabel.textContent = `${nights} Malam x ${rooms} Unit`;
        if (basePrice) basePrice.textContent = formatRupiah(total);

        if (selectedPayment === 'dp') {
            const dpAmount = total * 0.50;
            const remainingAmount = total - dpAmount;
            if (totalPrice) totalPrice.textContent = formatRupiah(dpAmount);
            if (totalPayLabel) totalPayLabel.textContent = 'Nominal DP 50% Bayar Sekarang';
            if (dpRemainingPrice) dpRemainingPrice.textContent = formatRupiah(remainingAmount);
            if (dpInfoRow) {
                dpInfoRow.classList.remove('hidden');
                dpInfoRow.classList.add('flex');
            }
        } else {
            if (totalPrice) totalPrice.textContent = formatRupiah(total);
            if (totalPayLabel) totalPayLabel.textContent = 'Total Tagihan (Full 100%)';
            if (dpInfoRow) {
                dpInfoRow.classList.add('hidden');
                dpInfoRow.classList.remove('flex');
            }
        }
    }

    // Pengecekan ketersediaan unit melalui AJAX
    async function checkAvailability() {
        syncCheckoutFromDuration();
        const checkin = checkinInput.value;
        const checkout = checkoutInput.value;
        const rooms = roomsInput.value;
        
        if (!checkin || !checkout || !rooms || calculateNights() <= 0) {
            return;
        }

        if (statusDiv) {
            statusDiv.className = 'p-3 rounded-xl text-xs font-semibold bg-slate-50 text-slate-600 animate-pulse block';
            statusDiv.textContent = 'Memeriksa ketersediaan unit...';
        }

        try {
            const response = await fetch('{{ route("bookings.check") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id_unit: {{ $unit->id_unit }},
                    tanggal_checkin: checkin,
                    tanggal_checkout: checkout,
                    jumlah_kamar: rooms
                })
            });

            const result = await response.json();
            if (statusDiv) {
                statusDiv.className = 'p-3 rounded-xl text-xs font-semibold block';
                statusDiv.textContent = result.message;
            }

            if (result.success) {
                if (statusDiv) statusDiv.classList.add('bg-emerald-50', 'text-emerald-700', 'border', 'border-emerald-200');
                if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            } else {
                if (statusDiv) statusDiv.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
                if (btnSubmit) {
                    btnSubmit.disabled = true;
                    btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }
        } catch (error) {
            console.error('Error checking availability:', error);
            if (statusDiv) {
                statusDiv.className = 'p-3 rounded-xl text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 block';
                statusDiv.textContent = 'Gagal memeriksa ketersediaan. Silakan coba lagi.';
            }
        }
    }

    // Event listener tombol minus dan plus durasi
    if (btnDurasiMinus) {
        btnDurasiMinus.addEventListener('click', (e) => {
            e.preventDefault();
            const current = calculateNights();
            setDuration(current - 1);
        });
    }

    if (btnDurasiPlus) {
        btnDurasiPlus.addEventListener('click', (e) => {
            e.preventDefault();
            const current = calculateNights();
            setDuration(current + 1);
        });
    }

    // Event listener ketik langsung pada input durasi malam
    if (durasiInput) {
        durasiInput.addEventListener('input', () => {
            let val = parseInt(durasiInput.value);
            if (!isNaN(val) && val >= 1) {
                if (val > 60) {
                    val = 60;
                    durasiInput.value = 60;
                }
                if (btnDurasiMinus) btnDurasiMinus.disabled = (val <= 1);
                syncCheckoutFromDuration();
                updatePrice();
                checkAvailability();
            }
        });

        durasiInput.addEventListener('blur', () => {
            let val = parseInt(durasiInput.value);
            if (isNaN(val) || val < 1) {
                setDuration(1);
            } else if (val > 60) {
                setDuration(60);
            } else {
                setDuration(val);
            }
        });
    }

    // Inisialisasi Flatpickr jika library dimuat
    if (typeof flatpickr !== 'undefined') {
        let disabledDates = [];
        let fpCheckin;

        async function loadBookedDates(jumlah_kamar = 1) {
            try {
                const res = await fetch(`{{ route('units.booked-dates') }}?id_unit={{ $unit->id_unit }}&jumlah_kamar=${jumlah_kamar}`);
                const data = await res.json();
                disabledDates = data.disabled_dates || [];

                if (fpCheckin) fpCheckin.set('disable', [function(date) {
                    const d = date.toISOString().split('T')[0];
                    return disabledDates.includes(d);
                }]);
            } catch (e) {
                console.error('Gagal memuat tanggal penuh:', e);
            }
        }

        fpCheckin = flatpickr("#tanggal_checkin", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            altInputClass: "w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-emerald-500 transition-colors cursor-pointer",
            locale: "id",
            minDate: "today",
            defaultDate: checkinInput.value || "today",
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                const dateStr = dayElem.dateObj.toISOString().split('T')[0];
                if (disabledDates.includes(dateStr)) {
                    dayElem.classList.add('flatpickr-booked');
                    dayElem.title = 'Unit penuh pada tanggal ini';
                }
            },
            onChange: function(selectedDates, dateStr) {
                syncCheckoutFromDuration();
                updatePrice();
                checkAvailability();
            }
        });

        loadBookedDates(parseInt(roomsInput.value) || 1);

        roomsInput.addEventListener('input', () => {
            loadBookedDates(parseInt(roomsInput.value) || 1);
        });
    } else {
        checkinInput.addEventListener('change', () => {
            syncCheckoutFromDuration();
            updatePrice();
            checkAvailability();
        });
    }

    roomsInput.addEventListener('input', () => {
        updatePrice();
        checkAvailability();
    });

    document.querySelectorAll('input[name="jenis_pembayaran"]').forEach(radio => {
        radio.addEventListener('change', updatePrice);
    });

    // Inisialisasi awal saat halaman dimuat
    setDuration(parseInt(durasiInput ? durasiInput.value : 1) || 1);
</script>
@endsection
