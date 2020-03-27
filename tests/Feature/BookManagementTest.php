<?php

namespace Tests\Feature;

use App\Author;
use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_book_can_be_adder_to_the_library()
    {
        //$this->withoutExceptionHandling();
        $response = $this->post('/books', $this->data());
        $this->assertCount(1, Book::all());
        $book = Book::first();
        $response->assertRedirect($book->path());
    }

    /** @test */
    public function a_title_is_required()
    {
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
        $response = $this->post('/books', array_merge($this->data(), ['author_id' => '']));
        $response->assertSessionHasErrors('author_id');
    }

    /** @test */
    public function a_book_can_be_updated()
    {
        $this->post('/books', $this->data());

        $book = Book::first();

        $response = $this->patch($book->path(), [
            'title' => 'New Title',
            'author_id' => 'Victor',
        ]);
        $this->assertEquals('New Title', Book::first()->title);
        //$this->assertEquals(9, Book::first()->author_id);
        $response->assertRedirect($book->fresh()->path());
    }

    /** @test */
    public function a_book_can_be_deleted()
    {
        //$this->withoutExceptionHandling();
        $response = $this->post('/books', $this->data());

        $book = Book::first();
        $this->assertCount(1, Book::all());
        $response = $this->delete($book->path());

        $this->assertCount(0, Book::all());
        $response->assertRedirect(route('books.index'));
    }

    /** @test */
    public function a_new_author_is_automatically_added()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/books', [
            'title'  => 'Cool Book Title',
            'author_id' => 'Victor',
        ]);

        $book = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());
    }

    private function data()
    {
        return [
            'title'  => 'Cool Book Title',
            'author_id' => 'Victor',
        ];
    }
}
