<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StreamController;

// --- Authentifizierung (öffentlich) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- Öffentliches Lesen ---
Route::get('/videos', [VideoController::class, 'index']);
Route::get('/posts', [PostController::class, 'index']);
Route::get('/streams', [StreamController::class, 'index']);

// --- Geschützt: nur eingeloggte Nutzer ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/videos', [VideoController::class, 'store']);
    Route::post('/videos/{id}/comments', [VideoController::class, 'addComment']);
    Route::post('/posts', [PostController::class, 'store']);
});