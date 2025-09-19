<?php

namespace App\Models;
use App\Models\DetailPerhitungan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perhitungan extends Model
{
    use HasFactory;

    protected $table = 'perhitungan';

    protected $primaryKey = 'id_perhitungan';

    protected $fillable = [
        'tanggal',
    ];

    public $timestamps = false; // <== TAMBAHKAN INI


    public function details()
    {
        return $this->hasMany(DetailPerhitungan::class, 'id_perhitungan');
    }
}
