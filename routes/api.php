<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CsvController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/public/posts', [PublicController::class, 'posts']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/categories/export', [ExcelController::class, 'exportCategories']);
    Route::get('/posts/export', [ExcelController::class, 'exportPosts']);
    Route::post('/categories/import', [ExcelController::class, 'importCategories']);

    Route::apiResource('posts', PostController::class);

    Route::apiResource('categories', CategoryController::class);
});
