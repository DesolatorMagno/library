<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Author;
use Carbon\Carbon;

class AuthorManagementTest extends TestCase
{
    use RefreshDatabase;
    /** @test
     * @group author
     *
     */
    public function an_author_can_be_created()
    {
        //$this->withoutExceptionHandling();
        $response = $this->post(route('authors.store'), [
            'name' => 'Author Name',
            'dob' => '05/14/1988'
        ]);

        $authors = Author::all();
        $this->assertCount(1, $authors);
        $this->assertInstanceOf(Carbon::class, $authors->first()->dob);
        $this->assertEquals('1988/14/05', $authors->first()->dob->format('Y/d/m'));
        $response->assertStatus(200);
    }
}
