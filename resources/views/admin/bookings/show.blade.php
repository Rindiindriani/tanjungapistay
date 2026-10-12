{{-- Menggunakan layout utama admin --}}
@extends('layouts.admin')

{{-- Menentukan judul halaman detail pemesanan --}}
@section('title', 'Detail Pemesanan - Tanjung Api')
@section('header_title', 'Detail Pemesanan')

@section('content')
{{-- Menghitung durasi malam menginap berdasarkan tanggal check-in dan check-out --}}
@php
    $ci = \Carbon\Carbon::parse($booking->tanggal_checkin);
    $co = \Carbon\Carbon::parse($booking->tanggal_checkout);
    $nights = $ci->diffInDays($co);
    if ($nights <= 0) $nights = 1;
@endphp

<div class="max-w-4xl mx-auto space-y-6">
    {{-- Tombol Kembali ke Daftar Pemesanan --}}
    <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Daftar Pemesanan
    </a>

    {{-- Spanduk Status Reservasi & Tombol Aksi Cepat Admin --}}
    <div class="shadow-sm rounded-3xl overflow-hidden border border-slate-200">

        @if($booking->status === 'pending')
            {{-- Tampilan Banner jika Status Menunggu Pembayaran --}}
            <div class="bg-amber-50 p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 text-amber-900">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-extrabold text-slate-900 leading-snug">Menunggu Pembayaran Tamu</h4>
                        <p class="text-xs text-slate-700 mt-0.5">
                            Tamu belum menyelesaikan pembayaran reservasi ini. Admin dapat mengonfirmasi secara manual jika pembayaran telah diterima langsung atau membatalkan reservasi.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 flex-shrink-0 flex-wrap">
                    {{-- Tombol Konfirmasi Pembayaran Manual --}}
                    <form action="{{ route('admin.bookings.confirm', $booking->id_reservasi) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa pembayaran reservasi ini telah diterima secara manual?');">
                        @csrf
                        <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-all active:scale-95">
                            ✅ Konfirmasi Manual
                        </button>
                    </form>

                    {{-- Tombol Batalkan Reservasi --}}
                    <form action="{{ route('admin.bookings.cancel', $booking->id_reservasi) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?');">
                        @csrf
                        <button type="submit" class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-all active:scale-95">
                            ❌ Batalkan Reservasi
                        </button>
                    </form>
                </div>
            </div>

        @elseif($booking->status === 'confirmed' || $booking->status === 'completed')
            {{-- Tampilan Banner jika Status Reservasi Lunas & Terkonfirmasi / Selesai --}}
            <div class="bg-emerald-50 p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 text-emerald-800">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-extrabold text-slate-900 leading-snug">
                            {{ $booking->status === 'completed' ? 'Pemesanan Selesai (Lunas & Check-out)' : 'Pembayaran Terkonfirmasi' }}
                        </h4>
                        <p class="text-xs text-slate-600 mt-0.5">
                            @if($booking->pembayaran && $booking->pembayaran->jenis_pembayaran === 'dp' && $booking->pembayaran->status_pelunasan === 'belum_lunas')
                                <strong class="text-amber-800">⚠️ Skema DP 50%:</strong> Tamu telah membayar DP 50% (Rp {{ number_format($booking->pembayaran->nominal_dp, 0, ',', '.') }}). Sisa 50% sebesar <strong class="text-slate-900 font-extrabold">Rp {{ number_format($booking->pembayaran->sisa_pembayaran, 0, ',', '.') }}</strong> wajib dilunasi saat check-in di lokasi.
                            @else
                                {{ $booking->status === 'completed' ? 'Tamu telah menyelesaikan masa menginap di penginapan.' : 'Reservasi ini telah diverifikasi lunas 100%. Tamu berhak melakukan check-in pada tanggal yang telah ditentukan.' }}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 flex-shrink-0 flex-wrap">
                    @if($booking->status === 'confirmed')
                        {{-- Tombol Konfirmasi Pelunasan DP (jika skema DP & belum lunas) --}}
                        @if($booking->pembayaran && $booking->pembayaran->jenis_pembayaran === 'dp' && $booking->pembayaran->status_pelunasan === 'belum_lunas')
                            <form action="{{ route('admin.bookings.confirm-pelunasan', $booking->id_reservasi) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa tamu telah melunasi sisa tagihan DP 50% sebesar Rp {{ number_format($booking->pembayaran->sisa_pembayaran, 0, ',', '.') }} saat check-in?');">
                                @csrf
                                <button type="submit" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-all active:scale-95">
                                    ✅ Konfirmasi Pelunasan Sisa DP (Rp {{ number_format($booking->pembayaran->sisa_pembayaran, 0, ',', '.') }})
                                </button>
                            </form>
                        @endif

                        {{-- Tombol Checkout Manual oleh Admin (dengan deteksi late checkout) --}}
                        <div x-data="checkoutModal({
                            checkoutDate: '{{ $booking->tanggal_checkout }}',
                            biayaPermalam: {{ $booking->penginapan->harga * $booking->jumlah_kamar }},
                            idReservasi: {{ $booking->id_reservasi }},
                            nomorReservasi: '#TA-{{ str_pad($booking->id_reservasi, 4, '0', STR_PAD_LEFT) }}'
                        })">
                            <button type="button" @click="openModal()"
                                class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-extrabold text-xs rounded-xl shadow-md transition-all active:scale-95 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Checkout Tamu
                            </button>

                            {{-- Modal Checkout (Normal / Late) --}}
                            <div x-show="show" x-cloak style="display:none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm" x-transition>
                                <div @click.away="show = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-5">

                                    {{-- Header --}}
                                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                        <h3 class="font-extrabold text-lg" :class="isLate ? 'text-amber-700' : 'text-slate-900'">
                                            <span x-show="!isLate">Konfirmasi Checkout Tamu</span>
                                            <span x-show="isLate">⚠️ Checkout Terlambat!</span>
                                        </h3>
                                        <button @click="show = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                                    </div>

                                    {{-- Info Kebijakan Checkout --}}
                                    <div class="p-4 rounded-2xl text-xs space-y-2 border"
                                        :class="isLate ? 'bg-amber-50 border-amber-200 text-amber-900' : 'bg-emerald-50 border-emerald-200 text-emerald-900'">
                                        <div class="font-extrabold flex items-center gap-1.5">
                                            <svg class="w-4 h-4" :class="isLate ? 'text-amber-600' : 'text-emerald-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span x-text="isLate ? 'Tamu melewati batas checkout jam 12:00 WIB' : 'Checkout sesuai kebijakan (sebelum jam 12:00 WIB)'" class="leading-snug"></span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 pt-1">
                                            <div class="p-2 bg-white/70 rounded-xl">
                                                <span class="block text-[10px] font-bold uppercase tracking-wider opacity-70">Reservasi</span>
                                                <span class="font-extrabold" x-text="nomorReservasi"></span>
                                            </div>
                                            <div class="p-2 bg-white/70 rounded-xl">
                                                <span class="block text-[10px] font-bold uppercase tracking-wider opacity-70">Waktu Sekarang</span>
                                                <span class="font-extrabold" x-text="currentTimeStr"></span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Opsi Late Checkout Fee (hanya muncul jika terlambat) --}}
                                    <div x-show="isLate" class="p-4 bg-red-50 border border-red-200 rounded-2xl text-xs space-y-3">
                                        <div class="font-extrabold text-red-800 flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            Kebijakan Late Checkout
                                        </div>
                                        <p class="text-red-700 leading-relaxed">
                                            Tamu melewati batas waktu checkout <strong>jam 12:00 WIB</strong>. Sesuai kebijakan, dikenakan <strong>biaya tambahan 1 malam</strong>.
                                        </p>
                                        <div class="p-3 bg-white rounded-xl border border-red-200 flex justify-between items-center">
                                            <span class="font-bold text-slate-700">Biaya Tambahan (1 Malam):</span>
                                            <span class="font-extrabold text-red-700 text-sm" x-text="formatRupiah(biayaPermalam)"></span>
                                        </div>
                                        <label class="flex items-center gap-2.5 cursor-pointer">
                                            <input type="checkbox" x-model="addLateCharge" class="w-4 h-4 text-red-600 rounded">
                                            <span class="font-bold text-red-800">Tambahkan biaya late checkout ke tagihan</span>
                                        </label>
                                    </div>

                                    {{-- Form Submit --}}
                                    <form :action="formAction" method="POST">
                                        @csrf
                                        <input type="hidden" name="late_checkout" :value="isLate && addLateCharge ? '1' : '0'">
                                        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                                            <button type="button" @click="show = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800">Batal</button>
                                            <button type="submit" class="px-5 py-2.5 text-white font-extrabold text-xs rounded-xl shadow-md transition-all"
                                                :class="isLate && addLateCharge ? 'bg-red-600 hover:bg-red-700' : 'bg-slate-800 hover:bg-slate-900'">
                                                <span x-show="!isLate">✅ Konfirmasi Checkout</span>
                                                <span x-show="isLate && !addLateCharge">✅ Checkout (Tanpa Biaya Tambahan)</span>
                                                <span x-show="isLate && addLateCharge">⚠️ Checkout + Biaya Tambahan</span>
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>

                    @elseif($booking->status === 'completed')
                        <span class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-slate-100 text-slate-600 font-extrabold text-xs rounded-xl border border-slate-200">
                            ✅ Tamu Sudah Checkout
                        </span>
                    @endif
                </div>
            </div>


        @elseif($booking->status === 'postponed')
            {{-- Tampilan Banner jika Status Ditunda --}}
            <div class="bg-amber-50 p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 text-amber-900" x-data="{ adminRescheduleModal: false }">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-extrabold text-slate-900 leading-snug">Reservasi Ditunda (Maks. 1 Bulan)</h4>
                        <p class="text-xs text-slate-700 mt-0.5">
                            Batas Waktu Penundaan: <strong class="text-amber-900 font-extrabold">{{ $booking->batas_tunda_hingga ? $booking->batas_tunda_hingga->format('d M Y, H:i') : '-' }} WIB</strong>. Jika lewat 1 bulan tanpa reschedule, uang otomatis hangus.
                        </p>
                        @if($booking->alasan_penundaan)
                            <p class="text-xs text-slate-600 italic mt-0.5">Alasan: {{ $booking->alasan_penundaan }}</p>
                        @endif
                    </div>
                </div>



        @elseif($booking->status === 'cancelled')
            {{-- Tampilan Banner jika Status Reservasi Dibatalkan --}}
            <div class="bg-red-50 p-6 space-y-4 text-red-800">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <h4 class="font-extrabold text-slate-900 leading-snug">Reservasi Dibatalkan</h4>
                            <p class="text-xs text-slate-600 mt-0.5">Alasan: {{ $booking->alasan_pembatalan ?? 'Dibatalkan oleh pemesan / pengelola' }}</p>
                        </div>
                    </div>

                    @if($booking->status_refund === 'pending_refund')
                        <form action="{{ route('admin.bookings.confirm-refund', $booking->id_reservasi) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin telah melakukan transfer refund dana sebesar Rp {{ number_format($booking->jumlah_refund, 0, ',', '.') }} ke rekening tamu?');">
                            @csrf
                            <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-all active:scale-95 flex-shrink-0">
                                ✅ Konfirmasi Transfer Refund Selesai
                            </button>
                        </form>
                    @endif
                </div>

                @if($booking->persentase_refund > 0)
                    <div class="p-4 bg-white rounded-2xl border border-red-200 text-xs space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-slate-700">Pengajuan Refund Tamu:</span>
                            <span class="px-2.5 py-0.5 bg-amber-100 text-amber-900 font-extrabold rounded-lg text-xs border border-amber-300">
                                {{ $booking->persentase_refund }}% - Rp {{ number_format($booking->jumlah_refund, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 pt-1 font-semibold text-slate-700">
                            <div>Bank / E-Wallet: <strong class="text-slate-900">{{ $booking->bank_refund }}</strong></div>
                            <div>No. Rekening / HP: <strong class="text-slate-900">{{ $booking->rekening_refund }}</strong></div>
                            <div>Atas Nama: <strong class="text-slate-900">{{ $booking->nama_rekening_refund }}</strong></div>
                        </div>
                        <div class="pt-1">
                            @if($booking->status_refund === 'refunded')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-800 text-[11px] font-bold rounded-lg border border-emerald-300">
                                    ✅ Status: Transfer Refund Selesai Dikonfirmasi
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-100 text-amber-800 text-[11px] font-bold rounded-lg border border-amber-300">
                                    ⏳ Status: Menunggu Transfer Refund Pengelola
                                </span>
                            @endif
                        </div>
                    </div>
                @elseif($booking->status_refund === 'no_refund')
                    <div class="p-3 bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200">
                        Status Refund: <strong>Tidak Ada Refund / 0% (Sesuai Ketentuan Waktu Pembatalan)</strong>.
                    </div>
                @endif
            </div>
        @endif
    </div>


    {{-- Kartu Rincian Invoice Pemesanan --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        {{-- Header Invoice --}}
        <div class="bg-slate-900 text-white p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-800">
            <div class="space-y-1">
                <div class="text-xs font-bold uppercase tracking-wider text-emerald-400">Rincian Reservasi #TA-{{ str_pad($booking->id_reservasi, 4, '0', STR_PAD_LEFT) }}</div>
                <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight">Invoice Pemesanan</h2>
            </div>
            <div class="text-left sm:text-right text-xs text-slate-400 leading-relaxed font-semibold">
                <div>Tanggal Transaksi:</div>
                <div class="text-white">{{ $booking->created_at->format('d M Y, H:i') }} WIB</div>
            </div>
        </div>

        <div class="p-8 space-y-8">
            {{-- Baris 1: Informasi Tamu & Unit Akomodasi --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Data Pelanggan / Tamu --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pelanggan / Tamu</h4>
                    <div class="space-y-1 font-semibold text-sm">
                        <div class="text-slate-900 font-extrabold text-base flex items-center gap-2">
                            <span>{{ $booking->tamu->nama }}</span>
                            @if(($booking->tamu->kewarganegaraan ?? 'WNI') === 'WNA')
                                <span class="px-2 py-0.5 bg-purple-50 text-purple-700 text-[10px] font-extrabold rounded-full border border-purple-200">🌐 WNA</span>
                            @else
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-extrabold rounded-full border border-emerald-200">🇮🇩 WNI</span>
                            @endif
                        </div>
                        <div class="text-slate-600 flex items-center gap-1.5">
                            <span class="text-slate-400">Email:</span> {{ $booking->tamu->email ?? '-' }}
                        </div>
                        @if(($booking->tamu->kewarganegaraan ?? 'WNI') === 'WNA')
                            <div class="text-slate-600 flex items-center gap-1.5">
                                <span class="text-slate-400">No. Paspor:</span> <strong class="font-mono text-slate-800">{{ $booking->tamu->no_paspor ?? '-' }}</strong>
                            </div>
                            <div class="text-slate-600 flex items-center gap-1.5">
                                <span class="text-slate-400">Negara Asal:</span> <span class="text-slate-800 font-semibold">{{ $booking->tamu->negara ?? 'Luar Negeri' }}</span>
                            </div>
                        @else
                            @if(!empty($booking->tamu->no_ktp))
                                <div class="text-slate-600 flex items-center gap-1.5">
                                    <span class="text-slate-400">No. KTP / NIK:</span> <strong class="font-mono text-slate-800">{{ $booking->tamu->no_ktp }}</strong>
                                </div>
                            @endif
                        @endif
                        <div class="text-slate-600 flex items-start gap-1.5">
                            <span class="text-slate-400 flex-shrink-0">Alamat:</span>
                            <span class="text-slate-800 font-semibold">{{ $booking->tamu->alamat_lengkap_formatted }}</span>
                        </div>
                        <div class="text-slate-600 flex items-center gap-1.5">
                            <span class="text-slate-400">No HP:</span> {{ $booking->tamu->no_hp }}
                        </div>
                    </div>
                </div>
                
                {{-- Data Unit Akomodasi Dipesan --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Akomodasi Dipesan</h4>
                    <div class="space-y-1 font-semibold text-sm">
                        <div class="text-slate-900 font-extrabold text-base">{{ $booking->penginapan->nama_unit }}</div>
                        <div class="text-slate-600">{{ $booking->penginapan->alamat }}</div>
                        <div class="text-slate-600 uppercase text-xs px-2.5 py-0.5 rounded bg-slate-100 inline-block font-bold mt-1 text-slate-700">Tipe: {{ $booking->penginapan->tipe_unit }}</div>
                    </div>
                </div>
            </div>

            {{-- Baris 2: Grid Tanggal Check-in, Check-out, dan Durasi --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 bg-slate-50 p-6 rounded-2xl border border-slate-200">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tanggal Check-In</span>
                    <strong class="text-sm text-slate-900 block mt-0.5">{{ date('d M Y', strtotime($booking->tanggal_checkin)) }}</strong>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tanggal Check-Out</span>
                    <strong class="text-sm text-slate-900 block mt-0.5">{{ date('d M Y', strtotime($booking->tanggal_checkout)) }}</strong>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Durasi Menginap</span>
                    <strong class="text-sm text-slate-900 block mt-0.5">{{ $nights }} Malam</strong>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Jumlah Unit Kamar</span>
                    <strong class="text-sm text-slate-900 block mt-0.5">{{ $booking->jumlah_kamar }} Kamar</strong>
                </div>
            </div>

            {{-- Baris 3: Rincian Pembayaran --}}
            <div class="space-y-4">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rincian Harga & Skema Pembayaran</h4>
                <div class="border border-slate-200 rounded-2xl overflow-hidden divide-y divide-slate-100 text-sm">
                    <div class="p-4 flex justify-between font-semibold text-slate-750">
                        <span>Harga Kamar ({{ $booking->jumlah_kamar }} Unit x {{ $nights }} Malam)</span>
                        <span>Rp {{ number_format($booking->penginapan->harga * $booking->jumlah_kamar * $nights, 0, ',', '.') }}</span>
                    </div>

                    @if($booking->pembayaran && $booking->pembayaran->jenis_pembayaran === 'dp')
                        <div class="p-4 flex justify-between font-bold text-amber-900 bg-amber-50/50">
                            <span>Nominal DP (50%) - Terbayar</span>
                            <span>Rp {{ number_format($booking->pembayaran->nominal_dp, 0, ',', '.') }}</span>
                        </div>
                        <div class="p-4 flex justify-between font-bold text-slate-800 bg-slate-50/70">
                            <span>Sisa Pembayaran (50%) - Wajib Dilunasi saat Check-in</span>
                            <span class="{{ $booking->pembayaran->status_pelunasan === 'lunas' ? 'text-emerald-600' : 'text-amber-700' }}">
                                Rp {{ number_format($booking->pembayaran->sisa_pembayaran, 0, ',', '.') }}
                                ({{ $booking->pembayaran->status_pelunasan === 'lunas' ? 'LUNAS' : 'BELUM LUNAS' }})
                            </span>
                        </div>
                    @endif

                    <div class="p-4 flex justify-between font-bold text-slate-950 text-base bg-slate-50/50">
                        <span>Total Keseluruhan Pembayaran</span>
                        <span class="text-emerald-600 text-lg">Rp {{ number_format($booking->pembayaran->total_pembayaran, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Baris 4: Status Pembayaran & Gateway --}}
            <div class="pt-6 border-t border-slate-150 space-y-4">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Detail Pembayaran & Gateway</h4>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-sm font-semibold">
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                        <span class="text-[10px] text-slate-400 uppercase font-bold block">Skema Pembayaran</span>
                        <span class="text-slate-800 block mt-0.5 font-extrabold">
                            {{ $booking->pembayaran->jenis_pembayaran === 'dp' ? 'Uang Muka / DP (50%)' : 'Bayar Full (100%)' }}
                        </span>
                    </div>
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                        <span class="text-[10px] text-slate-400 uppercase font-bold block">Metode Pembayaran</span>
                        <span class="text-slate-800 block mt-0.5">{{ $booking->pembayaran->metode_pembayaran ?? '-' }}</span>
                    </div>
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                        <span class="text-[10px] text-slate-400 uppercase font-bold block">Tanggal Bayar</span>
                        <span class="text-slate-800 block mt-0.5">
                            @if($booking->pembayaran->tanggal_pembayaran)
                                {{ date('d M Y, H:i', strtotime($booking->pembayaran->tanggal_pembayaran)) }} WIB
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                        <span class="text-[10px] text-slate-400 uppercase font-bold block">Status Pelunasan</span>
                        @if($booking->pembayaran && $booking->pembayaran->jenis_pembayaran === 'dp' && $booking->pembayaran->status_pelunasan === 'belum_lunas')
                            <span class="text-amber-700 block mt-0.5 font-extrabold uppercase">⏳ Sisa DP Belum Lunas</span>
                        @elseif(($booking->pembayaran && $booking->pembayaran->status_pembayaran === 'lunas') || in_array($booking->status, ['confirmed', 'completed']))
                            <span class="text-emerald-600 block mt-0.5 font-extrabold uppercase">✅ Lunas 100%</span>
                        @elseif($booking->status === 'cancelled')
                            <span class="text-red-600 block mt-0.5 font-bold uppercase">Batal</span>
                        @else
                            <span class="text-amber-600 block mt-0.5 font-bold uppercase">Menunggu Pembayaran</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Baris 5: Fitur Notifikasi Otomatis & Manual (Email & WhatsApp) --}}
            <div class="pt-6 border-t border-slate-150 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kirim Notifikasi Otomatis (Email & WhatsApp)</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Kirimkan rincian status reservasi, konfirmasi pembayaran, atau pengingat jadwal check-in langsung ke Email & WhatsApp tamu.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <form action="{{ route('admin.bookings.notify', $booking->id_reservasi) }}" method="POST" class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl space-y-3">
                        @csrf
                        <input type="hidden" name="type" value="status">
                        <div class="flex items-center gap-2">
                            <span class="p-1.5 bg-emerald-100 text-emerald-700 rounded-lg font-bold text-xs">✉️ + 📱</span>
                            <span class="font-extrabold text-xs text-emerald-900">Notifikasi Update Status / Pembayaran</span>
                        </div>
                        <input type="text" name="custom_message" placeholder="Catatan tambahan untuk tamu (opsional)..." class="w-full px-3 py-2 text-xs font-semibold rounded-xl border border-emerald-300 focus:outline-none focus:border-emerald-600 bg-white">
                        <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all active:scale-95">
                            📲 Kirim Status (Email & WhatsApp)
                        </button>
                    </form>

                    <form action="{{ route('admin.bookings.notify', $booking->id_reservasi) }}" method="POST" class="p-4 bg-blue-50 border border-blue-200 rounded-2xl space-y-3">
                        @csrf
                        <input type="hidden" name="type" value="checkin_reminder">
                        <div class="flex items-center gap-2">
                            <span class="p-1.5 bg-blue-100 text-blue-700 rounded-lg font-bold text-xs">⏰ + 📱</span>
                            <span class="font-extrabold text-xs text-blue-900">Pengingat Jadwal Check-In (H-1)</span>
                        </div>
                        <input type="text" name="custom_message" placeholder="Pesan pengingat khusus (opsional)..." class="w-full px-3 py-2 text-xs font-semibold rounded-xl border border-blue-300 focus:outline-none focus:border-blue-600 bg-white">
                        <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all active:scale-95">
                            ⏰ Kirim Pengingat Check-In
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function checkoutModal(config) {
        return {
            show: false,
            checkoutDate: config.checkoutDate,
            biayaPermalam: config.biayaPermalam || 0,
            idReservasi: config.idReservasi,
            nomorReservasi: config.nomorReservasi || '',
            formAction: "{{ route('admin.bookings.checkout', ':id') }}".replace(':id', config.idReservasi),
            isLate: false,
            addLateCharge: false,
            currentTimeStr: '',

            openModal() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                this.currentTimeStr = `${hours}:${minutes} WIB`;

                // Batas checkout jam 12:00 WIB pada tanggal checkout
                const deadline = new Date(this.checkoutDate + 'T12:00:00');
                this.isLate = now > deadline;
                this.addLateCharge = this.isLate;

                this.show = true;
            },

            formatRupiah(amount) {
                return 'Rp ' + Number(amount).toLocaleString('id-ID');
            }
        };
    }
</script>
@endsection
