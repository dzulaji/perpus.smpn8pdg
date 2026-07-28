<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;

class BookCrudTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_create_book()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->from('/admin/books')->post('/admin/books', [
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

        $response->assertRedirect('/admin/books');
        $this->assertDatabaseHas('books', [
            'title' => 'Test Book',
            'category' => 'Fiksi'
        ]);
    }

    public function test_admin_can_update_book()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $book = Book::create([
            'code' => 'B0012',
            'title' => 'Old Title',
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

        $response = $this->actingAs($admin)->from('/admin/books')->put('/admin/books/' . $book->id, [
            'code' => 'B0012',
            'title' => 'New Title',
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
            'stock' => 10,
        ]);

        $response->assertRedirect('/admin/books');
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'New Title',
            'stock' => 10
        ]);
    }

    public function test_user_cannot_create_book()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->post('/admin/books', [
            'code' => 'B002',
            'title' => 'Hacked Book',
            'category' => 'Fiksi',
            'author' => 'Hacker',
            'publisher' => 'Hacker Pub',
            'year' => 2023,
            'description' => 'Hacked desc',
            'pages' => 10,
            'language' => 'Indonesia',
            'isbn_issn' => '123456789',
            'content_type' => 'Text',
            'media_type' => 'Print',
            'carrier_type' => 'Volume',
            'edition' => '1',
            'subject' => 'Umum',
            'isbn_issn' => '123456789',
            'content_type' => 'Text',
            'media_type' => 'Print',
            'carrier_type' => 'Volume',
            'edition' => '1',
            'subject' => 'Umum',
            'stock' => 1,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('books', [
            'title' => 'Hacked Book'
        ]);
    }
}
