<?php

// Mendefinisikan namespace model agar dapat dikenali oleh Laravel
namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk class ini
use Illuminate\Database\Eloquent\Model;

// Mendefinisikan class Pengelola sebagai model yang merepresentasikan tabel 'pengelola' di database
// Model ini menyimpan data admin/pengelola yang dapat mengakses panel admin
class Pengelola extends Model
{
    // Menentukan nama tabel database yang digunakan oleh model ini
    protected $table = 'pengelola';

    // Menentukan nama kolom primary key pada tabel pengelola
    protected $primaryKey = 'id_pengelola';

    protected $fillable = ['nama_pengelola', 'password', 'email', 'reset_password_token', 'reset_password_token_created_at'];

    // Menyembunyikan kolom password dan token dari hasil query untuk menjaga keamanan data sensitif admin
    protected $hidden = ['password', 'reset_password_token'];

    protected $casts = [
        'reset_password_token_created_at' => 'datetime',
    ];
}
