<?php

// Mendefinisikan namespace model agar dapat dikenali oleh Laravel
namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk class ini
use Illuminate\Database\Eloquent\Model;
// Mengimpor library Carbon untuk pengelolaan dan manipulasi tanggal/waktu
use Carbon\Carbon;

// Mendefinisikan class Reservasi sebagai model yang merepresentasikan tabel 'reservasi' di database
class Reservasi extends Model
{
    // Menentukan nama tabel database yang digunakan oleh model ini
    protected $table = 'reservasi';

    // Menentukan nama kolom primary key pada tabel reservasi
    protected $primaryKey = 'id_reservasi';

    // Mendefinisikan kolom-kolom yang boleh diisi secara massal (mass assignment)
    protected $fillable = [
        'id_tamu', 
        'id_unit', 
        'jumlah_kamar', 
        'tanggal_checkin', 
        'tanggal_checkout', 
        'jumlah_tamu', 
        'status', 
        'sumber',
        'persentase_refund',
        'jumlah_refund',
        'alasan_pembatalan',
        'bank_refund',
        'rekening_refund',
        'nama_rekening_refund',
        'status_refund',
        'tanggal_penundaan',
        'batas_tunda_hingga',
        'alasan_penundaan'
    ];

    protected $casts = [
        'tanggal_penundaan' => 'datetime',
        'batas_tunda_hingga' => 'datetime',
    ];


    /**
     * Relasi ke model Tamu (Many-to-One / Belongs To).
     * Setiap reservasi dimiliki oleh satu tamu.
     */
    public function tamu()
    {
        // Mendefinisikan relasi belongsTo ke model Tamu menggunakan foreign key 'id_tamu'
        return $this->belongsTo(Tamu::class, 'id_tamu', 'id_tamu');
    }

    /**
     * Relasi ke model Penginapan (Many-to-One / Belongs To).
     * Setiap reservasi terkait dengan satu unit penginapan.
     */
    public function penginapan()
    {
        // Mendefinisikan relasi belongsTo ke model Penginapan menggunakan foreign key 'id_unit'
        return $this->belongsTo(Penginapan::class, 'id_unit', 'id_unit');
    }

    /**
     * Relasi ke model Pembayaran (One-to-One / Has One).
     * Setiap reservasi memiliki satu data pembayaran.
     */
    public function pembayaran()
    {
        // Mendefinisikan relasi hasOne ke model Pembayaran menggunakan foreign key 'id_reservasi'
        return $this->hasOne(Pembayaran::class, 'id_reservasi', 'id_reservasi');
    }

    /**
     * Batas waktu pembayaran: 24 jam sejak reservasi dibuat.
     * Mengembalikan waktu kedaluwarsa reservasi dalam format Carbon.
     */
    public function expiresAt(): Carbon
    {
        // Menghitung waktu kedaluwarsa dengan menambahkan 24 jam ke waktu reservasi dibuat
        return $this->created_at->addHours(24);
    }

    /**
     * Cek apakah reservasi sudah kadaluarsa (belum bayar > 24 jam).
     * Mengembalikan true jika reservasi masih pending dan sudah melewati batas waktu 24 jam.
     */
    public function isExpired(): bool
    {
        // Reservasi dianggap kadaluarsa jika statusnya 'pending' dan waktu sekarang sudah melebihi waktu kedaluwarsa
        return $this->status === 'pending' && Carbon::now()->greaterThan($this->expiresAt());
    }

    /**
     * Sisa waktu dalam detik sebelum reservasi kadaluarsa.
     * Digunakan untuk menampilkan countdown timer di halaman pembayaran.
     */
    public function secondsUntilExpiry(): int
    {
        // Menghitung selisih waktu antara sekarang dan waktu kedaluwarsa dalam detik (minimal 0)
        return max(0, (int) Carbon::now()->diffInSeconds($this->expiresAt(), false));
    }

    /**
     * Auto-cancel reservasi yang belum bayar lebih dari 24 jam atau penundaan yang sudah lewat 1 bulan.
     * Mengembalikan jumlah reservasi yang dibatalkan.
     */
    public static function autoCancelExpired(): int
    {
        $count = 0;

        // 1. Reservasi pending >24 jam atau check-in sudah lewat
        $expiredAt = Carbon::now()->subHours(24);
        $today = Carbon::now()->toDateString();

        $expiredIds = self::where('status', 'pending')
            ->where(function ($query) use ($expiredAt, $today) {
                $query->where('created_at', '<=', $expiredAt)
                      ->orWhere('tanggal_checkin', '<', $today);
            })
            ->pluck('id_reservasi');

        if ($expiredIds->isNotEmpty()) {
            $count += self::whereIn('id_reservasi', $expiredIds)
                ->update(['status' => 'cancelled']);
        }

        // 2. Reservasi ditunda (postponed) yang sudah melewati batas_tunda_hingga (1 bulan)
        $expiredPostponed = self::where('status', 'postponed')
            ->where('batas_tunda_hingga', '<', Carbon::now())
            ->get();

        foreach ($expiredPostponed as $booking) {
            $booking->update([
                'status'            => 'cancelled',
                'persentase_refund' => 0,
                'jumlah_refund'     => 0,
                'status_refund'     => 'no_refund',
                'alasan_pembatalan' => 'Masa berlaku penundaan (1 bulan) telah berakhir tanpa penjadwalan ulang (uang hangus).'
            ]);
            $count++;
        }

        return $count;
    }

    /**
     * Auto checkout bookings that have passed their checkout date.
     * Fungsi statis yang secara otomatis mengubah status reservasi menjadi 'completed'
     * jika tanggal checkout sudah lewat dan memastikan status pembayaran lunas.
     */
    public static function autoCheckout()
    {
        // Mencari semua reservasi dengan status 'confirmed' yang tanggal checkout-nya sudah lewat
        $completedIds = self::where('status', 'confirmed')
            ->where('tanggal_checkout', '<', now()->toDateString())
            ->pluck('id_reservasi');

        if ($completedIds->isNotEmpty()) {
            self::whereIn('id_reservasi', $completedIds)
                ->update(['status' => 'completed']);
        }

        // Memastikan seluruh reservasi yang 'confirmed' atau 'completed' memiliki status_pembayaran 'lunas'
        $paidReservasiIds = self::whereIn('status', ['confirmed', 'completed'])->pluck('id_reservasi');
        if ($paidReservasiIds->isNotEmpty()) {
            Pembayaran::whereIn('id_reservasi', $paidReservasiIds)
                ->where('status_pembayaran', '!=', 'lunas')
                ->update(['status_pembayaran' => 'lunas']);
        }
    }

    /**
     * Perhitungan refund untuk kebijakan pembatalan & penundaan.
     * Aturan:
     * - Pembatalan langsung: 0% refund (Uang Hangus).
     * - Penundaan (Pending): Diberi waktu 1 bulan untuk reschedule.
     */
    public function getRefundCalculationAttribute(): array
    {
        $totalPay = $this->pembayaran ? (float) $this->pembayaran->total_pembayaran : 0;

        return [
            'percentage'   => 0,
            'amount'       => 0,
            'label'        => 'Pengembalian Dana 0% (Hangus)',
            'badge_color'  => 'bg-red-100 text-red-800 border-red-300',
            'description'  => 'Sesuai ketentuan, pembatalan reservasi menyebabkan dana hangus (0% refund). Anda dapat memilih opsi Penundaan jika ingin menjadwalkan ulang dalam 1 bulan.',
        ];
    }
}

