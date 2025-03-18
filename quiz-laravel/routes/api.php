<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\ResultController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CustomQuizController;

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

// Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user/update', [AuthController::class, 'updateProfile']);
    Route::get('/results/user', [ResultController::class, 'userStats']);
    Route::get('/results/user/history', [ResultController::class, 'userGameHistory']);
    
    // Custom Quiz Routes
    Route::post('/custom-quiz', [CustomQuizController::class, 'store']);
    Route::get('/custom-quiz', [CustomQuizController::class, 'index']);
    Route::get('/custom-quiz/{id}', [CustomQuizController::class, 'show']);
    Route::post('/custom-quiz/{id}/pdf', [CustomQuizController::class, 'generatePDF']);
    Route::post('/custom-quiz/{id}/presentation', [CustomQuizController::class, 'generatePresentation']);
    Route::get('/custom-quiz/{id}/questions', [CustomQuizController::class, 'getQuestions']);
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
