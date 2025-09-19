<?php

// App/Models/Kriteria.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';
    protected $primaryKey = 'id_kriteria';
    public $timestamps = false;

    protected $fillable = [
        'kriteria',
        'bobot',
        'tipe_aturan',
        'kolom_buku',
    ];

    // REVISI: Gunakan hasOne karena hubungannya satu-ke-satu
    public function pertanyaan()
    {
        return $this->hasOne(Pertanyaan::class, 'id_kriteria', 'id_kriteria');
    }

    // REVISI: Tambahkan relasi baru ke sub_kriteria
    public function subKriteria()
    {
        return $this->hasMany(SubKriteria::class, 'id_kriteria', 'id_kriteria')->orderBy('id_sub_kriteria', 'asc');
    }
}
