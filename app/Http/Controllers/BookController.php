<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchKeyword = $request->input('searchKeyword');

        // Cek apakah ada kata kunci pencarian
        if ($searchKeyword) {
            // Cari buku berdasarkan judul atau deskripsi
            $books = Book::where('title', 'like', '%' . $searchKeyword . '%')
                        ->orWhere('description', 'like', '%' . $searchKeyword . '%')
                        ->get();
        } else {
            // Jika tidak ada kata kunci, tampilkan semua buku
            $books = Book::all();
        }

        return view('pages.books', [
            'books' => $books,
            'searchKeyword' => $searchKeyword,  // Kirim keyword untuk keperluan alert
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return view('pages.booksDetail', [
            'book' => $book,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }
}

