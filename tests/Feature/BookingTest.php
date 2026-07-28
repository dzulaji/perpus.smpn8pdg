<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Booking;

class BookingTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_book_a_book()
    {
        $user = User::factory()->create(['role' => 'user']);
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

        $response = $this->actingAs($user)->from('/')->post('/booking', [
            'book_id' => $book->id,
            'status' => 'Diajukan',
            'is_denda' => 0,
            'alasan' => 'Pinjam untuk belajar',
            'expired_at' => now()->addDays(3)->format('Y-m-d'),
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'Diajukan'
        ]);
    }

    public function test_user_cannot_view_others_booking()
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);
        
        $book = Book::create([
            'code' => 'B001',
            'title' => 'Test Book',
            'category' => 'Fiksi',
            'author' => 'Author',
            'publisher' => 'Publisher',
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

        $booking = Booking::create([
            'user_id' => $user1->id,
            'book_id' => $book->id,
            'booking_code' => 'BK-12345',
            'pickup_date' => now()->addDay(),
            'return_date' => now()->addDays(3),
            'status' => 'Diajukan'
        ]);

        // User2 tries to view User1's booking
        $response = $this->actingAs($user2)->get('/booking/' . $booking->id);
        
        // Should be forbidden (403) or Not Found depending on implementation
        $response->assertStatus(403);
    }
}
