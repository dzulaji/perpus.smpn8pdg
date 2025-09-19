<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Imports\BooksImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

use Illuminate\Http\Request;

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
            'media_type' =>'required',
            'link' => 'required_if:media_type,Buku Elektronik|mimes:pdf|max:10000',
            'carrier_type' => 'required',
            'edition' => 'required',
            'subject' => 'required',
        ]);

        if (isset($validatedData['cover'])) {
            $validatedData['cover'] = $request->file('cover')->store('books-cover');
        }

        if ($request->hasFile('link') && $request->file('link')->isValid()) {
            $file = $request->file('link');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('buku_pdf', $filename, 'public');
            $validatedData['link'] = $path;
        }

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
            'link' => 'nullable|mimes:pdf|max:10000',
            'carrier_type' => 'required',
            'edition' => 'required',
            'subject' => 'required',
        ]);

        if (isset($validatedData['cover'])) {
            $validatedData['cover'] = $request->file('cover')->store('books-cover');
        }

        if ($request->hasFile('link') && $request->file('link')->isValid()) {
            $file = $request->file('link');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('buku_pdf', $filename, 'public');
            $validatedData['link'] = $path;
        }

        $book->update($validatedData);

        return redirect()->back()->with('success', 'Buku berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
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
