<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Reservation;
use App\Book;

class BookCheckOutTest extends TestCase
{
    use RefreshDatabase;
    /** @test  */
    public function a_book_can_be_checked_out_by_a_signed_in_user()
    {
        $book = factory(Book::class)->create();

        $this->actingAs($user = factory(User::class)->create())
            ->post(route('checkout.store', ['book' => $book->id]));

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
    }

    /** @test  */
    public function only_signed_in_users_can_checkout_a_book()
    {
        //$this->withoutExceptionHandling();

        $book = factory(Book::class)->create();

        $this->post(route('checkout.store', ['book' => $book->id]))
            ->assertRedirect('/login');

        $this->assertCount(0, Reservation::all());
    }

    /** @test  */
    public function only_real_books_can_be_checked_out()
    {
        $id = 123;
        $this->actingAs($user = factory(User::class)->create())
            ->post(route('checkout.store', ['book' => $id]))
            ->assertStatus(404);

        $this->assertCount(0, Reservation::all());
    }

    /** @test  */
    public function a_book_can_be_checked_in_by_a_signed_in_user()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('checkout.store', ['book' => $book->id]));

        $this->actingAs($user)
            ->post(route('checkin.store', ['book' => $book->id]));

        $reservation = Reservation::first();
        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);
        $this->assertEquals(now(), $reservation->checked_out_at);
        $this->assertEquals(now(), $reservation->checked_in_at);
    }

    /** @test  */
    public function only_signed_in_users_can_checkin_a_book()
    {
        //$this->withoutExceptionHandling();

        $book = factory(Book::class)->create();
        $this->actingAs(factory(User::class)->create())
            ->post(route('checkout.store', ['book' => $book->id]))
            ->assertStatus(200);

        Auth::logout();

        $this->post(route('checkin.store', ['book' => $book->id]))
            ->assertRedirect('/login');

        $this->assertCount(1, Reservation::all());

        $reservation = Reservation::first();
        $this->assertCount(1, Reservation::all());
        $this->assertNull($reservation->checked_in_at);
    }

    /** @test  */
    public function a_404_is_trown_if_a_book_is_not_checked_out_first()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('checkin.store', ['book' => $book->id]))
            ->assertStatus(404);
    }
}
