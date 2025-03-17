<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\ParticipantController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('events.index');
});

Route::resource('events', EventController::class);

Route::post('events/{event}/participants', [ParticipantController::class, 'store'])
    ->name('participants.store');

Route::delete('events/{event}/participants/{participant}', [ParticipantController::class, 'destroy'])
    ->name('participants.destroy');

Route::post('events/{event}/check-in/{qrCode}', [ParticipantController::class, 'checkIn'])
    ->name('participants.check-in'); 