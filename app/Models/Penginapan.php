<?php

// Mendefinisikan namespace model agar dapat dikenali oleh Laravel
namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk class ini
use Illuminate\Database\Eloquent\Model;

// Mendefinisikan class Penginapan sebagai model yang merepresentasikan tabel 'penginapan' di database
class Penginapan extends Model
{
    // Menentukan nama tabel database yang digunakan oleh model ini
    protected $table = 'penginapan';

    // Menentukan nama kolom primary key pada tabel penginapan
    protected $primaryKey = 'id_unit';

    // Mendefinisikan kolom-kolom yang boleh diisi secara massal (mass assignment)
    // Termasuk: ID pengelola, nama unit, alamat, fasilitas, harga, tipe unit, jumlah unit, dan foto
    protected $fillable = ['id_pengelola', 'nama_unit', 'alamat', 'fasilitas', 'harga', 'tipe_unit', 'jumlah_unit', 'foto'];

    /**
     * Relasi ke model Pengelola (Many-to-One / Belongs To).
     * Setiap unit penginapan dimiliki dan dikelola oleh satu pengelola (admin).
     */
    public function pengelola()
    {
        // Mendefinisikan relasi belongsTo ke model Pengelola menggunakan foreign key 'id_pengelola'
        return $this->belongsTo(Pengelola::class, 'id_pengelola', 'id_pengelola');
    }

    /**
     * Relasi ke galeri foto tambahan (One-to-Many).
     */
    public function fotos()
    {
        return $this->hasMany(FotoPenginapan::class, 'id_unit', 'id_unit');
    }

    /**
     * Mengambil daftar seluruh foto (foto utama + galeri foto tambahan).
     */
    public function getSemuaFotoAttribute(): array
    {
        $list = [];
        if ($this->foto) {
            $list[] = $this->foto;
        }
        foreach ($this->fotos as $galeri) {
            if ($galeri->foto && !in_array($galeri->foto, $list)) {
                $list[] = $galeri->foto;
            }
        }
        return count($list) > 0 ? $list : ['hero_beach.png'];
    }
}
