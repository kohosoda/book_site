<?php

namespace App\Services;

use App\Models\Book;

class GoogleBooksClient
{
    public function request(string $searchWord)
    {
        // パラメータ
        $maxResults = 40; // 最大で 40 件まで一度に取得できる
        $startIndex = 0;
        $baseUrl = 'https://www.googleapis.com/books/v1/volumes';

        // Google API にリクエストを投げる
        $url = "{$baseUrl}?q={$searchWord}&maxResults={$maxResults}&startIndex={$startIndex}";
        $response = $this->requestToApi($url);

        $books = $this->createBookInstances($response);

        return $books;
    }

    private function requestToApi(string $url): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
            throw new \Exception("Curl Failed. error: {$error}");
        }

        return $response;
    }

    private function createBookInstances(string $response): array
    {
        $decodedResponse = json_decode($response, true);

        $books = [];

        // リクエストの結果を保存用に変換する
        foreach ($decodedResponse['items'] as $bookData) {
            // title, authors が空の場合はスキップ
            if (
                !isset($bookData['volumeInfo']['title'])
                || !isset($bookData['volumeInfo']['authors'])
            ) {
                continue;
            }

            $book = new Book(
                googleId: $bookData['id'],
                title: $bookData['volumeInfo']['title'],
                authors: $bookData['volumeInfo']['authors'],
                price: $bookData['saleInfo']['listPrice']['amount'] ?? null,
                pageCount: $bookData['volumeInfo']['pageCount'] ?? null,
                publisher: $bookData['volumeInfo']['publisher'] ?? null,
                publishedDate: isset($bookData['volumeInfo']['publishedDate']) ? new \DateTimeImmutable($bookData['volumeInfo']['publishedDate']) : null,
                category: $bookData['volumeInfo']['categories'][0] ?? 'その他',
                description: $bookData['volumeInfo']['description'] ?? '',
                createdAt: new \DateTimeImmutable(),
                updatedAt: new \DateTimeImmutable(),
            );

            $books[] = $book;
        }

        return $books;
    }
}
