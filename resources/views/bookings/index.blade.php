{{-- Menggunakan layout utama aplikasi tamu --}}
@extends('layouts.app')

{{-- Menentukan judul halaman riwayat pemesanan --}}
@section('title', 'Riwayat Reservasi - Tanjung Api')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Header Halaman --}}
    <div class="mb-10 space-y-2">
        <span class="text-emerald-600 font-bold text-sm uppercase tracking-widest">HALAMAN TAMU</span>
        <h1 class="text-3xl font-extrabold text-slate-950 tracking-tight">Riwayat Pemesanan Anda</h1>
        <p class="text-slate-500 text-sm">Lihat status reservasi, rincian pembayaran, dan tiket masuk unit Anda.</p>
    </div>

    {{-- Memeriksa apakah tamu belum memiliki riwayat reservasi --}}
    @if($bookings->isEmpty())
        {{-- Tampilan Kosong (Empty State) --}}
        <div class="text-center py-20 bg-white rounded-3xl border border-slate-100 shadow-sm max-w-lg mx-auto space-y-6">
            <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300 mx-auto">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div class="space-y-2">
                <h3 class="font-extrabold text-lg text-slate-800">Belum Ada Pemesanan</h3>
                <p class="text-slate-500 text-sm max-w-xs mx-auto">Anda belum pernah melakukan reservasi unit di Pantai Tanjung Api.</p>
            </div>
            <a href="{{ route('units.index') }}" class="inline-flex items-center justify-center px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all shadow-md shadow-emerald-600/10">
                Jelajahi Unit
            </a>
        </div>
    @else
        {{-- Tabel Daftar Reservasi Tamu --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-900/5 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    {{-- Header Tabel --}}
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold text-xs uppercase tracking-wider">
                            <th class="p-6">ID Booking</th>
                            <th class="p-6">Unit</th>
                            <th class="p-6">Check-In / Durasi</th>
                            <th class="p-6">Jumlah</th>
                            <th class="p-6">Total Harga</th>
                            <th class="p-6">Status</th>
                            <th class="p-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    
                    {{-- Baris Data Reservasi --}}
                    <tbody class="divide-y divide-slate-100 text-sm font-semibold text-slate-700">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-slate-50/50 transition-colors" x-data="{ cancelModal: false, postponeModal: false, rescheduleModal: false, resumeModal: false }">
                                {{-- ID Reservasi --}}
                                <td class="p-6 text-slate-950 font-bold">#TA-{{ str_pad($booking->id_reservasi, 4, '0', STR_PAD_LEFT) }}</td>
                                
                                {{-- Informasi Unit Penginapan --}}
                                <td class="p-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0">
                                            <img src="{{ asset('images/' . $booking->penginapan->foto) }}" class="w-full h-full object-cover" alt="{{ $booking->penginapan->nama_unit }}">
                                        </div>
                                        <div>
                                            <div class="text-slate-900 font-bold leading-tight">{{ $booking->penginapan->nama_unit }}</div>
                                            <div class="text-slate-400 text-xs font-medium uppercase mt-0.5">{{ $booking->penginapan->tipe_unit }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Tanggal Check-in & Durasi Menginap --}}
                                <td class="p-6">
                                    @php
                                        $durasi = \Carbon\Carbon::parse($booking->tanggal_checkin)->diffInDays(\Carbon\Carbon::parse($booking->tanggal_checkout));
                                    @endphp
                                    <div class="text-slate-900 font-bold">{{ date('d M Y', strtotime($booking->tanggal_checkin)) }}</div>
                                    <div class="text-slate-400 text-xs font-medium mt-0.5">{{ $durasi }} Malam</div>
                                </td>

                                {{-- Jumlah Unit Dipesan --}}
                                <td class="p-6 text-slate-500 font-medium">
                                    {{ $booking->jumlah_kamar }} Kamar
                                </td>

                                {{-- Total Biaya Pembayaran --}}
                                <td class="p-6">
                                    <div class="space-y-1">
                                        <span class="text-emerald-600 font-extrabold text-base block">Rp {{ number_format($booking->pembayaran->total_pembayaran ?? 0, 0, ',', '.') }}</span>
                                        @if($booking->pembayaran && $booking->pembayaran->jenis_pembayaran === 'dp')
                                            <div class="space-y-0.5 text-xs">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-amber-50 text-amber-800 font-bold border border-amber-200 text-[11px]">
                                                    DP (50%): Rp {{ number_format($booking->pembayaran->nominal_dp, 0, ',', '.') }}
                                                </span>
                                                <div class="text-slate-500 font-medium text-[11px]">
                                                    Sisa: <strong class="text-amber-700 font-bold">Rp {{ number_format($booking->pembayaran->sisa_pembayaran, 0, ',', '.') }}</strong>
                                                </div>
                                            </div>
                                        @else
                                            <span class="inline-block text-[11px] font-bold text-slate-400 uppercase">Bayar Full (100%)</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Status Reservasi & Hitung Mundur --}}
                                <td class="p-6">
                                    @if($booking->status === 'pending')
                                        {{-- Hitung sisa detik menuju batas waktu pembayaran --}}
                                        @php
                                            $seconds = $booking->secondsUntilExpiry();
                                            $isUrgent = $seconds < 3600; // < 1 jam
                                        @endphp
                                        <div class="space-y-1.5">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $isUrgent ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $isUrgent ? 'bg-red-500 animate-pulse' : 'bg-amber-500' }}"></span>
                                                @if(!$booking->pembayaran)
                                                    Belum Bayar
                                                @elseif($booking->pembayaran->jenis_pembayaran === 'dp' && $booking->pembayaran->status_pelunasan === 'belum_lunas')
                                                    DP (50%) - Sisa Rp {{ number_format($booking->pembayaran->sisa_pembayaran, 0, ',', '.') }}
                                                @else
                                                    Menunggu Konfirmasi
                                                @endif
                                            </span>
                                            @if($seconds > 0)
                                                <div class="text-xs {{ $isUrgent ? 'text-red-600 font-bold' : 'text-slate-400 font-medium' }} flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Batas: <span class="booking-countdown font-mono" data-seconds="{{ $seconds }}">--:--:--</span>
                                                </div>
                                            @else
                                                <div class="text-xs text-red-600 font-bold">⚠ Waktu habis</div>
                                            @endif
                                        </div>
                                    @elseif($booking->status === 'confirmed')
                                        {{-- Status Terkonfirmasi / Lunas --}}
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif / Terkonfirmasi
                                        </span>
                                    @elseif($booking->status === 'postponed')
                                        {{-- Status Ditunda --}}
                                        <div class="space-y-1">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-300">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                Ditunda (Maks. 1 Bulan)
                                            </span>
                                            <div class="text-[11px] text-amber-700 font-semibold">
                                                Reschedule s/d {{ $booking->batas_tunda_hingga ? $booking->batas_tunda_hingga->format('d M Y') : '-' }}
                                            </div>
                                        </div>
                                    @elseif($booking->status === 'cancelled')
                                         {{-- Status Dibatalkan --}}
                                         <div class="space-y-1">
                                             @if($booking->status_refund === 'pending_refund')
                                                 {{-- Menunggu konfirmasi dari admin, tamu masih bisa lanjutkan --}}
                                                 <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-700 border border-orange-200">
                                                     <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                                                     Menunggu Konfirmasi
                                                 </span>
                                                 <div class="text-[11px] text-orange-700 font-semibold">
                                                     ⏳ Pengajuan pembatalan diproses admin
                                                 </div>
                                             @else
                                                 <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                                     <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                     Dibatalkan
                                                 </span>
                                                 @if($booking->persentase_refund > 0)
                                                     <div class="text-[11px] font-bold text-amber-700">
                                                         @if($booking->status_refund === 'refunded')
                                                             ✅ Refund {{ $booking->persentase_refund }}% Cair
                                                         @else
                                                             ⏳ Refund {{ $booking->persentase_refund }}% Proses
                                                         @endif
                                                     </div>
                                                 @endif
                                             @endif
                                         </div>
                                    @else
                                        {{-- Status Selesai --}}
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                            Selesai
                                        </span>
                                    @endif
                                </td>

                                {{-- Tombol Rincian Pesanan & Pembatalan / Penundaan / Reschedule --}}
                                <td class="p-6 text-right">
                                    <div class="flex items-center justify-end gap-2 flex-wrap">
                                        <a href="{{ route('bookings.show', $booking->id_reservasi) }}" class="inline-flex items-center justify-center px-3.5 py-2 border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/10 rounded-xl text-xs font-bold text-slate-700 hover:text-emerald-600 transition-all">
                                            Rincian Pesanan
                                        </a>

                                        @if($booking->status === 'pending')
                                            <a href="{{ route('payment.sandbox', $booking->id_reservasi) }}" class="inline-flex items-center justify-center px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                                                Bayar
                                            </a>
                                            <button type="button" @click="cancelModal = true" class="inline-flex items-center justify-center px-3.5 py-2 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-xl text-xs font-bold transition-all shadow-sm">
                                                Batalkan
                                            </button>
                                        @elseif($booking->status === 'confirmed')
                                            <button type="button" @click="cancelModal = true" class="inline-flex items-center justify-center px-3.5 py-2 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-xl text-xs font-bold transition-all shadow-sm">
                                                ❌ Batalkan
                                            </button>
                                        @elseif($booking->status === 'cancelled')
                                            {{-- Tombol Lanjutkan Pemesanan Kembali --}}
                                            <button type="button" @click="resumeModal = true" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                                                ✅ Lanjutkan Reservasi
                                            </button>
                                        @endif
                                    </div>

                                    {{-- Modals Pembatalan, Penundaan & Reschedule --}}
                                    @if($booking->status === 'pending')
                                        <!-- Modal Pembatalan Pending -->
                                        <div x-show="cancelModal" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm text-left" x-transition>
                                            <div @click.away="cancelModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 space-y-5 shadow-2xl border border-slate-100">
                                                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                                    <h3 class="font-extrabold text-lg text-red-600">Konfirmasi Pembatalan Pesanan</h3>
                                                    <button type="button" @click="cancelModal = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                                                </div>
                                                <div class="p-4 bg-red-50 border border-red-200 rounded-2xl text-xs text-red-900 space-y-2">
                                                    <p class="leading-relaxed font-medium">
                                                        Apakah Anda yakin ingin membatalkan pesanan #TA-{{ str_pad($booking->id_reservasi, 4, '0', STR_PAD_LEFT) }}? Pesanan yang dibatalkan tidak dapat dikembalikan.
                                                    </p>
                                                </div>
                                                <form action="{{ route('bookings.cancel', $booking->id_reservasi) }}" method="POST" class="space-y-4">
                                                    @csrf
                                                    <div class="pt-2 flex items-center justify-end gap-3 border-t border-slate-100">
                                                        <button type="button" @click="cancelModal = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800">
                                                            Kembali
                                                        </button>
                                                        <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-all">
                                                            Ya, Batalkan Pesanan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @elseif($booking->status === 'confirmed')
                                        <!-- Modal Popup Pembatalan Direct (Uang Hangus) -->
                                        <div x-show="cancelModal" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm text-left" x-transition>
                                            <div @click.away="cancelModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 space-y-5 shadow-2xl border border-slate-100">
                                                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                                    <h3 class="font-extrabold text-lg text-red-600">Konfirmasi Pembatalan Reservasi</h3>
                                                    <button type="button" @click="cancelModal = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
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
                                                        <label for="alasan_pembatalan_{{ $booking->id_reservasi }}" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Alasan Pembatalan <span class="text-slate-400 text-[10px]">(Opsional)</span></label>
                                                        <textarea name="alasan_pembatalan" id="alasan_pembatalan_{{ $booking->id_reservasi }}" rows="3" placeholder="Tuliskan alasan pembatalan Anda..."
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

                                    {{-- Modal Konfirmasi Lanjutkan Reservasi --}}
                                    @if($booking->status === 'cancelled' && $booking->status_refund === 'pending_refund')
                                        <div x-show="resumeModal" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm text-left" x-transition>
                                            <div @click.away="resumeModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 space-y-5 shadow-2xl border border-slate-100">
                                                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                                    <h3 class="font-extrabold text-lg text-emerald-700">Lanjutkan Reservasi?</h3>
                                                    <button type="button" @click="resumeModal = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                                                </div>
                                                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs text-emerald-900 space-y-2">
                                                    <div class="font-extrabold flex items-center gap-1.5 text-emerald-900">
                                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        <span>Batalkan Pengajuan Pembatalan</span>
                                                    </div>
                                                    <p class="leading-relaxed">
                                                        Reservasi <strong>#TA-{{ str_pad($booking->id_reservasi, 4, '0', STR_PAD_LEFT) }}</strong> akan dikembalikan ke status <strong>Aktif / Terkonfirmasi</strong>. Pengajuan pembatalan Anda akan dianggap batal.
                                                    </p>
                                                </div>
                                                <form action="{{ route('bookings.resume', $booking->id_reservasi) }}" method="POST">
                                                    @csrf
                                                    <div class="pt-2 flex items-center justify-end gap-3 border-t border-slate-100">
                                                        <button type="button" @click="resumeModal = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800">
                                                            Kembali
                                                        </button>
                                                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-all">
                                                            ✅ Ya, Lanjutkan Reservasi
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
// ===== Countdown Timer untuk Pembayaran =====
document.addEventListener('DOMContentLoaded', function () {
    const countdownEls = document.querySelectorAll('.booking-countdown');

    countdownEls.forEach(function (el) {
        let seconds = parseInt(el.dataset.seconds, 10);

        function formatTime(s) {
            if (s <= 0) return '00:00:00';
            const h = Math.floor(s / 3600);
            const m = Math.floor((s % 3600) / 60);
            const sec = s % 60;
            return [h, m, sec].map(v => String(v).padStart(2, '0')).join(':');
        }

        el.textContent = formatTime(seconds);

        const interval = setInterval(function () {
            seconds--;
            if (seconds <= 0) {
                el.textContent = '00:00:00';
                clearInterval(interval);
                // Refresh halaman otomatis agar status ter-update
                setTimeout(() => location.reload(), 2000);
            } else {
                el.textContent = formatTime(seconds);
            }
        }, 1000);
    });
});
</script>
@endsection
