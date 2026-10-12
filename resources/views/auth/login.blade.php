@extends('layouts.app')

@section('title', 'Masuk - Tanjung Api')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 bg-slate-50 py-12">
    <div class="max-w-md w-full bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-900/5 p-8 space-y-6">
        <div class="text-center space-y-2">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Selamat Datang</h2>
            <p class="text-slate-500 text-sm">Masuk ke akun tamu Anda untuk memesan unit.</p>
        </div>



        @if($errors->has('auth_failed'))
            <div class="space-y-2">
                <div class="flex items-center gap-2 p-3.5 bg-red-50 border border-red-100 text-red-700 text-xs font-semibold rounded-xl">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span>{{ $errors->first('auth_failed') }}</span>
                </div>
                @if(str_contains($errors->first('auth_failed'), 'belum diverifikasi') && old('email'))
                    <form action="{{ route('verify.resend') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ old('email') }}">
                        <button type="submit" class="w-full text-center py-2 px-3 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 text-xs font-bold rounded-xl transition-all">
                            📧 Kirim Ulang Link Verifikasi Ke {{ old('email') }}
                        </button>
                    </form>
                @endif
            </div>
        @endif



        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Alamat Email</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="Alamat Email"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-emerald-500 transition-colors">
                @error('email')
                    <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label for="password" class="block text-xs font-bold text-slate-600 uppercase">Kata Sandi</label>
                    <a href="{{ route('forgot-password') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition-colors">Lupa Kata Sandi?</a>
                </div>
                <div class="relative">
                    <input type="password" name="password" id="password" required placeholder="Kata Sandi"
                        class="w-full px-4 py-3 pr-12 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-emerald-500 transition-colors">
                    <button type="button" onclick="togglePassword('password', 'eye-login')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-600 transition-colors p-1 rounded-lg">
                        <svg id="eye-login" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all shadow-md shadow-emerald-600/10 active:scale-95">
                Masuk Sekarang
            </button>
        </form>

        <div class="text-center pt-4 border-t border-slate-100 text-xs sm:text-sm text-slate-500 space-y-2">
            <div>Belum memiliki akun? <a href="{{ route('register') }}" class="text-emerald-600 hover:underline font-bold transition-all">Daftar Akun Baru</a></div>
            <div class="pt-2"><a href="{{ route('admin.login') }}" class="text-slate-400 hover:text-emerald-600 font-semibold transition-colors">Masuk sebagai Pengelola (Admin)</a></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    const isHidden = input.type === 'password';

    input.type = isHidden ? 'text' : 'password';

    // Ganti icon: password terlihat (text) => Mata Nyala (Open Eye) | password tersembunyi => Mata Coret (Slash Eye)
    icon.innerHTML = isHidden
        ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`
        : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
}
</script>
@endsection
