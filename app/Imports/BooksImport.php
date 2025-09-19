<?php

namespace App\Imports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BooksImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Fungsi ini memetakan setiap baris di Excel ke dalam model Book.
        // Pastikan nama kolom di file Excel Anda (heading row) sama persis
        // dengan key yang ada di sini (misal: 'judul', 'penulis', 'tahun_terbit').
        return new Book([
            'title'       => $row['judul'],
            'code'        => $row['kode_buku'],
            'author'      => $row['penulis'],
            'year'        => $row['tahun_terbit'],
            'publisher'   => $row['penerbit'],
            'description' => $row['deskripsi'],
            'category'    => $row['kategori'],
            'stock'       => $row['stok'],
            'pages'       => $row['jumlah_halaman'],
            'language'    => $row['bahasa'],
            'isbn_issn'   => $row['isbn_issn'],
            'content_type' => $row['tipe_isi'],
            'media_type'   => $row['tipe_media'],
            'carrier_type' => $row['tipe_pembawa'],
            'edition'      => $row['edisi'],
            'subject'      => $row['subjek'],
        ]);
    }

    /**
     * Menentukan aturan validasi untuk setiap baris di file Excel.
     * Jika ada baris yang tidak lolos validasi, seluruh proses impor akan dibatalkan.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'kode_buku' => 'required|string|unique:books,code',
            'penulis' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1000',
            'penerbit' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'jumlah_halaman' => 'nullable|integer',
            'bahasa' => 'nullable|string',
            'isbn_issn' => 'nullable|string',
            'tipe_isi'     => 'nullable|string',
            'tipe_media'   => 'nullable|string',
            'tipe_pembawa' => 'nullable|string',
            'edisi'        => 'nullable|string',
            'subjek'       => 'nullable|string',
        ];
    }
}
