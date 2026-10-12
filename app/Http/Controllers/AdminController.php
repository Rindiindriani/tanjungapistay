<?php

// Mendefinisikan namespace controller agar dapat dikenali oleh Laravel
namespace App\Http\Controllers;

// Mengimpor model-model yang dibutuhkan oleh controller ini
use App\Models\Penginapan;
use App\Models\Reservasi;
use App\Models\Pembayaran;
use App\Models\DetailPembayaran;
use App\Models\Tamu;
use App\Models\Pengelola;

// Mengimpor kelas-kelas pendukung dari Laravel dan library pihak ketiga
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Services\NotificationService;
use Midtrans\Config;
use Midtrans\Snap;


// Mendefinisikan class AdminController yang mewarisi dari Controller dasar
class AdminController extends Controller
{
    /**
     * Admin Dashboard.
     * Menampilkan halaman utama admin dengan statistik dan grafik pemesanan,
     * serta status ketersediaan stok unit penginapan berdasarkan filter tanggal.
     */
    public function dashboard(Request $request)
    {
        // Otomatis membatalkan reservasi kadaluarsa & checkout yang sudah lewat
        Reservasi::autoCancelExpired();
        Reservasi::autoCheckout();

        // Parameter filter tanggal untuk pemantauan ketersediaan unit
        $filterCheckin = $request->input('filter_checkin', now()->toDateString());
        $filterCheckout = $request->input('filter_checkout');

        // Jika checkout belum diisi atau <= checkin, otomatis diset 1 hari setelah checkin
        if (!$filterCheckout || $filterCheckout <= $filterCheckin) {
            $filterCheckout = \Carbon\Carbon::parse($filterCheckin)->addDay()->toDateString();
        }

        // Ambil semua unit penginapan dari database dan hitung stok ketersediaan pada tanggal tersebut
        $unitsStatus = Penginapan::orderBy('nama_unit', 'asc')->get()->map(function ($unit) use ($filterCheckin, $filterCheckout) {
            $maxUnits = (int) $unit->jumlah_unit;

            // Hitung unit yang dipesan/ditempati pada rentang tanggal checkin - checkout (kecuali yang dibatalkan)
            $bookedUnits = (int) Reservasi::where('id_unit', $unit->id_unit)
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($filterCheckin, $filterCheckout) {
                    $query->where('tanggal_checkin', '<', $filterCheckout)
                          ->where('tanggal_checkout', '>', $filterCheckin);
                })
                ->sum('jumlah_kamar');

            $sisaKamar = max(0, $maxUnits - $bookedUnits);
            $isPenuh = ($sisaKamar <= 0);
            $persenTerisi = $maxUnits > 0 ? min(100, round(($bookedUnits / $maxUnits) * 100)) : 0;

            return (object) [
                'id_unit'       => $unit->id_unit,
                'nama_unit'     => $unit->nama_unit,
                'tipe_unit'     => $unit->tipe_unit,
                'total_unit'    => $maxUnits,
                'terisi'        => $bookedUnits,
                'sisa'          => $sisaKamar,
                'is_penuh'      => $isPenuh,
                'persen_terisi' => $persenTerisi,
            ];
        });

        $totalVilla = Penginapan::where('tipe_unit', 'like', '%villa%')->sum('jumlah_unit');
        $totalTenda = Penginapan::where('tipe_unit', 'like', '%tenda%')->sum('jumlah_unit');
        if ($totalVilla == 0 && $totalTenda == 0) {
            $totalVilla = Penginapan::sum('jumlah_unit');
        }

        $totalUnitKamar = $unitsStatus->sum('total_unit');
        $totalTersedia = $unitsStatus->sum('sisa');
        $totalTerisi = $unitsStatus->sum('terisi');

        // Jika request via AJAX / JSON, kembalikan response JSON
        if ($request->ajax()) {
            return response()->json([
                'success'        => true,
                'filterCheckin'  => $filterCheckin,
                'filterCheckout' => $filterCheckout,
                'unitsStatus'    => $unitsStatus,
                'totalUnitKamar' => $totalUnitKamar,
                'totalTersedia'  => $totalTersedia,
                'totalTerisi'    => $totalTerisi,
            ]);
        }

        // Menghitung total semua reservasi aktif yang ada di database (tidak termasuk yang dibatalkan)
        $totalReservasi = Reservasi::where('status', '!=', 'cancelled')->count();

        // Menghitung jumlah reservasi dengan status 'pending' (belum dikonfirmasi)
        $pendingReservasi = Reservasi::where('status', 'pending')->count();

        // Menghitung jumlah reservasi dengan status 'cancelled' (dibatalkan / kadaluarsa)
        $cancelledReservasi = Reservasi::where('status', 'cancelled')->count();

        // Menghitung total pendapatan bersih (Pendapatan aktif lunas + sisa potongan pembatalan jika ada)
        $pendapatanAktif = Pembayaran::whereHas('reservasi', function ($q) {
            $q->whereIn('status', ['confirmed', 'completed']);
        })->where('status_pembayaran', 'lunas')->sum('total_pembayaran');

        $pendapatanBatal = Reservasi::where('status', 'cancelled')
            ->whereHas('pembayaran', function ($q) {
                $q->where('status_pembayaran', 'lunas');
            })
            ->get()
            ->sum(function ($r) {
                $total = $r->pembayaran->total_pembayaran ?? 0;
                $refund = $r->jumlah_refund ?? 0;
                return max(0, $total - $refund);
            });

        $totalPendapatan = $pendapatanAktif + $pendapatanBatal;

        // Mengambil 5 reservasi terbaru beserta relasi tamu, penginapan, dan pembayaran untuk ditampilkan di dashboard
        $recentBookings = Reservasi::with(['tamu', 'penginapan', 'pembayaran'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Mengambil data jumlah pemesanan per hari selama 7 hari terakhir untuk grafik garis (line chart)
        $chartData = Reservasi::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Mengisi hari-hari yang tidak ada data pemesanan dengan nilai 0 agar grafik tetap lengkap
        $dates = [];
        $counts = [];
        for ($i = 6; $i >= 0; $i--) {
            $dateString = now()->subDays($i)->format('Y-m-d');
            $dates[] = now()->subDays($i)->format('d M');
            $counts[] = $chartData[$dateString] ?? 0;
        }

        // Mengembalikan tampilan dashboard admin beserta semua data yang diperlukan
        return view('admin.dashboard', compact(
            'totalReservasi',
            'pendingReservasi',
            'cancelledReservasi',
            'totalPendapatan',
            'recentBookings',
            'dates',
            'counts',
            'unitsStatus',
            'filterCheckin',
            'filterCheckout',
            'totalVilla',
            'totalTenda',
            'totalUnitKamar',
            'totalTersedia',
            'totalTerisi'
        ));
    }

    /**
     * Lodging Units Listing CRUD.
     * Menampilkan daftar semua unit penginapan yang tersedia.
     */
    public function listUnits()
    {
        // Mengambil semua data unit penginapan dari database, diurutkan dari yang terbaru (paling atas)
        $units = Penginapan::orderBy('id_unit', 'desc')->get();

        // Mengembalikan tampilan halaman daftar unit penginapan
        return view('admin.units.index', compact('units'));
    }

    /**
     * Menampilkan form untuk menambahkan unit penginapan baru.
     */
    public function createUnit()
    {
        // Mengambil jenis-jenis unit yang sudah ada di database untuk opsi pembantu
        $existingTypes = Penginapan::distinct()->pluck('tipe_unit');

        // Mengembalikan tampilan form tambah unit penginapan
        return view('admin.units.create', compact('existingTypes'));
    }

    /**
     * Menyimpan data unit penginapan baru ke database.
     */
    public function storeUnit(Request $request)
    {
        // Memvalidasi data yang dikirimkan dari form tambah unit penginapan
        $request->validate([
            'nama_unit'     => 'required|string|max:50',
            'alamat'        => 'required|string|max:100',
            'fasilitas'     => 'required|string|max:300',
            'harga'         => 'required|numeric|min:0',
            'tipe_unit'     => 'required|string|max:50',
            'jumlah_unit'   => 'required|integer|min:1',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_galeri.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Menginisialisasi nama foto sebagai null (default jika tidak ada foto yang diunggah)
        $fotoName = null;

        // Memeriksa apakah ada file foto utama yang diunggah
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fotoName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $fotoName);
        }

        // Menyimpan data unit penginapan baru ke database
        $unit = Penginapan::create([
            'id_pengelola' => session('admin_id'),
            'nama_unit'    => $request->nama_unit,
            'alamat'       => $request->alamat,
            'fasilitas'    => $request->fasilitas,
            'harga'        => $request->harga,
            'tipe_unit'    => strtolower(trim($request->tipe_unit)),
            'jumlah_unit'  => $request->jumlah_unit,
            'foto'         => $fotoName,
        ]);

        // Memeriksa apakah ada foto galeri tambahan yang diunggah
        if ($request->hasFile('foto_galeri')) {
            foreach ($request->file('foto_galeri') as $index => $fileGaleri) {
                if ($fileGaleri) {
                    $galeriName = time() . '_galeri_' . $index . '_' . $fileGaleri->getClientOriginalName();
                    $fileGaleri->move(public_path('images'), $galeriName);
                    FotoPenginapan::create([
                        'id_unit'  => $unit->id_unit,
                        'foto'     => $galeriName,
                        'is_utama' => false,
                    ]);
                }
            }
        }

        // Mengarahkan kembali ke halaman daftar unit dengan pesan sukses
        return redirect()->route('admin.units.index')->with('success', 'Unit penginapan beserta foto galeri berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data unit penginapan yang sudah ada.
     */
    public function editUnit($id)
    {
        // Mencari data unit berdasarkan ID beserta galeri foto tambahan
        $unit = Penginapan::with('fotos')->findOrFail($id);

        // Mengambil jenis-jenis unit yang sudah ada di database untuk opsi pembantu
        $existingTypes = Penginapan::distinct()->pluck('tipe_unit');

        // Mengembalikan tampilan form edit unit dengan data yang sudah ada
        return view('admin.units.edit', compact('unit', 'existingTypes'));
    }

    /**
     * Memperbarui data unit penginapan yang sudah ada di database.
     */
    public function updateUnit(Request $request, $id)
    {
        // Mencari data unit yang akan diperbarui berdasarkan ID
        $unit = Penginapan::findOrFail($id);

        // Memvalidasi data baru yang dikirimkan dari form edit unit penginapan
        $request->validate([
            'nama_unit'     => 'required|string|max:50',
            'alamat'        => 'required|string|max:100',
            'fasilitas'     => 'required|string|max:300',
            'harga'         => 'required|numeric|min:0',
            'tipe_unit'     => 'required|string|max:50',
            'jumlah_unit'   => 'required|integer|min:1',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_galeri.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Menggunakan nama foto lama sebagai nilai default jika tidak ada foto baru yang diunggah
        $fotoName = $unit->foto;

        // Memeriksa apakah ada foto utama baru yang diunggah
        if ($request->hasFile('foto')) {
            if ($unit->foto && File::exists(public_path('images/' . $unit->foto))) {
                File::delete(public_path('images/' . $unit->foto));
            }
            $file = $request->file('foto');
            $fotoName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $fotoName);
        }

        // Memperbarui data unit penginapan di database
        $unit->update([
            'nama_unit'   => $request->nama_unit,
            'alamat'      => $request->alamat,
            'fasilitas'   => $request->fasilitas,
            'harga'       => $request->harga,
            'tipe_unit'   => strtolower(trim($request->tipe_unit)),
            'jumlah_unit' => $request->jumlah_unit,
            'foto'        => $fotoName,
        ]);

        // Memeriksa apakah ada foto galeri tambahan baru yang diunggah
        if ($request->hasFile('foto_galeri')) {
            foreach ($request->file('foto_galeri') as $index => $fileGaleri) {
                if ($fileGaleri) {
                    $galeriName = time() . '_galeri_' . $index . '_' . $fileGaleri->getClientOriginalName();
                    $fileGaleri->move(public_path('images'), $galeriName);
                    FotoPenginapan::create([
                        'id_unit'  => $unit->id_unit,
                        'foto'     => $galeriName,
                        'is_utama' => false,
                    ]);
                }
            }
        }

        // Mengarahkan kembali ke halaman daftar unit dengan pesan sukses setelah berhasil diperbarui
        return redirect()->route('admin.units.index')->with('success', 'Unit penginapan dan galeri foto berhasil diperbarui.');
    }

    /**
     * Menghapus foto galeri tertentu dari unit penginapan.
     */
    public function deleteFotoGaleri($id)
    {
        $fotoGaleri = FotoPenginapan::findOrFail($id);

        if ($fotoGaleri->foto && File::exists(public_path('images/' . $fotoGaleri->foto))) {
            File::delete(public_path('images/' . $fotoGaleri->foto));
        }

        $fotoGaleri->delete();

        return back()->with('success', 'Foto galeri berhasil dihapus.');
    }

    /**
     * Menghapus data unit penginapan dari database.
     */
    public function destroyUnit($id)
    {
        // Mencari data unit yang akan dihapus berdasarkan ID
        $unit = Penginapan::findOrFail($id);

        // Menghapus file foto dari server jika foto tersedia dan ditemukan di penyimpanan
        if ($unit->foto && File::exists(public_path('images/' . $unit->foto))) {
            File::delete(public_path('images/' . $unit->foto));
        }

        // Menghapus data unit penginapan dari database secara permanen
        $unit->delete();

        // Mengarahkan kembali ke halaman daftar unit dengan pesan sukses setelah dihapus
        return redirect()->route('admin.units.index')->with('success', 'Unit penginapan berhasil dihapus.');
    }

    /**
     * Bookings List & Admin Actions.
     * Menampilkan daftar semua reservasi yang ada beserta aksi admin.
     */
    public function listBookings()
    {
        // Otomatis membatalkan reservasi kadaluarsa & checkout yang sudah lewat
        Reservasi::autoCancelExpired();
        Reservasi::autoCheckout();

        // Mengambil semua data reservasi beserta relasi tamu, penginapan, dan pembayaran, diurutkan terbaru
        $bookings = Reservasi::with(['tamu', 'penginapan', 'pembayaran'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Mengembalikan tampilan halaman daftar semua pemesanan
        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Menampilkan detail satu reservasi tertentu untuk dilihat oleh admin.
     */
    public function showBooking($id)
    {
        // Otomatis membatalkan reservasi kadaluarsa & checkout yang sudah lewat
        Reservasi::autoCancelExpired();
        Reservasi::autoCheckout();

        // Mencari reservasi berdasarkan ID beserta semua relasi yang diperlukan (termasuk detail gateway pembayaran)
        $booking = Reservasi::with(['tamu', 'penginapan', 'pembayaran.detailPembayaran.gateway'])
            ->findOrFail($id);

        // Mengembalikan tampilan detail reservasi yang dipilih
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Mengkonfirmasi reservasi secara manual oleh admin (tanpa melalui payment gateway).
     */
    public function confirmBooking($id)
    {
        // Mencari data reservasi yang akan dikonfirmasi berdasarkan ID
        $booking = Reservasi::findOrFail($id);

        // Mengubah status reservasi menjadi 'confirmed' (dikonfirmasi)
        $booking->update(['status' => 'confirmed']);

        // Mencari data pembayaran yang terkait dengan reservasi ini
        $pembayaran = Pembayaran::where('id_reservasi', $booking->id_reservasi)->first();

        // Jika data pembayaran ditemukan, perbarui statusnya menjadi lunas dengan tanggal hari ini
        if ($pembayaran) {
            $pembayaran->update([
                'status_pembayaran' => 'lunas',
                'tanggal_pembayaran' => now(),
                // Menggunakan metode pembayaran yang sudah ada, atau 'Manual Admin' jika belum ada
                'metode_pembayaran' => $pembayaran->metode_pembayaran ?? 'Manual Admin'
            ]);
        }

        // Kirim notifikasi otomatis (Email & WhatsApp) setelah dikonfirmasi admin
        NotificationService::sendBookingStatusNotification($booking, 'Reservasi Anda telah dikonfirmasi secara manual oleh Pengelola.');

        // Mengarahkan kembali ke halaman sebelumnya dengan pesan sukses konfirmasi manual
        return back()->with('success', 'Reservasi berhasil dikonfirmasi secara manual dan notifikasi terkirim ke tamu.');
    }

    /**
     * Membatalkan reservasi oleh admin.
     */
    public function cancelBooking($id)
    {
        // Mencari data reservasi yang akan dibatalkan berdasarkan ID
        $booking = Reservasi::findOrFail($id);

        // Mengubah status reservasi menjadi 'cancelled' (dibatalkan, 0% refund)
        $booking->update([
            'status'            => 'cancelled',
            'persentase_refund' => 0,
            'jumlah_refund'     => 0,
            'status_refund'     => 'no_refund',
            'alasan_pembatalan' => 'Dibatalkan oleh Admin'
        ]);

        // Kirim notifikasi otomatis (Email & WhatsApp)
        NotificationService::sendBookingStatusNotification($booking, 'Reservasi Anda telah dibatalkan oleh Pengelola.');

        // Mengarahkan kembali ke halaman sebelumnya dengan pesan sukses pembatalan
        return back()->with('success', 'Reservasi berhasil dibatalkan (Pengembalian dana 0% / Hangus) & notifikasi terkirim ke tamu.');
    }

    /**
     * Reschedule reservasi yang ditunda oleh Admin.
     */
    public function rescheduleBooking(Request $request, $id)
    {
        $booking = Reservasi::with('penginapan')->findOrFail($id);

        if ($booking->status !== 'postponed') {
            return back()->with('error', 'Hanya reservasi berstatus Ditunda yang dapat dijadwalkan ulang.');
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
            return back()->with('error', "Unit penginapan tidak cukup tersedia pada tanggal $checkin s/d $checkout (Sisa unit: $available).");
        }

        $booking->update([
            'tanggal_checkin'    => $checkin,
            'tanggal_checkout'   => $checkout,
            'status'             => 'confirmed',
            'tanggal_penundaan'  => null,
            'batas_tunda_hingga' => null,
        ]);

        // Kirim notifikasi otomatis (Email & WhatsApp)
        NotificationService::sendBookingStatusNotification($booking, 'Penjadwalan ulang (reschedule) oleh pengelola telah dikonfirmasi.');

        return back()->with('success', 'Penjadwalan ulang (reschedule) oleh Admin berhasil dikonfirmasi ke tanggal baru & notifikasi terkirim.');
    }

    /**
     * Mengirim notifikasi manual / pengingat checkin via Email & WhatsApp dari admin panel.
     */
    public function sendManualNotification(Request $request, $id)
    {
        $booking = Reservasi::with(['tamu', 'penginapan', 'pembayaran'])->findOrFail($id);

        $type    = $request->input('type', 'status'); // 'status' or 'checkin_reminder'
        $custom  = $request->input('custom_message');

        if ($type === 'checkin_reminder') {
            $res = NotificationService::sendCheckinReminder($booking, $custom);
            $msg = "Notifikasi pengingat check-in berhasil dikirimkan ke tamu.";
        } else {
            $res = NotificationService::sendBookingStatusNotification($booking, $custom);
            $msg = "Notifikasi status reservasi berhasil dikirimkan ke tamu.";
        }

        return back()->with('success', $msg);
    }

    /**
     * Walk-in Reservation: Show form.
     * Menampilkan form untuk membuat reservasi langsung (tamu datang langsung ke tempat).
     */
    public function walkinIndex()
    {
        // Mengambil semua data unit penginapan untuk ditampilkan sebagai pilihan di form walk-in
        $units = Penginapan::all();

        // Mengembalikan tampilan halaman form reservasi walk-in
        return view('admin.walkin.index', compact('units'));
    }

    /**
     * Walk-in Reservation: Check availability via AJAX.
     * Memeriksa ketersediaan unit penginapan pada tanggal yang dipilih (dipanggil via AJAX).
     */
    public function walkinCheckAvailability(Request $request)
    {
        // Memvalidasi input dari AJAX request untuk pengecekan ketersediaan
        $request->validate([
            'id_unit'          => 'required|exists:penginapan,id_unit',
            'tanggal_checkin'  => 'required|date|after_or_equal:today',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
            'jumlah_kamar'     => 'required|integer|min:1',
        ]);

        // Mencari data unit penginapan berdasarkan ID yang dipilih
        $unit = Penginapan::findOrFail($request->id_unit);

        // Mengambil jumlah total unit yang tersedia pada unit penginapan ini
        $maxUnits = $unit->jumlah_unit;

        // Menghitung jumlah unit yang sudah dipesan pada rentang tanggal yang dipilih (kecuali yang dibatalkan)
        $bookedUnits = Reservasi::where('id_unit', $request->id_unit)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request) {
                // Memeriksa tumpang tindih tanggal (overlap) antara reservasi yang ada dengan tanggal yang diminta
                $query->where('tanggal_checkin', '<', $request->tanggal_checkout)
                      ->where('tanggal_checkout', '>', $request->tanggal_checkin);
            })
            ->sum('jumlah_kamar');

        // Menghitung sisa unit yang masih tersedia (minimal 0, tidak boleh negatif)
        $available = max(0, $maxUnits - $bookedUnits);

        // Mengembalikan respons JSON: tersedia jika jumlah yang diminta <= unit yang tersedia
        if ($request->jumlah_kamar <= $available) {
            return response()->json([
                'success'   => true,
                'available' => $available,
                'message'   => "Unit tersedia! Sisa unit: $available unit."
            ]);
        }

        // Mengembalikan respons JSON: tidak tersedia jika unit yang diminta melebihi ketersediaan
        return response()->json([
            'success'   => false,
            'available' => $available,
            'message'   => "Unit tidak mencukupi. Hanya tersisa $available unit."
        ]);
    }

    /**
     * Walk-in Reservation: Process and store.
     * Memproses dan menyimpan reservasi walk-in (tamu datang langsung) ke database.
     */
    public function walkinStore(Request $request)
    {
        // Memvalidasi semua data yang dikirimkan dari form reservasi walk-in
        $request->validate([
            'nama'             => 'required|string|max:50',
            'kewarganegaraan'   => 'required|in:WNI,WNA',
            'no_ktp'           => 'required_if:kewarganegaraan,WNI|nullable|string|max:20',
            'no_paspor'        => 'required_if:kewarganegaraan,WNA|nullable|string|max:50',
            'provinsi'         => 'nullable|string|max:100',
            'kabupaten_kota'   => 'nullable|string|max:100',
            'kecamatan'        => 'nullable|string|max:100',
            'negara'           => 'nullable|string|max:100',
            'alamat_ktp'       => 'nullable|string|max:255',
            'no_hp'            => 'required|string|max:15',
            'email'            => 'nullable|email|max:50',
            'jenis_kelamin'    => 'nullable|in:Laki-laki,Perempuan',
            'id_unit'          => 'required|exists:penginapan,id_unit',
            'jumlah_kamar'     => 'required|integer|min:1',
            'tanggal_checkin'  => 'required|date|after_or_equal:today',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
            'jumlah_tamu'      => 'required|integer|min:1',
            'metode_pembayaran'=> 'required|in:Tunai,Transfer',
        ], [
            // Pesan validasi kustom dalam bahasa Indonesia
            'nama.required'                 => 'Nama tamu wajib diisi.',
            'no_ktp.required_if'            => 'No. KTP / NIK tamu WNI wajib diisi.',
            'no_paspor.required_if'         => 'No. Paspor tamu Luar Negeri (WNA) wajib diisi.',
            'no_hp.required'                => 'No. HP tamu wajib diisi.',
            'id_unit.required'              => 'Unit penginapan wajib dipilih.',
            'jumlah_kamar.min'              => 'Jumlah unit minimal 1.',
            'tanggal_checkin.after_or_equal'=> 'Tanggal check-in harus hari ini atau setelahnya.',
            'tanggal_checkout.after'        => 'Tanggal check-out harus setelah tanggal check-in.',
            'jumlah_tamu.min'               => 'Jumlah tamu minimal 1.',
        ]);

        // Mengambil data unit penginapan yang dipilih
        $unit = Penginapan::findOrFail($request->id_unit);

        // Validasi batas kapasitas maksimal 6 orang per unit untuk tipe Villa
        if (strtolower($unit->tipe_unit) === 'villa') {
            $maxAllowedGuests = 6 * $request->jumlah_kamar;
            if ($request->jumlah_tamu > $maxAllowedGuests) {
                return back()->withErrors([
                    'jumlah_tamu' => "Untuk unit tipe Villa, kapasitas maksimal adalah 6 orang per unit (Maksimal {$maxAllowedGuests} orang untuk {$request->jumlah_kamar} unit)."
                ])->withInput();
            }
        }

        // Memeriksa ketersediaan unit untuk tanggal yang dipilih sebelum membuat reservasi
        $maxUnits = $unit->jumlah_unit;
        $bookedUnits = Reservasi::where('id_unit', $request->id_unit)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request) {
                // Memeriksa tumpang tindih tanggal antara reservasi yang ada dengan tanggal baru
                $query->where('tanggal_checkin', '<', $request->tanggal_checkout)
                      ->where('tanggal_checkout', '>', $request->tanggal_checkin);
            })
            ->sum('jumlah_kamar');
        $available = max(0, $maxUnits - $bookedUnits);

        // Mengembalikan error jika jumlah unit yang diminta melebihi ketersediaan
        if ($request->jumlah_kamar > $available) {
            return back()->withErrors(['ketersediaan' => "Unit tidak mencukupi. Hanya tersisa $available unit."])->withInput();
        }

        // Data payload tamu
        $guestData = [
            'nama'            => $request->nama,
            'kewarganegaraan' => $request->kewarganegaraan,
            'no_ktp'          => $request->kewarganegaraan === 'WNI' ? $request->no_ktp : null,
            'no_paspor'       => $request->kewarganegaraan === 'WNA' ? $request->no_paspor : null,
            'provinsi'        => $request->provinsi,
            'kabupaten_kota'  => $request->kabupaten_kota,
            'kecamatan'       => $request->kecamatan,
            'negara'          => $request->kewarganegaraan === 'WNA' ? ($request->negara ?? 'Luar Negeri') : 'Indonesia',
            'alamat_ktp'      => $request->alamat_ktp,
            'no_hp'           => $request->no_hp,
            'jenis_kelamin'   => $request->jenis_kelamin,
            'sumber'          => 'walkin',
        ];

        // Mencari atau membuat data tamu berdasarkan email (jika email diisi)
        if (!empty($request->email)) {
            // Mencari tamu yang sudah ada berdasarkan email
            $tamu = Tamu::where('email', $request->email)->first();
            if (!$tamu) {
                // Membuat data tamu baru jika email belum terdaftar di database
                $tamu = Tamu::create(array_merge($guestData, [
                    'email'    => $request->email,
                    'password' => null,
                ]));
            } else {
                $tamu->update($guestData);
            }
        } else {
            // Jika tidak ada email → selalu membuat data tamu baru (walk-in tanpa akun)
            $tamu = Tamu::create(array_merge($guestData, [
                'email'    => null,
                'password' => null,
            ]));
        }


        // Menghitung jumlah malam menginap dan total harga yang harus dibayar
        $checkin  = \Carbon\Carbon::parse($request->tanggal_checkin);
        $checkout = \Carbon\Carbon::parse($request->tanggal_checkout);
        $nights   = $checkin->diffInDays($checkout);
        if ($nights <= 0) $nights = 1; // Minimal 1 malam jika perhitungan menghasilkan 0

        // Menghitung total pembayaran: harga per malam × jumlah unit × jumlah malam
        $totalPay = $unit->harga * $request->jumlah_kamar * $nights;

        // ── TRANSFER BANK: arahkan ke Midtrans SNAP ─────────────────────────
        if ($request->metode_pembayaran === 'Transfer') {
            // Membuat data reservasi baru dengan status 'pending' karena pembayaran belum selesai
            $reservasi = Reservasi::create([
                'id_tamu'          => $tamu->id_tamu,
                'id_unit'          => $unit->id_unit,
                'jumlah_kamar'     => $request->jumlah_kamar,
                'tanggal_checkin'  => $request->tanggal_checkin,
                'tanggal_checkout' => $request->tanggal_checkout,
                'jumlah_tamu'      => $request->jumlah_tamu,
                'status'           => 'pending',    // Status pending karena belum selesai bayar
                'sumber'           => 'walkin',
            ]);

            // Membuat data pembayaran awal dengan status 'belum lunas'
            Pembayaran::create([
                'id_reservasi'       => $reservasi->id_reservasi,
                'tanggal_pembayaran' => null,           // Belum ada tanggal pembayaran
                'total_pembayaran'   => $totalPay,
                'metode_pembayaran'  => null,           // Belum ditentukan sampai payment selesai
                'status_pembayaran'  => 'belum lunas',
            ]);

            // Mengkonfigurasi Midtrans SDK dengan API key dari file konfigurasi
            Config::$serverKey    = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized  = true;
            Config::$is3ds        = true;

            // Membuat Order ID unik dengan format WI-XXXX-timestamp untuk membedakan dari reservasi tamu online
            $orderId = 'WI-' . str_pad($reservasi->id_reservasi, 4, '0', STR_PAD_LEFT) . '-' . time();

            // Menyiapkan parameter transaksi yang akan dikirim ke Midtrans
            $params = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => (int) $totalPay,
                ],
                'customer_details' => [
                    'first_name' => $tamu->nama,
                    'email'      => $tamu->email ?? 'noemail@walkin.com', // Email dummy jika tamu tidak punya email
                    'phone'      => $tamu->no_hp ?? '',
                ],
                'item_details' => [
                    [
                        'id'       => 'UNIT-' . $unit->id_unit,
                        'price'    => (int) $unit->harga,
                        'quantity' => (int) $request->jumlah_kamar,
                        'name'     => $unit->nama_unit . ' (' . $nights . ' malam)',
                    ],
                ],
                'callbacks' => [
                    // URL yang akan dipanggil Midtrans setelah pembayaran selesai
                    'finish' => route('admin.payment.walkin.finish'),
                ],
            ];

            try {
                // Mengambil Snap Token dari Midtrans untuk mengarahkan ke halaman pembayaran
                $snapToken = Snap::getSnapToken($params);
            } catch (\Exception $e) {
                // Mencatat error ke log jika gagal mendapatkan Snap Token dari Midtrans
                Log::error('Midtrans Walkin Snap Error: ' . $e->getMessage());

                // Rollback: menghapus data pembayaran dan reservasi yang baru dibuat jika terjadi error
                $reservasi->pembayaran()->delete();
                $reservasi->delete();

                return back()->withErrors([
                    'payment' => 'Gagal membuat sesi pembayaran Midtrans: ' . $e->getMessage(),
                ])->withInput();
            }

            // Menentukan URL redirect Midtrans berdasarkan mode (production atau sandbox)
            $snapRedirectUrl = config('midtrans.is_production')
                ? "https://app.midtrans.com/snap/v2/vtweb/{$snapToken}"
                : "https://app.sandbox.midtrans.com/snap/v2/vtweb/{$snapToken}";

            // Mengarahkan admin ke halaman pembayaran Midtrans
            return redirect()->away($snapRedirectUrl);
        }

        // ── TUNAI: langsung dikonfirmasi karena pembayaran diterima langsung di tempat ─────────────────────────
        // Membuat data reservasi baru dengan status 'confirmed' langsung (karena bayar tunai)
        $reservasi = Reservasi::create([
            'id_tamu'          => $tamu->id_tamu,
            'id_unit'          => $unit->id_unit,
            'jumlah_kamar'     => $request->jumlah_kamar,
            'tanggal_checkin'  => $request->tanggal_checkin,
            'tanggal_checkout' => $request->tanggal_checkout,
            'jumlah_tamu'      => $request->jumlah_tamu,
            'status'           => 'confirmed', // Langsung confirmed karena sudah bayar tunai
            'sumber'           => 'walkin',
        ]);

        // Membuat data pembayaran dengan status 'lunas' karena sudah dibayar tunai
        Pembayaran::create([
            'id_reservasi'       => $reservasi->id_reservasi,
            'tanggal_pembayaran' => now(),      // Tanggal pembayaran adalah hari ini
            'total_pembayaran'   => $totalPay,
            'metode_pembayaran'  => 'Tunai',    // Metode pembayaran adalah tunai
            'status_pembayaran'  => 'lunas',    // Langsung lunas karena bayar di tempat
        ]);

        // Mengarahkan ke halaman detail reservasi yang baru dibuat dengan pesan sukses
        return redirect()->route('admin.bookings.show', $reservasi->id_reservasi)
            ->with('success', 'Reservasi langsung (walk-in) berhasil dibuat untuk tamu: ' . $tamu->nama);
    }

    /**
     * List all registered guests.
     * Menampilkan daftar semua tamu yang terdaftar di sistem.
     */
    public function listGuests()
    {
        // Mengambil semua data tamu dari database diurutkan berdasarkan waktu pendaftaran terbaru
        $guests = Tamu::orderBy('created_at', 'desc')->get();

        // Mengembalikan tampilan halaman daftar tamu
        return view('admin.guests.index', compact('guests'));
    }

    /**
     * Menghapus satu data akun tamu dari sistem.
     */
    public function destroyGuest($id)
    {
        $guest = Tamu::findOrFail($id);
        $nama = $guest->nama;

        // Menghapus tamu (reservasi & pembayaran tamu otomatis terhapus karena foreign key cascade)
        $guest->delete();

        return back()->with('success', "Akun tamu '{$nama}' berhasil dihapus.");
    }

    /**
     * Membersihkan / menghapus seluruh akun tamu yang terdaftar beserta relasi pemesanannya.
     */
    public function destroyAllGuests()
    {
        $count = Tamu::count();

        // Menghapus semua tamu dalam transaksi aman
        DB::transaction(function () {
            Tamu::query()->delete();
        });

        return back()->with('success', "Berhasil membersihkan seluruh data tamu ({$count} akun) beserta riwayat pemesanannya.");
    }

    /**
     * Financial report index with filters.
     * Menampilkan laporan keuangan dengan filter tanggal.
     */
    public function reportIndex(Request $request)
    {
        // Otomatis membatalkan reservasi kadaluarsa & checkout yang sudah lewat
        Reservasi::autoCancelExpired();
        Reservasi::autoCheckout();

        // Mengambil parameter filter tanggal dari request (opsional)
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        // Membangun query untuk mengambil reservasi aktif (confirmed & completed)
        $query = Reservasi::with(['tamu', 'penginapan', 'pembayaran'])
            ->whereIn('status', ['confirmed', 'completed']);

        // Menambahkan filter tanggal jika parameter start_date dan end_date diisi
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_checkin', [$startDate, $endDate]);
        }

        // Mengeksekusi query dan mengurutkan hasil berdasarkan tanggal check-in
        $reports         = $query->orderBy('tanggal_checkin', 'asc')->get();

        // Menghitung total pendapatan dari seluruh reservasi yang terfilter
        $totalRevenue    = $reports->sum(function ($r) {
            return $r->pembayaran->total_pembayaran ?? 0;
        });

        // Menghitung total jumlah pengunjung (penjumlahan jumlah_tamu dari semua penyewa/unit)
        $totalPengunjung = $reports->sum('jumlah_tamu');

        // Mengembalikan tampilan halaman laporan keuangan dengan data yang sudah difilter
        return view('admin.reports.index', compact('reports', 'totalRevenue', 'totalPengunjung', 'startDate', 'endDate'));
    }

    /**
     * Financial report ready for print view.
     * Menampilkan laporan keuangan dalam tampilan siap cetak.
     */
    public function reportPrint(Request $request)
    {
        // Mengambil parameter filter tanggal dari request untuk tampilan cetak
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        // Membangun query yang sama dengan reportIndex untuk mendapatkan data yang konsisten
        $query = Reservasi::with(['tamu', 'penginapan', 'pembayaran'])
            ->whereIn('status', ['confirmed', 'completed']);

        // Menambahkan filter tanggal jika parameter diisi
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_checkin', [$startDate, $endDate]);
        }

        // Mengeksekusi query dan menghitung total pendapatan & total pengunjung
        $reports         = $query->orderBy('tanggal_checkin', 'asc')->get();
        $totalRevenue    = $reports->sum(function ($r) {
            return $r->pembayaran->total_pembayaran ?? 0;
        });
        $totalPengunjung = $reports->sum('jumlah_tamu');

        // Mengembalikan tampilan halaman laporan siap cetak (print view)
        return view('admin.reports.print', compact('reports', 'totalRevenue', 'totalPengunjung', 'startDate', 'endDate'));
    }


    /**
     * Show admin profile page.
     * Menampilkan halaman profil admin yang sedang login.
     */
    public function showProfile()
    {
        // Mengambil data admin berdasarkan ID yang tersimpan di sesi login
        $admin = Pengelola::findOrFail(session('admin_id'));

        // Mengembalikan tampilan halaman profil admin
        return view('admin.profile', compact('admin'));
    }

    /**
     * Update admin profile info.
     * Memperbarui informasi profil admin (nama dan email).
     */
    public function updateProfile(Request $request)
    {
        // Mengambil ID admin dari sesi yang sedang login
        $adminId = session('admin_id');

        // Mencari data admin berdasarkan ID sesi
        $admin = Pengelola::findOrFail($adminId);

        // Memvalidasi data profil baru yang dikirimkan dari form edit profil
        $request->validate([
            'nama_pengelola' => 'required|string|max:50',
            // Memastikan email unik kecuali untuk akun admin yang sedang diedit (ignore diri sendiri)
            'email'          => 'required|email|unique:pengelola,email,' . $adminId . ',id_pengelola',
        ], [
            // Pesan validasi kustom dalam bahasa Indonesia
            'nama_pengelola.required' => 'Nama lengkap wajib diisi.',
            'nama_pengelola.max'      => 'Nama lengkap maksimal 50 karakter.',
            'email.required'          => 'Email wajib diisi.',
            'email.email'             => 'Format email tidak valid.',
            'email.unique'            => 'Email sudah digunakan oleh akun lain.',
        ]);

        // Memperbarui data nama dan email admin di database
        $admin->update([
            'nama_pengelola' => $request->nama_pengelola,
            'email'          => $request->email,
        ]);

        // Memperbarui informasi admin yang tersimpan di sesi agar tampilan navbar ikut ter-update
        session([
            'admin_nama'  => $admin->nama_pengelola,
            'admin_email' => $admin->email,
        ]);

        // Mengarahkan kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update admin password.
     * Memperbarui kata sandi admin setelah memverifikasi kata sandi lama.
     */
    public function updatePassword(Request $request)
    {
        // Mengambil ID admin dari sesi yang sedang login
        $adminId = session('admin_id');

        // Mencari data admin berdasarkan ID sesi
        $admin = Pengelola::findOrFail($adminId);

        // Memvalidasi input kata sandi lama dan kata sandi baru dari form ganti password
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed', // 'confirmed' berarti harus ada field new_password_confirmation
        ], [
            // Pesan validasi kustom dalam bahasa Indonesia
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'new_password.required'     => 'Kata sandi baru wajib diisi.',
            'new_password.min'          => 'Kata sandi baru minimal 6 karakter.',
            'new_password.confirmed'    => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // Membandingkan kata sandi lama yang dimasukkan dengan yang tersimpan (plaintext karena sistem login lama)
        if ($request->current_password !== $admin->password) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.']);
        }

        // Menyimpan kata sandi baru ke database (plaintext sesuai sistem login yang ada)
        $admin->update([
            'password' => $request->new_password,
        ]);

        // Mengarahkan kembali ke halaman sebelumnya dengan pesan sukses ganti password
        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }

    /**
     * Memproses checkout manual tamu oleh admin (confirmed → completed).
     * Jika checkout melewati jam 12:00 di tanggal checkout, admin bisa memilih
     * apakah perlu menambahkan biaya 1 malam tambahan (late checkout fee).
     */
    public function checkoutBooking(Request $request, $id)
    {
        $booking = Reservasi::with('pembayaran')->findOrFail($id);

        // Hanya reservasi berstatus 'confirmed' yang dapat di-checkout
        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Hanya reservasi berstatus Terkonfirmasi yang dapat di-checkout.');
        }

        $now          = now(); // Waktu sekarang (server)
        $checkoutDate = \Carbon\Carbon::parse($booking->tanggal_checkout);
        $checkoutDeadline = $checkoutDate->copy()->setTime(12, 0, 0); // Batas checkout jam 12:00

        $isLate       = $now->gt($checkoutDeadline); // Apakah sudah lewat jam 12:00 pada tanggal checkout?
        $addLateCharge = $request->boolean('late_checkout'); // Admin memilih tambah biaya

        // Jika terlambat dan admin memilih tambah biaya 1 malam
        if ($isLate && $addLateCharge && $booking->pembayaran) {
            $biayaTambahan = $booking->penginapan->harga * $booking->jumlah_kamar; // 1 malam tambahan
            $totalBaru = $booking->pembayaran->total_pembayaran + $biayaTambahan;

            $booking->pembayaran->update([
                'total_pembayaran' => $totalBaru,
                'status_pembayaran' => 'lunas',
                'status_pelunasan'  => 'lunas',
                'sisa_pembayaran'   => 0,
                'catatan' => ($booking->pembayaran->catatan ? $booking->pembayaran->catatan . ' | ' : '')
                           . 'Late checkout fee: +Rp ' . number_format($biayaTambahan, 0, ',', '.') . ' (1 malam @ ' . now()->format('H:i') . ')',
            ]);

            $pesan = 'Late checkout berhasil dicatat. Biaya tambahan 1 malam (Rp ' . number_format($biayaTambahan, 0, ',', '.') . ') telah ditambahkan.';
        } else {
            // Checkout normal — tandai pembayaran lunas
            if ($booking->pembayaran) {
                $booking->pembayaran->update([
                    'status_pembayaran' => 'lunas',
                    'status_pelunasan'  => 'lunas',
                    'sisa_pembayaran'   => 0,
                ]);
            }
            $pesan = 'Checkout berhasil dikonfirmasi.';
        }

        // Tandai reservasi selesai
        $booking->update(['status' => 'completed']);

        // Kirim notifikasi otomatis (Email & WhatsApp)
        NotificationService::sendBookingStatusNotification($booking, 'Tamu telah melakukan checkout. Terima kasih telah menginap di Tanjung Api!');

        return back()->with('success', $pesan . ' Reservasi #TA-' . str_pad($booking->id_reservasi, 4, '0', STR_PAD_LEFT) . ' telah selesai.');
    }

    /**
     * Mengonfirmasi transfer pengembalian dana (refund) selesai oleh Admin.
     */
    public function confirmRefund($id)
    {
        $booking = Reservasi::findOrFail($id);
        if ($booking->status_refund === 'pending_refund') {
            $booking->update([
                'status_refund' => 'refunded'
            ]);
            return back()->with('success', 'Pengembalian dana (refund) sebesar Rp ' . number_format($booking->jumlah_refund, 0, ',', '.') . ' telah dikonfirmasi dan selesai ditransfer.');
        }

        return back()->with('error', 'Status refund tidak dapat diubah.');
    }

    /**
     * Mengonfirmasi pelunasan sisa DP 50% oleh Admin saat tamu check-in di lokasi.
     */
    public function confirmPelunasan($id)
    {
        $booking = Reservasi::with('pembayaran')->findOrFail($id);
        $pembayaran = $booking->pembayaran;

        if ($pembayaran && $pembayaran->jenis_pembayaran === 'dp' && $pembayaran->status_pelunasan === 'belum_lunas') {
            $sisa = $pembayaran->sisa_pembayaran;
            $pembayaran->update([
                'status_pelunasan' => 'lunas',
                'sisa_pembayaran'   => 0,
            ]);
            return back()->with('success', 'Pelunasan sisa DP 50% sebesar Rp ' . number_format($sisa, 0, ',', '.') . ' telah berhasil dikonfirmasi LUNAS (100%).');
        }

        return back()->with('error', 'Status pelunasan tidak dapat diubah.');
    }
}


