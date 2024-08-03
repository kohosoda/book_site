<?php

namespace Tests\Unit;

use App\Services\GoogleBooksClient;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class GoogleBooksClientTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testRequest(): void
    {
        $response = file_get_contents("files/response.txt");
        $client = new GoogleBooksClient();
        $books = $client->request($response);
    }
}
