<?php

// Mendefinisikan namespace model agar dapat dikenali oleh Laravel
namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk class ini
use Illuminate\Database\Eloquent\Model;

// Mendefinisikan class PaymentGateway sebagai model yang merepresentasikan tabel 'payment_gateway' di database
// Model ini menyimpan data penyedia layanan pembayaran yang diintegrasikan (misalnya: Midtrans)
class PaymentGateway extends Model
{
    // Menentukan nama tabel database yang digunakan oleh model ini
    protected $table = 'payment_gateway';

    // Menentukan nama kolom primary key pada tabel payment_gateway
    protected $primaryKey = 'id_gateway';

    // Mendefinisikan kolom-kolom yang boleh diisi secara massal (mass assignment)
    // Termasuk: nama gateway pembayaran dan informasi/deskripsi gateway
    protected $fillable = ['nama_gateway', 'info_gateway'];
}
