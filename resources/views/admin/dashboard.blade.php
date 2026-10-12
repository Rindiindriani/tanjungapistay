@extends('layouts.admin')

@section('title', 'Dashboard Pengelola - Tanjung Api')
@section('header_title', 'Dashboard Analisis')

@section('content')
<!-- Statistic Counters -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat 1: Total Reservasi -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div class="space-y-2">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Reservasi</span>
            <span class="text-3xl font-extrabold text-slate-900 block">{{ $totalReservasi }}</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2-2 2 0 00-2-2"></path>
            </svg>
        </div>
    </div>
    
    <!-- Stat 2: Menunggu Pembayaran -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div class="space-y-2">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Menunggu Pembayaran</span>
            <span class="text-3xl font-extrabold text-slate-900 block">{{ $pendingReservasi }}</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Stat 3: Dibatalkan / Kadaluarsa -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div class="space-y-2">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Dibatalkan / Batal</span>
            <span class="text-3xl font-extrabold text-slate-900 block">{{ $cancelledReservasi }}</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
    </div>

    <!-- Stat 4: Total Pendapatan -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div class="space-y-2">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Pendapatan</span>
            <span class="text-2xl font-extrabold text-emerald-600 block">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Chart Column (2/3 width) -->
    <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
        <h3 class="font-extrabold text-lg text-slate-900 tracking-tight">Tren Pemesanan (7 Hari Terakhir)</h3>
        <div class="h-72">
            <canvas id="bookingChart"></canvas>
        </div>
    </div>

    <!-- Stats summary / Status Unit Tanjung Api (1/3 width) -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between" id="unitStatusContainer">
        <div class="space-y-4">
            <!-- Card Header -->
            <div class="flex items-start justify-between gap-2">
                <div>
                    <h3 class="font-extrabold text-lg text-slate-900 tracking-tight flex items-center gap-2">
                        <span>Status Unit Tanjung Api</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Cek ketersediaan kamar & tenda real-time berdasarkan tanggal.
                    </p>
                </div>
                <!-- Indikator Legenda -->
                <div class="flex flex-col items-end gap-1 text-[11px] font-bold">
                    <span class="inline-flex items-center gap-1 text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Hijau: Ada Stok
                    </span>
                    <span class="inline-flex items-center gap-1 text-red-700 bg-red-50 px-2 py-0.5 rounded-md border border-red-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Merah: Penuh
                    </span>
                </div>
            </div>

            <!-- Form Filter Tanggal -->
            <form action="{{ route('admin.dashboard') }}" method="GET" id="filterUnitForm" class="bg-slate-50 p-3 rounded-2xl border border-slate-200 space-y-2.5">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label for="filter_checkin" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Check-In</label>
                        <input type="date" name="filter_checkin" id="filter_checkin" value="{{ $filterCheckin }}"
                               class="w-full text-xs font-semibold px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                    </div>
                    <div>
                        <label for="filter_checkout" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Check-Out</label>
                        <input type="date" name="filter_checkout" id="filter_checkout" value="{{ $filterCheckout }}"
                               class="w-full text-xs font-semibold px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1 text-xs">
                    <div class="text-[11px] text-slate-500 font-medium">
                        Periode: <strong class="text-slate-700 font-bold" id="periodeLabel">{{ date('d M Y', strtotime($filterCheckin)) }} - {{ date('d M Y', strtotime($filterCheckout)) }}</strong>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <button type="button" id="btnResetToday" class="px-2 py-1 rounded-lg text-[11px] font-bold text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 transition-colors">
                            Hari Ini
                        </button>
                        <button type="submit" id="btnFilterSubmit" class="px-3 py-1 rounded-lg text-[11px] font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition-colors flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Filter
                        </button>
                    </div>
                </div>
            </form>

            <!-- Loading Spinner untuk Filter AJAX -->
            <div id="unitLoading" class="hidden py-4 text-center">
                <div class="inline-flex items-center gap-2 text-xs font-bold text-emerald-600">
                    <svg class="animate-spin h-4 w-4 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Memperbarui status unit...</span>
                </div>
            </div>

            <!-- List Unit Status Ketersediaan -->
            <div id="unitListWrapper" class="space-y-2.5 max-h-64 sm:max-h-72 overflow-y-auto pr-1">
                @forelse($unitsStatus as $unit)
                    <div class="p-3 rounded-2xl border transition-all duration-200 {{ $unit->is_penuh ? 'bg-red-50/40 border-red-200 hover:border-red-300' : 'bg-emerald-50/30 border-emerald-200 hover:border-emerald-300' }}">
                        <div class="flex items-center justify-between gap-2 mb-1.5">
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-slate-900 text-xs sm:text-sm truncate">
                                    {{ $unit->nama_unit }}
                                </div>
                                <div class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">
                                    {{ $unit->tipe_unit }} • Total: {{ $unit->total_unit }} Unit
                                </div>
                            </div>

                            <!-- Badge Status: Merah Jika Penuh, Hijau Jika Masih Ada Stok -->
                            <div>
                                @if($unit->is_penuh)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-red-100 text-red-700 border border-red-300 shadow-sm">
                                        <span class="w-2 h-2 rounded-full bg-red-600"></span>
                                        Penuh (0 Unit)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 shadow-sm">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Tersedia: {{ $unit->sisa }} Unit
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Progress Bar Ketersediaan Unit -->
                        <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                            @if($unit->is_penuh)
                                <div class="bg-red-500 h-full rounded-full w-full"></div>
                            @else
                                <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: {{ $unit->persen_terisi }}%"></div>
                            @endif
                        </div>

                        <div class="flex justify-between items-center text-[10px] font-semibold text-slate-500 mt-1">
                            <span>Terisi: {{ $unit->terisi }} Unit</span>
                            <span>Sisa: <strong class="{{ $unit->is_penuh ? 'text-red-600 font-bold' : 'text-emerald-700 font-bold' }}">{{ $unit->sisa }} Unit</strong></span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-slate-400 text-xs font-semibold">
                        Tidak ada data unit penginapan.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Footer Ringkasan Unit -->
        <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold">
            <span class="text-slate-500">Total Stok: <strong class="text-slate-800" id="totalUnitLabel">{{ $totalUnitKamar }} Unit</strong></span>
            <div class="flex items-center gap-2">
                <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-200" id="summaryTersedia">
                    🟢 {{ $totalTersedia }} Tersedia
                </span>
                <span class="text-red-700 bg-red-50 px-2 py-0.5 rounded-lg border border-red-200" id="summaryTerisi">
                    🔴 {{ $totalTerisi }} Terisi
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings Table -->
<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-extrabold text-lg text-slate-900">Pemesanan Terbaru</h3>
        <a href="{{ route('admin.bookings.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition-colors">Lihat Semua</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-150 text-slate-500 font-bold text-xs uppercase tracking-wider">
                    <th class="p-6">ID Booking</th>
                    <th class="p-6">Tamu / Pelanggan</th>
                    <th class="p-6">Unit</th>
                    <th class="p-6">Check-In / Out</th>
                    <th class="p-6">Total Harga</th>
                    <th class="p-6">Status</th>
                    <th class="p-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm font-semibold text-slate-700">
                @forelse($recentBookings as $booking)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-6 text-slate-950 font-bold">#TA-{{ str_pad($booking->id_reservasi, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="p-6">
                            <div class="text-slate-900 font-bold leading-tight">{{ $booking->tamu->nama }}</div>
                            <div class="text-slate-400 text-xs font-medium mt-0.5">{{ $booking->tamu->email }}</div>
                        </td>
                        <td class="p-6">
                            <div class="text-slate-900 font-bold leading-tight">{{ $booking->penginapan->nama_unit }}</div>
                            <div class="text-slate-400 text-xs font-medium uppercase mt-0.5">{{ $booking->jumlah_kamar }} Kamar ({{ $booking->penginapan->tipe_unit }})</div>
                        </td>
                        <td class="p-6">
                            <div class="text-slate-900 font-bold">{{ date('d M Y', strtotime($booking->tanggal_checkin)) }}</div>
                            <div class="text-slate-400 text-xs font-medium mt-0.5">s.d {{ date('d M Y', strtotime($booking->tanggal_checkout)) }}</div>
                        </td>
                        <td class="p-6">
                            <span class="text-emerald-600 font-bold text-base">Rp {{ number_format($booking->pembayaran->total_pembayaran, 0, ',', '.') }}</span>
                        </td>
                        <td class="p-6">
                            @if($booking->status === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Menunggu Pembayaran
                                </span>
                            @elseif($booking->status === 'confirmed')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Lunas / Terkonfirmasi
                                </span>
                            @elseif($booking->status === 'cancelled')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                    Dibatalkan
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    Selesai
                                </span>
                            @endif
                        </td>
                        <td class="p-6 text-right">
                            <a href="{{ route('admin.bookings.show', $booking->id_reservasi) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-slate-100 hover:bg-emerald-600 hover:text-white rounded-lg text-xs font-bold text-slate-600 transition-colors">
                                Kelola
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">Belum ada transaksi pemesanan terbaru.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const ctx = document.getElementById('bookingChart').getContext('2d');
    const dates = @json($dates);
    const counts = @json($counts);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Jumlah Reservasi Harian',
                data: counts,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10b981',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(241, 245, 249, 1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // ── Script Filter Ketersediaan Unit Tanjung Api ──
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('filterUnitForm');
        const checkinInput = document.getElementById('filter_checkin');
        const checkoutInput = document.getElementById('filter_checkout');
        const btnResetToday = document.getElementById('btnResetToday');
        const unitListWrapper = document.getElementById('unitListWrapper');
        const unitLoading = document.getElementById('unitLoading');
        const periodeLabel = document.getElementById('periodeLabel');
        const summaryTersedia = document.getElementById('summaryTersedia');
        const summaryTerisi = document.getElementById('summaryTerisi');
        const totalUnitLabel = document.getElementById('totalUnitLabel');

        function formatDateDisplay(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr + 'T00:00:00');
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const day = String(d.getDate()).padStart(2, '0');
            const mon = months[d.getMonth()];
            const year = d.getFullYear();
            return `${day} ${mon} ${year}`;
        }

        function getTodayStr() {
            const today = new Date();
            const y = today.getFullYear();
            const m = String(today.getMonth() + 1).padStart(2, '0');
            const d = String(today.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        }

        function getTomorrowStr(baseDateStr) {
            const base = baseDateStr ? new Date(baseDateStr + 'T00:00:00') : new Date();
            base.setDate(base.getDate() + 1);
            const y = base.getFullYear();
            const m = String(base.getMonth() + 1).padStart(2, '0');
            const d = String(base.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        }

        // Ketika checkin berubah, pastikan checkout minimal H+1
        checkinInput.addEventListener('change', function() {
            if (!checkoutInput.value || checkoutInput.value <= this.value) {
                checkoutInput.value = getTomorrowStr(this.value);
            }
            fetchUnitStatus();
        });

        checkoutInput.addEventListener('change', function() {
            if (this.value <= checkinInput.value) {
                alert('Tanggal Check-Out harus setelah tanggal Check-In');
                this.value = getTomorrowStr(checkinInput.value);
            }
            fetchUnitStatus();
        });

        // Tombol Hari Ini
        if (btnResetToday) {
            btnResetToday.addEventListener('click', function() {
                const today = getTodayStr();
                checkinInput.value = today;
                checkoutInput.value = getTomorrowStr(today);
                fetchUnitStatus();
            });
        }

        // Form Submit
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                fetchUnitStatus();
            });
        }

        // AJAX Fetch Status Unit
        function fetchUnitStatus() {
            const ci = checkinInput.value;
            const co = checkoutInput.value;

            if (!ci || !co) return;

            // Update label periode
            if (periodeLabel) {
                periodeLabel.textContent = `${formatDateDisplay(ci)} - ${formatDateDisplay(co)}`;
            }

            if (unitLoading) unitLoading.classList.remove('hidden');
            if (unitListWrapper) unitListWrapper.classList.add('opacity-40');

            const url = `{{ route('admin.dashboard') }}?filter_checkin=${encodeURIComponent(ci)}&filter_checkout=${encodeURIComponent(co)}`;

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Gagal memuat status unit');
                return res.json();
            })
            .then(data => {
                if (data.success && data.unitsStatus) {
                    renderUnits(data.unitsStatus);
                    if (summaryTersedia) summaryTersedia.textContent = `🟢 ${data.totalTersedia} Tersedia`;
                    if (summaryTerisi) summaryTerisi.textContent = `🔴 ${data.totalTerisi} Terisi`;
                    if (totalUnitLabel) totalUnitLabel.textContent = `${data.totalUnitKamar} Unit`;

                    // Update browser history URL tanpa reload
                    window.history.replaceState({}, '', url);
                }
            })
            .catch(err => {
                console.error(err);
                // Fallback submit form secara biasa jika AJAX terkendala
                form.submit();
            })
            .finally(() => {
                if (unitLoading) unitLoading.classList.add('hidden');
                if (unitListWrapper) unitListWrapper.classList.remove('opacity-40');
            });
        }

        function renderUnits(units) {
            if (!unitListWrapper) return;

            if (!units || units.length === 0) {
                unitListWrapper.innerHTML = `
                    <div class="text-center py-6 text-slate-400 text-xs font-semibold">
                        Tidak ada data unit penginapan.
                    </div>
                `;
                return;
            }

            let html = '';
            units.forEach(unit => {
                const isPenuh = unit.is_penuh;
                const cardClass = isPenuh 
                    ? 'bg-red-50/40 border-red-200 hover:border-red-300' 
                    : 'bg-emerald-50/30 border-emerald-200 hover:border-emerald-300';

                const badge = isPenuh 
                    ? `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-red-100 text-red-700 border border-red-300 shadow-sm">
                           <span class="w-2 h-2 rounded-full bg-red-600"></span>
                           Penuh (0 Unit)
                       </span>`
                    : `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 shadow-sm">
                           <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                           Tersedia: ${unit.sisa} Unit
                       </span>`;

                const progressBar = isPenuh
                    ? `<div class="bg-red-500 h-full rounded-full w-full"></div>`
                    : `<div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: ${unit.persen_terisi}%"></div>`;

                const sisaClass = isPenuh ? 'text-red-600 font-bold' : 'text-emerald-700 font-bold';

                html += `
                    <div class="p-3 rounded-2xl border transition-all duration-200 ${cardClass}">
                        <div class="flex items-center justify-between gap-2 mb-1.5">
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-slate-900 text-xs sm:text-sm truncate">
                                    ${escapeHtml(unit.nama_unit)}
                                </div>
                                <div class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">
                                    ${escapeHtml(unit.tipe_unit)} • Total: ${unit.total_unit} Unit
                                </div>
                            </div>
                            <div>${badge}</div>
                        </div>

                        <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                            ${progressBar}
                        </div>

                        <div class="flex justify-between items-center text-[10px] font-semibold text-slate-500 mt-1">
                            <span>Terisi: ${unit.terisi} Unit</span>
                            <span>Sisa: <strong class="${sisaClass}">${unit.sisa} Unit</strong></span>
                        </div>
                    </div>
                `;
            });

            unitListWrapper.innerHTML = html;
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
    });
</script>
@endsection
