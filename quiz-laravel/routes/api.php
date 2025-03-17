<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\ResultController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FriendshipController;
use App\Http\Controllers\API\GameInviteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // User API
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/status', [AuthController::class, 'updateStatus']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Friendship API
    Route::get('/friends', [FriendshipController::class, 'index']);
    Route::get('/friends/pending', [FriendshipController::class, 'pendingRequests']);
    Route::get('/friends/search', [FriendshipController::class, 'search']);
    Route::post('/friends', [FriendshipController::class, 'store']);
    Route::put('/friends/{id}/accept', [FriendshipController::class, 'accept']);
    Route::put('/friends/{id}/reject', [FriendshipController::class, 'reject']);
    Route::delete('/friends/{id}', [FriendshipController::class, 'destroy']);
    
    // Game Invites API
    Route::get('/game-invites', [GameInviteController::class, 'index']);
    Route::post('/game-invites', [GameInviteController::class, 'store']);
    Route::put('/game-invites/{id}/accept', [GameInviteController::class, 'accept']);
    Route::put('/game-invites/{id}/reject', [GameInviteController::class, 'reject']);
    Route::get('/games/{gameId}', [GameInviteController::class, 'show']);
});

// Questions API
Route::get('/questions/seed', [QuestionController::class, 'seed']);
Route::get('/questions', [QuestionController::class, 'index']);
Route::post('/questions', [QuestionController::class, 'store']);
Route::get('/questions/{id}', [QuestionController::class, 'show']);
Route::put('/questions/{id}', [QuestionController::class, 'update']);
Route::delete('/questions/{id}', [QuestionController::class, 'destroy']);

// Categories API
Route::get('/categories', [QuestionController::class, 'categories']);

// Results API
Route::get('/results', [ResultController::class, 'index']);
Route::post('/results', [ResultController::class, 'store']);
Route::get('/top-scores', [ResultController::class, 'topScores']);

// System routes
Route::get('/cleanup/game-invites', [GameInviteController::class, 'cleanupExpired']);
