<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;

// --- Authentifizierung (Öffentlich) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- Öffentlich abrufbar ---
Route::get('/videos', [VideoController::class, 'index']);
Route::get('/posts', [PostController::class, 'index']);
Route::get('/streams', [StreamController::class, 'index']);

// HIER: Die Kommentare sind jetzt an den Post gebunden
Route::get('/posts/{postId}/comments', [CommentController::class, 'index']);

// --- Geschützte Routen (Nur für eingeloggte Nutzer) ---
Route::middleware('auth:sanctum')->group(function () {
    // Benutzerprofil
    Route::get('/user/me', [AuthController::class, 'me']);
    Route::put('/user/profile', [UserController::class, 'update']);

    // Neue Inhalte erstellen
    Route::post('/videos', [VideoController::class, 'store']);
    Route::post('/posts', [PostController::class, 'store']);
    
    // HIER: Der Kommentar-Speicher-Prozess braucht den Post als Referenz
    Route::post('/posts/{postId}/comments', [CommentController::class, 'store']);
    
    // Voting-Route
    Route::post('/posts/{post}/vote', [PostController::class, 'vote']);
});