@extends('layouts.app')

@section('title', 'Lupa Kata Sandi - Tanjung Api')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 bg-slate-50 py-12">
    <div class="max-w-md w-full bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-900/5 p-8 space-y-6">
        <div class="text-center space-y-2">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Lupa Kata Sandi?</h2>
            <p class="text-slate-500 text-sm">Masukkan alamat email Anda untuk menerima instruksi reset kata sandi.</p>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold rounded-xl flex items-start gap-2">
                <svg class="w-5 h-5 flex-shrink-0 text-emerald-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-red-50 border border-red-200 text-red-700 text-xs font-semibold rounded-xl flex items-start gap-2">
                <svg class="w-5 h-5 flex-shrink-0 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('forgot-password.send') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Alamat Email Terdaftar</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="contoh@gmail.com"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-emerald-500 transition-colors">
                @error('email')
                    <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all shadow-md shadow-emerald-600/10 active:scale-95">
                Kirim Link Reset Kata Sandi
            </button>
        </form>

        <div class="text-center pt-4 border-t border-slate-100 text-xs sm:text-sm text-slate-500">
            Ingat kata sandi Anda? <a href="{{ route('login') }}" class="text-emerald-600 hover:underline font-bold transition-all">Kembali ke Halaman Masuk</a>
        </div>
    </div>
</div>
@endsection
