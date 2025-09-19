<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Book;
use App\Models\Perhitungan;
use App\Models\DetailPerhitungan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        $latestBooks = Book::orderBy('created_at', 'desc')->take(3)->get();

        $topRecommendedBooks = [];

        if (Auth::check()) {
            $userId = Auth::id();
            $latestPerhitunganId = DetailPerhitungan::where('id_user', $userId)
                ->orderByDesc('id_perhitungan')
                ->value('id_perhitungan');

            if ($latestPerhitunganId) {
                $topRecommendedBooks = DetailPerhitungan::where('id_user', $userId)
                    ->where('id_perhitungan', $latestPerhitunganId)
                    ->with(['book', 'normalisasi']) // Pastikan 'normalisasi' di-load di sini
                    ->orderByDesc('skor_akhir')
                    ->take(3)
                    ->get();
            }
        }

        return view('index', compact('latestBooks', 'topRecommendedBooks'));
    }
    // public function index()
    // {
    //     $latestBooks = Book::orderBy('created_at', 'desc')->take(3)->get(); // Mengambil 3 buku terbaru
    //     return view('index', compact('latestBooks'));
    // }


    public function category()
    {
        // if ($_POST) {
        //     $selectedCategory = $_POST['selectedCategory'];
        // }
        // return view('index', [
        //     'categories' => Category::all(),
        //     // 'books_fiksi' => Book::where('category_id', 1)->take(4)->get(),
        //     // 'books_nonfiksi' => Book::where('category_id', 2)->take(4)->get(),
        //     'selectedCategory' => Book::where('category_id', $selectedCategory)->take(4)->get()
        // ]);
    }
}
