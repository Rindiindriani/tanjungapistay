@extends('layouts.app')

@section('title', 'Lupa Kata Sandi Pengelola - Tanjung Api')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 bg-slate-900 py-12 relative overflow-hidden">
    <div class="absolute w-[300px] h-[300px] rounded-full bg-emerald-500/10 blur-[100px] top-1/4 -left-12"></div>
    <div class="absolute w-[300px] h-[300px] rounded-full bg-emerald-500/10 blur-[100px] bottom-1/4 -right-12"></div>

    <div class="max-w-md w-full bg-slate-950/80 backdrop-blur-md rounded-3xl border border-slate-800 shadow-2xl p-8 space-y-6 relative z-10">
        <div class="text-center space-y-2">
            <span class="px-2.5 py-1 rounded bg-emerald-500/10 text-emerald-400 text-xs font-bold uppercase tracking-wider border border-emerald-500/20">Akses Internal</span>
            <h2 class="text-3xl font-extrabold text-white tracking-tight pt-2">Lupa Kata Sandi Admin</h2>
            <p class="text-slate-400 text-xs sm:text-sm">Masukkan email pengelola yang terdaftar untuk menerima link atur ulang kata sandi.</p>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-950/50 border border-emerald-900/60 text-emerald-300 text-xs font-semibold rounded-xl flex items-start gap-2">
                <svg class="w-5 h-5 flex-shrink-0 text-emerald-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-red-950/50 border border-red-900/60 text-red-400 text-xs font-semibold rounded-xl flex items-start gap-2">
                <svg class="w-5 h-5 flex-shrink-0 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.forgot-password.send') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Email Pengelola</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="admin@tanjungapi.com"
                    class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm font-semibold text-white placeholder-slate-600 focus:outline-none focus:border-emerald-500 transition-colors">
                @error('email')
                    <span class="text-xs text-red-400 font-medium mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all shadow-lg shadow-emerald-600/20 active:scale-95">
                Kirim Link Reset Admin
            </button>
        </form>

        <div class="text-center pt-4 border-t border-slate-800">
            <a href="{{ route('admin.login') }}" class="text-slate-400 hover:text-emerald-400 text-xs sm:text-sm font-bold transition-all">Kembali ke Login Pengelola</a>
        </div>
    </div>
</div>
@endsection
