<?php

namespace Tests\Feature;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookTest extends TestCase
{

    private $list;

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

    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_example()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }


    public function test_create_books()
    {

        $list = Book::factory()->make();
        $response = $this->postJson(route('books.store'), ['name' => $list->name])
            ->assertCreated()
            ->json('data');

        $this->assertEquals($list->name, $response['name']);
        $this->assertDatabaseHas('books', ['name' => $list->name]);
    }

    public function test_fetch_all_books()
    {
        $this->createBookList();
        $response = $this->getJson(route('books.index'))->json('data');

        $this->assertEquals(1, count($response));
        $this->assertEquals('my list', $response[0]['name']);
    }

    public function test_update_book()
    {
        $this->patchJson(route('books.update', $this->list->id), ['name' => 'updated name'])
            ->assertOk();

        $this->assertDatabaseHas('books', ['id' => $this->list->id, 'name' => 'updated name']);
    }

    public function test_fetch_single_books()
    {
        $response = $this->getJson(route('books.show', $this->list->id))
            ->assertOk()
            ->json('data');

        $this->assertEquals($response['name'], $this->list->name);
    }

    public function test_delete_book()
    {
        $this->deleteJson(route('todo-list.destroy', $this->list->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('todo_lists', ['name' => $this->list->name]);
    }
}
