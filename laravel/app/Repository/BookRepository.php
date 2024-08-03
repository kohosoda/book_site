<?php

namespace App\Repository;

use App\Models\Book;
use Illuminate\Support\Facades\DB;

class BookRepository
{
    public function save(Book $book)
    {
        $categoryId = $this->saveToCategories($book);
        $authorIds = $this->saveToAuthors($book);

        DB::table('books')->updateOrInsert(
            [
                'google_id' => $book->googleId,
            ],
            [
                'title' => $book->title,
                'price' => $book->price,
                'page_count' => $book->pageCount,
                'publisher' => $book->publisher,
                'published_date' => $book->publishedDate,
                'category_id' => $categoryId,
                'description' => $book->description,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // rel_books_authors テーブルに保存
        $bookId = DB::table('books')->where('google_id', $book->googleId)->value('id');
        foreach ($authorIds as $authorId) {
            DB::table('rel_books_authors')->updateOrInsert(
                [
                    'book_id' => $bookId,
                    'author_id' => $authorId,
                ],
                [
                    'book_id' => $bookId,
                    'author_id' => $authorId,
                ]
            );
        }
    }

    private function saveToCategories(Book $book)
    {
        $category = DB::table('categories')->where('name', $book->category)->first();

        if (!$category) {
            $categoryId = DB::table('categories')->insertGetId(
                [
                    'name' => $book->category,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        } else {
            $categoryId = $category->id;
        }

        return $categoryId;
    }

    private function saveToAuthors(Book $book)
    {
        $authorIds = [];

        foreach ($book->authors as $authorName) {
            $author = DB::table('authors')->where('name', $authorName)->first();

            if (!$author) {
                $authorId = DB::table('authors')->insertGetId(
                    [
                        'name' => $authorName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
                $authorIds[] = $authorId;
            } else {
                $authorIds[] = $author->id;
            }
        }

        return $authorIds;
    }
}
