<?php

// Mendefinisikan namespace middleware agar dapat dikenali oleh Laravel
namespace App\Http\Middleware;

// Mengimpor kelas-kelas pendukung dari Laravel dan Symfony HTTP Foundation
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Mendefinisikan class AdminAuth untuk memverifikasi autentikasi pengelola/admin
class AdminAuth
{
    /**
     * Memeriksa apakah permintaan (request) memiliki sesi login admin yang valid.
     * Jika admin belum login, pengguna akan diarahkan ke halaman login admin dengan pesan error.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Memeriksa apakah terdapat kunci 'admin_id' dalam sesi yang sedang berlangsung
        if (!$request->session()->has('admin_id')) {
            // Jika sesi admin tidak ditemukan, arahkan ke halaman login admin
            return redirect()->route('admin.login')->with('error', 'Akses dibatasi. Silakan masuk sebagai pengelola.');
        }

        // Jika sesi admin terverifikasi, lanjutkan permintaan ke proses berikutnya
        return $next($request);
    }
}
