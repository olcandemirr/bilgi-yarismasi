<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CustomQuizController;
use App\Http\Controllers\API\ResultController;
use App\Http\Controllers\API\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Ana sayfa
Route::get('/', function () {
    return view('welcome');
});

// Özel yarışma rotaları
Route::middleware(['auth:sanctum'])->group(function () {
    // Özel yarışma oluşturma ve yönetme
    Route::get('/custom-quiz', function () {
        return view('welcome');
    })->name('custom-quiz.index');
    
    Route::get('/custom-quiz/create', function () {
        return view('welcome');
    })->name('custom-quiz.create');
    
    Route::get('/custom-quiz/{id}', function () {
        return view('welcome');
    })->name('custom-quiz.show');
    
    // PDF ve sunum indirme rotaları
    Route::get('/custom-quiz/{id}/pdf', [CustomQuizController::class, 'downloadPDF'])
        ->name('custom-quiz.pdf');
    
    Route::get('/custom-quiz/{id}/presentation', [CustomQuizController::class, 'downloadPresentation'])
        ->name('custom-quiz.presentation');
    
    // Oyun sonuçları
    Route::get('/results', function () {
        return view('welcome');
    })->name('results.index');
    
    Route::get('/results/{id}', function () {
        return view('welcome');
    })->name('results.show');
    
    // Profil sayfası
    Route::get('/profile', function () {
        return view('welcome');
    })->name('profile');
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('welcome');
    })->name('dashboard');
});

// Auth rotaları
Route::get('/login', function () {
    return view('welcome');
})->name('login');

Route::get('/register', function () {
    return view('welcome');
})->name('register');

// Catch-all route for SPA
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
