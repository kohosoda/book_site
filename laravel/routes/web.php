<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BookRecommndController;
use Illuminate\Support\Facades\Route;

Route::controller(BookController::class)->group(function () {
    Route::get('/api/books/search', 'searchByKeyword');
    Route::get('/api/books/{id}', 'show');
});

Route::get('/api/books/{id}/recommendations', BookRecommndController::class);

Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');
