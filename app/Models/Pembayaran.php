<?php

// Mendefinisikan namespace model agar dapat dikenali oleh Laravel
namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk class ini
use Illuminate\Database\Eloquent\Model;

// Mendefinisikan class Pembayaran sebagai model yang merepresentasikan tabel 'pembayaran' di database
class Pembayaran extends Model
{
    // Menentukan nama tabel database yang digunakan oleh model ini
    protected $table = 'pembayaran';

    // Menentukan nama kolom primary key pada tabel pembayaran
    protected $primaryKey = 'id_pembayaran';

    // Mendefinisikan kolom-kolom yang boleh diisi secara massal (mass assignment)
    protected $fillable = [
        'id_reservasi', 
        'tanggal_pembayaran', 
        'total_pembayaran', 
        'metode_pembayaran', 
        'jenis_pembayaran',
        'nominal_dp',
        'sisa_pembayaran',
        'status_pelunasan',
        'bank_tujuan', 
        'status_pembayaran'
    ];


    /**
     * Relasi ke model Reservasi (Many-to-One / Belongs To).
     * Setiap pembayaran terkait dengan satu reservasi.
     */
    public function reservasi()
    {
        // Mendefinisikan relasi belongsTo ke model Reservasi menggunakan foreign key 'id_reservasi'
        return $this->belongsTo(Reservasi::class, 'id_reservasi', 'id_reservasi');
    }

    /**
     * Relasi ke model DetailPembayaran (One-to-One / Has One).
     * Setiap pembayaran memiliki satu detail pembayaran (informasi gateway, jenis, dll).
     */
    public function detailPembayaran()
    {
        // Mendefinisikan relasi hasOne ke model DetailPembayaran menggunakan foreign key 'id_pembayaran'
        return $this->hasOne(DetailPembayaran::class, 'id_pembayaran', 'id_pembayaran');
    }
}
