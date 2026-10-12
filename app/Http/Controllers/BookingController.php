<?php

// Mendefinisikan namespace controller agar dapat dikenali oleh Laravel
namespace App\Http\Controllers;

// Mengimpor model-model yang digunakan dalam proses pemesanan (booking)
use App\Models\Penginapan;
use App\Models\Reservasi;
use App\Models\Pembayaran;

// Mengimpor kelas pendukung dari Laravel dan library Carbon untuk pengelolaan tanggal
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\NotificationService;


// Mendefinisikan class BookingController yang menangani proses pemesanan oleh tamu
class BookingController extends Controller
{
    /**
     * Check lodging availability for date range.
     * Memeriksa ketersediaan unit penginapan untuk rentang tanggal yang diminta (dipanggil via AJAX).
     */
    public function checkAvailability(Request $request)
    {
        // Jika durasi_malam dikirimkan dan tanggal_checkout kosong/belum sesuai, hitung otomatis
        if ($request->filled('durasi_malam') && $request->filled('tanggal_checkin') && (!$request->filled('tanggal_checkout') || $request->tanggal_checkout <= $request->tanggal_checkin)) {
            $request->merge([
                'tanggal_checkout' => \Carbon\Carbon::parse($request->tanggal_checkin)->addDays((int) $request->durasi_malam)->toDateString()
            ]);
        }

        // Memvalidasi input dari AJAX request untuk pengecekan ketersediaan unit
        $request->validate([
            'id_unit'          => 'required|exists:penginapan,id_unit',
            'tanggal_checkin'  => 'required|date|after_or_equal:today',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
            'jumlah_kamar'     => 'required|integer|min:1',
        ]);

        $id_unit   = $request->id_unit;
        $checkin   = $request->tanggal_checkin;
        $checkout  = $request->tanggal_checkout;
        $requested = $request->jumlah_kamar;

        $available = $this->getAvailableRooms($id_unit, $checkin, $checkout);

        // Kebijakan waktu: checkout maks 12:00, check-in mulai 14:00
        $infoWaktu = ' (Check-in mulai 14:00 | Checkout maks 12:00)';

        if ($requested <= $available) {
            return response()->json([
                'success'   => true,
                'available' => $available,
                'message'   => "\u2705 Unit tersedia! Sisa unit: $available unit.$infoWaktu"
            ]);
        }

        $detailPesan = $available > 0
            ? "Tersisa $available unit (jumlah yang diminta: $requested)."
            : "Semua unit sudah dipesan.";

        return response()->json([
            'success'   => false,
            'available' => $available,
            'message'   => "Unit penuh pada rentang tanggal ini. $detailPesan Coba tanggal lain."
        ]);
    }

    /**
     * Helper to calculate available rooms.
     * Menghitung unit tersedia dengan logika:
     * - Overlap: A.checkin < B.checkout AND A.checkout > B.checkin
     * - Tanggal checkout = tanggal checkin baru TIDAK dianggap konflik (checkout jam 12:00, checkin jam 14:00)
     * - Hanya menghitung reservasi aktif: pending, confirmed, postponed (bukan cancelled/completed)
     */
    private function getAvailableRooms($id_unit, $checkin, $checkout)
    {
        $unit     = Penginapan::findOrFail($id_unit);
        $maxUnits = $unit->jumlah_unit;

        // Hitung unit yang sudah dipesan pada rentang tanggal [checkin, checkout)
        // Aturan overlap: A.checkout > B.checkin (STRICT) → tanggal sama tidak konflik
        // sehingga tamu baru bisa checkin di hari yang sama dengan checkout tamu lain
        $bookedUnits = Reservasi::where('id_unit', $id_unit)
            ->whereIn('status', ['pending', 'confirmed', 'postponed']) // hanya booking aktif
            ->where(function ($query) use ($checkin, $checkout) {
                // Overlap standar: [A.checkin, A.checkout) ∩ [B.checkin, B.checkout) ≠ ∅
                // Kondisi: A.checkin < B.checkout AND A.checkout > B.checkin
                $query->where('tanggal_checkin', '<', $checkout)
                      ->where('tanggal_checkout', '>', $checkin);
            })
            ->sum('jumlah_kamar');

        return max(0, $maxUnits - $bookedUnits);
    }

    /**
     * Mengembalikan daftar rentang tanggal yang sudah penuh (semua unit terpakai)
     * untuk unit tertentu. Digunakan Flatpickr untuk disable tanggal penuh di kalender.
     */
    public function getBookedDates(Request $request)
    {
        $request->validate([
            'id_unit'      => 'required|exists:penginapan,id_unit',
            'jumlah_kamar' => 'nullable|integer|min:1',
        ]);

        $id_unit      = $request->id_unit;
        $unit         = Penginapan::findOrFail($id_unit);
        $maxUnits     = $unit->jumlah_unit;
        $requested    = $request->input('jumlah_kamar', 1);

        // Ambil semua booking aktif untuk unit ini mulai dari hari ini
        $activeBookings = Reservasi::where('id_unit', $id_unit)
            ->whereIn('status', ['pending', 'confirmed', 'postponed'])
            ->where('tanggal_checkout', '>', now()->toDateString())
            ->get(['tanggal_checkin', 'tanggal_checkout', 'jumlah_kamar']);

        // Hitung semua tanggal yang sudah penuh (booked units >= maxUnits - requested + 1)
        $fullyBookedRanges = [];
        foreach ($activeBookings as $booking) {
            $start = $booking->tanggal_checkin;
            $end   = $booking->tanggal_checkout; // checkout date = available for new checkin

            // Periksa setiap hari dalam rentang booking ini
            $current = Carbon::parse($start);
            $endDate = Carbon::parse($end); // exclusive: checkout day is free for new checkin

            while ($current->lt($endDate)) {
                $dateStr = $current->toDateString();
                if (!isset($fullyBookedRanges[$dateStr])) {
                    $fullyBookedRanges[$dateStr] = 0;
                }
                $fullyBookedRanges[$dateStr] += $booking->jumlah_kamar;
                $current->addDay();
            }
        }

        // Filter hanya tanggal yang benar-benar penuh (tidak cukup untuk requested)
        $disabledDates = [];
        foreach ($fullyBookedRanges as $date => $booked) {
            if (($maxUnits - $booked) < $requested) {
                $disabledDates[] = $date;
            }
        }

        return response()->json([
            'disabled_dates' => $disabledDates,
            'checkout_time'  => '12:00',
            'checkin_time'   => '14:00',
            'info'           => 'Tanggal checkout unit lain tersedia untuk check-in baru (checkout 12:00 / check-in 14:00)',
        ]);
    }

    /**
     * Store guest reservation.
     * Menyimpan reservasi baru yang dibuat oleh tamu dan membuat data pembayaran awal.
     */
    public function store(Request $request)
    {
        // Jika durasi_malam dikirimkan dan tanggal_checkout kosong/belum sesuai, hitung otomatis
        if ($request->filled('durasi_malam') && $request->filled('tanggal_checkin') && (!$request->filled('tanggal_checkout') || $request->tanggal_checkout <= $request->tanggal_checkin)) {
            $request->merge([
                'tanggal_checkout' => \Carbon\Carbon::parse($request->tanggal_checkin)->addDays((int) $request->durasi_malam)->toDateString()
            ]);
        }

        // Memvalidasi semua data pemesanan yang dikirimkan dari form booking tamu
        $request->validate([
            'id_unit'          => 'required|exists:penginapan,id_unit',
            'tanggal_checkin'  => 'required|date|after_or_equal:today',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
            'jumlah_kamar'     => 'required|integer|min:1',
            'jumlah_tamu'      => 'required|integer|min:1',
            'jenis_pembayaran' => 'nullable|in:full,dp',
        ], [
            // Pesan error validasi kustom dalam bahasa Indonesia
            'tanggal_checkin.after_or_equal' => 'Tanggal check-in harus hari ini atau setelahnya.',
            'tanggal_checkout.after'         => 'Tanggal check-out harus setelah tanggal check-in.',
            'jumlah_kamar.min'               => 'Jumlah unit dipesan minimal 1.',
            'jumlah_tamu.min'                => 'Jumlah tamu minimal 1.',
        ]);

        // Mengambil data unit penginapan yang dipilih tamu
        $unit      = Penginapan::findOrFail($request->id_unit);
        $checkin   = $request->tanggal_checkin;
        $checkout  = $request->tanggal_checkout;
        $requested = $request->jumlah_kamar;

        // Validasi batas kapasitas maksimal 6 orang per unit untuk tipe Villa
        if (strtolower($unit->tipe_unit) === 'villa') {
            $maxAllowedGuests = 6 * $requested;
            if ($request->jumlah_tamu > $maxAllowedGuests) {
                return back()->withErrors([
                    'jumlah_tamu' => "Untuk unit tipe Villa, kapasitas maksimal adalah 6 orang per unit (Maksimal {$maxAllowedGuests} orang untuk {$requested} unit)."
                ])->withInput();
            }
        }

        // Memverifikasi ulang ketersediaan unit sebelum membuat reservasi untuk mencegah overbooking
        $available = $this->getAvailableRooms($unit->id_unit, $checkin, $checkout);
        if ($requested > $available) {
            // Mengembalikan error jika unit tidak cukup tersedia
            return back()->withErrors(['ketersediaan' => "Kamar tidak mencukupi untuk rentang tanggal tersebut. Tersisa: $available unit."])->withInput();
        }


        // Membuat data reservasi baru di database dengan status 'pending' (menunggu pembayaran)
        $reservasi = Reservasi::create([
            'id_tamu'          => session('tamu_id'), // Mengambil ID tamu dari sesi yang sedang login
            'id_unit'          => $unit->id_unit,
            'jumlah_kamar'     => $requested,
            'tanggal_checkin'  => $checkin,
            'tanggal_checkout' => $checkout,
            'jumlah_tamu'      => $request->jumlah_tamu,
            'status'           => 'pending', // Status pending karena belum ada pembayaran
        ]);

        // Menghitung jumlah malam menginap berdasarkan selisih tanggal check-in dan check-out
        $ciDate = Carbon::parse($checkin);
        $coDate = Carbon::parse($checkout);
        $nights = $ciDate->diffInDays($coDate);
        if ($nights <= 0) $nights = 1; // Minimal 1 malam

        // Menghitung total harga yang harus dibayar: harga per malam × jumlah unit × jumlah malam
        $totalPay = $unit->harga * $requested * $nights;
        $jenisPembayaran = $request->input('jenis_pembayaran', 'full');

        if ($jenisPembayaran === 'dp') {
            $nominalDp = $totalPay * 0.50;
            $sisaBayar = $totalPay - $nominalDp;
            $statusPelunasan = 'belum_lunas';
        } else {
            $jenisPembayaran = 'full';
            $nominalDp = $totalPay;
            $sisaBayar = 0;
            $statusPelunasan = 'lunas';
        }

        // Membuat data pembayaran awal dengan status 'belum lunas' untuk reservasi yang baru dibuat
        Pembayaran::create([
            'id_reservasi'       => $reservasi->id_reservasi,
            'tanggal_pembayaran' => null,           // Belum ada tanggal pembayaran karena belum dibayar
            'total_pembayaran'   => $totalPay,
            'jenis_pembayaran'   => $jenisPembayaran,
            'nominal_dp'         => $nominalDp,
            'sisa_pembayaran'   => $sisaBayar,
            'status_pelunasan'   => $statusPelunasan,
            'metode_pembayaran'  => null,           // Belum ditentukan metode pembayaran
            'status_pembayaran'  => 'belum lunas',  // Status awal adalah belum lunas
        ]);

        // Menghapus sesi intended agar tidak dialihkan ke halaman yang salah setelah booking
        session()->forget(['url.intended', 'booking_attempt']);

        // Kirim notifikasi otomatis (Email & WhatsApp) untuk reservasi baru
        NotificationService::sendBookingStatusNotification($reservasi, 'Reservasi baru Anda berhasil dibuat. Silakan selesaikan pembayaran.');

        // Mengarahkan tamu ke halaman detail reservasi yang baru dibuat dengan pesan sukses
        return redirect()->route('bookings.show', $reservasi->id_reservasi)
            ->with('success', 'Reservasi berhasil dibuat! Silakan lanjutkan pembayaran.');
    }


    /**
     * Display bookings list for logged in guest.
     * Menampilkan daftar semua reservasi milik tamu yang sedang login.
     */
    public function index()
    {
        // Otomatis membatalkan reservasi kadaluarsa & checkout yang sudah lewat
        Reservasi::autoCancelExpired();
        Reservasi::autoCheckout();

        // Mengambil ID tamu dari sesi yang sedang login
        $tamuId = session('tamu_id');

        // Mengambil semua reservasi milik tamu ini beserta relasi penginapan dan pembayaran, diurutkan terbaru
        $bookings = Reservasi::with(['penginapan', 'pembayaran'])
            ->where('id_tamu', $tamuId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Mengembalikan tampilan halaman daftar pemesanan tamu
        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show booking details / invoice.
     * Menampilkan detail satu reservasi beserta informasi penginapan dan perhitungan malam.
     */
    public function show($id)
    {
        // Otomatis membatalkan reservasi kadaluarsa & checkout yang sudah lewat
        Reservasi::autoCancelExpired();
        Reservasi::autoCheckout();

        // Mengambil ID tamu dari sesi yang sedang login
        $tamuId = session('tamu_id');

        // Mencari reservasi berdasarkan ID dan memastikan reservasi ini milik tamu yang sedang login
        $booking = Reservasi::with(['penginapan', 'pembayaran', 'tamu'])
            ->where('id_tamu', $tamuId)
            ->where('id_reservasi', $id)
            ->firstOrFail(); // Jika tidak ditemukan atau bukan milik tamu ini, tampilkan 404

        // Menghitung jumlah malam menginap berdasarkan selisih tanggal check-in dan check-out
        $ciDate = Carbon::parse($booking->tanggal_checkin);
        $coDate = Carbon::parse($booking->tanggal_checkout);
        $nights = $ciDate->diffInDays($coDate);
        if ($nights <= 0) $nights = 1; // Minimal 1 malam

        // Mengembalikan tampilan halaman detail reservasi dengan data booking dan jumlah malam
        return view('bookings.show', compact('booking', 'nights'));
    }

    /**
     * Memproses pembatalan reservasi oleh tamu (Refund 0% / Hangus).
     */
    public function cancel(Request $request, $id)
    {
        $tamuId = session('tamu_id');
        $booking = Reservasi::with(['pembayaran'])
            ->where('id_tamu', $tamuId)
            ->where('id_reservasi', $id)
            ->firstOrFail();

        if (in_array($booking->status, ['cancelled', 'completed'])) {
            return back()->with('error', 'Reservasi ini tidak dapat dibatalkan.');
        }

        if ($booking->status === 'confirmed') {
            // Reservasi confirmed: set status_refund = 'pending_refund' agar tamu bisa
            // membatalkan pengajuan ini (Lanjutkan) selama admin belum memproses refund.
            $booking->update([
                'status'            => 'cancelled',
                'alasan_pembatalan' => $request->input('alasan_pembatalan', 'Dibatalkan oleh tamu'),
                'persentase_refund' => 0,
                'jumlah_refund'     => 0,
                'status_refund'     => 'pending_refund', // Menunggu diproses admin (bisa ditarik tamu)
            ]);
        } else {
            // Reservasi pending: langsung cancelled, tidak ada refund
            $booking->update([
                'status'            => 'cancelled',
                'alasan_pembatalan' => $request->input('alasan_pembatalan', 'Dibatalkan oleh tamu'),
                'persentase_refund' => 0,
                'jumlah_refund'     => 0,
                'status_refund'     => 'no_refund',
            ]);
        }

        // Kirim notifikasi otomatis (Email & WhatsApp)
        NotificationService::sendBookingStatusNotification($booking, 'Reservasi Anda telah dibatalkan.');

        return redirect()->back()
            ->with('success', 'Reservasi berhasil dibatalkan. Anda masih dapat melanjutkan reservasi ini selama admin belum memproses pengajuan.');
    }

    /**
     * Melanjutkan kembali reservasi yang dibatalkan tamu.
     */
    public function resumeBooking($id)
    {
        $tamuId = session('tamu_id');
        $booking = Reservasi::with('pembayaran')
            ->where('id_tamu', $tamuId)
            ->where('id_reservasi', $id)
            ->firstOrFail();

        // Hanya bisa dilanjutkan jika status saat ini cancelled
        if ($booking->status !== 'cancelled') {
            return back()->with('error', 'Hanya reservasi yang berstatus dibatalkan yang dapat dilanjutkan kembali.');
        }

        // Tentukan status pemulihan: jika pembayaran sudah lunas -> 'confirmed', jika belum lunas -> 'pending'
        $restoredStatus = ($booking->pembayaran && $booking->pembayaran->status_pembayaran === 'lunas') ? 'confirmed' : 'pending';

        // Kembalikan ke status aktif
        $booking->update([
            'status'            => $restoredStatus,
            'status_refund'     => 'none',
            'alasan_pembatalan' => null,
            'persentase_refund' => 0,
            'jumlah_refund'     => 0,
        ]);

        // Kirim notifikasi otomatis (Email & WhatsApp)
        NotificationService::sendBookingStatusNotification($booking, 'Reservasi Anda telah dilanjutkan kembali dan kini aktif.');

        return redirect()->route('bookings.show', $booking->id_reservasi)
            ->with('success', 'Reservasi berhasil dilanjutkan kembali! Status reservasi kini aktif kembali.');
    }

    /**
     * Memproses penundaan reservasi oleh tamu (diberi waktu 1 bulan).
     */
    public function postpone(Request $request, $id)
    {
        $tamuId = session('tamu_id');
        $booking = Reservasi::where('id_tamu', $tamuId)
            ->where('id_reservasi', $id)
            ->firstOrFail();

        if (!in_array($booking->status, ['confirmed'])) {
            return back()->with('error', 'Hanya reservasi yang sudah lunas/terkonfirmasi yang dapat ditunda.');
        }

        $request->validate([
            'alasan_penundaan' => 'nullable|string|max:500',
        ]);

        $now = Carbon::now();
        $deadline = $now->copy()->addMonth();

        $booking->update([
            'status'             => 'postponed',
            'tanggal_penundaan'  => $now,
            'batas_tunda_hingga' => $deadline,
            'alasan_penundaan'   => $request->input('alasan_penundaan', 'Pengajuan penundaan oleh tamu'),
        ]);

        // Kirim notifikasi otomatis (Email & WhatsApp)
        NotificationService::sendBookingStatusNotification($booking, 'Reservasi Anda telah ditunda (berlaku s.d 1 bulan).');

        return redirect()->back()
            ->with('success', 'Reservasi berhasil ditunda. Anda diberikan waktu 1 bulan hingga ' . $deadline->translatedFormat('d F Y') . ' untuk melakukan penjadwalan ulang (reschedule).');
    }

    /**
     * Memproses penjadwalan ulang (reschedule) tanggal check-in & check-out untuk reservasi yang ditunda.
     */
    public function reschedule(Request $request, $id)
    {
        $tamuId = session('tamu_id');
        $booking = Reservasi::with('penginapan')
            ->where('id_tamu', $tamuId)
            ->where('id_reservasi', $id)
            ->firstOrFail();

        if ($booking->status !== 'postponed') {
            return back()->with('error', 'Hanya reservasi berstatus Ditunda yang dapat dijadwalkan ulang.');
        }

        if (Carbon::now()->greaterThan($booking->batas_tunda_hingga)) {
            $booking->update([
                'status'            => 'cancelled',
                'persentase_refund' => 0,
                'jumlah_refund'     => 0,
                'status_refund'     => 'no_refund',
                'alasan_pembatalan' => 'Batas waktu penundaan (1 bulan) telah habis (uang hangus).'
            ]);
            return redirect()->back()->with('error', 'Batas waktu penundaan (1 bulan) telah habis. Reservasi telah dibatalkan dan uang hangus.');
        }

        $request->validate([
            'tanggal_checkin'  => 'required|date|after_or_equal:today',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
        ], [
            'tanggal_checkin.after_or_equal' => 'Tanggal check-in baru harus hari ini atau tanggal setelahnya.',
            'tanggal_checkout.after'         => 'Tanggal check-out harus setelah tanggal check-in.',
        ]);

        $checkin  = $request->tanggal_checkin;
        $checkout = $request->tanggal_checkout;

        // Cek ketersediaan unit (mengabaikan reservasi ini sendiri)
        $maxUnits = $booking->penginapan->jumlah_unit;
        $bookedUnits = Reservasi::where('id_unit', $booking->id_unit)
            ->where('id_reservasi', '!=', $booking->id_reservasi)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($checkin, $checkout) {
                $query->where('tanggal_checkin', '<', $checkout)
                      ->where('tanggal_checkout', '>', $checkin);
            })
            ->sum('jumlah_kamar');

        $available = max(0, $maxUnits - $bookedUnits);

        if ($booking->jumlah_kamar > $available) {
            return back()->with('error', "Maaf, unit penginapan tidak cukup tersedia pada tanggal $checkin s/d $checkout (Sisa unit: $available). Silakan pilih tanggal lain.");
        }

        // Perbarui tanggal reservasi & kembalikan status ke confirmed
        $booking->update([
            'tanggal_checkin'    => $checkin,
            'tanggal_checkout'   => $checkout,
            'status'             => 'confirmed',
            'tanggal_penundaan'  => null,
            'batas_tunda_hingga' => null,
        ]);

        // Kirim notifikasi otomatis (Email & WhatsApp)
        NotificationService::sendBookingStatusNotification($booking, 'Penjadwalan ulang (reschedule) berhasil dikonfirmasi.');

        return redirect()->back()
            ->with('success', 'Penjadwalan ulang (reschedule) berhasil! Reservasi Anda telah terkonfirmasi untuk tanggal baru.');
    }
}

