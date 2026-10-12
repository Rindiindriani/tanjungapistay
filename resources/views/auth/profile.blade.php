@extends('layouts.app')

@section('title', 'Profil Saya - Tanjung Api')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
    <!-- Header Page Title -->
    <div class="flex flex-col gap-1 mb-8 animate-fade-in">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight sm:text-4xl">Pengaturan Profil & Keamanan</h1>
        <p class="text-sm sm:text-base text-slate-500">Kelola informasi profil akun tamu Anda dan ubah kata sandi secara berkala untuk kenyamanan dan keamanan.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left Side: Profile Card (Referencing the uploaded image layout) -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-900/5 overflow-hidden flex flex-col">
            <!-- Green Card Header Banner -->
            <div class="h-32 bg-gradient-to-r from-emerald-600 to-teal-600 relative flex items-center justify-center">
                <span class="absolute text-emerald-500/10 font-black text-6xl tracking-widest select-none uppercase">PROFILE</span>
            </div>

            <!-- Avatar Wrapper with camera icon overlay for file trigger -->
            <div class="flex flex-col items-center px-6 pb-6 -mt-16 text-center">
                <div class="relative group mb-4">
                    <div class="w-32 h-32 rounded-full border-4 border-white bg-white shadow-lg overflow-hidden flex items-center justify-center relative">
                        @if($tamu->foto)
                            <img id="avatar-preview" src="{{ asset('images/profiles/' . $tamu->foto) }}" class="w-full h-full object-cover" alt="Foto Profil">
                        @else
                            <!-- Beautiful Default SVG User Avatar -->
                            <div id="avatar-placeholder" class="w-full h-full bg-gradient-to-tr from-slate-100 to-slate-200 flex items-center justify-center text-slate-400">
                                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <img id="avatar-preview" class="w-full h-full object-cover hidden" alt="Foto Profil">
                        @endif
                    </div>
                    <!-- Upload overlay camera button -->
                    <label for="foto-upload" class="absolute bottom-1.5 right-1.5 bg-emerald-600 text-white p-2 rounded-full cursor-pointer shadow-md hover:bg-emerald-700 transition-all border border-white hover:scale-105 active:scale-95 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </label>
                </div>

                <!-- Info -->
                <h3 class="font-extrabold text-slate-800 text-xl leading-snug">{{ $tamu->nama }}</h3>
                <p class="text-sm text-slate-400 font-medium mb-4">{{ $tamu->email }}</p>

                <!-- Badges block -->
                <div class="flex flex-col gap-2 w-full mt-2">
                    <div class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-slate-600 bg-slate-50 border border-slate-100 rounded-xl justify-center">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tamu Terdaftar
                    </div>

                    <!-- Email Verification Badge -->
                    @if($tamu->hasVerifiedEmail())
                        <div class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-xl justify-center">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            Email Terverifikasi
                        </div>
                    @else
                        <div class="flex flex-col items-center gap-1.5 p-3 text-xs font-bold text-amber-800 bg-amber-50 border border-amber-200 rounded-xl">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Email Belum Diverifikasi
                            </div>
                            <span class="text-[11px] text-amber-700 font-medium text-center">Batas waktu verifikasi Gmail 3 hari dari pendaftaran.</span>
                            <form action="{{ route('verify.resend') }}" method="POST" class="w-full mt-1">
                                @csrf
                                <button type="submit" class="w-full py-1.5 px-3 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-bold transition-colors">
                                    Kirim Ulang Email Verifikasi
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Phone Verification Badge -->
                    @if($tamu->hasVerifiedPhone())
                        <div class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-xl justify-center">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            Telepon Terverifikasi
                        </div>
                    @else
                        <div class="flex flex-col items-center gap-1.5 p-3 text-xs font-bold text-blue-800 bg-blue-50 border border-blue-200 rounded-xl">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Telepon Belum Diverifikasi
                            </div>
                            @if($tamu->no_hp_verification_code)
                                <span class="text-[11px] text-blue-600 font-semibold">Kode OTP: <code class="bg-blue-100 px-1.5 py-0.5 rounded text-blue-900">{{ $tamu->no_hp_verification_code }}</code></span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>


        <!-- Right Side: Forms (Profile Data & Password) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Edit Profile Card -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-900/5 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-base sm:text-lg">Informasi Profil</h3>
                        <p class="text-xs text-slate-400">Perbarui data kontak dan identitas akun Anda.</p>
                    </div>
                </div>

                <form id="profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                    @csrf
                    <!-- Hidden file input triggered by camera icon -->
                    <input type="file" id="foto-upload" name="foto" class="hidden" accept="image/*">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Lengkap -->
                        <div class="space-y-1.5">
                            <label for="nama" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama', $tamu->nama) }}" required 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:bg-white rounded-xl text-sm font-semibold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all @error('nama') border-red-500 @enderror" placeholder="Nama Lengkap">
                            @error('nama')
                                <span class="text-xs text-red-500 font-semibold block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- No. KTP / NIK -->
                        <div class="space-y-1.5">
                            <label for="no_ktp" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">No. KTP / NIK</label>
                            <input type="text" id="no_ktp" name="no_ktp" value="{{ old('no_ktp', $tamu->no_ktp) }}" maxlength="20"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:bg-white rounded-xl text-sm font-semibold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all @error('no_ktp') border-red-500 @enderror" placeholder="16 digit NIK / No. KTP">
                            @error('no_ktp')
                                <span class="text-xs text-red-500 font-semibold block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Kewarganegaraan -->
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Kewarganegaraan</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center justify-center gap-2 p-2.5 border rounded-xl cursor-pointer text-xs font-bold transition-all {{ old('kewarganegaraan', $tamu->kewarganegaraan ?? 'WNI') === 'WNI' ? 'border-emerald-500 bg-emerald-50 text-emerald-800' : 'border-slate-200 bg-slate-50 text-slate-600' }}">
                                    <input type="radio" name="kewarganegaraan" value="WNI" {{ old('kewarganegaraan', $tamu->kewarganegaraan ?? 'WNI') === 'WNI' ? 'checked' : '' }} class="hidden" onchange="this.form.submit()">
                                    <span>🇮🇩 WNI (Indonesia)</span>
                                </label>
                                <label class="flex items-center justify-center gap-2 p-2.5 border rounded-xl cursor-pointer text-xs font-bold transition-all {{ old('kewarganegaraan', $tamu->kewarganegaraan) === 'WNA' ? 'border-emerald-500 bg-emerald-50 text-emerald-800' : 'border-slate-200 bg-slate-50 text-slate-600' }}">
                                    <input type="radio" name="kewarganegaraan" value="WNA" {{ old('kewarganegaraan', $tamu->kewarganegaraan) === 'WNA' ? 'checked' : '' }} class="hidden" onchange="this.form.submit()">
                                    <span>🌐 WNA (Luar Negeri)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Provinsi / Kab / Kec / Negara -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:col-span-2 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <div>
                                <label for="provinsi" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Provinsi</label>
                                <input type="text" id="provinsi" name="provinsi" list="list_provinsi" value="{{ old('provinsi', $tamu->provinsi) }}"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-emerald-500" placeholder="Provinsi">
                            </div>
                            <div>
                                <label for="kabupaten_kota" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Kabupaten / Kota</label>
                                <input type="text" id="kabupaten_kota" name="kabupaten_kota" list="list_kabupaten" value="{{ old('kabupaten_kota', $tamu->kabupaten_kota) }}"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-emerald-500" placeholder="Kabupaten / Kota">
                            </div>
                            <div>
                                <label for="kecamatan" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Kecamatan</label>
                                <input type="text" id="kecamatan" name="kecamatan" list="list_kecamatan" value="{{ old('kecamatan', $tamu->kecamatan) }}"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-emerald-500" placeholder="Kecamatan">
                            </div>
                            @if(($tamu->kewarganegaraan ?? 'WNI') === 'WNA')
                                <div class="md:col-span-3">
                                    <label for="negara" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Negara Asal (Country)</label>
                                    <input type="text" id="negara" name="negara" list="list_negara" value="{{ old('negara', $tamu->negara) }}"
                                           class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-emerald-500" placeholder="Negara">
                                </div>
                            @endif
                        </div>

                        <!-- Alamat Detail -->
                        <div class="space-y-1.5 md:col-span-2">
                            <label for="alamat_ktp" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Alamat Jalan / Desa / Detail Domisili</label>
                            <input type="text" id="alamat_ktp" name="alamat_ktp" value="{{ old('alamat_ktp', $tamu->alamat_ktp) }}" 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:bg-white rounded-xl text-sm font-semibold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all @error('alamat_ktp') border-red-500 @enderror" placeholder="Contoh: Dusun Merbau, Desa Sebubus RT 02 / RW 01">
                            @error('alamat_ktp')
                                <span class="text-xs text-red-500 font-semibold block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-1.5">
                            <label for="email" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Alamat Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $tamu->email) }}" required 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:bg-white rounded-xl text-sm font-semibold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all @error('email') border-red-500 @enderror" placeholder="contoh@domain.com">
                            @error('email')
                                <span class="text-xs text-red-500 font-semibold block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- No HP -->
                        <div class="space-y-1.5">
                            <label for="no_hp" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">No. Handphone / WhatsApp</label>
                            <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $tamu->no_hp) }}" required 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:bg-white rounded-xl text-sm font-semibold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all @error('no_hp') border-red-500 @enderror" placeholder="08xxxxxxxxxx">
                            @error('no_hp')
                                <span class="text-xs text-red-500 font-semibold block mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @error('foto')
                        <div class="p-3.5 bg-red-50 border border-red-100 rounded-xl text-red-600 text-xs font-semibold">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all shadow-md shadow-emerald-600/10 hover:shadow-lg active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Phone Verification Card (If not verified) -->
            @if(!$tamu->hasVerifiedPhone())
                <div class="bg-white rounded-3xl border border-blue-100 shadow-xl shadow-blue-900/5 overflow-hidden">
                    <div class="px-6 py-5 border-b border-blue-100 bg-blue-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 text-blue-600 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-base sm:text-lg">Verifikasi Nomor Telepon</h3>
                                <p class="text-xs text-slate-500">Masukkan 6 digit kode verifikasi OTP yang dikirim ke nomor HP Anda ({{ $tamu->no_hp }}).</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 space-y-4">
                        <form action="{{ route('verify.phone') }}" method="POST" class="flex flex-col sm:flex-row items-end gap-4">
                            @csrf
                            <div class="space-y-1.5 w-full">
                                <label for="code" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Kode Verification (6 Digit)</label>
                                <input type="text" name="code" id="code" maxlength="6" required placeholder="Contoh: 123456" 
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:bg-white rounded-xl text-center tracking-widest font-mono text-lg font-bold focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('code') border-red-500 @enderror">
                            </div>
                            <button type="submit" class="w-full sm:w-auto px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition-all shadow-md shadow-blue-600/10 active:scale-95 flex-shrink-0">
                                Verifikasi HP
                            </button>
                        </form>

                        <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
                            <span class="text-slate-400">Tidak menerima kode SMS?</span>
                            <form action="{{ route('verify.phone.resend') }}" method="POST">
                                @csrf
                                <button type="submit" class="font-bold text-blue-600 hover:text-blue-700 underline">Kirim Ulang Kode OTP</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Change Password Card -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-900/5 overflow-hidden">

                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-base sm:text-lg">Keamanan & Ubah Sandi</h3>
                        <p class="text-xs text-slate-400">Amankan akun Anda dengan mengganti kata sandi secara periodik.</p>
                    </div>
                </div>

                <form action="{{ route('profile.update-password') }}" method="POST" class="p-6 sm:p-8 space-y-6">
                    @csrf
                    
                    <!-- Current Password -->
                    <div class="space-y-1.5">
                        <label for="current_password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Kata Sandi Saat Ini</label>
                        <div class="relative">
                            <input type="password" id="current_password" name="current_password" required 
                                   class="w-full px-4 py-3 pr-12 bg-slate-50 border border-slate-200 focus:bg-white rounded-xl text-sm font-semibold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all @error('current_password') border-red-500 @enderror" placeholder="••••••••">
                            <button type="button" onclick="togglePwd('current_password', 'eye1o', 'eye1c')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-500 transition-colors focus:outline-none">
                                <svg id="eye1o" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg id="eye1c" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        @error('current_password')
                            <span class="text-xs text-red-500 font-semibold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- New Password -->
                        <div class="space-y-1.5">
                            <label for="new_password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Kata Sandi Baru</label>
                            <div class="relative">
                                <input type="password" id="new_password" name="new_password" required 
                                       class="w-full px-4 py-3 pr-12 bg-slate-50 border border-slate-200 focus:bg-white rounded-xl text-sm font-semibold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all @error('new_password') border-red-500 @enderror" placeholder="Min. 6 karakter">
                                <button type="button" onclick="togglePwd('new_password', 'eye2o', 'eye2c')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-500 transition-colors focus:outline-none">
                                    <svg id="eye2o" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg id="eye2c" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                            @error('new_password')
                                <span class="text-xs text-red-500 font-semibold block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- New Password Confirmation -->
                        <div class="space-y-1.5">
                            <label for="new_password_confirmation" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Ulangi Kata Sandi Baru</label>
                            <div class="relative">
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required 
                                       class="w-full px-4 py-3 pr-12 bg-slate-50 border border-slate-200 focus:bg-white rounded-xl text-sm font-semibold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all" placeholder="Ulangi kata sandi baru">
                                <button type="button" onclick="togglePwd('new_password_confirmation', 'eye3o', 'eye3c')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-500 transition-colors focus:outline-none">
                                    <svg id="eye3o" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg id="eye3c" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all shadow-md shadow-emerald-600/10 hover:shadow-lg active:scale-95">
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
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
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('foto-upload');
        const previewImg = document.getElementById('avatar-preview');
        const placeholderDiv = document.getElementById('avatar-placeholder');
        
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                    if (placeholderDiv) {
                        placeholderDiv.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    });

    function togglePwd(inputId, eyeOpenId, eyeClosedId) {
        const input = document.getElementById(inputId);
        const eyeOpen = document.getElementById(eyeOpenId);
        const eyeClosed = document.getElementById(eyeClosedId);
        if (input.type === 'password') {
            input.type = 'text';
            eyeClosed.classList.add('hidden');
            eyeOpen.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        }
    }
</script>
@endsection
