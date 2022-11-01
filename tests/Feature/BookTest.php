<?php

namespace Tests\Feature;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    private $list;


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->list = $this->createBookList([
            "name" => "My First Book",
            "isbn" => "123-3213243567",
            "authors" => [
                "John Doe"
            ],
            "number_of_pages" => 350,
            "publisher" => "Acme Books",
            "country" => "United States",
            "release_date" => "2019-08-01"
        ]);
    }

    public function test_fetch_single_books()
    {
        $response = $this->getJson(route('books.show', $this->list->id))
            ->assertOk()
            ->json('data');

        $this->assertEquals($response['name'], $this->list->name);
    }

    public function test_update_book()
    {
        $this->patchJson(route('books.update', $this->list->id), [
            'name' => 'updated name',
            'isbn' => 'updated isbn',
            'authors' => ['updated authors'],
            'number_of_pages' => 123,
            'publisher' => 'updated publisher',
            'country' => 'updated country',
            'release_date' => '2019-08-01'
        ])
            ->assertOk();

        $this->assertDatabaseHas('books', ['id' => $this->list->id, 'name' => 'updated name']);
    }

    public function test_delete_book()
    {
        $this->deleteJson(route('books.destroy', $this->list->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('books', ['name' => $this->list->name]);
    }
}
