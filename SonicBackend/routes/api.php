<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StreamController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/videos', [VideoController::class, 'index']);
Route::get('/videos/{id}/comments', [VideoController::class, 'getComments']);
Route::get('/posts', [PostController::class, 'index']);
Route::get('/streams', [StreamController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/videos', [VideoController::class, 'store']);
    Route::post('/videos/{id}/comments', [VideoController::class, 'addComment']);
    Route::post('/posts', [PostController::class, 'store']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/me', [AuthController::class, 'me']);
    Route::get('/me', [AuthController::class, 'me']); // Diese Zeile hinzufügen
});