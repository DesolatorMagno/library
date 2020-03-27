<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Reservation;
use App\Book;

class BookReservationsTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_book_can_be_checked_out()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();
        $book->checkout($user);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
    }

    /** @test */
    public function a_book_can_be_returned()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();
        $book->checkout($user);
        $book->checkin($user);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_in_at);
    }

    /** @test */
    public function a_user_can_checked_out_a_book_twice()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();
        $book->checkout($user);
        $book->checkin($user);
        $book->checkout($user);

        $reserved = $book->reserved($user);
        $this->assertCount(2, Reservation::all());
        $this->assertEquals($user->id, $reserved->user_id);
        $this->assertEquals($book->id, $reserved->book_id);
        $this->assertNull($reserved->checked_in_at);
        $this->assertEquals(now(), $reserved->checked_out_at);


        $book->checkin($user);
        $lastReservation = $book->lastReservation($user);
        $this->assertCount(2, Reservation::all());
        $this->assertEquals($user->id, $lastReservation->user_id);
        $this->assertEquals($book->id, $lastReservation->book_id);
        $this->assertNotNull($lastReservation->checked_in_at);
        $this->assertEquals(now(), $lastReservation->checked_out_at);
    }

    /** @test */
    public function if_not_checked_out_exception_is_thrown()
    {
        $this->expectException(\Exception::class);

        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkin($user);
    }


    // if no checkedout, then exception
    // a user can checkout twice
}
