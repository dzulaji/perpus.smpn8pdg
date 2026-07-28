<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Book, Kriteria, Normalisasi, Perhitungan, DetailPerhitungan, Pertanyaan}; // Pastikan semua model di-import
use Illuminate\Support\Facades\{Auth, DB, Log}; // Tambahkan Log

class RekomendasiController extends Controller
{
    public function index()
{
    // Ambil semua pertanyaan yang memiliki kriteria yang valid
    $pertanyaan = Pertanyaan::has('kriteria')->with('kriteria')->get();

    // REVISI: Hitung total bobot dari semua kriteria
    $totalBobot = Kriteria::sum('bobot');

    // Kirim variabel $totalBobot ke view
    return view('pages.rekomendasi', compact('pertanyaan', 'totalBobot'));
}

    /**
     * Menghitung nilai (1-4) sebuah buku untuk satu kriteria tertentu.
     * Versi ini melakukan query ke database setiap kali dipanggil.
    */
    public function skorBuku(Book $book, $namaKriteria)
    {
    // 1. Mengambil Aturan dari Database
    // Sistem mencari ke tabel 'kriteria' untuk data kriteria yang namanya sama dengan $namaKriteria.
    // with('subKriteria') secara otomatis mengambil semua sub-kriteria (pilihan jawaban) yang berelasi dengannya.
    // ->first() berarti hanya mengambil satu data kriteria yang cocok.
    $kriterium = Kriteria::where('kriteria', $namaKriteria)->with('subKriteria')->first();

    // 2. Pemeriksaan Awal (Pengaman)
    // Ini adalah blok untuk memastikan semua data yang dibutuhkan ada.
    // Jika kriteria tidak ditemukan, atau jika kriteria belum dipetakan ke kolom buku (`kolom_buku`=NULL),
    // atau jika kolom yang dimaksud tidak ada di data buku, maka fungsi langsung mengembalikan skor terendah (1).
    if (!$kriterium || !$kriterium->kolom_buku || !isset($book->{$kriterium->kolom_buku})) {
        return 1;
    }

    // 3. Mengambil Nilai Atribut Buku secara Dinamis
    // Sistem mengambil nama kolom dari database (misal: 'year', 'category', 'stock').
    $kolomBuku = $kriterium->kolom_buku;
    // Kemudian, sistem menggunakan nama kolom itu untuk mengambil nilai dari objek buku.
    // Contoh: jika $kolomBuku adalah 'year', maka ini sama dengan $book->year.
    $nilaiAtributBuku = $book->{$kolomBuku};

    // 4. Loop Melalui Setiap Aturan Sub-Kriteria
    // Sistem akan memeriksa setiap pilihan jawaban (sub-kriteria) satu per satu
    // untuk menemukan aturan mana yang cocok dengan nilai atribut buku.
    foreach ($kriterium->subKriteria as $sub) {

        // 4a. Pengecekan ATURAN ANGKA
        // Kondisi ini akan berjalan jika sub-kriteria ini memiliki aturan 'operator'
        // DAN nilai dari buku adalah sebuah angka (atau string angka seperti "2023").
        if ($sub->operator !== null && is_numeric($nilaiAtributBuku)) {
            $nilaiBukuNumerik = (float)$nilaiAtributBuku; // Mengubah nilai buku menjadi angka desimal untuk perbandingan

            // Memeriksa operator mana yang harus digunakan
            switch ($sub->operator) {
                case '=':
                    if ($nilaiBukuNumerik == $sub->nilai_angka_1) return $sub->nilai;
                    break;
                case '>=':
                    if ($nilaiBukuNumerik >= $sub->nilai_angka_1) return $sub->nilai;
                    break;
                case '<=':
                    if ($nilaiBukuNumerik <= $sub->nilai_angka_1) return $sub->nilai;
                    break;
                case '>':
                    if ($nilaiBukuNumerik > $sub->nilai_angka_1) return $sub->nilai;
                    break;
                case '<':
                    if ($nilaiBukuNumerik < $sub->nilai_angka_1) return $sub->nilai;
                    break;
                case 'hingga':
                    if (isset($sub->nilai_angka_2) && $nilaiBukuNumerik >= $sub->nilai_angka_1 && $nilaiBukuNumerik <= $sub->nilai_angka_2) return $sub->nilai;
                    break;
            }
            // Jika salah satu kondisi di atas terpenuhi (true), fungsi akan 'return $sub->nilai'
            // dan langsung berhenti di sini tanpa melanjutkan loop.
        }
        // 4b. Pengecekan ATURAN TEKS
        // Kondisi ini akan berjalan jika sub-kriteria ini memiliki aturan 'nilai_teks'
        // DAN nilai dari buku adalah sebuah string.
        elseif ($sub->nilai_teks !== null && is_string($nilaiAtributBuku)) {
            // Melakukan perbandingan teks persis (setelah diubah ke huruf kecil agar tidak case-sensitive)
            if (strtolower($nilaiAtributBuku) == strtolower($sub->nilai_teks)) {
                return $sub->nilai; // Jika cocok, langsung kembalikan nilai dan berhenti.
            }
        }
    }

    // 5. FALLBACK (Rencana Cadangan) Khusus untuk Kriteria TEKS
    // Blok ini hanya akan berjalan jika loop di atas selesai tanpa menemukan kecocokan teks yang PERSIS.
    // Ini berguna untuk kasus seperti Edisi, di mana "Edisi Keempat" harus cocok dengan aturan "Lainnya".
    if ($kriterium->tipe_aturan == 'TEKS') {
        foreach ($kriterium->subKriteria as $sub) {
            if ($sub->nilai_teks !== null && is_string($nilaiAtributBuku)) {
                // Sekarang ia mencoba perbandingan parsial (apakah teks aturan terkandung di dalam nilai buku)
                if (str_contains(strtolower($nilaiAtributBuku), strtolower($sub->nilai_teks))) {
                    return $sub->nilai;
                }
            }
        }
    }

    // 6. Nilai Default Terakhir
    // Jika setelah semua pengecekan di atas tidak ada satupun aturan yang cocok,
    // maka fungsi akan mengembalikan nilai 1 sebagai skor terendah.
    return 1;
    }

    private function hitungUtility($preferensiUser, $nilaiBuku)
    {
        if ($preferensiUser === null || $nilaiBuku === null) {
            return 0;
        }
        $maxRange = 3; // Rentang nilai 1-4
        $selisih = abs($nilaiBuku - (int)$preferensiUser);
        $utility = 1 - ($selisih / $maxRange);
        return max(0, $utility);
    }

    public function proses(Request $request)
    {
        $jawaban = $request->input('jawaban');
        session(['jawaban' => $jawaban]); // Simpan jawaban ke session untuk digunakan di halaman hasil

        if (empty($jawaban) || !is_array($jawaban)) {
            return redirect()->back()->with('error', 'Harap isi semua jawaban preferensi.');
        }

        // Opsional: Validasi tambahan untuk jawaban jika diperlukan
        foreach($jawaban as $id_kriteria => $pref) {
            if($pref === null || $pref === '') {
                return redirect()->back()->with('error', 'Semua preferensi kriteria harus diisi.');
            }
            if(!is_numeric($pref) || (int)$pref < 1 || (int)$pref > 4) {
                 // Log::warning("Preferensi tidak valid: kriteria {$id_kriteria}, pref: {$pref}");
                 // Anda bisa lebih ketat atau mengabaikan jika form sudah membatasi
            }
        }


        DB::beginTransaction();
        try {
            $perhitungan = Perhitungan::create([
                'tanggal' => now(), // Menggunakan default dari migrasi jika useCurrent() [cite: 12]
            ]);

            $books = Book::all();
            if ($books->isEmpty()) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Tidak ada data buku untuk diproses.');
            }

            $kriterias = Kriteria::all();
            if ($kriterias->isEmpty()) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Tidak ada data kriteria untuk diproses.');
            }

            $hasilPerhitunganMentah = [];
            $maxSkorMentah = 0;

            foreach ($books as $book) {
                $skorSMARTMentah = 0;
                foreach ($kriterias as $kriteria) {
                    $idKriteriaLoop = $kriteria->id_kriteria;
                    $bobot = (float) $kriteria->bobot;
                    $preferensiUser = isset($jawaban[$idKriteriaLoop]) ? (int) $jawaban[$idKriteriaLoop] : null;

                    if ($preferensiUser === null) { // Seharusnya tidak terjadi jika form 'required'
                        continue;
                    }

                    $nilaiBuku = $this->skorBuku($book, $kriteria->kriteria);
                    $utility = $this->hitungUtility($preferensiUser, $nilaiBuku);
                    $skorSMARTMentah += $utility * $bobot;
                }

                $hasilPerhitunganMentah[] = [
                    'book_id' => $book->id,
                    'skor_smart_mentah' => $skorSMARTMentah,
                ];

                if ($skorSMARTMentah > $maxSkorMentah) {
                    $maxSkorMentah = $skorSMARTMentah;
                }
            }

            // Hindari pembagian dengan nol jika semua skor adalah 0
            if ($maxSkorMentah <= 0) {
                 // Jika semua skor 0 atau negatif (seharusnya tidak terjadi dengan utility >= 0),
                 // maka semua normalisasi akan 0 (atau NaN jika $maxSkorMentah = 0).
                 // Jika tidak ada buku yang cocok sama sekali, $maxSkorMentah bisa 0.
                 // Dalam kasus ini, semua utilities akan 0%.
                 // Bisa juga set $maxSkorMentah = 1 agar tidak error, tapi hasilnya tetap 0 jika skornya 0.
                 // Atau, jika tidak ada hasil positif, mungkin lebih baik beri pesan.
                 // Untuk sekarang, jika maxSkor 0, semua utilities akan 0.
                 // Kita set ke 1 untuk menghindari error pembagian dgn 0 jika $maxSkorMentah benar-benar 0.
                 // Namun jika ada skor > 0, $maxSkorMentah akan > 0.
                 if (count(array_filter(array_column($hasilPerhitunganMentah, 'skor_smart_mentah'), function($s) { return $s > 0; })) === 0) {
                     // Semua skor adalah 0, tidak masalah jika maxSkorMentah tetap 0 atau 1
                 }
                 if ($maxSkorMentah == 0) $maxSkorMentah = 1; // Untuk pembagian
            }


            foreach ($hasilPerhitunganMentah as $hasil) {
                $detail = DetailPerhitungan::create([
                    'id_perhitungan' => $perhitungan->id_perhitungan,
                    'id' => $hasil['book_id'], // id buku
                    'skor_akhir' => $hasil['skor_smart_mentah'], // Skor mentah SMART [cite: 3]
                    'id_user' => Auth::id() ?? 0, // Ganti 0 dengan id user default jika diperlukan, atau buat nullable
                ]);

                $skorNormalisasi = $hasil['skor_smart_mentah'] / $maxSkorMentah;

                Normalisasi::create([
                    'id_detail' => $detail->id_detail,
                    'normalisasi' => $skorNormalisasi,
                    'utilities' => $skorNormalisasi * 100,
                ]);
            }

            DB::commit();
            return redirect()->route('rekomendasi.hasil', ['id' => $perhitungan->id_perhitungan]);

        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("Error Proses Rekomendasi: " . $th->getMessage() . "\nStack Trace:\n" . $th->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses rekomendasi. Silakan coba lagi nanti.');
        }
    }

    public function hasil($id_perhitungan_encode) // Ubah nama var agar jelas
    {
        // $id_perhitungan = base64_decode($id_perhitungan_encode); // Jika Anda encode ID
        $id_perhitungan = $id_perhitungan_encode; // Asumsi ID tidak di-encode

        // Ambil data Normalisasi, urutkan berdasarkan 'utilities' DESC
        $hasilNormalisasi = Normalisasi::whereHas('detail', function ($query) use ($id_perhitungan) {
            $query->where('id_perhitungan', $id_perhitungan);
        })
        ->with(['detail.book', 'detail.perhitungan']) // Eager load relasi
        ->orderByDesc('utilities')
        ->take(25)
        ->get();

        if ($hasilNormalisasi->isEmpty()) {
            return view('pages.hasil', [
                'dataHasilRekomendasi' => [],
                'jawaban' => session('jawaban', []),
                'kriterias' => Kriteria::all(), // Tetap kirim kriteria untuk header tabel jika perlu
                'error_message' => 'Tidak ada hasil rekomendasi untuk perhitungan ini.'
            ]);
        }

        $kriterias = Kriteria::all();
        $jawaban = session('jawaban', []); // Ambil jawaban dari session

        $dataHasilRekomendasi = [];
        $_maxSkorMentahOverall = 0; // Untuk perhitungan normalisasi rincian

        // Dapatkan semua skor mentah dari DetailPerhitungan untuk id_perhitungan ini untuk menentukan maxSkorMentahOverall
        $detailPerhitunganUntukMax = DetailPerhitungan::where('id_perhitungan', $id_perhitungan)->get();
        if(!$detailPerhitunganUntukMax->isEmpty()){
            $_maxSkorMentahOverall = $detailPerhitunganUntukMax->max('skor_akhir') ?? 0;
        }
        if ($_maxSkorMentahOverall <= 0) $_maxSkorMentahOverall = 1; // Hindari pembagian dengan 0


        foreach ($hasilNormalisasi as $itemNormalisasi) {
            $book = $itemNormalisasi->detail->book;
            $skorMentahBukuIni = $itemNormalisasi->detail->skor_akhir; // Ambil skor mentah dari DB [cite: 3]
            $rincianKriteriaBuku = [];

            foreach ($kriterias as $kriteria) {
                $idKriteriaLoop = $kriteria->id_kriteria;
                $bobot = (float) $kriteria->bobot;
                // Pastikan jawaban ada dan merupakan integer
                $preferensiUser = isset($jawaban[$idKriteriaLoop]) ? (int) $jawaban[$idKriteriaLoop] : null;

                $nilaiBuku = $this->skorBuku($book, $kriteria->kriteria);
                $utility = $this->hitungUtility($preferensiUser, $nilaiBuku);
                $skorBobot = $utility * $bobot;

                $rincianKriteriaBuku[] = [
                    'nama_kriteria' => $kriteria->kriteria,
                    'jawaban_user' => $preferensiUser ?? 'N/A',
                    'nilai_buku' => $nilaiBuku,
                    'selisih' => ($preferensiUser !== null) ? abs($preferensiUser - $nilaiBuku) : 'N/A',
                    'utility' => $utility,
                    'bobot' => $bobot,
                    'skor_bobot' => $skorBobot,
                ];
            }

            $dataHasilRekomendasi[] = [
                'book' => $book,
                'skor_mentah_buku' => $skorMentahBukuIni,
                'utilities_persen' => $itemNormalisasi->utilities, // Dari tabel Normalisasi [cite: 5]
                'normalisasi_value' => $itemNormalisasi->normalisasi, // Dari tabel Normalisasi [cite: 5]
                'rincian_per_kriteria' => $rincianKriteriaBuku,
            ];
        }

        return view('pages.hasil', compact('dataHasilRekomendasi', 'jawaban', 'kriterias', '_maxSkorMentahOverall'));
    }

    public function history()
{
    $user = Auth::user();

    // Ambil ID perhitungan terakhir dari user login
    $lastPerhitungan = DetailPerhitungan::where('id_user', $user->id)
        ->orderByDesc('id_perhitungan')
        ->first();

    if (!$lastPerhitungan) {
        return view('pages.history', [
            'dataHasilRekomendasi' => [],
            'error_message' => 'Anda belum memiliki riwayat rekomendasi.'
        ]);
    }

    $id_perhitungan = $lastPerhitungan->id_perhitungan;

    $hasilNormalisasi = Normalisasi::whereHas('detail', function ($query) use ($id_perhitungan) {
        $query->where('id_perhitungan', $id_perhitungan);
    })
        ->with(['detail.book', 'detail.perhitungan'])
        ->orderByDesc('utilities')
        ->take(25)
        ->get();

    if ($hasilNormalisasi->isEmpty()) {
        return view('pages.history', [
            'dataHasilRekomendasi' => [],
            'error_message' => 'Tidak ada hasil rekomendasi untuk perhitungan ini.'
        ]);
    }

    $kriterias = Kriteria::all();
    $dataHasilRekomendasi = [];
    $_maxSkorMentahOverall = DetailPerhitungan::where('id_perhitungan', $id_perhitungan)->max('skor_akhir') ?? 1;

    foreach ($hasilNormalisasi as $itemNormalisasi) {
        $book = $itemNormalisasi->detail->book;
        $skorMentahBukuIni = $itemNormalisasi->detail->skor_akhir;
        $rincianKriteriaBuku = [];

        foreach ($kriterias as $kriteria) {
            $idKriteriaLoop = $kriteria->id_kriteria;
            $bobot = (float) $kriteria->bobot;

            $nilaiBuku = $this->skorBuku($book, $kriteria->kriteria);
            $utility = $this->hitungUtility(null, $nilaiBuku);
            $skorBobot = $utility * $bobot;

            $rincianKriteriaBuku[] = [
                'nama_kriteria' => $kriteria->kriteria,
                'jawaban_user' => 'N/A',
                'nilai_buku' => $nilaiBuku,
                'selisih' => 'N/A',
                'utility' => $utility,
                'bobot' => $bobot,
                'skor_bobot' => $skorBobot,
            ];
            }

            $dataHasilRekomendasi[] = [
                'book' => $book,
                'skor_mentah_buku' => $skorMentahBukuIni,
                'utilities_persen' => $itemNormalisasi->utilities,
                'normalisasi_value' => $itemNormalisasi->normalisasi,
                'rincian_per_kriteria' => $rincianKriteriaBuku,
            ];
        }

        return view('pages.history', compact('dataHasilRekomendasi', '_maxSkorMentahOverall'));
    }
}
