<?php

// Mendefinisikan namespace model agar dapat dikenali oleh Laravel
namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk class ini
use Illuminate\Database\Eloquent\Model;

// Mendefinisikan class DetailPembayaran sebagai model yang merepresentasikan tabel 'detail_pembayaran' di database
class DetailPembayaran extends Model
{
    // Menentukan nama tabel database yang digunakan oleh model ini
    protected $table = 'detail_pembayaran';

    // Menentukan nama kolom primary key pada tabel detail_pembayaran
    protected $primaryKey = 'id_detail';

    // Mendefinisikan kolom-kolom yang boleh diisi secara massal (mass assignment)
    protected $fillable = ['id_pembayaran', 'id_gateway', 'jenis_pembayaran', 'jumlah_pembayaran', 'tanggal_detail'];

    /**
     * Relasi ke model Pembayaran (Many-to-One / Belongs To).
     * Setiap detail pembayaran terkait dengan satu data pembayaran utama.
     */
    public function pembayaran()
    {
        // Mendefinisikan relasi belongsTo ke model Pembayaran menggunakan foreign key 'id_pembayaran'
        return $this->belongsTo(Pembayaran::class, 'id_pembayaran', 'id_pembayaran');
    }

    /**
     * Relasi ke model PaymentGateway (Many-to-One / Belongs To).
     * Setiap detail pembayaran terkait dengan satu payment gateway yang digunakan (misalnya: Midtrans).
     */
    public function gateway()
    {
        // Mendefinisikan relasi belongsTo ke model PaymentGateway menggunakan foreign key 'id_gateway'
        return $this->belongsTo(PaymentGateway::class, 'id_gateway', 'id_gateway');
    }
}
