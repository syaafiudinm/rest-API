<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('list-articles', [App\Http\Controllers\API\v1\ArticleController::class, 'index']);
    Route::post('store-articles', [App\Http\Controllers\API\v1\ArticleController::class, 'store'])->middleware('api');
    Route::get('show-article/{id}', [App\Http\Controllers\API\v1\ArticleController::class, 'show'])->middleware('api');
    Route::put('update-article/{id}', [App\Http\Controllers\API\v1\ArticleController::class, 'update'])->middleware('api');
});
