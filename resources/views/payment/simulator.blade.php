@extends('layouts.app')

@section('title', 'Simulator Pembayaran - Tanjung Api')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12 bg-slate-50">
    <div class="max-w-lg w-full space-y-6">

        {{-- Header Badge --}}
        <div class="text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-amber-100 border border-amber-300 rounded-full text-amber-700 text-xs font-bold uppercase tracking-wider mb-4">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Simulator Pembayaran (Demo)
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900">Selesaikan Pembayaran</h1>
            <p class="text-slate-500 text-sm mt-1">Pilih metode pembayaran untuk menyelesaikan reservasi Anda.</p>
        </div>

        {{-- Order Summary Card --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-900/5 overflow-hidden">
            <div class="bg-slate-950 text-white px-6 py-4 flex justify-between items-center">
                <div>
                    <div class="text-xs text-emerald-400 font-bold uppercase tracking-wider">ID Reservasi</div>
                    <div class="text-lg font-extrabold">TA-{{ str_pad($booking->id_reservasi, 4, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-slate-400 font-semibold">Total Tagihan</div>
                    <div class="text-xl font-extrabold text-emerald-400">Rp {{ number_format($pembayaran->total_pembayaran, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="px-6 py-4 border-b border-slate-100">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500 font-medium">Nama Pemesan</span>
                    <span class="font-bold text-slate-900">{{ $booking->tamu->nama }}</span>
                </div>
                <div class="flex justify-between text-sm mt-2">
                    <span class="text-slate-500 font-medium">Akomodasi</span>
                    <span class="font-bold text-slate-900">{{ $booking->penginapan->nama_unit }}</span>
                </div>
                <div class="flex justify-between text-sm mt-2">
                    <span class="text-slate-500 font-medium">Check-in / Check-out</span>
                    <span class="font-bold text-slate-900">
                        {{ date('d M Y', strtotime($booking->tanggal_checkin)) }} –
                        {{ date('d M Y', strtotime($booking->tanggal_checkout)) }}
                    </span>
                </div>
            </div>

            {{-- Payment Method Form --}}
            <form action="{{ route('payment.simulator.process', $booking->id_reservasi) }}" method="POST" class="px-6 py-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Pilih Metode Pembayaran</label>
                    <div class="space-y-2" id="payment-methods">

                        @php
                        $methods = [
                            ['value' => 'bank_transfer', 'label' => 'Transfer Bank', 'sub' => 'BCA, BNI, BRI, Mandiri', 'icon' => '🏦'],
                            ['value' => 'gopay',         'label' => 'GoPay',          'sub' => 'Dompet Digital Gojek',    'icon' => '💚'],
                            ['value' => 'qris',          'label' => 'QRIS',           'sub' => 'Scan QR dari semua e-wallet', 'icon' => '📱'],
                            ['value' => 'shopeepay',     'label' => 'ShopeePay',      'sub' => 'Dompet Digital Shopee',   'icon' => '🟠'],
                            ['value' => 'credit_card',   'label' => 'Kartu Kredit / Debit', 'sub' => 'Visa, Mastercard, JCB', 'icon' => '💳'],
                        ];
                        @endphp

                        @foreach($methods as $i => $m)
                        <label class="method-card flex items-center gap-4 p-4 border-2 rounded-2xl cursor-pointer transition-all hover:border-emerald-400 hover:bg-emerald-50/50 {{ $i === 0 ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 bg-white' }}">
                            <input type="radio" name="metode" value="{{ $m['value'] }}" class="hidden" {{ $i === 0 ? 'checked' : '' }}>
                            <span class="text-2xl leading-none select-none">{{ $m['icon'] }}</span>
                            <div class="flex-1">
                                <div class="font-bold text-sm text-slate-900">{{ $m['label'] }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">{{ $m['sub'] }}</div>
                            </div>
                            <div class="check-indicator w-5 h-5 rounded-full border-2 flex items-center justify-center {{ $i === 0 ? 'border-emerald-500 bg-emerald-500' : 'border-slate-300' }} transition-all">
                                <svg class="w-3 h-3 text-white {{ $i === 0 ? '' : 'hidden' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all shadow-md shadow-emerald-600/20 active:scale-95 mt-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Konfirmasi Pembayaran
                </button>
            </form>
        </div>

        <div class="text-center">
            <a href="{{ route('bookings.show', $booking->id_reservasi) }}" class="text-sm text-slate-400 hover:text-slate-600 font-semibold transition-colors">
                ← Kembali ke Rincian Reservasi
            </a>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
document.querySelectorAll('.method-card').forEach(card => {
    card.addEventListener('click', function () {
        // Reset semua
        document.querySelectorAll('.method-card').forEach(c => {
            c.classList.remove('border-emerald-500', 'bg-emerald-50');
            c.classList.add('border-slate-200', 'bg-white');
            c.querySelector('.check-indicator').classList.remove('border-emerald-500', 'bg-emerald-500');
            c.querySelector('.check-indicator').classList.add('border-slate-300');
            c.querySelector('.check-indicator svg').classList.add('hidden');
            c.querySelector('input[type=radio]').checked = false;
        });

        // Aktifkan yang diklik
        this.classList.remove('border-slate-200', 'bg-white');
        this.classList.add('border-emerald-500', 'bg-emerald-50');
        const indicator = this.querySelector('.check-indicator');
        indicator.classList.remove('border-slate-300');
        indicator.classList.add('border-emerald-500', 'bg-emerald-500');
        indicator.querySelector('svg').classList.remove('hidden');
        this.querySelector('input[type=radio]').checked = true;
    });
});
</script>
@endsection
