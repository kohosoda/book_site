<?php

namespace Tests\Unit\Repository;

use App\Models\Book;
use App\Repository\BookRepository;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BookRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }

    /**
     * save メソッドのテスト
     */
    public function testSave(): void
    {
        $book = new Book(
            googleId: '1234567890',
            title: 'テスト',
            authors: ['山田太郎', '鈴木一郎'],
            price: 1000,
            pageCount: 100,
            publisher: '出版社',
            publishedDate: new \DateTimeImmutable('2021-01-01'),
            category: "Computer",
            description: 'テスト',
            createdAt: new \DateTimeImmutable(),
            updatedAt: new \DateTimeImmutable(),
        );

        $repository = new BookRepository();
        $repository->save($book);

        $this->assertDatabaseHas('books', [
            'google_id' => '1234567890',
        ]);
    }
}
