<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\CommentController;

// --- Authentifizierung (Öffentlich) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- Öffentlich abrufbar (Auch ohne Login sichtbar) ---
Route::get('/videos', [VideoController::class, 'index']);
Route::get('/posts', [PostController::class, 'index']);
Route::get('/streams', [StreamController::class, 'index']);
Route::get('/comments', [CommentController::class, 'index']); // Kommentare lesen darf jeder

// --- Geschützte Routen (Nur für eingeloggte Nutzer via Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {
    // Benutzerprofil & eigene Uploads für "Mein Account"
    Route::get('/user/me', [AuthController::class, 'me']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);

    // Neue Inhalte erstellen
    Route::post('/videos', [VideoController::class, 'store']);
    Route::post('/posts', [PostController::class, 'store']); // Neue Diskussionen im Backend speichern

    // Neuen Kommentar schreiben (für Video oder Post)
    Route::post('/comments', [CommentController::class, 'store']);

    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
});