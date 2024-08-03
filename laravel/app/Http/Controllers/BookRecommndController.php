<?php

namespace App\Http\Controllers;

use App\Repository\BookQueryRepository;
use App\Services\SolrClient;
use Illuminate\Http\Request;

class BookRecommndController extends Controller
{
    public function __invoke(
        string $id,
        SolrClient $client,
        BookQueryRepository $bookRepository,
        Request $request,
    ) {
        $raws = $request->get('raws') ?? 10;
        if (!$this->validateRawsParam($raws)) {
            return response()->json([
                'status' => "error",
                'error' => '無効なクエリパラメータです。rawsは0から40の範囲で指定してください。',
            ], 400);
        }

        $bookIds = $client->searchSimlarBooks(id: $id, count: $raws);
        $books = $bookRepository->findMany($bookIds);

        return response()->json([
            'status' => 'success',
            'books' => $books,
        ]);
    }

    private function validateRawsParam(string $raws): bool
    {
        $raws = (int)$raws;
        if ($raws < 0 || $raws > 40) {
            return false;
        }

        return true;
    }
}
