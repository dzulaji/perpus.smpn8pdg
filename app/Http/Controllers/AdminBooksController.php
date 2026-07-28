<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Imports\BooksImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class AdminBooksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.pages.books.index", [
            'books' => Book::all(),
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
        $validatedData = $request->validate([
            'title' => 'required|min:3',
            'author' => 'required',
            'year' => 'required',
            'publisher' => 'required',
            'category' => 'required',
            'description' => 'required|min:10',
            'stock' => 'required|integer',
            'cover' => 'image|file|max:1024',
            'pages' => 'required|integer',
            'language' => 'required',
            'isbn_issn' => 'required',
            'content_type' => 'required',
            'media_type' =>'required',
            'link' => 'nullable|required_if:media_type,Buku Elektronik',
            'carrier_type' => 'required',
            'edition' => 'required',
            'subject' => 'required',
        ]);

        if (isset($validatedData['cover'])) {
            $validatedData['cover'] = $request->file('cover')->store('books-cover', 'public');
        }

        // Generate kode unik acak 5 karakter
        do {
            $code = strtoupper(Str::random(5));
        } while (Book::where('code', $code)->exists());
        $validatedData['code'] = $code;

        Book::create($validatedData);

        return redirect()->back()->with('success', 'Buku berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return view('admin.pages.books.show', [
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
        $validatedData = $request->validate([
            'title' => 'required|min:3',
            'code' => 'required|min:5',
            'author' => 'required',
            'year' => 'required',
            'publisher' => 'required',
            'category' => 'required',
            'description' => 'required|min:10',
            'stock' => 'required|integer',
            'cover' => 'image|file|max:1024',
            'pages' => 'required|integer',
            'language' => 'required',
            'isbn_issn' => 'required',
            'content_type' => 'required',
            'media_type' => 'required',
            'link' => 'nullable|required_if:media_type,Buku Elektronik',
            'carrier_type' => 'required',
            'edition' => 'required',
            'subject' => 'required',
        ]);

        if (isset($validatedData['cover'])) {
            if ($book->cover) {
                if (Storage::disk('public')->exists($book->cover)) {
                    Storage::disk('public')->delete($book->cover);
                } elseif (Storage::exists($book->cover)) {
                    Storage::delete($book->cover);
                }
            }
            $validatedData['cover'] = $request->file('cover')->store('books-cover', 'public');
        }

        $book->update($validatedData);

        return redirect()->back()->with('success', 'Buku berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        // Hapus fisik cover dari server
        if ($book->cover) {
            if (Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            } elseif (Storage::exists($book->cover)) {
                Storage::delete($book->cover);
            }
        }

        $book->delete();

        return redirect('/admin/books')->with('success', 'buku berhasil dihapus!');
    }

    /**
     * Menangani proses impor data buku dari file Excel.
     */
    public function import(Request $request)
    {
        // 1. Validasi file yang di-upload
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            // 2. Lakukan impor
            Excel::import(new BooksImport, $request->file('file'));

            // 3. Jika berhasil, kembalikan dengan pesan sukses
            return redirect()->back()->with('success', 'Data buku berhasil diimpor!');

        } catch (ValidationException $e) {
            // 4. Jika terjadi error validasi di dalam file Excel, tangkap dan tampilkan
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Error di baris <strong>" . $failure->row() . "</strong>: " . implode(', ', $failure->errors());
            }
            return redirect()->back()->with('import_errors', $errorMessages);
        }
    }
}
