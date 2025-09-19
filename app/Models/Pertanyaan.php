<?php

namespace App\Models;
use App\Models\Kriteria; // REVISI: Gunakan huruf kapital 'K'

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    use HasFactory;

    protected $table = 'pertanyaan';
    protected $primaryKey = 'id_pertanyaan';
    public $timestamps = false;

    protected $fillable = [
        'id_kriteria',
        'pertanyaan',
    ];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria');
    }
}
