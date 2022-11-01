<?php

namespace Tests;

use App\Models\Book;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function createBookList($args = [])
    {
        return Book::factory()->create($args);
    }
}
