@extends('layouts.app')

@section('title', 'Rincian Reservasi - Tanjung Api')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumbs -->
    <nav class="flex mb-8 text-xs sm:text-sm text-slate-500 font-medium" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="hover:text-emerald-600 transition-colors">Beranda</a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-4.5 h-4.5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <a href="{{ route('bookings.index') }}" class="ml-1 md:ml-2 hover:text-emerald-600 transition-colors">Riwayat Pesanan</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-4.5 h-4.5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 md:ml-2 text-slate-800 font-semibold">Rincian Reservasi</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Booking Status Alert Banners (confirmed, completed, postponed & cancelled) -->
    @if($booking->status === 'confirmed' || $booking->status === 'completed')
    <div class="mb-8">
        <div class="flex items-start gap-3 p-6 bg-emerald-50 border border-emerald-200 rounded-3xl text-emerald-800 shadow-sm animate-fade-in">
            <svg class="w-6 h-6 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h4 class="font-extrabold text-slate-900 leading-snug">
                    {{ $booking->status === 'completed' ? 'Pemesanan Selesai' : 'Pembayaran Lunas & Terkonfirmasi!' }}
                </h4>
                <p class="text-xs text-slate-600 mt-0.5">
                    {{ $booking->status === 'completed' ? 'Terima kasih telah menginap di Pantai Tanjung Api.' : 'Reservasi Anda telah aktif. Tunjukkan bukti transfer ini kepada pengelola pada saat check-in.' }}
                </p>
            </div>
        </div>
    </div>
    @elseif($booking->status === 'postponed')
    <div class="mb-8">
        <div class="p-6 bg-amber-50 border border-amber-200 rounded-3xl text-amber-900 shadow-sm space-y-3 animate-fade-in">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="space-y-1">
                    <h4 class="font-extrabold text-slate-900 leading-snug">Reservasi Ditunda (Pending Reschedule)</h4>
                    <p class="text-xs text-slate-700 leading-relaxed">
                        Reservasi Anda berstatus ditunda. Anda diberikan batas waktu <strong>1 bulan</strong> hingga 
                        <strong class="text-amber-900 font-extrabold">{{ $booking->batas_tunda_hingga ? $booking->batas_tunda_hingga->translatedFormat('d F Y, H:i') : '-' }} WIB</strong> 
                        untuk melakukan penjadwalan ulang (*reschedule*). Jika melewati tenggat waktu tersebut tanpa *reschedule*, uang reservasi akan hangus.
                    </p>
                    @if($booking->alasan_penundaan)
                        <p class="text-xs text-slate-600 italic">Alasan penundaan: {{ $booking->alasan_penundaan }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @elseif($booking->status === 'cancelled')
    <div class="mb-8 space-y-4" x-data="{ resumeModal: false }">
        <div class="p-6 bg-red-50 border border-red-200 rounded-3xl text-red-800 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <h4 class="font-extrabold text-slate-900 leading-snug">Reservasi Dibatalkan</h4>
                        <p class="text-xs text-slate-600 mt-0.5">Alasan: {{ $booking->alasan_pembatalan ?? 'Dibatalkan oleh pemesan/pengelola' }}</p>
                    </div>
                </div>

                {{-- Tombol Lanjutkan Pemesanan Kembali --}}
                <button type="button" @click="resumeModal = true" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-all active:scale-95 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Lanjutkan Pemesanan
                </button>
            </div>

            <div class="p-3 bg-red-100/60 border border-red-200 text-red-800 text-xs font-semibold rounded-xl">
                Reservasi ini berstatus dibatalkan. Jika Anda ingin mengaktifkannya kembali untuk melanjutkan pembayaran atau menginap, klik tombol <strong>"Lanjutkan Pemesanan"</strong> di atas.
            </div>
        </div>

        {{-- Modal Konfirmasi Lanjutkan Reservasi --}}
        <div x-show="resumeModal" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm text-left" x-transition>
            <div @click.away="resumeModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 space-y-5 shadow-2xl border border-slate-100">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-lg text-emerald-800">Konfirmasi Lanjutkan Pemesanan</h3>
                    <button type="button" @click="resumeModal = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                </div>
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs text-emerald-900 space-y-2">
                    <p class="leading-relaxed font-medium">
                        Apakah Anda ingin mengaktifkan kembali reservasi <strong>#TA-{{ str_pad($booking->id_reservasi, 4, '0', STR_PAD_LEFT) }}</strong>? 
                        Reservasi akan diaktifkan kembali sehingga Anda dapat melanjutkan pembayaran atau persiapan menginap.
                    </p>
                </div>
                <form action="{{ route('bookings.resume', $booking->id_reservasi) }}" method="POST">
                    @csrf
                    <div class="pt-2 flex items-center justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="resumeModal = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-all">
                            Ya, Lanjutkan Pemesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif


    <!-- Invoice Details Card -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-900/5 overflow-hidden">
        <!-- Invoice Header -->
        <div class="bg-slate-950 text-white p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="space-y-1">
                <div class="text-xs font-bold uppercase tracking-wider text-emerald-400">Rincian Reservasi</div>
                <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight">TA-{{ str_pad($booking->id_reservasi, 4, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <div class="text-left sm:text-right text-xs text-slate-400 leading-relaxed font-semibold">
                <div>Tanggal Pemesanan:</div>
                <div class="text-white">{{ $booking->created_at->format('d M Y, H:i') }} WIB</div>
            </div>
        </div>

        <div class="p-8 space-y-8">
            <!-- Row 1: Guest & Accommodation Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Tamu Info -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pemesan</h4>
                    <div class="space-y-1 font-semibold text-sm">
                        <div class="text-slate-900 font-extrabold text-base flex items-center gap-2">
                            <span>{{ $booking->tamu->nama }}</span>
                            @if(($booking->tamu->kewarganegaraan ?? 'WNI') === 'WNA')
                                <span class="px-2 py-0.5 bg-purple-50 text-purple-700 text-[10px] font-extrabold rounded-full border border-purple-200">🌐 WNA</span>
                            @else
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-extrabold rounded-full border border-emerald-200">🇮🇩 WNI</span>
                            @endif
                        </div>
                        @if(($booking->tamu->kewarganegaraan ?? 'WNI') === 'WNA')
                            <div class="text-slate-600">No. Paspor: <span class="font-mono text-slate-800 font-bold">{{ $booking->tamu->no_paspor ?? '-' }}</span></div>
                            <div class="text-slate-600">Negara Asal: <span class="text-slate-800 font-semibold">{{ $booking->tamu->negara ?? 'Luar Negeri' }}</span></div>
                        @else
                            @if(!empty($booking->tamu->no_ktp))
                                <div class="text-slate-600">NIK / No. KTP: <span class="font-mono text-slate-800 font-bold">{{ $booking->tamu->no_ktp }}</span></div>
                            @endif
                        @endif
                        <div class="text-slate-600">Alamat: <span class="text-slate-800 font-semibold">{{ $booking->tamu->alamat_lengkap_formatted }}</span></div>
                        <div class="text-slate-600">{{ $booking->tamu->email ?? '-' }}</div>
                        <div class="text-slate-600">{{ $booking->tamu->no_hp }}</div>
                    </div>
                </div>
                
                <!-- Akomodasi Info -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Akomodasi</h4>
                    <div class="space-y-1 font-semibold text-sm">
                        <div class="text-slate-900 font-extrabold text-base">{{ $booking->penginapan->nama_unit }}</div>
                        <div class="text-slate-600">{{ $booking->penginapan->alamat }}</div>
                        <div class="text-slate-600 uppercase text-xs px-2 py-0.5 rounded bg-slate-100 inline-block font-bold mt-1 text-slate-700">Tipe: {{ $booking->penginapan->tipe_unit }}</div>
                    </div>
                </div>
            </div>

            <!-- Row 2: Date Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Check-In</span>
                    <strong class="text-sm text-slate-900 block mt-0.5">{{ date('d M Y', strtotime($booking->tanggal_checkin)) }}</strong>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Check-Out</span>
                    <strong class="text-sm text-slate-900 block mt-0.5">{{ date('d M Y', strtotime($booking->tanggal_checkout)) }}</strong>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Durasi</span>
                    <strong class="text-sm text-slate-900 block mt-0.5">{{ $nights }} Malam</strong>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Jumlah Unit</span>
                    <strong class="text-sm text-slate-900 block mt-0.5">{{ $booking->jumlah_kamar }} Unit</strong>
                </div>
            </div>

            <!-- Row 3: Payment Breakdown -->
            <div class="space-y-4">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rincian Harga & Skema Pembayaran</h4>
                <div class="border border-slate-100 rounded-2xl overflow-hidden divide-y divide-slate-100 text-sm">
                    <div class="p-4 flex justify-between font-semibold text-slate-700">
                        <span>Harga Kamar ({{ $booking->jumlah_kamar }} Unit x {{ $nights }} Malam)</span>
                        <span>Rp {{ number_format($booking->penginapan->harga * $booking->jumlah_kamar * $nights, 0, ',', '.') }}</span>
                    </div>

                    @if($booking->pembayaran && $booking->pembayaran->jenis_pembayaran === 'dp')
                        <div class="p-4 flex justify-between font-bold text-amber-900 bg-amber-50/50">
                            <span>Nominal DP (50%) - Dibayar Awal</span>
                            <span>Rp {{ number_format($booking->pembayaran->nominal_dp, 0, ',', '.') }}</span>
                        </div>
                        <div class="p-4 flex justify-between font-bold text-slate-800 bg-slate-50/70">
                            <span>Sisa Tagihan (50%) - Dibayar saat Check-in</span>
                            <span class="text-amber-700">Rp {{ number_format($booking->pembayaran->sisa_pembayaran, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="p-4 flex justify-between font-bold text-slate-950 text-base bg-slate-50/50">
                        <span>Total Keseluruhan Tagihan</span>
                        <span class="text-emerald-600 text-lg">Rp {{ number_format($booking->pembayaran->total_pembayaran, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Banner Menunggu Pembayaran di bawah Total Tagihan --}}
                @if($booking->status === 'pending' || ($booking->pembayaran && $booking->pembayaran->status_pembayaran !== 'lunas'))
                    @php
                        $isDp = ($booking->pembayaran && $booking->pembayaran->jenis_pembayaran === 'dp');
                        $isPaid = ($booking->pembayaran && $booking->pembayaran->status_pembayaran === 'lunas');
                        $amountToPay = $isDp ? ($booking->pembayaran->nominal_dp ?? 0) : ($booking->pembayaran->total_pembayaran ?? 0);
                    @endphp

                    @if(!$isPaid)
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-5 bg-amber-50 border border-amber-200 rounded-2xl text-amber-800 shadow-sm mt-2">
                            <div class="flex gap-3">
                                <svg class="w-6 h-6 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-extrabold text-slate-900 leading-snug">
                                        Menunggu Pembayaran {{ $isDp ? 'Uang Muka / DP (50%)' : 'Full (100%)' }}
                                    </h4>
                                    <p class="text-xs text-slate-600 mt-0.5">
                                        Nominal yang harus dibayar: <strong class="text-emerald-700 font-extrabold text-sm">Rp {{ number_format($amountToPay, 0, ',', '.') }}</strong>
                                        @if($isDp)
                                            <span class="text-slate-500 font-medium">(Sisa 50% sebesar Rp {{ number_format($booking->pembayaran->sisa_pembayaran ?? 0, 0, ',', '.') }} dibayar saat check-in)</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('payment.sandbox', $booking->id_reservasi) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm rounded-xl transition-all shadow-md active:scale-95 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Bayar Sekarang (Midtrans SNAP)
                            </a>
                        </div>
                    @elseif($isDp && $booking->pembayaran->status_pelunasan === 'belum_lunas')
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-5 bg-amber-50 border border-amber-200 rounded-2xl text-amber-800 shadow-sm mt-2">
                            <div class="flex gap-3">
                                <svg class="w-6 h-6 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-extrabold text-slate-900 leading-snug">DP 50% Telah Lunas!</h4>
                                    <p class="text-xs text-slate-600 mt-0.5">Sisa tagihan sebesar <strong class="text-amber-700">Rp {{ number_format($booking->pembayaran->sisa_pembayaran, 0, ',', '.') }}</strong> akan dibayarkan saat check-in.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-5 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 shadow-sm mt-2">
                            <div class="flex gap-3">
                                <svg class="w-6 h-6 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-extrabold text-slate-900 leading-snug">Pembayaran Lunas &amp; Terkonfirmasi!</h4>
                                    <p class="text-xs text-slate-600 mt-0.5">Reservasi Anda telah lunas dan aktif. Tunjukkan nomor reservasi ini saat check-in.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Row 4: Transaction Status Details -->
            @if(in_array($booking->status, ['confirmed', 'completed']) || ($booking->pembayaran && $booking->pembayaran->status_pembayaran === 'lunas'))
                <div class="pt-6 border-t border-slate-100 space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Detail Pembayaran</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm font-semibold">
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                            <span class="text-[10px] text-slate-400 uppercase font-bold block">Skema Pembayaran</span>
                            <span class="text-slate-800 block mt-0.5 font-extrabold">
                                {{ $booking->pembayaran->jenis_pembayaran === 'dp' ? 'Uang Muka / DP (50%)' : 'Bayar Full (100%)' }}
                            </span>
                        </div>
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                            <span class="text-[10px] text-slate-400 uppercase font-bold block">Metode Pembayaran</span>
                            <span class="text-slate-800 block mt-0.5">{{ $booking->pembayaran->metode_pembayaran ?? '-' }}</span>
                        </div>
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                            <span class="text-[10px] text-slate-400 uppercase font-bold block">Status Pelunasan</span>
                            @if($booking->pembayaran->jenis_pembayaran === 'dp' && $booking->pembayaran->status_pelunasan === 'belum_lunas')
                                <span class="text-amber-700 font-extrabold block mt-0.5">⏳ DP Lunas (Sisa Rp {{ number_format($booking->pembayaran->sisa_pembayaran, 0, ',', '.') }} saat Check-in)</span>
                            @else
                                <span class="text-emerald-600 font-extrabold block mt-0.5">✅ LUNAS 100%</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif


            <!-- Row 5: Kebijakan Pembatalan & Penundaan (Status confirmed atau postponed) -->
            @if(in_array($booking->status, ['pending', 'confirmed', 'postponed']))
                <div class="pt-6 border-t border-slate-100 space-y-4" x-data="{ cancelModal: false, postponeModal: false, rescheduleModal: false }">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-5 bg-slate-50 border border-slate-200 rounded-2xl">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Ketentuan Pembatalan & Penundaan</span>
                                @if($booking->status === 'postponed')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold border bg-amber-100 text-amber-800 border-amber-300">
                                        Ditunda (Maks. 1 Bulan)
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold border bg-red-100 text-red-800 border-red-300">
                                        Pembatalan = Uang Hangus (0%)
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                @if($booking->status === 'postponed')
                                    Silakan lakukan penjadwalan ulang (*reschedule*) sebelum <strong>{{ $booking->batas_tunda_hingga ? $booking->batas_tunda_hingga->translatedFormat('d F Y') : '-' }}</strong> agar uang tidak hangus.
                                @else
                                    Jika Anda berhalangan hadir, Anda dapat memilih <strong>Penundaan (1 Bulan)</strong> untuk *reschedule* tanggal lain. Pembatalan langsung akan menyebabkan dana hangus (0% refund).
                                @endif
                            </p>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0 flex-wrap sm:flex-nowrap">
                            @if($booking->status === 'pending')
                                <form action="{{ route('bookings.cancel', $booking->id_reservasi) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?');">
                                    @csrf
                                    <button type="submit" class="px-4 py-2.5 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 font-bold text-xs rounded-xl transition-all shadow-sm active:scale-95">
                                        Batalkan Pemesanan
                                    </button>
                                </form>
                            @elseif($booking->status === 'confirmed')
                                <button @click="cancelModal = true" class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl transition-all shadow-md active:scale-95">
                                    ❌ Batalkan (Uang Hangus)
                                </button>
                            @elseif($booking->status === 'postponed')
                                <button @click="rescheduleModal = true" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl transition-all shadow-md active:scale-95 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Jadwalkan Ulang (Reschedule)
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Modal Popup Pembatalan Direct (Uang Hangus) -->
                    @if($booking->status === 'confirmed')
                        <div x-show="cancelModal" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition>
                            <div @click.away="cancelModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 space-y-5 shadow-2xl border border-slate-100">
                                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                    <h3 class="font-extrabold text-lg text-red-600">Konfirmasi Pembatalan Reservasi</h3>
                                    <button @click="cancelModal = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                                </div>

                                <div class="p-4 bg-red-50 border border-red-200 rounded-2xl text-xs text-red-900 space-y-2">
                                    <div class="font-extrabold flex items-center gap-1.5 text-red-900">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        <span>PERHATIAN (UANG HANGUS):</span>
                                    </div>
                                    <p class="leading-relaxed">
                                        Sesuai kebijakan yang berlaku, jika Anda membatalkan reservasi secara langsung, maka <strong>uang pembayaran 100% hangus (0% refund)</strong>.
                                    </p>
                                </div>

                                <form action="{{ route('bookings.cancel', $booking->id_reservasi) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="alasan_pembatalan" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Alasan Pembatalan <span class="text-slate-400 text-[10px]">(Opsional)</span></label>
                                        <textarea name="alasan_pembatalan" id="alasan_pembatalan" rows="3" placeholder="Tuliskan alasan pembatalan Anda..."
                                                  class="w-full px-4 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 focus:border-red-500 focus:outline-none"></textarea>
                                    </div>

                                    <div class="pt-2 flex items-center justify-end gap-3 border-t border-slate-100">
                                        <button type="button" @click="cancelModal = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800">
                                            Kembali
                                        </button>
                                        <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-all">
                                            Ya, Batalkan & Uang Hangus
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Modal Popup Reschedule (Penjadwalan Ulang) -->
                    @if($booking->status === 'postponed')
                        <div x-show="rescheduleModal" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition>
                            <div @click.away="rescheduleModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 space-y-5 shadow-2xl border border-slate-100">
                                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                    <h3 class="font-extrabold text-lg text-slate-900">Jadwalkan Ulang (Reschedule)</h3>
                                    <button @click="rescheduleModal = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                                </div>

                                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs text-emerald-900 space-y-1">
                                    <div class="font-extrabold flex items-center gap-1.5">
                                        <span>Unit Penginapan: {{ $booking->penginapan->nama_unit }} ({{ $booking->jumlah_kamar }} Unit)</span>
                                    </div>
                                    <p class="leading-relaxed">
                                        Pilih tanggal check-in dan check-out baru. Batas akhir penundaan Anda adalah <strong>{{ $booking->batas_tunda_hingga ? $booking->batas_tunda_hingga->translatedFormat('d F Y') : '-' }}</strong>.
                                    </p>
                                </div>

                                <form action="{{ route('bookings.reschedule', $booking->id_reservasi) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="tanggal_checkin_reschedule" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Check-in Baru <span class="text-red-500">*</span></label>
                                            <input type="date" name="tanggal_checkin" id="tanggal_checkin_reschedule" min="{{ date('Y-m-d') }}" required
                                                   class="w-full px-4 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 focus:border-emerald-500 focus:outline-none bg-white">
                                        </div>
                                        <div>
                                            <label for="tanggal_checkout_reschedule" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Check-out Baru <span class="text-red-500">*</span></label>
                                            <input type="date" name="tanggal_checkout" id="tanggal_checkout_reschedule" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required
                                                   class="w-full px-4 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 focus:border-emerald-500 focus:outline-none bg-white">
                                        </div>
                                    </div>

                                    <div class="pt-2 flex items-center justify-end gap-3 border-t border-slate-100">
                                        <button type="button" @click="rescheduleModal = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800">
                                            Batal
                                        </button>
                                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-all">
                                            Konfirmasi Reschedule
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>

    <!-- Tombol Navigasi Bawah -->
    <div class="mt-6 flex flex-col sm:flex-row gap-3">
        <a href="{{ route('units.show', $booking->id_unit) }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-white border border-slate-200 hover:border-emerald-400 hover:text-emerald-600 text-slate-700 font-semibold text-sm rounded-xl transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>
</div>
@endsection
