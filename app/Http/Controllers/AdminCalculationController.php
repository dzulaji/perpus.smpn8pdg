<?php

namespace App\Http\Controllers;

use App\Models\Perhitungan;
use App\Models\DetailPerhitungan;
use Illuminate\Http\Request;

class AdminCalculationController extends Controller
{
    public function index()
    {
        // Ambil semua data perhitungan
        $perhitungans = Perhitungan::with(['details.book', 'details'])->get();

        // Siapkan array hasil yang akan dikirim ke view
        $results = $perhitungans->map(function ($perhitungan) {
            // Ambil detail dengan skor_akhir tertinggi untuk perhitungan ini
            $topDetail = $perhitungan->details->sortByDesc('skor_akhir')->first();

            return [
                'id_perhitungan' => $perhitungan->id_perhitungan,
                'tanggal' => $perhitungan->tanggal,
                'skor_akhir' => $topDetail?->skor_akhir ?? '-',
                'book_title' => $topDetail?->book->title ?? '-',
                'user_name' => $topDetail?->user->name ?? '-',
            ];
        });

        return view('admin.pages.calculation.index', [
            'results' => $results
        ]);
    }
}
