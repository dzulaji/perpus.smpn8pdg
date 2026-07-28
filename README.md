# E-Katalog Perpustakaan SMP Negeri 8 Padang

Sebuah sistem informasi manajemen perpustakaan modern yang dilengkapi dengan Sistem Pendukung Keputusan (SPK) berbasis algoritma **SMART (Simple Multi-Attribute Rating Technique)** untuk merekomendasikan buku kepada siswa secara cerdas dan personal.

---

## 💻 Teknologi yang Digunakan

Aplikasi ini dibangun menggunakan *stack* teknologi modern untuk memastikan kecepatan, keamanan, dan skalabilitas:

- **Bahasa Pemrograman**: PHP (Minimal v8.1)
- **Framework**: Laravel 10 (Full-stack MVC)
- **Database**: MySQL (Relational Database Management System)
- **Frontend & Styling**: 
  - Bootstrap (Kerangka UI utama)
  - DataTables (Tabel dinamis dengan fitur sortir, filter, dan pagination)
  - SweetAlert2 (Tampilan popup peringatan yang interaktif)
- **Testing**: PHPUnit (Pengujian Otomatis / Automated Testing)
- **Library Tambahan**: 
  - `maatwebsite/excel`: Untuk fitur Import & Export data menggunakan format `.xlsx` dan `.csv`.

---

## ⚙️ Fitur Utama & Logika Bisnis

Aplikasi E-Katalog ini membagi hak akses ke dalam 3 jenis peran (Role): **Admin**, **Librarian (Pustakawan)**, dan **User (Siswa)**. Berikut adalah penjabaran logika bisnis dari fitur-fiturnya:

### 1. Manajemen Autentikasi (Keamanan Fleksibel)
- **Login & Register**: Pengguna dapat masuk menggunakan *username* atau *email*.
- **Saklar Keamanan Password**: Sistem ini memiliki konfigurasi unik (`USE_PASSWORD_HASHING`) di file `.env`. Admin bisa memilih untuk menggunakan enkripsi *hash* berstandar industri, atau mematikannya sementara jika dibutuhkan untuk proses migrasi data lama (sebagai teks biasa).
- **Pembatasan Akses (Middleware)**: Pengguna biasa secara tegas ditolak mengakses halaman admin untuk menjamin integritas data.

### 2. Manajemen Pengguna (User Management)
- **CRUD Pengguna**: Admin dapat menambah, melihat detail, mengubah (termasuk *reset password*), dan menghapus pengguna.
- **Import Massal (Excel)**: Admin dapat mendaftarkan ribuan siswa sekaligus di awal tahun ajaran baru cukup dengan mengunggah satu file Excel. Sistem secara pintar akan mendeteksi *username/email/NIS* yang ganda dan melompatinya tanpa menyebabkan aplikasi *error*.

### 3. Manajemen Katalog Buku (Book Management)
- **CRUD Buku**: Admin dan Pustakawan memegang kendali penuh atas pendataan buku. Kode buku unik dihasilkan otomatis oleh sistem saat penambahan data.
- **Buku Fisik vs Digital**: Sistem mendukung dua varian media:
  - *Buku Cetak*: Memerlukan manajemen stok (stock > 0).
  - *Buku Elektronik*: Stok dibekukan menjadi 0, dan kolom tautan (*Link Google Drive/PDF*) menjadi wajib diisi.
- **Import Massal (Excel)**: Seperti halnya pengguna, buku juga dapat diimpor massal beserta semua metadatanya (ISBN, Penerbit, dsb).

### 4. Sistem Peminjaman (Booking System)
- Pengguna (siswa) dapat mengajukan permohonan peminjaman buku (*Booking*) dengan menyertakan alasan. Status permohonan awalnya akan berstatus **Diajukan**.
- Admin/Pustakawan dapat menyetujui, menolak, atau menyelesaikan proses pengembalian buku, yang secara otomatis akan terintegrasi dengan perhitungan denda (jika terlambat).

### 5. SPK Rekomendasi Buku (Metode SMART)
Ini adalah "Otak Cerdas" dari aplikasi perpustakaan.
- **Kuesioner Personal**: Sistem akan meminta pengguna untuk menjawab beberapa pertanyaan kuesioner singkat.
- **Perhitungan SMART**: Jawaban dari pengguna akan diolah menggunakan algoritma SMART. Sistem akan mencocokkan jawaban dengan bobot **Kriteria** dan **Sub-Kriteria** yang ada pada *database*.
- **Normalisasi Matriks & Utilities**: Sistem menghitung nilai akhir dengan membandingkan seluruh buku di katalog.
- **Top 25**: Untuk menjaga agar aplikasi tetap ringan dan *loading* cepat, sistem hanya akan memunculkan maksimal 25 buku dengan tingkat relevansi tertinggi (peringkat terbaik) kepada pengguna.

---

## 📈 Status Perkembangan Aplikasi (Progres)

Saat ini, aplikasi **E-Katalog Perpustakaan SMP Negeri 8 Padang** telah mencapai tahap **SIAP DEPLOY (Production Ready)**. 

Pencapaian terkini yang telah diselesaikan:
1. **Stabilisasi Database**: Telah dilakukan injeksi migrasi *database* untuk membuat kolom-kolom sekunder bersifat opsional (*nullable*), mencegah terjadinya *fatal error* (layar 500) jika ada kesalahan pengisian *form* oleh admin.
2. **Automated Testing (Lulus 100%)**: Skrip pengujian otomatis (Tes Autentikasi, Tes CRUD, Tes Booking, dan Tes Logika SMART) seluruhnya telah berhasil melewati uji standar tanpa ada *bug* atau celah logika.
3. **Penyempurnaan UI**: Tombol-tombol interaktif (seperti tombol hapus dengan konfirmasi) telah menggunakan logika *Event Delegation*, memastikannya dapat berfungsi sempurna di ribuan baris data tabel *pagination*.
4. **Fungsi Mass Import**: Integrasi penambahan buku dan pengguna via `.xlsx` telah rampung.

