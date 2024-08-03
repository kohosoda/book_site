<?php

namespace App\Repository;

use App\Models\BookViewModel;
use Illuminate\Support\Facades\DB;

class BookQueryRepository
{
    public function find(string $id): ?BookViewModel
    {
        $books = $this->findMany([$id]);
        if (empty($books)) {
            return null;
        }

        return $books[0];
    }

    /**
     * @param string[]
     * @return Book[]
     */
    public function findMany(array $ids): array
    {
        $rawBooks = DB::table('books')
            ->select(
                'books.id',
                'google_id',
                'title',
                'price',
                'page_count',
                'publisher',
                'published_date',
                'description',
                'categories.name as category_name',
                DB::raw('GROUP_CONCAT(authors.name) as author_names'),
            )
            ->join('categories', 'books.category_id', '=', 'categories.id')
            ->join('rel_books_authors', 'books.id', '=', 'rel_books_authors.book_id')
            ->join('authors', 'authors.id', '=', 'rel_books_authors.author_id')
            ->whereIn('books.id', $ids)
            ->orderByRaw("FIELD(books.id, " . implode(',', $ids) . ")") // 元々の id 順で取得するために FIELD を使用
            ->groupBy('books.id')
            ->get();

        $books = [];
        foreach ($rawBooks as $rawBook) {
            $books[] = new BookViewModel(
                id: $rawBook->id,
                googleId: $rawBook->google_id,
                title: $rawBook->title,
                authors: explode(',', $rawBook->author_names),
                price: $rawBook->price,
                pageCount: $rawBook->page_count,
                publisher: $rawBook->publisher,
                publishedDate: $rawBook->published_date ? new \DateTimeImmutable($rawBook->published_date) : null,
                category: $rawBook->category_name,
                description: $rawBook->description,
                createdAt: new \DateTimeImmutable(),
                updatedAt: new \DateTimeImmutable()
            );
        }

        return $books;
    }
}
