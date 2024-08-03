<?php

namespace Tests\Unit;

use App\Services\SolrClient;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SolrClientTest extends TestCase
{
    protected SolrClient $solrClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->solrClient = new SolrClient();
    }

    public function testSearchSimilarBooks()
    {
        // モックレスポンスを設定
        Http::fake([
            'http://solr:8983/solr/books/select?q=id%3A1&fl=description_vector&wt=json' => Http::response(
                [
                    'response' => [
                        'docs' => [
                            ['description_vector' => [0.1, 0.2, 0.3]]
                        ]
                    ]
                ]
            ),
            'http://solr:8983/solr/books/select' => Http::response(
                [
                    'response' => [
                        'docs' => [
                            ['id' => 1, 'score' => 0.8],
                            ['id' => 2, 'score' => 0.7],
                            ['id' => 3, 'score' => 0.6],
                            ['id' => 4, 'score' => 0.5],
                            ['id' => 5, 'score' => 0.4],
                            ['id' => 6, 'score' => 0.3],
                        ]
                    ]
                ]
            ),
        ]);

        $result = $this->solrClient->searchSimlarBooks(id: 1, count: 5);
        $this->assertEqualsCanonicalizing([2, 3, 4, 5, 6], $result); // '1' は除外されるべき
    }

    public function testSearchByKeyword()
    {
        $keyword = 'react';
        $count = 5;

        // モックレスポンスを設定
        Http::fake([
            'http://solr:8983/solr/books/select?q=title%3Areact%20OR%20description%3Areact&wt=json&fl=id%2Ctitle&rows=5' => Http::response([
                'response' => [
                    'docs' => [
                        ['id' => 1, 'title' => 'test1'],
                        ['id' => 2, 'title' => 'test2'],
                        ['id' => 3, 'title' => 'test3'],
                        ['id' => 4, 'title' => 'test4'],
                        ['id' => 5, 'title' => 'test5'],
                    ]
                ]
            ], 200),
        ]);

        // SolrClient のメソッドを呼び出す
        $result = $this->solrClient->searchByKeyword($keyword, $count);
        $this->assertEquals([1, 2, 3, 4, 5], $result);
    }
}