<?php

// Mendefinisikan namespace controller agar dapat dikenali oleh Laravel
namespace App\Http\Controllers;

// Mengimpor model Penginapan yang digunakan untuk mengambil data unit penginapan
use App\Models\Penginapan;

// Mengimpor kelas Request dari Laravel untuk menangani input dari pengguna
use Illuminate\Http\Request;

// Mendefinisikan class HomeController yang menangani halaman-halaman publik (tidak perlu login)
class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (beranda) aplikasi beserta semua unit penginapan.
     */
    public function index()
    {
        // Mengambil semua data unit penginapan dari database, diurutkan terbaru
        $units = Penginapan::orderBy('id_unit', 'desc')->get();

        // Mengembalikan tampilan halaman utama (home) dengan data unit penginapan
        return view('home', compact('units'));
    }

    /**
     * Menampilkan halaman daftar semua unit penginapan dengan fitur filter berdasarkan tipe.
     */
    public function units(Request $request)
    {
        // Memulai query builder untuk tabel penginapan
        $query = Penginapan::query();

        // Mengecek apakah ada parameter filter 'tipe'
        if ($request->filled('tipe')) {
            $query->where('tipe_unit', $request->tipe);
        }

        // Mengeksekusi query dan mengambil data unit penginapan terbaru
        $units = $query->orderBy('id_unit', 'desc')->get();

        // Mengambil daftar seluruh tipe unit unik yang ada di database
        $allTypes = Penginapan::distinct()->pluck('tipe_unit')->toArray();

        // Mengembalikan tampilan halaman daftar unit penginapan
        return view('units.index', compact('units', 'allTypes'));
    }

    /**
     * Menampilkan halaman detail satu unit penginapan berdasarkan ID.
     */
    public function showUnit($id)
    {
        // Mencari data unit penginapan berdasarkan ID, jika tidak ditemukan tampilkan 404
        $unit = Penginapan::findOrFail($id);

        // Mengambil data booking_attempt dari sesi (diset saat tamu mencoba booking tanpa login)
        $bookingAttempt = session()->get('booking_attempt');

        // Menghapus data booking_attempt dari sesi agar hanya digunakan sekali (untuk redirect awal)
        session()->forget('booking_attempt');

        // Mengembalikan tampilan halaman detail unit penginapan beserta data booking attempt
        return view('units.show', compact('unit', 'bookingAttempt'));
    }

    /**
     * Menampilkan halaman tentang kami (about).
     */
    public function about()
    {
        // Mengembalikan tampilan halaman tentang kami
        return view('about');
    }

    /**
     * Menampilkan halaman kontak.
     */
    public function contact()
    {
        // Mengembalikan tampilan halaman kontak
        return view('contact');
    }

    /**
     * Memproses pesan yang dikirimkan melalui form kontak.
     */
    public function sendContactMessage(Request $request)
    {
        // Memvalidasi semua data yang dikirimkan melalui form kontak
        $request->validate([
            'nama'   => 'required|string|max:255',
            'email'  => 'required|email|max:255',
            'subjek' => 'required|string|max:255',
            'pesan'  => 'required|string|min:10', // Pesan minimal 10 karakter
        ], [
            // Pesan error validasi kustom dalam bahasa Indonesia
            'nama.required'   => 'Nama wajib diisi.',
            'email.required'  => 'Email wajib diisi.',
            'email.email'     => 'Format email tidak valid.',
            'subjek.required' => 'Subjek wajib diisi.',
            'pesan.required'  => 'Pesan wajib diisi.',
            'pesan.min'       => 'Pesan minimal berisi 10 karakter.',
        ]);

        // Mengarahkan kembali ke halaman kontak dengan pesan sukses setelah pesan berhasil "dikirim"
        // (Catatan: pesan belum benar-benar dikirim via email, hanya simulasi sukses)
        return redirect()->route('contact')->with('success', 'Pesan Anda berhasil dikirim! Kami akan menghubungi Anda segera.');
    }
}
