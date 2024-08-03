<?php

namespace App\Http\Controllers;

use App\Repository\BookQueryRepository;
use App\Services\SolrClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookController extends Controller
{
    /**
     * 与えられた id を持つ本情報を返す。
     */
    public function show(
        string $id,
        BookQueryRepository $bookRepository,
    ) {
        $book = $bookRepository->find($id);

        return response()->json($book);
    }

    /**
     * 与えられたキーワードをもとに本を検索して結果を返す。
     */
    public function searchByKeyword(
        Request $request,
        SolrClient $client,
        BookQueryRepository $bookRepository,
    ) {
        $keyword = $request->get('keyword');
        if (empty($keyword)) {
            return response()->json([
                'status' => "error",
                'error' => 'キーワードを指定してください。',
            ], 400);
        }
        $raws = $request->get('raws') ?? 20;
        if (!$this->validateRawsParam($raws)) {
            return response()->json([
                'status' => "error",
                'error' => '無効なクエリパラメータです。rawsは0から100の範囲で指定してください。',
            ], 400);
        }

        $bookIds = $client->searchByKeyword(keyword: $keyword, count: $raws);
        $books = $bookRepository->findMany($bookIds);

        return response()->json([
            'status' => 'success',
            'books' => $books,
        ]);
    }

    private function validateRawsParam(string $raws): bool
    {
        $raws = (int)$raws;
        if ($raws < 0 || $raws > 100) {
            return false;
        }

        return true;
    }
}
