<?php

// Mengimpor kelas Route dari Laravel untuk mendefinisikan semua rute aplikasi
use Illuminate\Support\Facades\Route;

// Mengimpor semua controller yang digunakan dalam rute-rute di bawah ini
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;

// ─── WEBHOOK MIDTRANS ────────────────────────────────────────────────────────
// Rute khusus untuk menerima notifikasi server-to-server dari Midtrans
// Diletakkan di luar middleware CSRF karena dipanggil langsung oleh server Midtrans (bukan browser)
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');

// ─── RUTE PUBLIK ─────────────────────────────────────────────────────────────
// Rute-rute ini dapat diakses oleh siapa saja tanpa perlu login

// Halaman utama (beranda) aplikasi
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman daftar semua unit penginapan (bisa difilter berdasarkan tipe via query string)
Route::get('/units', [HomeController::class, 'units'])->name('units.index');

// Halaman detail satu unit penginapan berdasarkan ID
Route::get('/units/{id}', [HomeController::class, 'showUnit'])->name('units.show');

// Halaman tentang kami
Route::get('/tentang', [HomeController::class, 'about'])->name('about');

// Halaman formulir kontak (GET untuk tampilkan form)
Route::get('/kontak', [HomeController::class, 'contact'])->name('contact');

// Memproses pesan yang dikirim melalui form kontak (POST untuk proses kiriman form)
Route::post('/kontak', [HomeController::class, 'sendContactMessage'])->name('contact.send');

// Endpoint AJAX untuk mengecek ketersediaan unit penginapan (dipanggil via JavaScript di halaman booking)
Route::post('/bookings/check-availability', [BookingController::class, 'checkAvailability'])->name('bookings.check');
Route::post('/units/check-availability', [BookingController::class, 'checkAvailability'])->name('units.check-availability');

// Endpoint AJAX untuk mendapatkan daftar tanggal yang sudah penuh (untuk kalender Flatpickr)
// Checkout date TIDAK diblokir karena checkout jam 12:00 dan check-in berikutnya bisa jam 14:00
Route::get('/units/booked-dates', [BookingController::class, 'getBookedDates'])->name('units.booked-dates');


// ─── RUTE AUTENTIKASI TAMU ───────────────────────────────────────────────────
// Rute untuk proses login dan registrasi tamu

// Halaman form login tamu
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// Memproses permintaan login tamu
Route::post('/login', [AuthController::class, 'login']);

// Halaman form registrasi tamu baru
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// Memproses permintaan registrasi tamu baru
Route::post('/register', [AuthController::class, 'register']);

// Halaman form lupa kata sandi tamu
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');

// Memproses permintaan kirim link reset kata sandi tamu
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('forgot-password.send');

// Halaman form atur ulang kata sandi tamu dengan token
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('reset-password');

// Memproses pembaruan kata sandi tamu yang di-reset
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password.update');

// Memproses verifikasi email dari link di Gmail
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verify.email');

// Memproses kirim ulang email verifikasi
Route::post('/resend-verification', [AuthController::class, 'resendVerification'])->name('verify.resend');

// Memproses verifikasi kode telepon
Route::post('/verify-phone', [AuthController::class, 'verifyPhone'])->name('verify.phone');

// Memproses kirim ulang kode telepon
Route::post('/resend-phone-code', [AuthController::class, 'resendPhoneCode'])->name('verify.phone.resend');

// Memproses permintaan logout tamu
Route::post('/logout', [AuthController::class, 'logoutTamu'])->name('logout');


// ─── RUTE AUTENTIKASI ADMIN ──────────────────────────────────────────────────
// Rute untuk proses login dan logout admin/pengelola

// Halaman form login admin/pengelola
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');

// Memproses permintaan login admin/pengelola
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

// Halaman form lupa kata sandi admin/pengelola
Route::get('/admin/forgot-password', [AuthController::class, 'showAdminForgotPassword'])->name('admin.forgot-password');

// Memproses permintaan kirim link reset kata sandi admin/pengelola
Route::post('/admin/forgot-password', [AuthController::class, 'sendAdminResetLink'])->name('admin.forgot-password.send');

// Halaman form atur ulang kata sandi admin/pengelola dengan token
Route::get('/admin/reset-password/{token}', [AuthController::class, 'showAdminResetPassword'])->name('admin.reset-password');

// Memproses pembaruan kata sandi admin/pengelola yang di-reset
Route::post('/admin/reset-password', [AuthController::class, 'adminResetPassword'])->name('admin.reset-password.update');

// Memproses permintaan logout admin/pengelola
Route::post('/admin/logout', [AuthController::class, 'logoutAdmin'])->name('admin.logout');

// ─── RUTE TAMU YANG SUDAH LOGIN ──────────────────────────────────────────────
// Rute-rute berikut hanya bisa diakses jika tamu sudah login
// Middleware 'tamu.auth' akan mengecek apakah ada sesi tamu_id yang aktif
Route::middleware('tamu.auth')->group(function () {

    // Halaman daftar semua reservasi milik tamu yang sedang login
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');

    // Memproses pembuatan reservasi baru oleh tamu
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

    // Halaman detail satu reservasi beserta invoice pembayaran
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');

    // Memproses pembatalan reservasi oleh tamu (0% refund / hangus)
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Memproses penundaan reservasi oleh tamu (diberi tenggat 1 bulan)
    Route::post('/bookings/{id}/postpone', [BookingController::class, 'postpone'])->name('bookings.postpone');

    // Memproses penjadwalan ulang (reschedule) oleh tamu
    Route::post('/bookings/{id}/reschedule', [BookingController::class, 'reschedule'])->name('bookings.reschedule');

    // Melanjutkan kembali reservasi yang sudah dibatalkan (selama admin belum proses refund)
    Route::post('/bookings/{id}/resume', [BookingController::class, 'resumeBooking'])->name('bookings.resume');




    // ── Rute Pembayaran ──────────────────────────────────────────────────────

    // Callback dari Midtrans setelah proses pembayaran selesai (Midtrans mengarahkan tamu ke sini)
    Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');

    // Mengarahkan tamu ke halaman pembayaran Midtrans (Snap Redirect)
    Route::get('/payment/{id_reservasi}', [PaymentController::class, 'showSandbox'])->name('payment.sandbox');

    // Menampilkan halaman simulator pembayaran lokal (fallback jika Midtrans tidak bisa diakses)
    Route::get('/payment/{id_reservasi}/simulator', [PaymentController::class, 'showSimulator'])->name('payment.simulator');

    // Memproses simulasi pembayaran lokal
    Route::post('/payment/{id_reservasi}/simulator', [PaymentController::class, 'processSimulator'])->name('payment.simulator.process');

    // ── Rute Profil Tamu ─────────────────────────────────────────────────────

    // Halaman profil tamu yang sedang login
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');

    // Memproses pembaruan data profil tamu (nama, email, no HP, foto, dll)
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Memproses pembaruan kata sandi tamu
    Route::post('/profile/update-password', [AuthController::class, 'updatePassword'])->name('profile.update-password');
});

// ─── RUTE ADMIN YANG SUDAH LOGIN ─────────────────────────────────────────────
// Rute-rute berikut hanya bisa diakses jika admin/pengelola sudah login
// Middleware 'admin.auth' mengecek apakah ada sesi admin_id yang aktif
// Semua URL di-prefix dengan '/admin' dan nama rute di-prefix dengan 'admin.'
Route::middleware('admin.auth')->prefix('admin')->name('admin.')->group(function () {

    // Halaman dashboard admin dengan statistik dan grafik
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // ── Manajemen Unit Penginapan (CRUD) ─────────────────────────────────────

    // Halaman daftar semua unit penginapan
    Route::get('/units', [AdminController::class, 'listUnits'])->name('units.index');

    // Halaman form tambah unit penginapan baru
    Route::get('/units/create', [AdminController::class, 'createUnit'])->name('units.create');

    // Memproses penyimpanan unit penginapan baru ke database
    Route::post('/units', [AdminController::class, 'storeUnit'])->name('units.store');

    // Halaman form edit unit penginapan berdasarkan ID
    Route::get('/units/{id}/edit', [AdminController::class, 'editUnit'])->name('units.edit');

    // Memproses pembaruan data unit penginapan (menggunakan method PUT)
    Route::put('/units/{id}', [AdminController::class, 'updateUnit'])->name('units.update');

    // Memproses penghapusan unit penginapan dari database (menggunakan method DELETE)
    Route::delete('/units/{id}', [AdminController::class, 'destroyUnit'])->name('units.destroy');

    // Memproses penghapusan satu foto galeri unit penginapan
    Route::delete('/units/foto/{id}', [AdminController::class, 'deleteFotoGaleri'])->name('units.foto.destroy');

    // ── Manajemen Pemesanan (Bookings) ───────────────────────────────────────

    // Halaman daftar semua reservasi dari semua tamu
    Route::get('/bookings', [AdminController::class, 'listBookings'])->name('bookings.index');

    // Halaman detail satu reservasi tertentu (dilihat oleh admin)
    Route::get('/bookings/{id}', [AdminController::class, 'showBooking'])->name('bookings.show');

    // Memproses konfirmasi reservasi secara manual oleh admin (tanpa payment gateway)
    Route::post('/bookings/{id}/confirm', [AdminController::class, 'confirmBooking'])->name('bookings.confirm');

    // Memproses pembatalan reservasi oleh admin
    Route::post('/bookings/{id}/cancel', [AdminController::class, 'cancelBooking'])->name('bookings.cancel');

    // Memproses penjadwalan ulang (reschedule) reservasi yang ditunda oleh admin
    Route::post('/bookings/{id}/reschedule', [AdminController::class, 'rescheduleBooking'])->name('bookings.reschedule');

    // Memproses konfirmasi transfer pengembalian dana (refund) selesai oleh admin
    Route::post('/bookings/{id}/confirm-refund', [AdminController::class, 'confirmRefund'])->name('bookings.confirm-refund');

    // Memproses konfirmasi pelunasan sisa DP 50% oleh admin
    Route::post('/bookings/{id}/confirm-pelunasan', [AdminController::class, 'confirmPelunasan'])->name('bookings.confirm-pelunasan');

    // Memproses checkout manual tamu oleh admin (mengubah status confirmed → completed)
    Route::post('/bookings/{id}/checkout', [AdminController::class, 'checkoutBooking'])->name('bookings.checkout');


    // Memproses pengiriman notifikasi manual / pengingat check-in via Email & WhatsApp
    Route::post('/bookings/{id}/notify', [AdminController::class, 'sendManualNotification'])->name('bookings.notify');




    // ── Reservasi Walk-in (Tamu Datang Langsung) ─────────────────────────────

    // Halaman form reservasi walk-in (tamu datang langsung ke tempat)
    Route::get('/walkin', [AdminController::class, 'walkinIndex'])->name('walkin.index');

    // Endpoint AJAX untuk mengecek ketersediaan unit dari form walk-in
    Route::post('/walkin/check-availability', [AdminController::class, 'walkinCheckAvailability'])->name('walkin.check');

    // Memproses penyimpanan reservasi walk-in ke database
    Route::post('/walkin', [AdminController::class, 'walkinStore'])->name('walkin.store');

    // Callback dari Midtrans setelah admin menyelesaikan pembayaran Transfer Bank via walk-in
    Route::get('/payment/walkin/finish', [PaymentController::class, 'walkinFinish'])->name('payment.walkin.finish');

    // ── Manajemen Data Tamu ──────────────────────────────────────────────────

    // Halaman daftar semua tamu yang terdaftar di sistem
    Route::get('/guests', [AdminController::class, 'listGuests'])->name('guests.index');

    // Menghapus satu data akun tamu
    Route::delete('/guests/{id}', [AdminController::class, 'destroyGuest'])->name('guests.destroy');

    // Membersihkan seluruh data akun tamu yang terdaftar
    Route::post('/guests/clean-all', [AdminController::class, 'destroyAllGuests'])->name('guests.clean-all');

    // ── Laporan Keuangan ─────────────────────────────────────────────────────

    // Halaman laporan keuangan dengan filter tanggal
    Route::get('/reports', [AdminController::class, 'reportIndex'])->name('reports.index');

    // Halaman laporan keuangan dalam tampilan siap cetak (print view)
    Route::get('/reports/print', [AdminController::class, 'reportPrint'])->name('reports.print');

    // ── Profil Admin ─────────────────────────────────────────────────────────

    // Halaman profil admin yang sedang login
    Route::get('/profile', [AdminController::class, 'showProfile'])->name('profile');

    // Memproses pembaruan data profil admin (nama dan email)
    Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');

    // Memproses pembaruan kata sandi admin
    Route::post('/profile/update-password', [AdminController::class, 'updatePassword'])->name('profile.update-password');
});

// ─── RUTE HELPER PEMBERSIH CACHE HOSTING (TANPA TERMINAL) ─────────────────────
// Rute darurat untuk membersihkan cache Laravel via browser jika hosting tidak memiliki Terminal SSH
Route::get('/clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    return '<div style="font-family:sans-serif; text-align:center; padding:50px;">
                <h1 style="color:#059669;">✅ Berhasil Membersihkan Cache Laravel!</h1>
                <p style="color:#4b5563;">Config, Cache, View, dan Route cache di hosting telah dibersihkan.</p>
                <a href="' . url('/') . '" style="display:inline-block; margin-top:15px; padding:10px 20px; background:#059669; color:#fff; text-decoration:none; border-radius:8px; font-weight:bold;">Kembali ke Beranda</a>
            </div>';
});
