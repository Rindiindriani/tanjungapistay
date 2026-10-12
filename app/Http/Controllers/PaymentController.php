<?php

// Mendefinisikan namespace controller agar dapat dikenali oleh Laravel
namespace App\Http\Controllers;

// Mengimpor model-model yang diperlukan dalam proses pembayaran
use App\Models\Reservasi;
use App\Models\Pembayaran;
use App\Models\DetailPembayaran;

// Mengimpor kelas pendukung dari Laravel dan library Midtrans
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\NotificationService;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;


// Mendefinisikan class PaymentController yang menangani semua proses pembayaran via Midtrans
class PaymentController extends Controller
{
    /**
     * Configure Midtrans SDK settings.
     * Mengkonfigurasi pengaturan SDK Midtrans dari file konfigurasi aplikasi.
     */
    private function configureMidtrans(): void
    {
        // Mengatur server key Midtrans dari konfigurasi (config/midtrans.php)
        Config::$serverKey    = config('midtrans.server_key');
        // Menentukan apakah menggunakan mode produksi atau sandbox (testing)
        Config::$isProduction = config('midtrans.is_production');
        // Mengaktifkan sanitasi input untuk keamanan
        Config::$isSanitized  = true;
        // Mengaktifkan verifikasi 3D Secure untuk kartu kredit
        Config::$is3ds        = true;
    }

    /**
     * Create a Midtrans Snap Token and redirect user to the
     * Midtrans-hosted payment page (Snap Redirect / Web Payment Link).
     * Membuat token pembayaran Midtrans dan mengarahkan tamu ke halaman pembayaran Midtrans.
     */
    public function showSandbox($id_reservasi)
    {
        // Mengambil ID tamu dari sesi yang sedang login
        $tamuId  = session('tamu_id');

        // Mencari data reservasi milik tamu ini beserta relasi penginapan, pembayaran, dan tamu
        $booking = Reservasi::with(['penginapan', 'pembayaran', 'tamu'])
            ->where('id_tamu', $tamuId)
            ->where('id_reservasi', $id_reservasi)
            ->firstOrFail(); // Jika tidak ditemukan, tampilkan 404

        // Mengambil data pembayaran yang terkait dengan reservasi ini
        $pembayaran = $booking->pembayaran;

        // Memeriksa apakah pembayaran sudah dilunasi sebelumnya, jika iya tidak perlu bayar lagi
        if ($pembayaran->status_pembayaran === 'lunas') {
            return redirect()->route('bookings.show', $id_reservasi)
                ->with('success', 'Pembayaran untuk reservasi ini sudah lunas.');
        }

        // Mengkonfigurasi Midtrans SDK dengan kredensial dari file konfigurasi
        $this->configureMidtrans();

        // Menghitung nominal yang harus dibayar saat ini (DP 50% atau Full 100%)
        $amountToPay = ($pembayaran->jenis_pembayaran === 'dp') ? (int) $pembayaran->nominal_dp : (int) $pembayaran->total_pembayaran;
        $itemName    = ($pembayaran->jenis_pembayaran === 'dp') ? 'DP 50% - ' . $booking->penginapan->nama_unit : $booking->penginapan->nama_unit;

        // Membuat Order ID unik per percobaan pembayaran menggunakan timestamp untuk menghindari duplikasi
        // Format: TA-XXXX-timestamp (TA = Tamu/Tamu, XXXX = ID reservasi dipad menjadi 4 digit)
        $orderId = 'TA-' . str_pad($booking->id_reservasi, 4, '0', STR_PAD_LEFT) . '-' . time();

        // Menyiapkan parameter transaksi yang akan dikirim ke Midtrans API
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $amountToPay, // Nominal pembayaran yang ditagihkan saat ini
            ],
            'customer_details' => [
                // Data identitas tamu untuk ditampilkan di halaman Midtrans
                'first_name' => $booking->tamu->nama,
                'email'      => $booking->tamu->email,
                'phone'      => $booking->tamu->no_hp ?? '', // Nomor HP opsional
            ],
            'item_details' => [
                [
                    'id'       => 'UNIT-' . $booking->id_unit,
                    'price'    => $amountToPay,                       // Harga ditagihkan saat ini
                    'quantity' => 1,                                   // Item paket reservasi
                    'name'     => $itemName,                           // Nama unit & keterangan DP/Full
                ],
            ],
            'callbacks' => [
                // URL yang akan dipanggil Midtrans setelah proses pembayaran selesai (berhasil/gagal)
                'finish' => route('payment.finish'),
            ],
        ];


        try {
            // Mengirim permintaan ke Midtrans API untuk mendapatkan Snap Token pembayaran
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            // Mencatat error ke log jika gagal mendapatkan Snap Token dari Midtrans
            Log::error('Midtrans Snap Error: ' . $e->getMessage());
            // Mengembalikan error ke halaman sebelumnya dengan pesan gagal
            return back()->withErrors([
                'payment' => 'Gagal membuat sesi pembayaran: ' . $e->getMessage(),
            ]);
        }

        // Menentukan URL redirect halaman pembayaran Midtrans berdasarkan mode (production atau sandbox)
        $snapRedirectUrl = config('midtrans.is_production')
            ? "https://app.midtrans.com/snap/v2/vtweb/{$snapToken}"
            : "https://app.sandbox.midtrans.com/snap/v2/vtweb/{$snapToken}";

        // Mengarahkan tamu ke halaman pembayaran Midtrans (full page redirect tanpa popup)
        return redirect()->away($snapRedirectUrl);
    }

    /**
     * Tampilkan halaman simulator pembayaran lokal (fallback jika Midtrans tidak bisa diakses).
     * Digunakan sebagai alternatif ketika Midtrans tidak tersedia (misalnya saat testing offline).
     */
    public function showSimulator($id_reservasi)
    {
        // Mengambil ID tamu dari sesi yang sedang login
        $tamuId  = session('tamu_id');

        // Mencari data reservasi milik tamu ini beserta relasi penginapan, pembayaran, dan tamu
        $booking = Reservasi::with(['penginapan', 'pembayaran', 'tamu'])
            ->where('id_tamu', $tamuId)
            ->where('id_reservasi', $id_reservasi)
            ->firstOrFail();

        // Mengambil data pembayaran yang terkait
        $pembayaran = $booking->pembayaran;

        // Memeriksa apakah pembayaran sudah dilunasi, jika iya arahkan ke halaman detail booking
        if ($pembayaran->status_pembayaran === 'lunas') {
            return redirect()->route('bookings.show', $id_reservasi)
                ->with('success', 'Pembayaran untuk reservasi ini sudah lunas.');
        }

        // Mengembalikan tampilan halaman simulator pembayaran lokal
        return view('payment.simulator', compact('booking', 'pembayaran'));
    }

    /**
     * Proses simulasi pembayaran lokal (tanpa koneksi ke Midtrans).
     * Mensimulasikan konfirmasi pembayaran secara lokal tanpa melalui Midtrans.
     */
    public function processSimulator(Request $request, $id_reservasi)
    {
        // Mengambil ID tamu dari sesi yang sedang login
        $tamuId  = session('tamu_id');

        // Mencari data reservasi milik tamu ini untuk diproses pembayarannya
        $booking = Reservasi::with(['penginapan', 'pembayaran', 'tamu'])
            ->where('id_tamu', $tamuId)
            ->where('id_reservasi', $id_reservasi)
            ->firstOrFail();

        // Mengambil data pembayaran yang terkait
        $pembayaran = $booking->pembayaran;

        // Mengecek apakah pembayaran sudah lunas sebelum diproses ulang
        if ($pembayaran->status_pembayaran === 'lunas') {
            return redirect()->route('bookings.show', $id_reservasi)
                ->with('success', 'Pembayaran sudah lunas.');
        }

        // Mengambil metode pembayaran yang dipilih dari form simulator (default: transfer_bank)
        $metode = $request->input('metode', 'transfer_bank');

        // Menandai pembayaran dan reservasi sebagai lunas menggunakan helper method markPaid
        $this->markPaid($booking, $pembayaran, $metode);

        // Mengarahkan ke halaman detail reservasi dengan pesan sukses setelah pembayaran dikonfirmasi
        return redirect()->route('bookings.show', $id_reservasi)
            ->with('success', 'Pembayaran berhasil dikonfirmasi! Reservasi Anda sekarang AKTIF.');
    }

    /**
     * Handle finish redirect from Midtrans after payment (tamu online).
     * Midtrans redirects here with: order_id, transaction_status, payment_type.
     * Menangani callback redirect dari Midtrans setelah tamu menyelesaikan proses pembayaran.
     */
    public function finish(Request $request)
    {
        // Mengambil parameter dari query string yang dikirimkan oleh Midtrans setelah pembayaran
        $orderId           = $request->order_id;
        $transactionStatus = $request->transaction_status;
        $paymentType       = $request->payment_type ?? 'other'; // Metode pembayaran yang digunakan

        // Mengekstrak ID reservasi dari Order ID dengan format: TA-XXXX-timestamp
        preg_match('/TA-(\d+)-/', $orderId ?? '', $matches);
        $reservasiId = isset($matches[1]) ? (int) $matches[1] : null;

        // Mengembalikan error jika ID reservasi tidak berhasil diekstrak dari Order ID
        if (!$reservasiId) {
            return redirect()->route('bookings.index')
                ->withErrors(['payment' => 'Sesi pembayaran tidak valid.']);
        }

        // Mencari data reservasi beserta pembayarannya berdasarkan ID yang diekstrak
        $booking    = Reservasi::with('pembayaran')->find($reservasiId);
        $pembayaran = $booking?->pembayaran;

        // Mengembalikan error jika reservasi atau data pembayaran tidak ditemukan
        if (!$booking || !$pembayaran) {
            return redirect()->route('bookings.index')
                ->withErrors(['payment' => 'Reservasi tidak ditemukan.']);
        }

        // Memproses status transaksi yang diterima dari Midtrans
        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            // Transaksi berhasil: tandai pembayaran sebagai lunas dan reservasi sebagai confirmed
            $this->markPaid($booking, $pembayaran, $paymentType);
            return redirect()->route('bookings.show', $reservasiId)
                ->with('success', 'Pembayaran berhasil dikonfirmasi! Reservasi Anda sekarang AKTIF.');
        } elseif ($transactionStatus === 'pending') {
            // Transaksi masih pending: notifikasi akan datang via webhook ketika selesai
            return redirect()->route('bookings.show', $reservasiId)
                ->with('info', 'Pembayaran Anda sedang diproses. Status akan diperbarui otomatis.');
        } else {
            // Transaksi gagal atau dibatalkan: informasikan ke tamu untuk mencoba lagi
            return redirect()->route('bookings.show', $reservasiId)
                ->withErrors(['payment' => 'Pembayaran gagal atau dibatalkan. Silakan coba lagi.']);
        }
    }

    /**
     * Handle finish redirect from Midtrans after walkin (Transfer Bank) payment.
     * Admin is redirected here after completing payment via Midtrans Snap.
     * Menangani callback redirect dari Midtrans setelah admin menyelesaikan pembayaran walk-in via Transfer Bank.
     */
    public function walkinFinish(Request $request)
    {
        // Mengambil parameter dari query string yang dikirimkan oleh Midtrans setelah pembayaran walk-in
        $orderId           = $request->order_id;
        $transactionStatus = $request->transaction_status;
        $paymentType       = $request->payment_type ?? 'other';

        // Mengekstrak ID reservasi dari Order ID dengan format walk-in: WI-XXXX-timestamp
        preg_match('/WI-(\d+)-/', $orderId ?? '', $matches);
        $reservasiId = isset($matches[1]) ? (int) $matches[1] : null;

        // Mengembalikan error jika ID reservasi tidak berhasil diekstrak dari Order ID
        if (!$reservasiId) {
            return redirect()->route('admin.bookings.index')
                ->withErrors(['payment' => 'Sesi pembayaran tidak valid.']);
        }

        // Mencari data reservasi beserta pembayarannya berdasarkan ID yang diekstrak
        $booking    = Reservasi::with('pembayaran')->find($reservasiId);
        $pembayaran = $booking?->pembayaran;

        // Mengembalikan error jika reservasi atau data pembayaran tidak ditemukan
        if (!$booking || !$pembayaran) {
            return redirect()->route('admin.bookings.index')
                ->withErrors(['payment' => 'Reservasi tidak ditemukan.']);
        }

        // Memproses status transaksi yang diterima dari Midtrans untuk pembayaran walk-in
        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            // Transaksi berhasil: tandai pembayaran sebagai lunas dan konfirmasi reservasi walk-in
            $this->markPaid($booking, $pembayaran, $paymentType);
            return redirect()->route('admin.bookings.show', $reservasiId)
                ->with('success', 'Pembayaran transfer berhasil dikonfirmasi via Midtrans! Reservasi AKTIF.');
        } elseif ($transactionStatus === 'pending') {
            // Transaksi masih pending: notifikasi webhook akan datang ketika selesai
            return redirect()->route('admin.bookings.show', $reservasiId)
                ->with('info', 'Pembayaran sedang diproses Midtrans. Status akan diperbarui otomatis.');
        } else {
            // Transaksi gagal atau dibatalkan: informasikan ke admin untuk mencoba lagi
            return redirect()->route('admin.bookings.show', $reservasiId)
                ->withErrors(['payment' => 'Pembayaran gagal atau dibatalkan. Silakan coba lagi.']);
        }
    }

    /**
     * Handle Midtrans server-to-server notification webhook.
     * Handles both TA- (tamu online) and WI- (walkin) order IDs.
     * Menangani notifikasi webhook dari server Midtrans untuk memperbarui status pembayaran secara otomatis.
     */
    public function notification(Request $request)
    {
        // Mengkonfigurasi Midtrans SDK sebelum memproses notifikasi
        $this->configureMidtrans();

        try {
            // Membuat objek Notification dari Midtrans SDK untuk membaca data notifikasi yang masuk
            $notification = new Notification();

            // Mengambil data-data penting dari notifikasi Midtrans
            $orderId           = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus       = $notification->fraud_status;
            $paymentType       = $notification->payment_type;

            // Mencatat informasi notifikasi ke log untuk keperluan debugging dan audit
            Log::info('Midtrans Notification', compact('orderId', 'transactionStatus', 'fraudStatus', 'paymentType'));

            // Mengekstrak ID reservasi dari Order ID - mendukung format TA- (tamu) dan WI- (walkin)
            if (preg_match('/TA-(\d+)-/', $orderId ?? '', $matches)) {
                // Format Order ID untuk tamu online: TA-XXXX-timestamp
                $reservasiId = (int) $matches[1];
            } elseif (preg_match('/WI-(\d+)-/', $orderId ?? '', $matches)) {
                // Format Order ID untuk walk-in: WI-XXXX-timestamp
                $reservasiId = (int) $matches[1];
            } else {
                // Format Order ID tidak dikenali
                $reservasiId = null;
            }

            // Mengembalikan respons 404 jika ID reservasi tidak berhasil diekstrak
            if (!$reservasiId) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Mencari data reservasi beserta pembayarannya berdasarkan ID yang diekstrak
            $booking    = Reservasi::with('pembayaran')->find($reservasiId);
            $pembayaran = $booking?->pembayaran;

            // Mengembalikan respons 404 jika reservasi atau data pembayaran tidak ditemukan
            if (!$booking || !$pembayaran) {
                return response()->json(['message' => 'Booking not found'], 404);
            }

            // Memproses berbagai status transaksi yang mungkin diterima dari Midtrans
            if ($transactionStatus === 'capture') {
                // Status 'capture' hanya berhasil jika fraud status adalah 'accept'
                if ($fraudStatus === 'accept') {
                    $this->markPaid($booking, $pembayaran, $paymentType);
                }
            } elseif ($transactionStatus === 'settlement') {
                // Status 'settlement' berarti pembayaran sudah berhasil dikonfirmasi oleh bank
                $this->markPaid($booking, $pembayaran, $paymentType);
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                // Status gagal/dibatalkan/kedaluwarsa: kembalikan status pembayaran dan reservasi ke pending
                $pembayaran->update(['status_pembayaran' => 'belum lunas']);
                $booking->update(['status' => 'pending']);
            }

            // Mengembalikan respons sukses ke server Midtrans setelah notifikasi berhasil diproses
            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            // Mencatat error ke log jika terjadi kesalahan saat memproses notifikasi
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Helper: mark booking & payment as paid and confirmed.
     * Fungsi pembantu untuk menandai pembayaran sebagai lunas dan reservasi sebagai dikonfirmasi.
     */
    private function markPaid($booking, $pembayaran, ?string $paymentType = null): void
    {
        // Mengecek apakah pembayaran sudah lunas sebelumnya untuk mencegah pemrosesan duplikat
        if ($pembayaran->status_pembayaran === 'lunas') {
            return; // Keluar dari fungsi jika sudah lunas
        }

        // Peta konversi dari kode tipe pembayaran Midtrans ke nama yang lebih mudah dibaca
        $metodeMap = [
            'bank_transfer' => 'Transfer Bank',
            'credit_card'   => 'Kartu Kredit',
            'gopay'         => 'GoPay',
            'qris'          => 'QRIS',
            'shopeepay'     => 'ShopeePay',
            'echannel'      => 'Mandiri Bill',
            'cstore'        => 'Gerai (Alfamart/Indomaret)',
            'other_va'      => 'Virtual Account',
            'akulaku'       => 'Akulaku',
        ];

        // Menggunakan tipe pembayaran 'other' sebagai default jika tidak disediakan
        $paymentType = $paymentType ?? 'other';

        // Mengkonversi kode tipe pembayaran ke label yang lebih mudah dibaca, tambahkan keterangan '(Midtrans)'
        $metodeLabel = ($metodeMap[$paymentType] ?? ucfirst(str_replace('_', ' ', $paymentType))) . ' (Midtrans)';

        // Memperbarui data pembayaran: menandai sebagai lunas dengan tanggal dan metode pembayaran
        $pembayaran->update([
            'tanggal_pembayaran' => now(),          // Tanggal saat pembayaran dikonfirmasi
            'metode_pembayaran'  => $metodeLabel,   // Label metode pembayaran yang digunakan
            'status_pembayaran'  => 'lunas',        // Mengubah status menjadi lunas
        ]);

        // Memperbarui status reservasi menjadi 'confirmed' setelah pembayaran berhasil
        $booking->update(['status' => 'confirmed']);

        // Menyimpan atau memperbarui detail pembayaran ke tabel detail_pembayaran
        // Menggunakan updateOrCreate agar tidak membuat duplikasi jika notifikasi datang dua kali
        DetailPembayaran::updateOrCreate(
            ['id_pembayaran' => $pembayaran->id_pembayaran], // Kunci pencarian: berdasarkan ID pembayaran
            [
                'id_gateway'        => 1,                           // ID gateway Midtrans di database
                'jenis_pembayaran'  => $paymentType,                // Jenis/tipe pembayaran dari Midtrans
                'jumlah_pembayaran' => $pembayaran->total_pembayaran, // Total yang dibayarkan
                'tanggal_detail'    => now(),                       // Tanggal pencatatan detail pembayaran
            ]
        );

        // Kirim notifikasi otomatis (Email & WhatsApp) setelah pembayaran berhasil
        NotificationService::sendBookingStatusNotification($booking, 'Pembayaran Anda telah berhasil dikonfirmasi! Reservasi Anda sekarang AKTIF.');
    }
}
