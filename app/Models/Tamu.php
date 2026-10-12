<?php

// Mendefinisikan namespace model agar dapat dikenali oleh Laravel
namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk class ini
use Illuminate\Database\Eloquent\Model;

// Mendefinisikan class Tamu sebagai model yang merepresentasikan tabel 'tamu' di database
// Model ini menyimpan data tamu yang mendaftar atau melakukan walk-in
class Tamu extends Model
{
    // Menentukan nama tabel database yang digunakan oleh model ini
    protected $table = 'tamu';

    // Menentukan nama kolom primary key pada tabel tamu
    protected $primaryKey = 'id_tamu';

    // Mendefinisikan kolom-kolom yang boleh diisi secara massal (mass assignment)
    protected $fillable = [
        'nama', 
        'no_ktp',
        'kewarganegaraan',
        'no_paspor',
        'alamat_ktp',
        'provinsi',
        'kabupaten_kota',
        'kecamatan',
        'negara',
        'email', 
        'email_verified_at', 
        'email_verification_token', 
        'no_hp', 
        'no_hp_verified_at', 
        'no_hp_verification_code', 
        'password', 
        'reset_password_token',
        'reset_password_token_created_at',
        'jenis_kelamin', 
        'foto', 
        'sumber'
    ];

    // Menyembunyikan kolom password dan token dari hasil query untuk keamanan
    protected $hidden = ['password', 'email_verification_token', 'no_hp_verification_code', 'reset_password_token'];

    // Casting tipe data kolom timestamp
    protected $casts = [
        'email_verified_at' => 'datetime',
        'no_hp_verified_at' => 'datetime',
        'reset_password_token_created_at' => 'datetime',
    ];

    /**
     * Memeriksa apakah email tamu sudah diverifikasi.
     */
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Memeriksa apakah nomor HP tamu sudah diverifikasi.
     */
    public function hasVerifiedPhone(): bool
    {
        return !is_null($this->no_hp_verified_at);
    }

    /**
     * Memeriksa apakah akun tamu belum verifikasi email dan sudah berumur lebih dari 3 hari.
     */
    public function isExpiredUnverified(): bool
    {
        if ($this->hasVerifiedEmail()) {
            return false;
        }

        // Jika pendaftaran lebih dari 3 hari yang lalu dan email belum diverifikasi
        return $this->created_at <= now()->subDays(3);
    }

    /**
     * Mengambil susunan alamat lengkap terstruktur.
     */
    public function getAlamatLengkapFormattedAttribute(): string
    {
        if ($this->kewarganegaraan === 'WNA') {
            $parts = array_filter([
                $this->alamat_ktp,
                $this->negara ? "Negara: {$this->negara}" : null,
                $this->no_paspor ? "Paspor: {$this->no_paspor}" : null,
            ]);
            return implode(', ', $parts) ?: 'Luar Negeri';
        }

        $parts = array_filter([
            $this->alamat_ktp,
            $this->kecamatan ? "Kec. {$this->kecamatan}" : null,
            $this->kabupaten_kota ? "Kab/Kota {$this->kabupaten_kota}" : null,
            $this->provinsi ? "Prov. {$this->provinsi}" : null,
        ]);

        return implode(', ', $parts) ?: '-';
    }
}

