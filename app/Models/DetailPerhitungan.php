<?php

namespace App\Models;

use App\Models\Book;
use App\Models\User;
use App\Models\Perhitungan;
use App\Models\Normalisasi; // Tambahkan use Normalisasi

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPerhitungan extends Model
{
    use HasFactory;

    protected $table = 'detail_perhitungan';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $fillable = [
        'id_perhitungan',
        'id',
        'skor_akhir',
        'id_user',
    ];

    public function perhitungan()
    {
        return $this->belongsTo(Perhitungan::class, 'id_perhitungan');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'id');
    }

    // REVISI: Tambahkan relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function normalisasi()
    {
        return $this->hasOne(Normalisasi::class, 'id_detail', 'id_detail');
    }
}
