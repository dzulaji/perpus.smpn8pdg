<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kriteria; // Import model Kriteria

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Mengosongkan tabel terlebih dahulu untuk menghindari duplikasi saat re-seed
        // Urutan penting karena ada foreign key
        DB::table('normalisasi')->delete();
        DB::table('detail_perhitungan')->delete();
        DB::table('perhitungan')->delete();
        DB::table('bookings')->delete();
        DB::table('sub_kriteria')->delete();
        DB::table('pertanyaan')->delete();
        DB::table('kriteria')->delete();
        DB::table('users')->delete();
        DB::table('books')->delete();

        // Reset auto-increment
        DB::statement('ALTER TABLE kriteria AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE pertanyaan AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE sub_kriteria AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE books AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE bookings AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE perhitungan AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE detail_perhitungan AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE normalisasi AUTO_INCREMENT = 1');


        // =================================================================
        // Seeder untuk Kriteria & Pertanyaan
        // =================================================================
        $kriteriaData = [
            // id_kriteria akan otomatis 1, 2, 3, dst.
            ['kriteria' => 'Tahun Terbit', 'bobot' => 0.15, 'tipe_aturan' => 'ANGKA', 'kolom_buku' => 'year', 'pertanyaan' => 'Buku terbitan tahun berapa yang anda butuhkan?'],
            ['kriteria' => 'Kategori', 'bobot' => 0.20, 'tipe_aturan' => 'TEKS', 'kolom_buku' => 'category', 'pertanyaan' => 'Jenis buku apa yang anda cari?'],
            ['kriteria' => 'Banyak Halaman', 'bobot' => 0.10, 'tipe_aturan' => 'ANGKA', 'kolom_buku' => 'pages', 'pertanyaan' => 'Berapa halaman buku yang anda butuhkan?'],
            ['kriteria' => 'Bahasa', 'bobot' => 0.10, 'tipe_aturan' => 'TEKS', 'kolom_buku' => 'language', 'pertanyaan' => 'Dalam bahasa apa buku yang anda butuhkan?'],
            ['kriteria' => 'Tipe Media', 'bobot' => 0.08, 'tipe_aturan' => 'TEKS', 'kolom_buku' => 'media_type', 'pertanyaan' => 'Berupa media apa buku yang anda cari?'],
            ['kriteria' => 'Tipe Isi', 'bobot' => 0.10, 'tipe_aturan' => 'TEKS', 'kolom_buku' => 'content_type', 'pertanyaan' => 'Apakah anda mencari buku yang hanya berupa teks atau gambar?'],
            ['kriteria' => 'Tipe Pembawa', 'bobot' => 0.07, 'tipe_aturan' => 'TEKS', 'kolom_buku' => 'carrier_type', 'pertanyaan' => 'Apakah buku yang anda cari berupa volume atau tunggal?'],
            ['kriteria' => 'Stok', 'bobot' => 0.05, 'tipe_aturan' => 'ANGKA', 'kolom_buku' => 'stock', 'pertanyaan' => 'Berapa banyak stok untuk buku yang anda cari?'],
            ['kriteria' => 'Edisi', 'bobot' => 0.15, 'tipe_aturan' => 'TEKS', 'kolom_buku' => 'edition', 'pertanyaan' => 'Anda mencari buku edisi berapa?'],
        ];

        foreach ($kriteriaData as $data) {
            // Buat kriteria baru dengan semua data yang sudah lengkap
            $kriteria = Kriteria::create([
                'kriteria' => $data['kriteria'],
                'bobot' => $data['bobot'],
                'tipe_aturan' => $data['tipe_aturan'],
                'kolom_buku' => $data['kolom_buku'], // <- Data kolom_buku langsung dimasukkan
            ]);

            // Buat pertanyaan yang berelasi dengannya
            $kriteria->pertanyaan()->create([
                'pertanyaan' => $data['pertanyaan'],
            ]);
        }

        // =================================================================
        // Seeder untuk SUB-KRITERIA
        // =================================================================
        $subKriteriaData = [
            // Tahun Terbit (id_kriteria = 1)
            1 => [
                ['nama_tampilan' => 'Terbit tahun 2021 atau setelahnya', 'operator' => '>=', 'nilai_angka_1' => 2021],
                ['nama_tampilan' => 'Terbit antara tahun 2018-2020', 'operator' => 'hingga', 'nilai_angka_1' => 2018, 'nilai_angka_2' => 2020],
                ['nama_tampilan' => 'Terbit antara tahun 2015-2017', 'operator' => 'hingga', 'nilai_angka_1' => 2015, 'nilai_angka_2' => 2017],
                ['nama_tampilan' => 'Terbit sebelum tahun 2015', 'operator' => '<', 'nilai_angka_1' => 2015],
            ],
            // Kategori (id_kriteria = 2)
            2 => [
                ['nama_tampilan' => 'Non-Fiksi', 'nilai_teks' => 'Non-Fiksi'],
                ['nama_tampilan' => 'Fiksi', 'nilai_teks' => 'Fiksi'],
            ],
            // Banyak Halaman (id_kriteria = 3)
            3 => [
                ['nama_tampilan' => 'Lebih dari 350 halaman', 'operator' => '>', 'nilai_angka_1' => 350],
                ['nama_tampilan' => '251-350 halaman', 'operator' => 'hingga', 'nilai_angka_1' => 251, 'nilai_angka_2' => 350],
                ['nama_tampilan' => '151-250 halaman', 'operator' => 'hingga', 'nilai_angka_1' => 151, 'nilai_angka_2' => 250],
                ['nama_tampilan' => '150 halaman atau kurang', 'operator' => '<=', 'nilai_angka_1' => 150],
            ],
            // Bahasa (id_kriteria = 4)
            4 => [
                ['nama_tampilan' => 'Bahasa Indonesia', 'nilai_teks' => 'Indonesia'],
                ['nama_tampilan' => 'Bahasa Inggris', 'nilai_teks' => 'Inggris'],
                ['nama_tampilan' => 'Bahasa asing lainnya', 'nilai_teks' => 'Lainnya'],
            ],
            // Tipe Media (id_kriteria = 5)
            5 => [
                ['nama_tampilan' => 'Buku Cetak', 'nilai_teks' => 'Buku Cetak'],
                ['nama_tampilan' => 'Buku Elektronik', 'nilai_teks' => 'Buku Elektronik'],
            ],
            // Tipe Isi (id_kriteria = 6)
            6 => [
                ['nama_tampilan' => 'Teks', 'nilai_teks' => 'Teks'],
                ['nama_tampilan' => 'Campuran', 'nilai_teks' => 'Campuran'],
                ['nama_tampilan' => 'Gambar', 'nilai_teks' => 'Gambar'],
            ],
            // Tipe Pembawa (id_kriteria = 7)
            7 => [
                ['nama_tampilan' => 'Volume', 'nilai_teks' => 'Volume'],
                ['nama_tampilan' => 'Tunggal', 'nilai_teks' => 'Tunggal'],
            ],
            // Stok (id_kriteria = 8)
            8 => [
                ['nama_tampilan' => 'Buku Digital', 'operator' => '=', 'nilai_angka_1' => 0],
                ['nama_tampilan' => 'Lebih dari 150 eksemplar', 'operator' => '>', 'nilai_angka_1' => 150],
                ['nama_tampilan' => '51-150 eksemplar', 'operator' => 'hingga', 'nilai_angka_1' => 51, 'nilai_angka_2' => 150],
                ['nama_tampilan' => '1-50 eksemplar', 'operator' => 'hingga', 'nilai_angka_1' => 1, 'nilai_angka_2' => 50],
            ],
            // Edisi (id_kriteria = 9)
            9 => [
                ['nama_tampilan' => 'Edisi Pertama', 'nilai_teks' => 'Edisi Pertama'],
                ['nama_tampilan' => 'Edisi Kedua', 'nilai_teks' => 'Edisi Kedua'],
                ['nama_tampilan' => 'Edisi Ketiga', 'nilai_teks' => 'Edisi Ketiga'],
                ['nama_tampilan' => 'Lainnya', 'nilai_teks' => 'Lainnya'], // 'Lainnya' akan dicocokkan jika tidak ada edisi lain yang cocok
            ],
        ];

        foreach ($subKriteriaData as $id_kriteria => $subKriterias) {
            $count = count($subKriterias);
            $nilaiMap = [];
            if ($count == 2) $nilaiMap = [4, 1];
            elseif ($count == 3) $nilaiMap = [4, 3, 2];
            elseif ($count >= 4) $nilaiMap = [4, 3, 2, 1];
            elseif ($count == 1) $nilaiMap = [4];

            foreach ($subKriterias as $index => $sub) {
                $nilaiToAssign = $nilaiMap[$index] ?? 1;
                DB::table('sub_kriteria')->insert([
                    'id_kriteria' => $id_kriteria,
                    'nama_tampilan' => $sub['nama_tampilan'],
                    'nilai' => $nilaiToAssign,
                    'nilai_teks' => $sub['nilai_teks'] ?? null,
                    'operator' => $sub['operator'] ?? null,
                    'nilai_angka_1' => $sub['nilai_angka_1'] ?? null,
                    'nilai_angka_2' => $sub['nilai_angka_2'] ?? null,
                ]);
            }
        }

        // =================================================================
        // Seeder untuk Users
        // =================================================================
        DB::table('users')->insert([
            [
                'name' => 'admin', 'username' => 'admin', 'nis_nip' => '123456789098765', 'email' => 'admin@admin.com',
                'role' => 'admin', 'password' => bcrypt('Op@update8pdg'), 'photo' => null,
            ],
            [
                'name' => 'adminperpus', 'username' => 'adminperpus', 'nis_nip' => '123456789098766', 'email' => 'adminperpus@admin.com',
                'role' => 'admin', 'password' => bcrypt('Op@update8pdg'), 'photo' => null,
            ],
            [
                'name' => 'dzul fauzi', 'username' => 'dzulaji', 'nis_nip' => '2011522001', 'email' => 'dzulaji@gmail.com',
                'role' => 'user', 'password' => bcrypt('12345'), 'photo' => null,
            ],
            [
                'name' => 'librarian', 'username' => 'pustakawan', 'nis_nip' => '123456789092765', 'email' => 'librarian@gmail.com',
                'role' => 'librarian', 'password' => bcrypt('pUst4k4Wan'), 'photo' => null,
            ]
        ]);

        // =================================================================
        // Seeder untuk Books (LENGKAP DARI FILE .sql)
        // =================================================================
        DB::table('books')->insert([
            [
                'id' => 1, 'title' => 'Ilmu Pengetahuan Alam untuk SMP/MTs Kelas VII (Edisi Revisi)', 'code' => 'BK9D3',
                'cover' => 'books-cover/yFGNlLGSppUBawIpQFCp7305mPYSIA4TMFA4553d.png',
                'author' => 'Victoriani Inabuy, Cece Sutia, Okky Fajar Tri Maryana, Budiyanti Dwi Hardanie, Sri Handayani Lestari',
                'year' => '2023', 'publisher' => 'Pusat Kurikulum dan Perbukuan', 'description' => 'Ini buku pelajaran',
                'category' => 'Non-Fiksi', 'stock' => 49, 'pages' => 264, 'language' => 'Indonesia',
                'isbn_issn' => '978-623-118-457-3', 'content_type' => 'Teks', 'media_type' => 'Buku Cetak',
                'link' => NULL, 'carrier_type' => 'Tunggal', 'edition' => 'Edisi Kedua', 'subject' => 'IPA',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 2, 'title' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan Kelas VII', 'code' => 'BKZ1A',
                'cover' => 'books-cover/APjcb08nf61gGIho7BDmMUO7hDO88VZtZ0vbpGtn.jpg', 'author' => 'Muhajir',
                'year' => '2017', 'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'description' => 'Ini buku pelajaran', 'category' => 'Non-Fiksi', 'stock' => 100, 'pages' => 328,
                'language' => 'Indonesia', 'isbn_issn' => '978-602-427-016-2', 'content_type' => 'Teks',
                'media_type' => 'Buku Cetak', 'link' => NULL, 'carrier_type' => 'Tunggal', 'edition' => 'Edisi Pertama',
                'subject' => 'PJOK', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 3, 'title' => 'Seni Budaya Kelas VII', 'code' => 'BK2P8',
                'cover' => 'books-cover/QK1uLEIzeafT7uAno4QARZ02w8ky3CZ5ayAbEB8P.jpg',
                'author' => 'Eko Purnomo, Deden Haerudin, Buyung Rohmanto, Julius Juih', 'year' => '2017',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud', 'description' => 'Ini buku pelajaran',
                'category' => 'Non-Fiksi', 'stock' => 60, 'pages' => 240, 'language' => 'Indonesia',
                'isbn_issn' => '978-602-427-024-7', 'content_type' => 'Teks', 'media_type' => 'Buku Cetak',
                'link' => NULL, 'carrier_type' => 'Tunggal', 'edition' => 'Edisi Ketiga', 'subject' => 'Seni Budaya',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 4, 'title' => 'Pendidikan Agama Islam dan Budi Pekerti Kelas VII', 'code' => 'BK6C4',
                'cover' => 'books-cover/ugui249fHiOsar5hPSQdYfPwfZpK6ojgWN00src0.jpg',
                'author' => 'Muhammad Ahsan, Sumiyati, Mustahdi', 'year' => '2017',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud', 'description' => 'Ini buku pelajaran',
                'category' => 'Non-Fiksi', 'stock' => 180, 'pages' => 224, 'language' => 'Indonesia',
                'isbn_issn' => '978-602-282-913-3', 'content_type' => 'Teks', 'media_type' => 'Buku Cetak',
                'link' => NULL, 'carrier_type' => 'Tunggal', 'edition' => 'Edisi Ketiga', 'subject' => 'PAI',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 5, 'title' => 'Ilmu Pengetahuan alam Kelas IX Semester 2', 'code' => 'BK9L2',
                'cover' => 'books-cover/mNjechCFz7ZGLDjF3Y5eloY2cwKPtjKlodWDpLMN.jpg',
                'author' => 'Siti Zubaidah, Susriyati Mahanal, Lia Yuliati, I Wayan Dasna, Ardian A. Pangestuti, Dyne R. Puspitasari, Hamim T. Mahfudhillah, Alifa Robitah, Zenia L. Kurniawati, Fatia Rosyida, Mar’atus Sholihah',
                'year' => '2018', 'publisher' => 'Pusat Kurikulum dan Perbukuan, Balibang, Kemendikbud',
                'description' => 'Ini buku pelajaran', 'category' => 'Non-Fiksi', 'stock' => 150, 'pages' => 278,
                'language' => 'Indonesia', 'isbn_issn' => '978-602-282-320-9', 'content_type' => 'Teks',
                'media_type' => 'Buku Cetak', 'link' => NULL, 'carrier_type' => 'Tunggal', 'edition' => 'Edisi Kedua',
                'subject' => 'IPA', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 6, 'title' => 'Pendidikan Pancasila dan Kewarganegaraan Kelas VII', 'code' => 'BKE7W',
                'cover' => 'books-cover/1ldzQyFiW5pJaLlInEaOnGCLXn3LNlx6qrTK61Jb.jpg',
                'author' => 'Lukman Surya Saputra, Aa Nurdiaman, dan Salikun', 'year' => '2017',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud.', 'description' => 'Ini buku pelajaran',
                'category' => 'Non-Fiksi', 'stock' => 100, 'pages' => 188, 'language' => 'Indonesia',
                'isbn_issn' => '978-602-282-961-4', 'content_type' => 'Teks', 'media_type' => 'Buku Cetak',
                'link' => NULL, 'carrier_type' => 'Tunggal', 'edition' => 'Edisi Ketiga', 'subject' => 'PKN',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 7, 'title' => 'Kika dan Dominika: Kala Remaja Ingin Berbisnis', 'code' => 'BKX3B',
                'cover' => 'books-cover/alf2kMDSsjN1Lw1GIJ5QptKZBrhpG9kURMYap3j5.png', 'author' => 'Fitri Hasanah',
                'year' => '2023', 'publisher' => 'Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi',
                'description' => 'Ini buku hiburan', 'category' => 'Fiksi', 'stock' => 5, 'pages' => 114,
                'language' => 'Indonesia', 'isbn_issn' => '978-623-118-028-5', 'content_type' => 'Campuran',
                'media_type' => 'Buku Cetak', 'link' => NULL, 'carrier_type' => 'Tunggal', 'edition' => 'Edisi Pertama',
                'subject' => 'Hiburan', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 8, 'title' => 'Komik Rampai Tema Lingkungan Hidup 5 Pandawa & Penglipuran', 'code' => 'BKM7P',
                'cover' => 'books-cover/9n4hffuk6gcHObrwbesyxSknX9CcEQTK6x1c1szG.png',
                'author' => 'Izzah Annisa, Sarah Fauzia', 'year' => '2023',
                'publisher' => 'Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi', 'description' => 'Ini buku hiburan',
                'category' => 'Fiksi', 'stock' => 20, 'pages' => 52, 'language' => 'Indonesia',
                'isbn_issn' => '978-623-118-022-3', 'content_type' => 'Gambar', 'media_type' => 'Buku Cetak',
                'link' => NULL, 'carrier_type' => 'Tunggal', 'edition' => 'Edisi Pertama', 'subject' => 'Hiburan',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 9, 'title' => 'Sekolah untuk Timur', 'code' => 'BKR2Y',
                'cover' => 'books-cover/1IncoTnwBlFF8vWsYDS1FunRD6kMADazj738eaYp.png', 'author' => 'Muhammad Fauzi',
                'year' => '2023', 'publisher' => 'Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi',
                'description' => 'Ini buku hiburan', 'category' => 'Fiksi', 'stock' => 0, 'pages' => 176,
                'language' => 'Indonesia', 'isbn_issn' => '978-623-118-012-4', 'content_type' => 'Campuran',
                'media_type' => 'Buku Elektronik', 'link' => 'buku_pdf/1748021752_Sekolah_Untuk_Timur.pdf',
                'carrier_type' => 'Tunggal', 'edition' => 'Edisi Pertama', 'subject' => 'Hiburan',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 10,
                'title' => 'My Name is Kali',
                'code' => 'BKV1D',
                'cover' => 'books-cover/rvBsyXOAqjvxLtOaK4JOUHc0jMZ6jFhRcaFjtU48.png',
                'author' => 'Anna Farida, Felishia',
                'year' => '2024',
                'publisher' => 'Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi',
                'description' => 'Ini buku hiburan',
                'category' => 'Fiksi',
                'stock' => 0,
                'pages' => 31,
                'language' => 'Inggris',
                'isbn_issn' => '978-623-118-328-8',
                'content_type' => 'Gambar',
                'media_type' => 'Buku Elektronik',
                'link' => 'buku_pdf/1751310762_Namaku_Kali_Ing.pdf',
                'carrier_type' => 'Tunggal',
                'edition' => 'Edisi Pertama',
                'subject' => 'Hiburan',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

    }

}
