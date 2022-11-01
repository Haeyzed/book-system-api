<?php

namespace Tests\Feature;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_create_books()
    {
        $book = factory(Book::class, 2)->create();
        $response = $this->get(route('books.index'));
        $resource = BookResource::collection($book);
        $response->assertResource($resource);
    }

    public function test_get_books()
    {
        $response = $this->getJson('/');

        $response->assertStatus(200);
    }
}
