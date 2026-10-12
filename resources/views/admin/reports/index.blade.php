@extends('layouts.admin')

@section('title', 'Laporan Keuangan - Tanjung Api')
@section('header_title', 'Laporan Keuangan')

@section('content')
<div class="space-y-6">
    <!-- Header Block -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="space-y-1">
            <h3 class="font-extrabold text-xl text-slate-900 tracking-tight">Laporan Keuangan & Pendapatan</h3>
            <p class="text-sm text-slate-500">Melihat rekapitulasi data pendapatan sewa unit Pantai Tanjung Api.</p>
        </div>
        
        <!-- Cetak Action -->
        @if(!$reports->isEmpty())
            <a href="{{ route('admin.reports.print', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" 
                class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all shadow-md shadow-emerald-600/10 hover:shadow-emerald-600/25 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak Laporan (PDF)
            </a>
        @endif
    </div>

    <!-- Summary Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Revenue Card -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="space-y-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Pendapatan Terfilter</span>
                <span class="text-3xl font-extrabold text-emerald-600 block">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Visitor Count Card -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="space-y-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Jumlah Pengunjung</span>
                <span class="text-3xl font-extrabold text-blue-600 block">{{ number_format($totalPengunjung, 0, ',', '.') }} Orang</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Bookings Count Card -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="space-y-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Jumlah Transaksi</span>
                <span class="text-3xl font-extrabold text-slate-900 block">{{ $reports->count() }} Transaksi</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2-2"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Date Filter Card -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
            <!-- Start Date -->
            <div class="space-y-1.5 w-full">
                <label for="start_date" class="text-xs font-bold text-slate-500 uppercase">Tanggal Awal Check-In</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm font-semibold text-slate-800 bg-white">
            </div>

            <!-- End Date -->
            <div class="space-y-1.5 w-full">
                <label for="end_date" class="text-xs font-bold text-slate-500 uppercase">Tanggal Akhir Check-In</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm font-semibold text-slate-800 bg-white">
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 w-full md:w-auto">
                <a href="{{ route('admin.reports.index') }}" class="w-full md:w-auto inline-flex items-center justify-center px-5 py-2.5 border border-slate-200 hover:border-slate-350 rounded-xl text-slate-600 font-bold text-sm transition-all text-center">
                    Reset
                </a>
                <button type="submit" class="w-full md:w-auto px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-xl transition-all shadow-md active:scale-95 flex-shrink-0">
                    Filter Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- Reports Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-extrabold text-sm text-slate-900 uppercase tracking-wider">Hasil Rekapitulasi</h3>
            @if($startDate && $endDate)
                <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full border border-slate-150">
                    Periode: {{ date('d M Y', strtotime($startDate)) }} s.d {{ date('d M Y', strtotime($endDate)) }}
                </span>
            @else
                <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full border border-slate-150">Semua Periode</span>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-150 text-slate-500 font-bold text-xs uppercase tracking-wider">
                        <th class="p-6">ID Booking</th>
                        <th class="p-6">Tamu / Pelanggan</th>
                        <th class="p-6">Akomodasi</th>
                        <th class="p-6 text-center">Pengunjung</th>
                        <th class="p-6">Tanggal Check-In</th>
                        <th class="p-6">Tanggal Check-Out</th>
                        <th class="p-6">Jumlah Bayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-semibold text-slate-700">
                    @forelse($reports as $report)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-6 text-slate-950 font-bold">#TA-{{ str_pad($report->id_reservasi, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="p-6">
                                <div class="text-slate-900 font-bold leading-tight">{{ $report->tamu->nama }}</div>
                                <div class="text-slate-400 text-xs font-medium mt-0.5">{{ $report->tamu->email }}</div>
                            </td>
                            <td class="p-6">
                                <div class="text-slate-900 font-bold leading-tight">{{ $report->penginapan->nama_unit }}</div>
                                <div class="text-slate-400 text-xs font-medium uppercase mt-0.5">{{ $report->jumlah_kamar }} Kamar ({{ $report->penginapan->tipe_unit }})</div>
                            </td>
                            <td class="p-6 text-center">
                                <span class="px-3 py-1 bg-blue-50 text-blue-700 font-extrabold text-xs rounded-full border border-blue-150">
                                    {{ $report->jumlah_tamu }} Orang
                                </span>
                            </td>
                            <td class="p-6 text-slate-600 font-medium">{{ date('d M Y', strtotime($report->tanggal_checkin)) }}</td>
                            <td class="p-6 text-slate-600 font-medium">{{ date('d M Y', strtotime($report->tanggal_checkout)) }}</td>
                            <td class="p-6 text-emerald-600 font-extrabold text-base">
                                Rp {{ number_format($report->pembayaran->total_pembayaran, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">Tidak ada transaksi terbayar lunas yang ditemukan dalam kriteria filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if(!$reports->isEmpty())
                    <tfoot class="bg-slate-100/80 border-t-2 border-slate-200 font-extrabold text-slate-900 text-sm">
                        <tr>
                            <td colspan="3" class="p-6 text-right uppercase tracking-wider text-xs text-slate-500">Total Pengunjung Terfilter:</td>
                            <td class="p-6 text-center text-blue-600 font-black text-base">{{ number_format($totalPengunjung, 0, ',', '.') }} Orang</td>
                            <td colspan="2" class="p-6 text-right uppercase tracking-wider text-xs text-slate-500">Total Pendapatan Terfilter:</td>
                            <td class="p-6 text-emerald-600 font-black text-base">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

    </div>
</div>
@endsection
