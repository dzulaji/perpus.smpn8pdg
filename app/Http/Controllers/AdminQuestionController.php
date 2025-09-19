<?php

namespace App\Http\Controllers;

use App\Models\Pertanyaan;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class AdminQuestionController extends Controller
{
    public function index()
    {
        return view('admin.pages.question.index', [
            'questions' => Pertanyaan::with('kriteria')->get(),
        ]);
    }
    public function update(Request $request, Pertanyaan $question)
    {
        $validated = $request->validate([
            'kriteria' => 'required',
            'pertanyaan' => 'required',
            'bobot' => 'required|numeric|min:0|max:1',
        ]);

        $kriteriaLama = $question->kriteria;
        $idKriteriaLama = $kriteriaLama->id_kriteria;

        // Tambahkan bobot baru yang ingin disimpan
        $bobotBaru = floatval($validated['bobot']);
        // Hitung total bobot selain kriteria yang sedang diedit
        $totalBobotLain = Kriteria::where('id_kriteria', '!=', $idKriteriaLama)->sum('bobot');

        $totalSetelahEdit  = $totalBobotLain + $bobotBaru;

        if (round($totalSetelahEdit, 2) > 1.0) {
            return redirect()->back()->withErrors(['bobot' => 'Nilai Bobot Lebih dari 1']);
        }

        $question->update([
            'pertanyaan' => $validated['pertanyaan'],
        ]);

            $question->kriteria->update([
                'kriteria' => $validated['kriteria'],
                'bobot' => $bobotBaru,
            ]);

        return redirect()->back()->with('success', 'Pertanyaan dan bobot berhasil diperbarui');
    }
}
