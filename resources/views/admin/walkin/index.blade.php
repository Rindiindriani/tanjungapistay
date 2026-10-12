@extends('layouts.admin')

@section('title', 'Reservasi Langsung (Walk-in) - Tanjung Api')
@section('header_title', 'Reservasi Langsung')

@section('styles')
<style>
    .walkin-card {
        animation: fadeInUp 0.5s ease-out;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .section-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }
    .availability-box {
        transition: all 0.3s ease;
    }
    .availability-box.available {
        background-color: #ecfdf5;
        border-color: #a7f3d0;
        color: #065f46;
    }
    .availability-box.unavailable {
        background-color: #fef2f2;
        border-color: #fecaca;
        color: #991b1b;
    }
    .price-summary {
        background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%);
        position: relative;
        overflow: hidden;
    }
    .price-summary::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
        pointer-events: none;
    }
    .input-focus-ring:focus {
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
    }
</style>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Page Header --}}
    <div class="walkin-card flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Reservasi Langsung</h1>
            <p class="text-sm text-slate-500 mt-1">Daftarkan tamu yang datang langsung ke lokasi penginapan.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Walk-in
            </span>
        </div>
    </div>

    {{-- Error display --}}
    @if ($errors->any())
        <div class="walkin-card bg-red-50 border border-red-200 rounded-2xl p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-bold text-red-800 text-sm">Terdapat kesalahan:</h4>
                    <ul class="mt-1 text-sm text-red-700 list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.walkin.store') }}" method="POST" id="walkinForm" class="space-y-6">
        @csrf

        {{-- Section 1: Data Tamu --}}
        <div class="walkin-card bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden" style="animation-delay: 0.1s;">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <span class="section-badge bg-blue-50 text-blue-700 border border-blue-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </span>
                <h3 class="font-extrabold text-slate-900">Data Tamu</h3>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Kewarganegaraan Toggle --}}
                <div class="sm:col-span-2 space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kewarganegaraan <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center justify-center gap-2 p-3 border-2 rounded-xl cursor-pointer transition-all font-bold text-sm" id="btnWniLabel">
                            <input type="radio" name="kewarganegaraan" id="kewarganegaraan_wni" value="WNI" {{ old('kewarganegaraan', 'WNI') === 'WNI' ? 'checked' : '' }} class="hidden" onchange="toggleKewarganegaraanForm()">
                            <span>🇮🇩 WNI (Indonesia)</span>
                        </label>
                        <label class="flex items-center justify-center gap-2 p-3 border-2 rounded-xl cursor-pointer transition-all font-bold text-sm" id="btnWnaLabel">
                            <input type="radio" name="kewarganegaraan" id="kewarganegaraan_wna" value="WNA" {{ old('kewarganegaraan') === 'WNA' ? 'checked' : '' }} class="hidden" onchange="toggleKewarganegaraanForm()">
                            <span>🌐 WNA (Luar Negeri)</span>
                        </label>
                    </div>
                </div>

                {{-- Nama Lengkap --}}
                <div class="sm:col-span-2">
                    <label for="nama" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                        class="input-focus-ring w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:border-emerald-500 focus:outline-none transition-all"
                        placeholder="Masukkan nama lengkap tamu (Sesuai Identitas)">
                </div>

                {{-- No. KTP / NIK (Tampil jika WNI) --}}
                <div id="field_wni_ktp">
                    <label for="no_ktp" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">No. KTP / NIK <span class="text-red-500">*</span></label>
                    <input type="text" name="no_ktp" id="no_ktp" value="{{ old('no_ktp') }}" maxlength="20"
                        class="input-focus-ring w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:border-emerald-500 focus:outline-none transition-all"
                        placeholder="16 digit NIK / No. KTP">
                </div>

                {{-- No. Paspor (Tampil jika WNA) --}}
                <div id="field_wna_paspor" class="hidden">
                    <label for="no_paspor" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">No. Paspor <span class="text-red-500">*</span></label>
                    <input type="text" name="no_paspor" id="no_paspor" value="{{ old('no_paspor') }}" maxlength="50"
                        class="input-focus-ring w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:border-emerald-500 focus:outline-none transition-all"
                        placeholder="Nomor Paspor Tamu WNA">
                </div>

                {{-- No HP --}}
                <div>
                    <label for="no_hp" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">No. HP / WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" required
                        class="input-focus-ring w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:border-emerald-500 focus:outline-none transition-all"
                        placeholder="08xxxxxxxxxx">
                </div>

                {{-- Form Alamat Terstruktur WNI --}}
                <div id="fields_wni_alamat" class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <div>
                        <label for="provinsi" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Provinsi</label>
                        <input type="text" name="provinsi" id="provinsi" list="list_provinsi" value="{{ old('provinsi') }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-900 focus:border-emerald-500 focus:outline-none"
                            placeholder="Provinsi">
                    </div>
                    <div>
                        <label for="kabupaten_kota" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Kabupaten / Kota</label>
                        <input type="text" name="kabupaten_kota" id="kabupaten_kota" list="list_kabupaten" value="{{ old('kabupaten_kota') }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-900 focus:border-emerald-500 focus:outline-none"
                            placeholder="Kabupaten / Kota">
                    </div>
                    <div>
                        <label for="kecamatan" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Kecamatan</label>
                        <input type="text" name="kecamatan" id="kecamatan" list="list_kecamatan" value="{{ old('kecamatan') }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-900 focus:border-emerald-500 focus:outline-none"
                            placeholder="Kecamatan">
                    </div>
                </div>

                {{-- Form Negara WNA --}}
                <div id="fields_wna_alamat" class="sm:col-span-2 hidden">
                    <label for="negara" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Negara Asal (Country) <span class="text-red-500">*</span></label>
                    <input type="text" name="negara" id="negara" list="list_negara" value="{{ old('negara') }}"
                        class="input-focus-ring w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:border-emerald-500 focus:outline-none transition-all"
                        placeholder="Negara">
                </div>

                {{-- Datalist Autocomplete Options --}}
                <datalist id="list_provinsi">
                    <option value="Kalimantan Barat">
                    <option value="Kalimantan Tengah">
                    <option value="Kalimantan Selatan">
                    <option value="Kalimantan Timur">
                    <option value="Kalimantan Utara">
                    <option value="DKI Jakarta">
                    <option value="Jawa Barat">
                    <option value="Jawa Tengah">
                    <option value="Jawa Timur">
                    <option value="DI Yogyakarta">
                    <option value="Banten">
                    <option value="Bali">
                    <option value="Nusa Tenggara Barat">
                    <option value="Nusa Tenggara Timur">
                    <option value="Sumatera Utara">
                    <option value="Sumatera Barat">
                    <option value="Riau">
                    <option value="Kepulauan Riau">
                    <option value="Jambi">
                    <option value="Sumatera Selatan">
                    <option value="Bangka Belitung">
                    <option value="Bengkulu">
                    <option value="Lampung">
                    <option value="Sulawesi Utara">
                    <option value="Sulawesi Tengah">
                    <option value="Sulawesi Selatan">
                    <option value="Sulawesi Tenggara">
                    <option value="Gorontalo">
                    <option value="Sulawesi Barat">
                    <option value="Maluku">
                    <option value="Maluku Utara">
                    <option value="Papua">
                    <option value="Papua Barat">
                    <option value="Papua Tengah">
                    <option value="Papua Pegunungan">
                    <option value="Papua Selatan">
                    <option value="Papua Barat Daya">
                    <option value="Aceh">
                </datalist>

                <datalist id="list_kabupaten">
                    <option value="Sambas">
                    <option value="Pontianak">
                    <option value="Singkawang">
                    <option value="Kubu Raya">
                    <option value="Mempawah">
                    <option value="Landak">
                    <option value="Sanggau">
                    <option value="Sekadau">
                    <option value="Sintang">
                    <option value="Melawi">
                    <option value="Kapuas Hulu">
                    <option value="Ketapang">
                    <option value="Kayong Utara">
                    <option value="Banjarmasin">
                    <option value="Balikpapan">
                    <option value="Samarinda">
                    <option value="Palangka Raya">
                    <option value="Tarakan">
                    <option value="Jakarta Selatan">
                    <option value="Jakarta Barat">
                    <option value="Jakarta Pusat">
                    <option value="Jakarta Timur">
                    <option value="Jakarta Utara">
                    <option value="Surabaya">
                    <option value="Bandung">
                    <option value="Medan">
                    <option value="Semarang">
                    <option value="Yogyakarta">
                    <option value="Denpasar">
                </datalist>

                <datalist id="list_kecamatan">
                    <option value="Paloh">
                    <option value="Teluk Keramat">
                    <option value="Tangaran">
                    <option value="Galing">
                    <option value="Jawai">
                    <option value="Jawai Selatan">
                    <option value="Sejangkung">
                    <option value="Sambas">
                    <option value="Subah">
                    <option value="Sajingan Besar">
                    <option value="Tekarang">
                    <option value="Pemangkat">
                    <option value="Semparuk">
                    <option value="Salatiga">
                    <option value="Selakau">
                    <option value="Selakau Timur">
                    <option value="Pontianak Selatan">
                    <option value="Pontianak Kota">
                    <option value="Pontianak Barat">
                    <option value="Pontianak Utara">
                    <option value="Pontianak Timur">
                    <option value="Sungai Raya">
                    <option value="Mempawah Hilir">
                </datalist>

                <datalist id="list_negara">
                    <option value="Malaysia">
                    <option value="Brunei Darussalam">
                    <option value="Singapore">
                    <option value="Australia">
                    <option value="China">
                    <option value="Japan">
                    <option value="South Korea">
                    <option value="United Kingdom">
                    <option value="United States">
                    <option value="Germany">
                    <option value="France">
                    <option value="Netherlands">
                    <option value="Saudi Arabia">
                    <option value="Thailand">
                    <option value="Vietnam">
                    <option value="Philippines">
                    <option value="India">
                </datalist>


                {{-- Alamat Jalan / Detail --}}
                <div class="sm:col-span-2">
                    <label for="alamat_ktp" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Alamat Jalan / Desa / Detail Domisili</label>
                    <input type="text" name="alamat_ktp" id="alamat_ktp" value="{{ old('alamat_ktp') }}"
                        class="input-focus-ring w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:border-emerald-500 focus:outline-none transition-all"
                        placeholder="Contoh: Dusun Merbau, Desa Sebubus RT 02 / RW 01">
                </div>

                {{-- Email --}}
                <div class="sm:col-span-2">
                    <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email <span class="text-slate-400 text-[10px]">(Opsional)</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="input-focus-ring w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:border-emerald-500 focus:outline-none transition-all"
                        placeholder="contoh@email.com">
                </div>
            </div>
        </div>



        {{-- Section 2: Data Reservasi --}}
        <div class="walkin-card bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden" style="animation-delay: 0.2s;">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <span class="section-badge bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </span>
                <h3 class="font-extrabold text-slate-900">Data Reservasi</h3>
            </div>
            <div class="p-6 space-y-5">
                {{-- Unit Penginapan --}}
                <div>
                    <label for="id_unit" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Unit Penginapan <span class="text-red-500">*</span></label>
                    <select name="id_unit" id="id_unit" required
                        class="input-focus-ring w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-900 focus:border-emerald-500 focus:outline-none transition-all bg-white">
                        <option value="">— Pilih Unit Penginapan —</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id_unit }}" 
                                data-harga="{{ $unit->harga }}" 
                                data-tipe="{{ $unit->tipe_unit }}"
                                data-jumlah="{{ $unit->jumlah_unit }}"
                                {{ old('id_unit') == $unit->id_unit ? 'selected' : '' }}>
                                {{ $unit->nama_unit }} — {{ ucfirst($unit->tipe_unit) }} — Rp {{ number_format($unit->harga, 0, ',', '.') }}/malam ({{ $unit->jumlah_unit }} unit)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    {{-- Jumlah Kamar --}}
                    <div>
                        <label for="jumlah_kamar" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jumlah Unit <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_kamar" id="jumlah_kamar" value="{{ old('jumlah_kamar', 1) }}" min="1" required
                            class="input-focus-ring w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-900 focus:border-emerald-500 focus:outline-none transition-all">
                    </div>

                    {{-- Tanggal Check-in --}}
                    <div>
                        <label for="tanggal_checkin" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                            Tanggal Check-in <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_checkin" id="tanggal_checkin" value="{{ old('tanggal_checkin', date('Y-m-d')) }}" required
                            class="input-focus-ring w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-900 focus:border-emerald-500 focus:outline-none transition-all">
                    </div>

                    {{-- Tanggal Check-out --}}
                    <div>
                        <label for="tanggal_checkout" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                            Tanggal Check-out <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_checkout" id="tanggal_checkout" value="{{ old('tanggal_checkout') }}" required
                            class="input-focus-ring w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-900 focus:border-emerald-500 focus:outline-none transition-all">
                    </div>
                </div>

                {{-- Jumlah Tamu --}}
                <div class="sm:w-1/3">
                    <label for="jumlah_tamu" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jumlah Tamu <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_tamu" id="jumlah_tamu" value="{{ old('jumlah_tamu', 1) }}" min="1" required
                        class="input-focus-ring w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-900 focus:border-emerald-500 focus:outline-none transition-all">
                    <p class="text-[11px] text-amber-700 font-semibold mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Catatan: Tipe Villa maksimal 6 orang per unit.
                    </p>
                </div>


                {{-- Availability Check Result --}}
                <div id="availabilityResult" class="availability-box hidden rounded-xl border p-4 text-sm font-semibold flex items-center gap-2 transition-all">
                    <svg id="availIcon" class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="availMessage"></span>
                </div>

                {{-- Unit not selected warning --}}
                <div id="unitWarning" class="hidden rounded-xl border border-amber-200 bg-amber-50 p-3 text-sm font-semibold text-amber-700 flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Pilih unit penginapan terlebih dahulu untuk melihat total harga.
                </div>
            </div>
        </div>

        {{-- Section 3: Pembayaran & Ringkasan --}}
        <div class="walkin-card bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden" style="animation-delay: 0.3s;">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <span class="section-badge bg-violet-50 text-violet-700 border border-violet-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </span>
                <h3 class="font-extrabold text-slate-900">Pembayaran</h3>
            </div>
            <div class="p-6 space-y-5">
                {{-- Metode Pembayaran --}}
                <div class="sm:w-1/2">
                    <label for="metode_pembayaran" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Metode Pembayaran <span class="text-red-500">*</span></label>
                    <select name="metode_pembayaran" id="metode_pembayaran" required
                        class="input-focus-ring w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-900 focus:border-emerald-500 focus:outline-none transition-all bg-white">
                        <option value="Tunai" {{ old('metode_pembayaran', 'Tunai') == 'Tunai' ? 'selected' : '' }}>💵 Tunai (Cash)</option>
                        <option value="Transfer" {{ old('metode_pembayaran') == 'Transfer' ? 'selected' : '' }}>🏦 Transfer Bank</option>
                    </select>
                </div>

                {{-- Info Midtrans (muncul saat Transfer dipilih) --}}
                <div class="sm:w-full" id="midtrans_info" style="{{ old('metode_pembayaran') == 'Transfer' ? '' : 'display:none;' }}">
                    <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-bold text-blue-800">Pembayaran via Midtrans SNAP</p>
                            <p class="text-xs text-blue-700 mt-0.5">Setelah klik "Proses Reservasi", Anda akan diarahkan ke halaman pembayaran Midtrans. Tamu dapat membayar menggunakan Transfer Bank, Virtual Account, GoPay, QRIS, dan lainnya.</p>
                        </div>
                    </div>
                </div>

                {{-- Price Summary Card --}}
                <div class="price-summary rounded-2xl p-6 text-white">
                    <div class="relative z-10">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-emerald-300 mb-4">Ringkasan Harga</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-emerald-100">Harga per Unit/Malam</span>
                                <span class="font-bold" id="displayHarga">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-emerald-100">Jumlah Unit</span>
                                <span class="font-bold" id="displayUnit">0</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-emerald-100">Jumlah Malam</span>
                                <span class="font-bold" id="displayMalam">0</span>
                            </div>
                            <div class="border-t border-emerald-500/30 my-2"></div>
                            <div class="flex justify-between items-center">
                                <span class="text-emerald-200 font-bold text-sm">Total Pembayaran</span>
                                <span class="text-2xl font-extrabold tracking-tight" id="displayTotal">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="walkin-card flex flex-col sm:flex-row items-center justify-between gap-4 pt-2" style="animation-delay: 0.4s;">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-slate-500 hover:text-slate-700 transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>
            <button type="submit" id="submitBtn"
                class="inline-flex items-center gap-2 px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-2xl text-sm font-extrabold shadow-lg shadow-emerald-600/20 hover:shadow-emerald-600/30 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Proses Reservasi Langsung
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
function toggleKewarganegaraanForm() {
    const isWni = document.getElementById('kewarganegaraan_wni').checked;
    const fieldWniKtp = document.getElementById('field_wni_ktp');
    const fieldWnaPaspor = document.getElementById('field_wna_paspor');
    const fieldsWniAlamat = document.getElementById('fields_wni_alamat');
    const fieldsWnaAlamat = document.getElementById('fields_wna_alamat');
    const btnWniLabel = document.getElementById('btnWniLabel');
    const btnWnaLabel = document.getElementById('btnWnaLabel');

    const inputKtp = document.getElementById('no_ktp');
    const inputPaspor = document.getElementById('no_paspor');

    if (isWni) {
        fieldWniKtp.classList.remove('hidden');
        fieldWnaPaspor.classList.add('hidden');
        fieldsWniAlamat.classList.remove('hidden');
        fieldsWnaAlamat.classList.add('hidden');

        inputKtp.required = true;
        inputPaspor.required = false;

        btnWniLabel.className = 'flex items-center justify-center gap-2 p-3 border-2 border-emerald-500 bg-emerald-50 text-emerald-800 rounded-xl cursor-pointer transition-all font-extrabold text-sm shadow-sm';
        btnWnaLabel.className = 'flex items-center justify-center gap-2 p-3 border-2 border-slate-200 bg-white text-slate-500 hover:bg-slate-50 rounded-xl cursor-pointer transition-all font-bold text-sm';
    } else {
        fieldWniKtp.classList.add('hidden');
        fieldWnaPaspor.classList.remove('hidden');
        fieldsWniAlamat.classList.add('hidden');
        fieldsWnaAlamat.classList.remove('hidden');

        inputKtp.required = false;
        inputPaspor.required = true;

        btnWniLabel.className = 'flex items-center justify-center gap-2 p-3 border-2 border-slate-200 bg-white text-slate-500 hover:bg-slate-50 rounded-xl cursor-pointer transition-all font-bold text-sm';
        btnWnaLabel.className = 'flex items-center justify-center gap-2 p-3 border-2 border-emerald-500 bg-emerald-50 text-emerald-800 rounded-xl cursor-pointer transition-all font-extrabold text-sm shadow-sm';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleKewarganegaraanForm();
    const unitSelect = document.getElementById('id_unit');
    const jumlahKamar = document.getElementById('jumlah_kamar');
    const checkinInput = document.getElementById('tanggal_checkin');
    const checkoutInput = document.getElementById('tanggal_checkout');
    const availResult = document.getElementById('availabilityResult');
    const availMessage = document.getElementById('availMessage');
    const availIcon = document.getElementById('availIcon');

    const displayHarga = document.getElementById('displayHarga');
    const displayUnit = document.getElementById('displayUnit');
    const displayMalam = document.getElementById('displayMalam');
    const displayTotal = document.getElementById('displayTotal');

    function formatDateIndo(dateStr) {
        if (!dateStr) return 'Belum dipilih';
        const parts = dateStr.split('-');
        if (parts.length !== 3) return dateStr;
        const year = parts[0];
        const month = parseInt(parts[1], 10);
        const day = parseInt(parts[2], 10);
        return `${day}/${month}/${year}`;
    }

    function updateDatePreviews() {
        const ciText = document.getElementById('checkinFormattedText');
        const coText = document.getElementById('checkoutFormattedText');
        if (ciText) ciText.textContent = formatDateIndo(checkinInput.value);
        if (coText) coText.textContent = formatDateIndo(checkoutInput.value);
    }

    function formatRupiah(num) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
    }

    function calculateNights() {
        const ci = new Date(checkinInput.value);
        const co = new Date(checkoutInput.value);
        if (isNaN(ci.getTime()) || isNaN(co.getTime())) return 0;
        const diff = Math.ceil((co - ci) / (1000 * 60 * 60 * 24));
        return diff > 0 ? diff : 0;
    }

    function updatePriceSummary() {
        const selected = unitSelect.options[unitSelect.selectedIndex];
        const harga = selected && selected.value ? parseFloat(selected.dataset.harga) : 0;
        const units = parseInt(jumlahKamar.value) || 0;
        const nights = calculateNights();
        const total = harga * units * nights;

        displayHarga.textContent = formatRupiah(harga);
        displayUnit.textContent = units + ' unit';
        displayMalam.textContent = nights + ' malam';
        displayTotal.textContent = formatRupiah(total);

        // Tampilkan peringatan jika unit belum dipilih
        const unitWarning = document.getElementById('unitWarning');
        if (unitWarning) {
            unitWarning.classList.toggle('hidden', !!(selected && selected.value));
        }
    }


    function checkAvailability() {
        const idUnit = unitSelect.value;
        const checkin = checkinInput.value;
        const checkout = checkoutInput.value;
        const rooms = jumlahKamar.value;

        if (!idUnit || !checkin || !checkout || !rooms) {
            availResult.classList.add('hidden');
            return;
        }

        fetch('{{ route("admin.walkin.check") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                id_unit: idUnit,
                tanggal_checkin: checkin,
                tanggal_checkout: checkout,
                jumlah_kamar: rooms,
            })
        })
        .then(res => res.json())
        .then(data => {
            availResult.classList.remove('hidden');
            availMessage.textContent = data.message;

            if (data.success) {
                availResult.className = 'availability-box available rounded-xl border p-4 text-sm font-semibold flex items-center gap-2 transition-all';
                availIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
            } else {
                availResult.className = 'availability-box unavailable rounded-xl border p-4 text-sm font-semibold flex items-center gap-2 transition-all';
                availIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
            }
        })
        .catch(() => {
            availResult.classList.add('hidden');
        });
    }

    // Debounce helper
    let debounceTimer;
    function debounce(fn, delay) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fn, delay);
    }

    // Attach event listeners
    unitSelect.addEventListener('change', function() {
        updatePriceSummary();
        debounce(checkAvailability, 300);
    });

    jumlahKamar.addEventListener('input', function() {
        updatePriceSummary();
        debounce(checkAvailability, 500);
    });

    checkinInput.addEventListener('change', function() {
        if (checkinInput.value) {
            const minCheckout = new Date(checkinInput.value);
            minCheckout.setDate(minCheckout.getDate() + 1);
            checkoutInput.min = minCheckout.toISOString().split('T')[0];
            if (checkoutInput.value && new Date(checkoutInput.value) <= new Date(checkinInput.value)) {
                checkoutInput.value = minCheckout.toISOString().split('T')[0];
            }
        }
        updatePriceSummary();
        debounce(checkAvailability, 300);
    });

    checkoutInput.addEventListener('change', function() {
        updatePriceSummary();
        debounce(checkAvailability, 300);
    });

    // Initialize
    updatePriceSummary();

    // Show/hide Midtrans info based on payment method
    const metodePembayaran = document.getElementById('metode_pembayaran');
    const midtransInfo = document.getElementById('midtrans_info');

    function toggleMidtransInfo() {
        if (metodePembayaran.value === 'Transfer') {
            midtransInfo.style.display = '';
        } else {
            midtransInfo.style.display = 'none';
        }
    }

    metodePembayaran.addEventListener('change', toggleMidtransInfo);
    toggleMidtransInfo(); // run on page load
});
</script>
@endsection
