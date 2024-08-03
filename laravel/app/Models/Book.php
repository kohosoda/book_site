<?php

namespace App\Models;

class Book
{

    public function __construct(
        public readonly string $googleId,
        public readonly string $title,
        public readonly array $authors,
        public readonly ?int $price,
        public readonly ?int $pageCount,
        public readonly ?string $publisher,
        public readonly ?\DateTimeInterface $publishedDate,
        public readonly string $category,
        public readonly string $description,
        public readonly \DateTimeImmutable $createdAt,
        public readonly \DateTimeImmutable $updatedAt,
    ) {
    }
}
