<?php

// Mendefinisikan namespace controller agar dapat dikenali oleh Laravel
namespace App\Http\Controllers;

// Mengimpor model Tamu dan Pengelola yang digunakan dalam proses autentikasi
use App\Models\Tamu;
use App\Models\Pengelola;
use App\Mail\VerifyEmailMail;
use App\Mail\ResetPasswordMail;
use App\Mail\AdminResetPasswordMail;

// Mengimpor kelas pendukung dari Laravel
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;




// Mendefinisikan class AuthController yang menangani autentikasi tamu dan admin
class AuthController extends Controller
{
    // === TAMU (GUEST) AUTHENTICATION ===

    /**
     * Menampilkan halaman form login untuk tamu.
     * Jika tamu sudah login, langsung diarahkan ke halaman utama.
     */
    public function showLogin()
    {
        // Mengecek apakah tamu sudah login (ada sesi tamu_id), jika iya langsung ke halaman utama
        if (session()->has('tamu_id')) {
            return redirect()->route('home');
        }

        // Menyimpan URL halaman sebelumnya sebagai intended URL (untuk redirect setelah login)
        // Hanya diset jika belum ada url.intended yang diset oleh middleware sebelumnya
        if (!session()->has('url.intended')) {
            $prevUrl = url()->previous();
            if ($prevUrl && !in_array($prevUrl, [
                route('login'),
                route('register'),
                route('logout'),
                route('admin.login'),
                route('admin.logout')
            ])) {
                // Menyimpan URL halaman asal ke sesi agar bisa diarahkan kembali setelah login
                session()->put('url.intended', $prevUrl);
            }
        }

        // Mengembalikan tampilan halaman login tamu
        return view('auth.login');
    }

    /**
     * Memproses permintaan login dari tamu.
     * Memvalidasi kredensial dan membuat sesi jika berhasil.
     */
    public function login(Request $request)
    {
        // Memvalidasi data email dan password yang dikirimkan dari form login
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // Mencari data tamu di database berdasarkan email yang dimasukkan
        $tamu = Tamu::where('email', $request->email)->first();

        // Memeriksa apakah tamu ditemukan dan password yang dimasukkan cocok
        if ($tamu && Hash::check($request->password, $tamu->password)) {
            // Pengecekan otomatis: Jika akun belum verifikasi email dan pendaftaran >3 hari yang lalu
            if ($tamu->isExpiredUnverified()) {
                $namaTamu = $tamu->nama;
                $tamu->delete(); // Hapus akun otomatis karena >3 hari
                return back()->withErrors([
                    'auth_failed' => "Akun atas nama {$namaTamu} telah terhapus otomatis karena tidak mengklik link verifikasi email dalam 3 hari. Silakan mendaftar kembali."
                ])->withInput();
            }

            // Pengecekan wajib: Jika email belum diverifikasi, TOLAK MASUK
            if (!$tamu->hasVerifiedEmail()) {
                return back()->withErrors([
                    'auth_failed' => 'Akun Anda belum diverifikasi. Silakan periksa pesan/spam di email Gmail Anda (' . $tamu->email . ') dan klik link verifikasi terlebih dahulu untuk masuk.'
                ])->withInput();
            }



            // Jika email sudah terverifikasi -> Buat sesi login tamu
            session([
                'tamu_id'    => $tamu->id_tamu,
                'tamu_nama'  => $tamu->nama,
                'tamu_email' => $tamu->email,
            ]);

            return redirect()->intended(route('home'))->with('success', 'Selamat datang kembali, ' . $tamu->nama . '!');
        }


        // Mengembalikan error jika email atau password salah
        return back()->withErrors(['auth_failed' => 'Email atau kata sandi Anda salah.'])->withInput();
    }

    /**
     * Menampilkan halaman form registrasi untuk tamu baru.
     * Jika tamu sudah login, langsung diarahkan ke halaman utama.
     */
    public function showRegister()
    {
        if (session()->has('tamu_id')) {
            return redirect()->route('home');
        }

        if (!session()->has('url.intended')) {
            $prevUrl = url()->previous();
            if ($prevUrl && !in_array($prevUrl, [
                route('login'),
                route('register'),
                route('logout'),
                route('admin.login'),
                route('admin.logout')
            ])) {
                session()->put('url.intended', $prevUrl);
            }
        }

        return view('auth.register');
    }

    /**
     * Memproses pendaftaran tamu baru.
     * Memvalidasi data, menyimpan akun tamu baru, dan mengirimkan email verifikasi.
     */
    public function register(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:50',
            'email'    => 'required|email:rfc,dns|unique:tamu,email',
            'no_hp'    => 'required|string|max:15',
            'password' => 'required|min:6|confirmed',
        ], [
            'nama.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email atau domain tidak aktif/valid (harus alamat email yang nyata).',
            'email.unique'       => 'Email sudah terdaftar.',
            'no_hp.required'     => 'Nomor telepon wajib diisi.',
            'password.required'  => 'Kata sandi wajib diisi.',
            'password.min'       => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);


        $emailToken = Str::random(64);

        // Verify email domain has MX record
        $domain = substr(strrchr($request->email, '@'), 1);
        if (!checkdnsrr($domain, 'MX')) {
            return back()->withErrors(['email' => 'Domain email tidak dapat menerima email (tidak ada record MX).'])->withInput();
        }

        $tamu = Tamu::create([
            'nama'                     => $request->nama,
            'email'                    => $request->email,
            'email_verification_token' => $emailToken,
            'no_hp'                    => $request->no_hp,
            'password'                 => Hash::make($request->password),
        ]);

        // Attempt to send verification email
        try {
            Mail::to($tamu->email)->send(new VerifyEmailMail($tamu));
        } catch (\Exception $e) {
            Log::error("Gagal mengirim email verifikasi ke {$tamu->email}: " . $e->getMessage());
            // Remove the created user to avoid orphan account
            $tamu->delete();
            return back()->withErrors(['email' => 'Gagal mengirim email verifikasi. Pastikan alamat email dapat menerima pesan.'])->withInput();
        }

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Link verifikasi telah dikirimkan ke email Gmail Anda (' . $tamu->email . '). Silakan klik link verifikasi dalam waktu 3 hari agar akun tidak terhapus otomatis.');
    }

    /**
     * Menampilkan halaman form lupa kata sandi tamu.
     */
    public function showForgotPassword()
    {
        if (session()->has('tamu_id')) {
            return redirect()->route('home');
        }
        return view('auth.forgot-password');
    }

    /**
     * Memproses pengiriman link reset kata sandi tamu.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $tamu = Tamu::where('email', $request->email)->first();

        if (!$tamu) {
            return back()->withErrors(['email' => 'Email tidak terdaftar pada akun tamu.'])->withInput();
        }

        $token = Str::random(64);
        $tamu->update([
            'reset_password_token'            => $token,
            'reset_password_token_created_at' => now(),
        ]);

        try {
            Mail::to($tamu->email)->send(new ResetPasswordMail($tamu));
            return back()->with('success', 'Link atur ulang kata sandi telah dikirimkan ke email Gmail Anda (' . $tamu->email . '). Silakan periksa inbox/spam Anda.');
        } catch (\Exception $e) {
            Log::error("Gagal mengirim email reset password tamu ke {$tamu->email}: " . $e->getMessage());
            return back()->with('error', 'Gagal mengirim email reset kata sandi. Pastikan konfigurasi email server aktif.')->withInput();
        }
    }

    /**
     * Menampilkan halaman form reset kata sandi tamu berdasarkan token.
     */
    public function showResetPassword($token)
    {
        $tamu = Tamu::where('reset_password_token', $token)->first();

        if (!$tamu) {
            return redirect()->route('forgot-password')->with('error', 'Link reset kata sandi tidak valid atau telah digunakan.');
        }

        if ($tamu->reset_password_token_created_at && $tamu->reset_password_token_created_at->addMinutes(60)->isPast()) {
            return redirect()->route('forgot-password')->with('error', 'Link reset kata sandi telah kadaluarsa. Silakan minta link baru.');
        }

        return view('auth.reset-password', compact('token', 'tamu'));
    }

    /**
     * Memproses pembaharuan kata sandi tamu dari form reset.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.required'  => 'Kata sandi baru wajib diisi.',
            'password.min'       => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $tamu = Tamu::where('reset_password_token', $request->token)->first();

        if (!$tamu) {
            return redirect()->route('forgot-password')->with('error', 'Link reset kata sandi tidak valid.');
        }

        if ($tamu->reset_password_token_created_at && $tamu->reset_password_token_created_at->addMinutes(60)->isPast()) {
            return redirect()->route('forgot-password')->with('error', 'Link reset kata sandi telah kadaluarsa. Silakan minta link baru.');
        }

        $tamu->update([
            'password'                        => Hash::make($request->password),
            'reset_password_token'            => null,
            'reset_password_token_created_at' => null,
        ]);

        return redirect()->route('login')->with('success', 'Kata sandi Anda berhasil diperbarui! Silakan masuk menggunakan kata sandi baru Anda.');
    }



    /**
     * Memproses verifikasi email saat tamu mengklik link di email.
     */
    public function verifyEmail($token)
    {
        $tamu = Tamu::where('email_verification_token', $token)->first();

        if (!$tamu) {
            return redirect()->route('login')->with('error', 'Link verifikasi tidak valid atau email sudah terverifikasi.');
        }

        // Cek jika sudah lebih dari 3 hari
        if ($tamu->isExpiredUnverified()) {
            $tamu->delete();
            return redirect()->route('login')->with('error', 'Batas waktu verifikasi email 3 hari telah berakhir. Akun Anda telah terhapus otomatis, silakan mendaftar kembali.');
        }

        // Tandai email sebagai terverifikasi
        $tamu->update([
            'email_verified_at'        => now(),
            'email_verification_token' => null,
        ]);

        return redirect()->route('login')->with('success', 'Selamat! Email Anda (' . $tamu->email . ') berhasil diverifikasi. Silakan masuk.');
    }

    /**
     * Kirim ulang link verifikasi email.
     */
    public function resendVerification(Request $request)
    {
        $tamu = null;

        if (session()->has('tamu_id')) {
            $tamu = Tamu::find(session('tamu_id'));
        } elseif ($request->filled('email')) {
            $tamu = Tamu::where('email', $request->email)->first();
        }

        if (!$tamu) {
            return back()->with('error', 'Akun tidak ditemukan. Silakan mendaftar terlebih dahulu.');
        }

        if ($tamu->hasVerifiedEmail()) {
            return back()->with('info', 'Email Anda (' . $tamu->email . ') sudah terverifikasi. Silakan masuk.');
        }

        if ($tamu->isExpiredUnverified()) {
            session()->forget(['tamu_id', 'tamu_nama', 'tamu_email']);
            $tamu->delete();
            return redirect()->route('login')->with('error', 'Batas waktu verifikasi email 3 hari telah berakhir. Akun Anda telah terhapus otomatis, silakan mendaftar kembali.');
        }

        // Buat token baru & kirim ulang
        $tamu->update([
            'email_verification_token' => Str::random(64),
        ]);

        try {
            Mail::to($tamu->email)->send(new VerifyEmailMail($tamu));
            return back()->with('success', 'Link verifikasi email baru telah dikirim ke ' . $tamu->email . '. Harap periksa kotak masuk/spam Gmail Anda.');
        } catch (\Exception $e) {
            Log::error("Gagal mengirim ulang email verifikasi: " . $e->getMessage());
            return back()->with('error', 'Gagal mengirim email verifikasi. Pastikan konfigurasi mail aktif.');
        }
    }


    

    

    

    /**
     * Menghapus sesi login tamu (logout).
     */
    public function logoutTamu()
    {
        session()->forget(['tamu_id', 'tamu_nama', 'tamu_email']);
        return redirect()->route('home')->with('success', 'Anda telah keluar.');
    }


    // === PENGELOLA (ADMIN) AUTHENTICATION ===

    /**
     * Menampilkan halaman form login untuk admin/pengelola.
     * Jika admin sudah login, langsung diarahkan ke dashboard admin.
     */
    public function showAdminLogin()
    {
        // Mengecek apakah admin sudah login (ada sesi admin_id), jika iya langsung ke dashboard
        if (session()->has('admin_id')) {
            return redirect()->route('admin.dashboard');
        }

        // Mengembalikan tampilan halaman login admin
        return view('admin.login');
    }

    /**
     * Memproses permintaan login dari admin/pengelola.
     * Memvalidasi kredensial dan membuat sesi admin jika berhasil.
     */
    public function adminLogin(Request $request)
    {
        // Memvalidasi data email dan password yang dikirimkan dari form login admin
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            // Pesan error validasi kustom dalam bahasa Indonesia
            'email.required'    => 'Email pengelola wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // Mencari data admin di database berdasarkan email yang dimasukkan
        $admin = Pengelola::where('email', $request->email)->first();

        // Memeriksa apakah admin ditemukan dan password yang dimasukkan cocok dengan hash di database
        if ($admin && Hash::check($request->password, $admin->password)) {
            // Membuat sesi login admin dengan menyimpan ID, nama, dan email ke sesi
            session([
                'admin_id'    => $admin->id_pengelola,
                'admin_nama'  => $admin->nama_pengelola,
                'admin_email' => $admin->email,
            ]);
            // Mengarahkan ke dashboard admin setelah login berhasil
            return redirect()->route('admin.dashboard')->with('success', 'Login pengelola berhasil.');
        }

        // Mengembalikan error jika email atau password admin salah
        return back()->withErrors(['auth_failed' => 'Email atau kata sandi pengelola salah.'])->withInput();
    }

    /**
     * Menampilkan halaman form lupa kata sandi admin/pengelola.
     */
    public function showAdminForgotPassword()
    {
        if (session()->has('admin_id')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.forgot-password');
    }

    /**
     * Memproses pengiriman link reset kata sandi admin/pengelola.
     */
    public function sendAdminResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email pengelola wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $admin = Pengelola::where('email', $request->email)->first();

        if (!$admin) {
            return back()->withErrors(['email' => 'Email pengelola tidak ditemukan di sistem.'])->withInput();
        }

        $token = Str::random(64);
        $admin->update([
            'reset_password_token'            => $token,
            'reset_password_token_created_at' => now(),
        ]);

        try {
            Mail::to($admin->email)->send(new AdminResetPasswordMail($admin));
            return back()->with('success', 'Link atur ulang kata sandi pengelola telah dikirimkan ke email (' . $admin->email . '). Silakan periksa inbox/spam Anda.');
        } catch (\Exception $e) {
            Log::error("Gagal mengirim email reset password admin ke {$admin->email}: " . $e->getMessage());
            return back()->with('error', 'Gagal mengirim email reset kata sandi. Pastikan konfigurasi email server aktif.')->withInput();
        }
    }

    /**
     * Menampilkan halaman form reset kata sandi admin/pengelola berdasarkan token.
     */
    public function showAdminResetPassword($token)
    {
        $admin = Pengelola::where('reset_password_token', $token)->first();

        if (!$admin) {
            return redirect()->route('admin.forgot-password')->with('error', 'Link reset kata sandi tidak valid atau telah digunakan.');
        }

        if ($admin->reset_password_token_created_at && $admin->reset_password_token_created_at->addMinutes(60)->isPast()) {
            return redirect()->route('admin.forgot-password')->with('error', 'Link reset kata sandi telah kadaluarsa. Silakan minta link baru.');
        }

        return view('admin.reset-password', compact('token', 'admin'));
    }

    /**
     * Memproses pembaharuan kata sandi admin/pengelola dari form reset.
     */
    public function adminResetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.required'  => 'Kata sandi baru wajib diisi.',
            'password.min'       => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $admin = Pengelola::where('reset_password_token', $request->token)->first();

        if (!$admin) {
            return redirect()->route('admin.forgot-password')->with('error', 'Link reset kata sandi tidak valid.');
        }

        if ($admin->reset_password_token_created_at && $admin->reset_password_token_created_at->addMinutes(60)->isPast()) {
            return redirect()->route('admin.forgot-password')->with('error', 'Link reset kata sandi telah kadaluarsa. Silakan minta link baru.');
        }

        $admin->update([
            'password'                        => Hash::make($request->password),
            'reset_password_token'            => null,
            'reset_password_token_created_at' => null,
        ]);

        return redirect()->route('admin.login')->with('success', 'Kata sandi pengelola berhasil diperbarui! Silakan masuk dengan kata sandi baru Anda.');
    }

    /**
     * Menghapus sesi login admin (logout).
     */
    public function logoutAdmin()
    {
        // Menghapus semua data sesi admin dari session storage
        session()->forget(['admin_id', 'admin_nama', 'admin_email']);

        // Mengarahkan ke halaman login admin dengan pesan sukses setelah logout
        return redirect()->route('admin.login')->with('success', 'Berhasil keluar dari panel pengelola.');
    }

    // === TAMU PROFILE MANAGEMENT ===

    /**
     * Show Tamu profile page.
     * Menampilkan halaman profil tamu yang sedang login.
     */
    public function showProfile()
    {
        // Mengambil data tamu berdasarkan ID yang tersimpan di sesi login
        $tamu = Tamu::findOrFail(session('tamu_id'));

        // Mengembalikan tampilan halaman profil tamu
        return view('auth.profile', compact('tamu'));
    }

    /**
     * Update Tamu profile info.
     * Memperbarui informasi profil tamu (nama, email, no HP, jenis kelamin, foto).
     */
    public function updateProfile(Request $request)
    {
        // Mengambil ID tamu dari sesi yang sedang login
        $tamuId = session('tamu_id');

        // Mencari data tamu berdasarkan ID sesi
        $tamu = Tamu::findOrFail($tamuId);

        // Memvalidasi semua data profil baru yang dikirimkan dari form edit profil
        $request->validate([
            'nama'            => 'required|string|max:50',
            'kewarganegaraan' => 'required|in:WNI,WNA',
            'no_ktp'          => 'nullable|string|max:20',
            'no_paspor'       => 'nullable|string|max:50',
            'provinsi'        => 'nullable|string|max:100',
            'kabupaten_kota'  => 'nullable|string|max:100',
            'kecamatan'       => 'nullable|string|max:100',
            'negara'          => 'nullable|string|max:100',
            'alamat_ktp'      => 'nullable|string|max:255',
            // Memastikan email unik kecuali untuk tamu yang sedang diedit (ignore diri sendiri)
            'email'           => 'required|email|unique:tamu,email,' . $tamuId . ',id_tamu',
            'no_hp'           => 'required|string|max:15',
            'jenis_kelamin'   => 'nullable|string|in:Laki-laki,Perempuan',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            // Pesan error validasi kustom dalam bahasa Indonesia
            'nama.required'       => 'Nama lengkap wajib diisi.',
            'nama.max'            => 'Nama lengkap maksimal 50 karakter.',
            'email.required'      => 'Email wajib diisi.',
            'email.email'         => 'Format email tidak valid.',
            'email.unique'        => 'Email sudah digunakan oleh akun lain.',
            'no_hp.required'      => 'Nomor HP wajib diisi.',
            'jenis_kelamin.in'    => 'Jenis kelamin tidak valid.',
            'foto.image'          => 'Berkas harus berupa gambar.',
            'foto.mimes'          => 'Format gambar yang diperbolehkan adalah jpeg, png, jpg, gif.',
            'foto.max'            => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Menggunakan nama foto lama sebagai nilai default jika tidak ada foto baru yang diunggah
        $fotoName = $tamu->foto;

        // Memeriksa apakah ada file foto profil baru yang diunggah
        if ($request->hasFile('foto')) {
            // Menghapus foto profil lama dari server jika ada dan ditemukan di penyimpanan
            if ($tamu->foto && \Illuminate\Support\Facades\File::exists(public_path('images/profiles/' . $tamu->foto))) {
                \Illuminate\Support\Facades\File::delete(public_path('images/profiles/' . $tamu->foto));
            }

            $file = $request->file('foto');
            // Membuat nama file foto yang unik menggunakan timestamp dan uniqid
            $fotoName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Memastikan direktori tujuan penyimpanan foto profil sudah ada, jika belum buat otomatis
            $destinationPath = public_path('images/profiles');
            if (!\Illuminate\Support\Facades\File::exists($destinationPath)) {
                \Illuminate\Support\Facades\File::makeDirectory($destinationPath, 0755, true);
            }

            // Memindahkan file foto baru ke direktori penyimpanan foto profil
            $file->move($destinationPath, $fotoName);
        }

        // Memperbarui data profil tamu di database dengan data yang baru
        $tamu->update([
            'nama'            => $request->nama,
            'kewarganegaraan' => $request->kewarganegaraan,
            'no_ktp'          => $request->no_ktp,
            'no_paspor'       => $request->no_paspor,
            'provinsi'        => $request->provinsi,
            'kabupaten_kota'  => $request->kabupaten_kota,
            'kecamatan'       => $request->kecamatan,
            'negara'          => $request->negara,
            'alamat_ktp'      => $request->alamat_ktp,
            'email'           => $request->email,
            'no_hp'           => $request->no_hp,
            'jenis_kelamin'   => $request->jenis_kelamin,
            'foto'            => $fotoName,
        ]);

        // Memperbarui informasi tamu yang tersimpan di sesi agar tampilan navbar ikut ter-update
        session([
            'tamu_nama'  => $tamu->nama,
            'tamu_email' => $tamu->email,
        ]);

        // Mengarahkan kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Update Tamu password.
     * Memperbarui kata sandi tamu setelah memverifikasi kata sandi lama.
     */
    public function updatePassword(Request $request)
    {
        // Mengambil ID tamu dari sesi yang sedang login
        $tamuId = session('tamu_id');

        // Mencari data tamu berdasarkan ID sesi
        $tamu = Tamu::findOrFail($tamuId);

        // Memvalidasi input kata sandi lama dan kata sandi baru dari form ganti password
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed',
        ], [
            // Pesan error validasi kustom dalam bahasa Indonesia
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'new_password.required'     => 'Kata sandi baru wajib diisi.',
            'new_password.min'          => 'Kata sandi baru minimal 6 karakter.',
            'new_password.confirmed'    => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // Memverifikasi kata sandi lama yang dimasukkan dengan yang tersimpan di database (menggunakan Hash)
        if (!Hash::check($request->current_password, $tamu->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.']);
        }

        // Memperbarui kata sandi tamu di database dengan enkripsi Hash baru
        $tamu->update([
            'password' => Hash::make($request->new_password),
        ]);

        // Mengarahkan kembali ke halaman sebelumnya dengan pesan sukses ganti password
        return back()->with('success', 'Kata sandi Anda berhasil diperbarui.');
    }
}
