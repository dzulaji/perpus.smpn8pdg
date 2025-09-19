<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKriteria extends Model
{
    use HasFactory;

    protected $table = 'sub_kriteria';
    protected $primaryKey = 'id_sub_kriteria';
    public $timestamps = false;

    protected $fillable = [
        'id_kriteria',
        'nama_tampilan',
        'nilai',
        'nilai_teks',
        'operator',
        'nilai_angka_1',
        'nilai_angka_2',
    ];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria', 'id_kriteria');
    }
}
