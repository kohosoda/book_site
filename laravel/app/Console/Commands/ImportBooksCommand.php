<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Repository\BookRepository;
use App\Services\GoogleBooksClient;
use DateTimeImmutable;
use Illuminate\Console\Command;

class ImportBooksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-books';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import books into tables.';

    /**
     * Execute the console command.
     */
    public function handle(
        GoogleBooksClient $googleBooksClient,
         BookRepository $bookRepository,
    )
    {
        $fileContent = file_get_contents(base_path('files/searchWord.txt'));
        $searchKeywords = explode("\n", $fileContent);

        foreach ($searchKeywords as $searchKeyword) {
            if (empty($searchKeyword)) {
                continue;
            }

            // Google API にリクエストを投げる
            $books = $googleBooksClient->request($searchKeyword);

            // DB に保存する
            foreach ($books as $book) {
                $bookRepository->save($book);
            }
        }
    }
}
