<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController; // Den Controller hier oben importieren

// --- Authentifizierung (Öffentlich) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- Öffentlich abrufbar ---
Route::get('/videos', [VideoController::class, 'index']);
Route::get('/posts', [PostController::class, 'index']);
Route::get('/streams', [StreamController::class, 'index']);
Route::get('/comments', [CommentController::class, 'index']);

// --- Geschützte Routen (Nur für eingeloggte Nutzer) ---
Route::middleware('auth:sanctum')->group(function () {
    // Benutzerprofil
    Route::get('/user/me', [AuthController::class, 'me']);
    Route::put('/user/profile', [UserController::class, 'update']);

    // Neue Inhalte erstellen
    Route::post('/videos', [VideoController::class, 'store']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::post('/comments', [CommentController::class, 'store']);
    
    // HIER gehört die Vote-Route rein, damit sie geschützt ist:
    Route::post('/posts/{post}/vote', [PostController::class, 'vote']);
});