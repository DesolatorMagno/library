<?php

namespace Tests\Feature;

use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_book_can_be_adder_to_the_library()
    {
        //$this->withoutExceptionHandling();
        $response = $this->post(
            '/books',
            [
                'title'  => 'Cool Book Title',
                'author' => 'Victor',
            ]
        );
        $response->assertOk();
        $this->assertCount(1, Book::all());

        $response->assertStatus(200);
    }

    /** @test */
    public function a_title_is_required()
    {
        //$this->withoutExceptionHandling();
        $response = $this->post(
            '/books',
            [
                'title'  => '',
                'author' => 'Victor',
            ]
        );
        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_author_is_required()
    {
        //$this->withoutExceptionHandling();
        $response = $this->post(
            '/books',
            [
                'title'  => 'Cool Book Title',
                'author' => '',
            ]
        );
        $response->assertSessionHasErrors('author');
    }

    /** @test */
    public function a_book_can_be_updated()
    {
        // $this->withoutExceptionHandling();

        $this->post('/books', [
            'title'  => 'Cool Book Title',
            'author' => 'Victor',
        ]);

        $book = Book::first();

        $response = $this->patch("/books/$book->id", [
            'title' => 'New Title',
            'author' => 'Victor',
        ]);

        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals('Victor', Book::first()->author);
    }
}
