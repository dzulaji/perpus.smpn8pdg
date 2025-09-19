<?php

namespace App\Models;
use App\Models\DetailPerhitungan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Normalisasi extends Model
{
    use HasFactory;

    protected $table = 'normalisasi'; // Sesuai nama tabel database
    protected $primaryKey = 'id_normalisasi';

    protected $fillable = [
        'id_detail',
        'normalisasi',
        'utilities',
    ];
    public $timestamps = false; // <== TAMBAHKAN INI

    // Relasi ke tabel Book
    public function detail()
    {
        return $this->belongsTo(DetailPerhitungan::class, 'id_detail');
    }
}
