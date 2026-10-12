<?php

// Mendefinisikan namespace middleware agar dapat dikenali oleh Laravel
namespace App\Http\Middleware;

// Mengimpor kelas-kelas pendukung dari Laravel dan Symfony HTTP Foundation
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Mendefinisikan class TamuAuth untuk memverifikasi autentikasi pengguna/tamu
class TamuAuth
{
    /**
     * Memeriksa apakah permintaan (request) memiliki sesi login tamu yang valid.
     * Jika belum login, menyimpan data percobaan pemesanan dan URL yang dituju ke sesi,
     * lalu mengarahkan pengguna ke halaman login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Memeriksa apakah terdapat kunci 'tamu_id' dalam sesi yang sedang berlangsung
        if (!$request->session()->has('tamu_id')) {
            // Memeriksa apakah pengguna sedang mencoba melakukan pemesanan (mengirimkan id_unit)
            if ($request->has('id_unit')) {
                // Menyimpan data draf pemesanan ke dalam sesi agar tidak hilang setelah login
                $request->session()->put('booking_attempt', $request->only([
                    'id_unit', 'tanggal_checkin', 'tanggal_checkout', 'jumlah_kamar', 'jumlah_tamu'
                ]));
                
                // Menentukan URL halaman detail unit sebagai tujuan pengalihan setelah login berhasil
                $request->session()->put('url.intended', route('units.show', $request->input('id_unit')));
            } else {
                // Jika bukan percobaan pemesanan langsung, simpan URL saat ini atau URL sebelumnya
                if ($request->isMethod('get')) {
                    // Jika metode HTTP adalah GET, simpan URL lengkap saat ini
                    $request->session()->put('url.intended', $request->fullUrl());
                } else {
                    // Jika metode HTTP selain GET (POST/PUT/DELETE), simpan URL halaman sebelumnya
                    $request->session()->put('url.intended', url()->previous());
                }
            }

            // Mengarahkan pengguna ke halaman login dengan pesan peringatan
            return redirect()->route('login')->with('error', 'Silakan masuk atau daftar terlebih dahulu untuk melakukan pemesanan.');
        }

        // Memeriksa apakah data tamu di database masih ada dan email sudah terverifikasi
        $tamu = \App\Models\Tamu::find($request->session()->get('tamu_id'));
        if (!$tamu) {
            $request->session()->forget(['tamu_id', 'tamu_nama', 'tamu_email']);
            return redirect()->route('login')->with('error', 'Sesi login tidak valid.');
        }

        if ($tamu->isExpiredUnverified()) {
            $request->session()->forget(['tamu_id', 'tamu_nama', 'tamu_email']);
            $tamu->delete();
            return redirect()->route('login')->with('error', 'Batas waktu verifikasi email 3 hari telah berakhir. Akun Anda terhapus otomatis.');
        }

        if (!$tamu->hasVerifiedEmail()) {
            $request->session()->forget(['tamu_id', 'tamu_nama', 'tamu_email']);
            return redirect()->route('login')->with('error', 'Akun Anda belum memverifikasi email. Silakan periksa link verifikasi di Gmail Anda (' . $tamu->email . ') terlebih dahulu.');
        }

        // Jika sesi tamu terverifikasi, lanjutkan permintaan ke proses berikutnya
        return $next($request);

    }
}
