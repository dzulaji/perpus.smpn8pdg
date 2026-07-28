<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Kriteria;

class SmartRecommendationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_smart_calculation_processes_successfully()
    {
        $user = User::factory()->create(['role' => 'user']);
        
        // Setup dummy criteria and book
        $kriteria = Kriteria::create([
            'id_kriteria' => 1,
            'kriteria' => 'Kategori',
            'bobot' => 20,
            'kolom_buku' => 'category',
            'tipe_aturan' => 'TEKS'
        ]);

        $book = Book::create([
            'code' => 'B001',
            'title' => 'Test Book',
            'category' => 'Fiksi',
            'author' => 'Author Name',
            'publisher' => 'Publisher Name',
            'year' => 2023,
            'description' => 'A test book description.',
            'pages' => 100,
            'language' => 'Indonesia',
            'isbn_issn' => '123456789',
            'content_type' => 'Text',
            'media_type' => 'Print',
            'carrier_type' => 'Volume',
            'edition' => '1',
            'subject' => 'Umum',
            'stock' => 5,
        ]);

        $response = $this->actingAs($user)->post('/rekomendasi/proses', [
            'jawaban' => [
                1 => 4 // Very important
            ]
        ]);

        $response->assertStatus(302); // Redirects to results
        $this->assertDatabaseHas('perhitungan', []);
        $this->assertDatabaseHas('detail_perhitungan', [
            'id' => $book->id
        ]);
        $this->assertDatabaseHas('normalisasi', []);
    }

    public function test_smart_requires_answers()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->post('/rekomendasi/proses', [
            'jawaban' => []
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
