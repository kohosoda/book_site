<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SolrClient
{
    const SOLR_QUERY_URL = 'http://solr:8983/solr/books/select';

    /**
     * Description のベクトル検索
     * 
     * @param string $id
     * @param int $count
     * @return int[]
     */
    public function searchSimlarBooks(string $id, int $count = 10): array
    {
        // 対象 book のベクトル値を取得する
        $params = ['q' => 'id:' . $id, 'fl' => 'description_vector', 'wt' => 'json'];
        $response = Http::get(self::SOLR_QUERY_URL, $params);
        $vector = $response->json()['response']['docs'][0]['description_vector'];

        // 類似の Description を持つ本を検索する
        $topK = $count + 1;
        $vectorText = '[' . implode(',', $vector) . ']';
        $payload = [
            "query" => "{!knn f=description_vector topK={$topK}}{$vectorText}",
            "fields" => ["id", "score"]
        ];
        $response = Http::post(self::SOLR_QUERY_URL, $payload);

        // [[ "id" => 1, "score" => 0.8 ], [ "id" => 2, "score" => 0.7 ], ...] のような配列
        $recommendations = $response->json()['response']['docs'];

        $bookIds = array_map(function ($recommendation) {
            return $recommendation['id'];
        }, $recommendations);
        $bookIds = array_filter($bookIds, function ($bookId) use ($id) {
            return $bookId !== $id;
        });

        return $bookIds;
    }

    /**
     * キーワード検索
     * 
     * @param string $keyword
     * @param int $count
     * @return int[]
     */
    public function searchByKeyword(string $keyword, int $count = 20): array
    {
        $query = "title:{$keyword} OR description:{$keyword}";
        $params = [
            'q' => $query,
            'wt' => 'json',
            'fl' => 'id,title',
            'rows' => $count,
        ];

        $response = Http::get(self::SOLR_QUERY_URL, $params);
        if ($response->failed()) {
            throw new \Exception();
        }

        $results = $response->json();
        $rawBooks = $results['response']['docs'];
        $bookIds = array_map(function ($book) {
            return $book['id'];
        }, $rawBooks);

        return $bookIds;
    }
}
