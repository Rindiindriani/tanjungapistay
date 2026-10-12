@extends('layouts.app')

@section('title', 'Login Pengelola - Tanjung Api')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 bg-slate-900 py-12 relative overflow-hidden">
    <!-- Soft green glow effects in background -->
    <div class="absolute w-[300px] h-[300px] rounded-full bg-emerald-500/10 blur-[100px] top-1/4 -left-12"></div>
    <div class="absolute w-[300px] h-[300px] rounded-full bg-emerald-500/10 blur-[100px] bottom-1/4 -right-12"></div>

    <div class="max-w-md w-full bg-slate-950/80 backdrop-blur-md rounded-3xl border border-slate-800 shadow-2xl p-8 space-y-6 relative z-10">
        <div class="text-center space-y-2">
            <span class="px-2.5 py-1 rounded bg-emerald-500/10 text-emerald-400 text-xs font-bold uppercase tracking-wider border border-emerald-500/20">Akses Internal</span>
            <h2 class="text-3xl font-extrabold text-white tracking-tight pt-2">Panel Pengelola</h2>
            <p class="text-slate-400 text-xs sm:text-sm">Silakan masukkan kredensial pengelola Anda untuk mengelola reservasi.</p>
        </div>

        @if($errors->has('auth_failed'))
            <div class="flex items-center gap-2 p-3.5 bg-red-950/40 border border-red-900/50 text-red-400 text-xs font-semibold rounded-xl">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span>{{ $errors->first('auth_failed') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Email Pengelola</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="admin@tanjungapi.com"
                    class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm font-semibold text-white placeholder-slate-600 focus:outline-none focus:border-emerald-500 transition-colors">
                @error('email')
                    <span class="text-xs text-red-400 font-medium mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label for="password" class="block text-xs font-bold text-slate-400 uppercase">Kata Sandi</label>
                    <a href="{{ route('admin.forgot-password') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition-colors">Lupa Kata Sandi?</a>
                </div>
                <div class="relative">
                    <input type="password" name="password" id="password" required placeholder="••••••••"
                        class="w-full px-4 py-3 pr-12 bg-slate-900 border border-slate-800 rounded-xl text-sm font-semibold text-white placeholder-slate-600 focus:outline-none focus:border-emerald-500 transition-colors">
                    <button type="button" id="togglePassword"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-emerald-400 transition-colors focus:outline-none"
                        onclick="togglePasswordVisibility()">
                        {{-- Eye Open --}}
                        <svg id="eyeOpen" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{-- Eye Closed --}}
                        <svg id="eyeClosed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="text-xs text-red-400 font-medium mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all shadow-lg shadow-emerald-600/20 active:scale-95">
                Masuk Sistem
            </button>
        </form>

        <div class="text-center pt-4 border-t border-slate-800">
            <a href="{{ route('login') }}" class="text-slate-500 hover:text-emerald-400 text-xs sm:text-sm font-bold transition-all">Kembali ke Halaman Tamu</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function togglePasswordVisibility() {
    const input = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

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
