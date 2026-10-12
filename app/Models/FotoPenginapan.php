<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoPenginapan extends Model
{
    protected $table = 'foto_penginapan';
    protected $primaryKey = 'id_foto';

    protected $fillable = [
        'id_unit',
        'foto',
        'is_utama'
    ];

    /**
     * Relasi balik ke model Penginapan.
     */
    public function penginapan()
    {
        return $this->belongsTo(Penginapan::class, 'id_unit', 'id_unit');
    }
}
