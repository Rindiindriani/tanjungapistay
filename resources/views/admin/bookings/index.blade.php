{{-- Menggunakan layout utama admin --}}
@extends('layouts.admin')

{{-- Menentukan judul halaman dan judul header navbar --}}
@section('title', 'Kelola Pemesanan - Tanjung Api')
@section('header_title', 'Kelola Pemesanan')

@section('content')
<div class="space-y-6">
    {{-- Blok Header Halaman --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <h3 class="font-extrabold text-xl text-slate-900 tracking-tight">Semua Transaksi Pemesanan</h3>
        <p class="text-sm text-slate-500 mt-1">Pantau dan kelola seluruh reservasi unit villa dan tenda tamu.</p>
    </div>

    {{-- Kartu Tabel Daftar Reservasi --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                {{-- Header Tabel --}}
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-150 text-slate-500 font-bold text-xs uppercase tracking-wider">
                        <th class="p-6">ID Booking</th>
                        <th class="p-6">Tamu / Pelanggan</th>
                        <th class="p-6">Unit</th>
                        <th class="p-6">Check-In / Durasi</th>
                        <th class="p-6">Total Harga</th>
                        <th class="p-6">Status</th>
                        <th class="p-6 text-right">Aksi</th>
                    </tr>
                </thead>

                {{-- Isi Tabel Reservasi --}}
                <tbody class="divide-y divide-slate-100 text-sm font-semibold text-slate-700">
                    {{-- Loop data reservasi, jika kosong tampilkan baris default --}}
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            {{-- ID Booking dengan format padded ID --}}
                            <td class="p-6 text-slate-950 font-bold">#TA-{{ str_pad($booking->id_reservasi, 4, '0', STR_PAD_LEFT) }}</td>
                            
                            {{-- Informasi Tamu --}}
                            <td class="p-6">
                                <div class="text-slate-900 font-bold leading-tight">{{ $booking->tamu->nama }}</div>
                                <div class="text-slate-400 text-xs font-medium mt-0.5">{{ $booking->tamu->email }}</div>
                            </td>

                            {{-- Informasi Unit Penginapan --}}
                            <td class="p-6">
                                <div class="text-slate-900 font-bold leading-tight">{{ $booking->penginapan->nama_unit }}</div>
                                <div class="text-slate-400 text-xs font-medium uppercase mt-0.5">{{ $booking->jumlah_kamar }} Kamar ({{ $booking->penginapan->tipe_unit }})</div>
                            </td>

                            {{-- Tanggal Check-in & Durasi Menginap --}}
                            <td class="p-6">
                                @php
                                    $durasi = \Carbon\Carbon::parse($booking->tanggal_checkin)->diffInDays(\Carbon\Carbon::parse($booking->tanggal_checkout));
                                @endphp
                                <div class="text-slate-900 font-bold">{{ date('d M Y', strtotime($booking->tanggal_checkin)) }}</div>
                                <div class="text-slate-400 text-xs font-medium mt-0.5">{{ $durasi }} Malam</div>
                            </td>

                            {{-- Total Pembayaran --}}
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

                            {{-- Badge Status Reservasi --}}
                            <td class="p-6">
                                @if($booking->status === 'pending')
                                    {{-- Status Menunggu Pembayaran --}}
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Menunggu Pembayaran
                                    </span>
                                @elseif($booking->status === 'confirmed')
                                    {{-- Status Lunas / Terkonfirmasi --}}
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Lunas / Terkonfirmasi
                                    </span>
                                @elseif($booking->status === 'postponed')
                                    {{-- Status Ditunda --}}
                                    <div class="space-y-1">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-300">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Ditunda (Maks. 1 Bulan)
                                        </span>
                                        <div class="text-[11px] text-amber-700 font-semibold">
                                            Tenggat: {{ $booking->batas_tunda_hingga ? $booking->batas_tunda_hingga->format('d M Y') : '-' }}
                                        </div>
                                    </div>
                                @elseif($booking->status === 'cancelled')
                                    {{-- Status Dibatalkan --}}
                                    <div class="space-y-1">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            Dibatalkan
                                        </span>
                                        @if($booking->status_refund === 'pending_refund')
                                            <div class="text-[11px] font-extrabold text-amber-700 animate-pulse">
                                                ⚠️ Perlu Transfer Refund ({{ $booking->persentase_refund }}%)
                                            </div>
                                        @elseif($booking->status_refund === 'refunded')
                                            <div class="text-[11px] font-bold text-emerald-700">
                                                ✅ Refund {{ $booking->persentase_refund }}% Transfer Selesai
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    {{-- Status Selesai / Completed --}}
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                        Selesai
                                    </span>
                                @endif
                            </td>

                            {{-- Tombol Aksi: Checkout & Detail --}}
                            <td class="p-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($booking->status === 'confirmed')
                                        {{-- Tombol Checkout Cepat Langsung dari Tabel --}}
                                        <form action="{{ route('admin.bookings.checkout', $booking->id_reservasi) }}" method="POST"
                                              onsubmit="return confirm('Konfirmasi checkout tamu {{ $booking->tamu->nama }} (#TA-{{ str_pad($booking->id_reservasi, 4, '0', STR_PAD_LEFT) }})? Status reservasi akan diselesaikan.');">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-extrabold shadow-sm transition-all active:scale-95">
                                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                                <span>Checkout</span>
                                            </button>
                                        </form>
                                    @elseif($booking->status === 'completed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-100 text-slate-500 rounded-xl text-[11px] font-bold border border-slate-200">
                                            ✅ Selesai
                                        </span>
                                    @endif

                                    <a href="{{ route('admin.bookings.show', $booking->id_reservasi) }}" class="inline-flex items-center justify-center px-3.5 py-2 bg-slate-100 hover:bg-emerald-600 border border-slate-200 hover:border-emerald-600 rounded-xl text-xs font-bold text-slate-700 hover:text-white transition-all">
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- Tampilan saat tidak ada data reservasi --}}
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">Belum ada transaksi pemesanan masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
