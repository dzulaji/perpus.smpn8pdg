<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminKriteriaController extends Controller
{
    /**
     * Daftar kolom buku yang bisa dipilih sebagai kriteria.
     * Didefinisikan sebagai properti agar mudah diakses oleh metode lain.
     */
    private $kolomBukuTersedia = [
        'category' => 'Kategori',
        'publisher' => 'Penerbit',
        'year' => 'Tahun Terbit',
        'author' => 'Penulis',
        'pages' => 'Banyak Halaman',
        'language' => 'Bahasa',
        'isbn_issn' => 'ISBN/ISSN',
        'content_type' => 'Tipe Isi',
        'media_type' => 'Tipe Media',
        'carrier_type' => 'Tipe Pembawa',
        'edition' => 'Edisi',
        'subject' => 'Subjek',
        'stock' => 'Stok',
    ];

    /**
     * Menampilkan halaman utama manajemen kriteria.
     */
    public function index()
    {
        $kriteria = Kriteria::with('pertanyaan')->orderBy('id_kriteria')->get();

        // Memfilter kolom yang sudah dipakai agar tidak bisa ditambahkan lagi
        $kolomSudahDipakai_mentah = $kriteria->pluck('kolom_buku')->toArray();
        $kolomSudahDipakai = array_filter($kolomSudahDipakai_mentah); // Menghapus nilai NULL
        $kolomUntukForm = array_diff_key($this->kolomBukuTersedia, array_flip($kolomSudahDipakai));

        $totalBobot = $kriteria->sum('bobot');

        // Mengirim semua data yang diperlukan ke view
        return view('admin.pages.kriteria.index', compact('kriteria', 'totalBobot', 'kolomUntukForm'));
    }

    /**
     * Menyimpan kriteria baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kolom_buku' => ['required', 'string', 'max:255', Rule::unique('kriteria', 'kolom_buku')],
            'pertanyaan' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0',
            'tipe_aturan' => ['required', Rule::in(['TEKS', 'ANGKA'])],
        ]);

        $namaKriteria = $this->kolomBukuTersedia[$validated['kolom_buku']] ?? ucfirst(str_replace('_', ' ', $validated['kolom_buku']));

        // Validasi agar total bobot tidak melebihi 1
        $totalBobotSaatIni = Kriteria::sum('bobot');
        if (($totalBobotSaatIni + $validated['bobot']) > 1.001) {
            return redirect()->back()->withInput()->withErrors(['bobot' => 'Total bobot semua kriteria tidak boleh melebihi 1.']);
        }

        DB::transaction(function () use ($validated, $namaKriteria) {
            $kriteria = Kriteria::create([
                'kriteria' => $namaKriteria,
                'kolom_buku' => $validated['kolom_buku'],
                'bobot' => $validated['bobot'],
                'tipe_aturan' => $validated['tipe_aturan'],
            ]);
            $kriteria->pertanyaan()->create(['pertanyaan' => $validated['pertanyaan']]);
        });

        return redirect()->route('admin.kriteria.index')->with('success', 'Kriteria baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan halaman detail untuk mengelola sub-kriteria.
     */
    public function show(Kriteria $kriterium)
    {
        $kriterium->load('subKriteria');
        return view('admin.pages.kriteria.show', compact('kriterium'));
    }

    /**
     * Mengupdate data kriteria yang ada.
     */
    public function update(Request $request, Kriteria $kriterium)
    {
        $validated = $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0',
            'tipe_aturan' => ['required', Rule::in(['TEKS', 'ANGKA'])],
        ]);

        $totalBobotLain = Kriteria::where('id_kriteria', '!=', $kriterium->id_kriteria)->sum('bobot');
        if (($totalBobotLain + $validated['bobot']) > 1.001) {
            return redirect()->back()->withInput()->withErrors(['bobot' => 'Total bobot semua kriteria tidak boleh melebihi 1.']);
        }

        DB::transaction(function () use ($validated, $kriterium) {
            $kriterium->update([
                'bobot' => $validated['bobot'],
                'tipe_aturan' => $validated['tipe_aturan'],
            ]);

            if ($kriterium->pertanyaan) {
                $kriterium->pertanyaan->update(['pertanyaan' => $validated['pertanyaan']]);
            } else {
                $kriterium->pertanyaan()->create(['pertanyaan' => $validated['pertanyaan']]);
            }
        });

        return redirect()->route('admin.kriteria.index')->with('success', 'Kriteria berhasil diperbarui.');
    }

    /**
     * Menghapus kriteria dari database.
     */
    public function destroy(Kriteria $kriterium)
    {
        DB::transaction(function () use ($kriterium) {
            optional($kriterium->pertanyaan)->delete();
            $kriterium->delete();
        });

        return redirect()->route('admin.kriteria.index')->with('success', 'Kriteria berhasil dihapus.');
    }

    /**
     * Menyimpan sub-kriteria baru untuk sebuah kriteria.
     */
    public function storeSubKriteria(Request $request, Kriteria $kriterium)
{
    if ($kriterium->subKriteria()->count() >= 4) {
        return redirect()->back()->withInput()->withErrors(['limit' => 'Sub-kriteria sudah mencapai batas maksimal (4 opsi).']);
    }

    $rules = [
        'nama_tampilan' => 'required|string|max:255', // Nama tampilan tetap wajib diisi
    ];

    if (strtolower(trim($kriterium->tipe_aturan)) == 'teks') {
        // REVISI: Tambahkan aturan 'unique' pada nilai_teks
        $rules['nilai_teks'] = [
            'required', 'string', 'max:255',
            Rule::unique('sub_kriteria')->where(function ($query) use ($kriterium) {
                return $query->where('id_kriteria', $kriterium->id_kriteria);
            }),
        ];
    } else { // ANGKA
        $rules['operator'] = ['required', Rule::in(['>=', '<=', '>', '<', 'hingga', '='])];
        $rules['nilai_angka_1'] = 'required|numeric';
        $rules['nilai_angka_2'] = 'nullable|numeric|required_if:operator,hingga';
    }

    $validated = $request->validate($rules, [
        'nilai_teks.unique' => 'Teks Aturan yang Anda masukkan sudah ada untuk kriteria ini.'
    ]);

    $dataToCreate = array_merge($validated, ['nilai' => 0]);
    $kriterium->subKriteria()->create($dataToCreate);
    $this->reassignNilai($kriterium);

    return redirect()->route('admin.kriteria.show', $kriterium)->with('success', 'Sub-kriteria berhasil ditambahkan.');
}

    /**
     * Mengupdate sub-kriteria yang ada.
     */
    public function updateSubKriteria(Request $request, SubKriteria $subkriterium)
{
    $kriterium = $subkriterium->kriteria;

    $rules = [
        'nama_tampilan' => 'required|string|max:255',
    ];

    if (strtolower(trim($kriterium->tipe_aturan)) == 'teks') {
        // REVISI: Tambahkan aturan 'unique' pada nilai_teks, abaikan ID saat ini
        $rules['nilai_teks'] = [
            'required', 'string', 'max:255',
            Rule::unique('sub_kriteria')->where(function ($query) use ($kriterium) {
                return $query->where('id_kriteria', $kriterium->id_kriteria);
            })->ignore($subkriterium->id_sub_kriteria, 'id_sub_kriteria'),
        ];
    } else { // ANGKA
        $rules['operator'] = ['required', Rule::in(['>=', '<=', '>', '<', 'hingga', '='])];
        $rules['nilai_angka_1'] = 'required|numeric';
        $rules['nilai_angka_2'] = 'nullable|numeric|required_if:operator,hingga';
    }

    $validated = $request->validate($rules, [
        'nilai_teks.unique' => 'Teks Aturan yang Anda masukkan sudah ada untuk kriteria ini.'
    ]);

    $subkriterium->update($validated);
    $this->reassignNilai($kriterium);

    return redirect()->route('admin.kriteria.show', $kriterium)->with('success', 'Sub-kriteria berhasil diperbarui.');
}

    /**
     * Menghapus sub-kriteria.
     */
    public function destroySubKriteria(SubKriteria $subkriterium)
    {
        $kriterium = $subkriterium->kriteria;
        $subkriterium->delete();
        $this->reassignNilai($kriterium);
        return redirect()->route('admin.kriteria.show', $kriterium)->with('success', 'Sub-kriteria berhasil dihapus.');
    }

    /**
     * Fungsi private untuk mengatur ulang nilai penomoran (1-4) sub-kriteria secara otomatis.
     */
    private function reassignNilai(Kriteria $kriteria)
    {
        $subKriteriaList = $kriteria->subKriteria()->orderBy('id_sub_kriteria', 'asc')->get();
        $count = $subKriteriaList->count();
        $nilaiMap = [];
        if ($count == 2) {$nilaiMap = [4, 1];}
        elseif ($count == 3) {$nilaiMap = [4, 3, 2];}
        elseif ($count >= 4) {$nilaiMap = [4, 3, 2, 1];}
        elseif ($count == 1) {$nilaiMap = [4];}

        foreach ($subKriteriaList as $index => $sub) {
            $nilaiToAssign = $nilaiMap[$index] ?? 1;
            $sub->update(['nilai' => $nilaiToAssign]);
        }
    }
}
