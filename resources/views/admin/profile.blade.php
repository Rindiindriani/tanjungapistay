@extends('layouts.admin')

@section('title', 'Profil Pengelola - Tanjung Api')
@section('header_title', 'Profil Pengelola')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header Page title -->
    <div class="flex flex-col gap-1">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pengaturan Profil & Keamanan</h1>
        <p class="text-sm text-slate-500">Kelola informasi profil akun Anda dan ubah kata sandi secara berkala untuk menjaga keamanan panel.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card Column (Left) -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 text-center flex flex-col items-center">
                <!-- Avatar image wrapper -->
                <div class="relative group mb-4">
                    <img src="{{ asset('images/logo.jpg') }}" class="w-24 h-24 rounded-full object-cover border-4 border-emerald-500 shadow-md" style="width: 96px; height: 96px; border-radius: 50%; object-fit: cover;" alt="Avatar">
                    <div class="absolute inset-0 bg-emerald-950/20 rounded-full blur-sm -z-10 group-hover:scale-105 transition-all"></div>
                </div>

                <!-- Info -->
                <h3 class="font-bold text-slate-900 text-lg leading-snug">{{ $admin->nama_pengelola }}</h3>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 mt-2 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/60 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Administrator
                </span>

                <div class="w-full border-t border-slate-100 my-5"></div>

                <!-- Admin details -->
                <div class="w-full space-y-3.5 text-left text-sm">
                    <div class="flex items-center gap-3 text-slate-600">
                        <svg class="w-4.5 h-4.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="truncate" title="ID Pengelola: {{ $admin->id_pengelola }}">ID: <strong>#{{ $admin->id_pengelola }}</strong></span>
                    </div>
                    <div class="flex items-center gap-3 text-slate-600">
                        <svg class="w-4.5 h-4.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="truncate" title="{{ $admin->email }}">{{ $admin->email }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Fields Column (Right) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Edit Profile Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-900 text-sm sm:text-base flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Informasi Profil
                    </h3>
                </div>
                <form action="{{ route('admin.profile.update') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="nama_pengelola" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Nama Akun (Profil)</label>
                            <input type="text" id="nama_pengelola" name="nama_pengelola" value="{{ old('nama_pengelola', $admin->nama_pengelola) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-medium @error('nama_pengelola') border-red-500 @enderror" placeholder="Nama Profil">
                            @error('nama_pengelola')
                                <span class="text-xs text-red-600 block mt-1 font-semibold">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label for="email" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Email Pengelola</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $admin->email) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-medium @error('email') border-red-500 @enderror" placeholder="Email Pengelola">
                            @error('email')
                                <span class="text-xs text-red-600 block mt-1 font-semibold">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-end pt-2">
                        <button type="submit" class="transition-all transform hover:scale-[1.01] active:scale-[0.99] bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold py-2.5 px-6 rounded-xl shadow-md shadow-emerald-500/10 text-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-900 text-sm sm:text-base flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Ubah Kata Sandi (Password)
                    </h3>
                </div>
                <form action="{{ route('admin.profile.update-password') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label for="current_password" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Kata Sandi Saat Ini</label>
                            <input type="password" id="current_password" name="current_password" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all @error('current_password') border-red-500 @enderror" placeholder="••••••••" required>
                            @error('current_password')
                                <span class="text-xs text-red-600 block mt-1 font-semibold">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label for="new_password" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Kata Sandi Baru</label>
                                <input type="password" id="new_password" name="new_password" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all @error('new_password') border-red-500 @enderror" placeholder="Min. 6 karakter" required>
                                @error('new_password')
                                    <span class="text-xs text-red-600 block mt-1 font-semibold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label for="new_password_confirmation" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" placeholder="Ulangi kata sandi baru" required>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end pt-2">
                        <button type="submit" class="transition-all transform hover:scale-[1.01] active:scale-[0.99] bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold py-2.5 px-6 rounded-xl shadow-md shadow-emerald-500/10 text-sm">
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
