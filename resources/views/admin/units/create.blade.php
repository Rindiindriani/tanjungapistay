@extends('layouts.admin')

@section('title', 'Tambah Unit Penginapan - Tanjung Api')
@section('header_title', 'Tambah Unit')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Breadcrumb / Back Link -->
    <a href="{{ route('admin.units.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Daftar Unit
    </a>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-extrabold text-lg text-slate-900">Form Tambah Unit Penginapan</h3>
            <p class="text-sm text-slate-500">Masukkan rincian data unit penginapan baru yang ingin disediakan.</p>
        </div>

        <form action="{{ route('admin.units.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Unit -->
                <div class="space-y-2">
                    <label for="nama_unit" class="text-sm font-bold text-slate-700">Nama Unit <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_unit" id="nama_unit" value="{{ old('nama_unit') }}" required 
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm font-semibold text-slate-800" 
                        placeholder="Contoh: Villa Atap Daun A1">
                    @error('nama_unit')
                        <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipe Unit -->
                <div class="space-y-2">
                    <label for="select_tipe_unit" class="text-sm font-bold text-slate-700">Jenis / Tipe Unit <span class="text-red-500">*</span></label>
                    @php
                        $oldTipe = strtolower(old('tipe_unit', 'villa'));
                        $isPreset = in_array($oldTipe, ['villa', 'tenda']);
                    @endphp
                    <select id="select_tipe_unit" onchange="handleTipeUnitChange()"
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm font-semibold text-slate-800 bg-white">
                        <option value="villa" {{ $oldTipe === 'villa' ? 'selected' : '' }}>🏡 Villa</option>
                        <option value="tenda" {{ $oldTipe === 'tenda' ? 'selected' : '' }}>🎪 Tenda</option>
                        <option value="lainnya" {{ !$isPreset && old('tipe_unit') ? 'selected' : '' }}>✏️ Lainnya (Ketik Sendiri...)</option>
                    </select>

                    <div id="wrapper_custom_tipe" class="{{ (!$isPreset && old('tipe_unit')) ? '' : 'hidden' }} mt-2 space-y-1">
                        <label for="input_custom_tipe" class="text-xs font-bold text-emerald-700">Ketik Jenis Unit Baru:</label>
                        <input type="text" id="input_custom_tipe" 
                            value="{{ !$isPreset ? old('tipe_unit') : '' }}"
                            placeholder="Contoh: Cottage, Glamping, Gazebo, Homestay"
                            class="w-full px-4 py-3 border border-emerald-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm font-semibold text-slate-800 bg-emerald-50/40">
                    </div>

                    <!-- Hidden input yang dikirim ke server -->
                    <input type="hidden" name="tipe_unit" id="tipe_unit" value="{{ old('tipe_unit', 'villa') }}">

                    <p class="text-[11px] text-slate-400 font-semibold">Pilih Villa, Tenda, atau pilih 'Lainnya' untuk mengetik sendiri jenis unit baru.</p>
                    @error('tipe_unit')
                        <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga per Malam -->
                <div class="space-y-2">
                    <label for="harga" class="text-sm font-bold text-slate-700">Harga / Malam (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga" id="harga" value="{{ old('harga') }}" required min="0"
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm font-semibold text-slate-800" 
                        placeholder="Contoh: 500000">
                    @error('harga')
                        <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto Utama Cover -->
                <div class="space-y-2">
                    <label for="foto" class="text-sm font-bold text-slate-700">Foto Sampul Utama</label>
                    <input type="file" name="foto" id="foto" accept="image/*"
                        class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm font-semibold text-slate-500 bg-slate-50 file:mr-4 file:py-1 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200 file:cursor-pointer">
                    <p class="text-[11px] text-slate-400 font-semibold mt-0.5">Foto sampul depan unit penginapan.</p>
                    @error('foto')
                        <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto Galeri Tambahan (Multiple) -->
                <div class="space-y-2 sm:col-span-2">
                    <label for="foto_galeri" class="text-sm font-bold text-slate-700">
                        Foto Galeri & Dalam Kamar (Bisa Pilih Banyak Foto)
                    </label>
                    <input type="file" name="foto_galeri[]" id="foto_galeri" accept="image/*" multiple
                        class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm font-semibold text-slate-500 bg-slate-50 file:mr-4 file:py-1 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 file:cursor-pointer">
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Unggah foto-foto suasana dalam kamar, tempat tidur, kamar mandi, pemandangan, dll. (Pilih beberapa file foto sekaligus).</p>
                    @error('foto_galeri.*')
                        <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jumlah Unit Tersedia -->
                <div class="space-y-2">
                    <label for="jumlah_unit" class="text-sm font-bold text-slate-700">Jumlah Unit Tersedia <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_unit" id="jumlah_unit" value="{{ old('jumlah_unit', 1) }}" required min="1"
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm font-semibold text-slate-800" 
                        placeholder="Contoh: 4">
                    <p class="text-[11px] text-slate-400 font-semibold mt-1">Total unit yang bisa dipesan bersamaan oleh tamu.</p>
                    @error('jumlah_unit')
                        <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Alamat -->
            <div class="space-y-2">
                <label for="alamat" class="text-sm font-bold text-slate-700">Alamat Lengkap Unit <span class="text-red-500">*</span></label>
                <input type="text" name="alamat" id="alamat" value="{{ old('alamat', 'Pantai Tanjung Api, Desa Sebubus, Paloh') }}" required
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm font-semibold text-slate-800" 
                    placeholder="Masukkan alamat lengkap unit penginapan">
                @error('alamat')
                    <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fasilitas -->
            <div class="space-y-2">
                <label for="fasilitas" class="text-sm font-bold text-slate-700">Fasilitas <span class="text-red-500">*</span></label>
                <textarea name="fasilitas" id="fasilitas" rows="4" required
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm font-semibold text-slate-800" 
                    placeholder="Tuliskan semua fasilitas yang didapatkan tamu. Pisahkan dengan tanda koma (,), contoh: AC, Kamar Mandi Dalam, TV, Sarapan 2 Pax, dll.">{{ old('fasilitas') }}</textarea>
                @error('fasilitas')
                    <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.units.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 rounded-xl text-slate-700 font-bold text-sm transition-all active:scale-95">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all shadow-md shadow-emerald-600/10 hover:shadow-emerald-600/25 active:scale-95">
                    Simpan Unit
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
function handleTipeUnitChange() {
    const select = document.getElementById('select_tipe_unit');
    const wrapper = document.getElementById('wrapper_custom_tipe');
    const inputCustom = document.getElementById('input_custom_tipe');
    const realInput = document.getElementById('tipe_unit');

    if (select.value === 'lainnya') {
        wrapper.classList.remove('hidden');
        inputCustom.focus();
        realInput.value = inputCustom.value.trim() || '';
    } else {
        wrapper.classList.add('hidden');
        realInput.value = select.value;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const inputCustom = document.getElementById('input_custom_tipe');
    if (inputCustom) {
        inputCustom.addEventListener('input', function() {
            const select = document.getElementById('select_tipe_unit');
            if (select.value === 'lainnya') {
                document.getElementById('tipe_unit').value = this.value.trim();
            }
        });
    }
    const form = inputCustom ? inputCustom.closest('form') : null;
    if (form) {
        form.addEventListener('submit', function() {
            const select = document.getElementById('select_tipe_unit');
            const realInput = document.getElementById('tipe_unit');
            if (select && select.value === 'lainnya' && inputCustom) {
                realInput.value = inputCustom.value.trim();
            }
        });
    }
    handleTipeUnitChange();
});
</script>
@endsection
@endsection
