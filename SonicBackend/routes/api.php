<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\DiscussionController; // <-- HIER MUSS DAS HIN!

// --- Authentifizierung (Öffentlich) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- Öffentlich abrufbar ---
Route::get('/videos', [VideoController::class, 'index']);
Route::get('/posts', [PostController::class, 'index']);
Route::get('/streams', [StreamController::class, 'index']);
Route::get('/posts/{postId}/comments', [CommentController::class, 'index']);

// --- Geschützte Routen (Nur für eingeloggte Nutzer) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/me', [AuthController::class, 'me']);
    Route::put('/user/profile', [UserController::class, 'update']);

    Route::post('/videos', [VideoController::class, 'store']);
    Route::post('/posts', [PostController::class, 'store']);
    
    // GRUPPEN ROUTEN
    Route::post('/groups', [GroupController::class, 'store']);
    Route::delete('/groups/{group}', [GroupController::class, 'destroy']);

    Route::post('/posts/{postId}/comments', [CommentController::class, 'store']);
    Route::post('/posts/{post}/vote', [PostController::class, 'vote']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);

    // DISKUSSION ROUTE
    // Middleware ist hier nicht mehr nötig, da sie vom Gruppen-Block oben "geerbt" wird
    Route::post('/groups/{groupId}/discussions', [DiscussionController::class, 'store']);
    Route::get('/groups/{groupId}/discussions', [DiscussionController::class, 'index']);
});
