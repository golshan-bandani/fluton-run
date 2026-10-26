<?php

use App\Http\Controllers\GameApiController;
use Illuminate\Support\Facades\Route;

// Final URLs: /api/players, /api/leaderboard, /api/scores, /api/quiz, /api/quiz/answer
Route::post('/players',     [GameApiController::class, 'register'])->middleware('throttle:10,1');
Route::get('/leaderboard',  [GameApiController::class, 'leaderboard'])->middleware('throttle:60,1');
Route::post('/scores',      [GameApiController::class, 'submitScore'])->middleware('throttle:30,1');
Route::get('/quiz',         [GameApiController::class, 'quiz'])->middleware('throttle:30,1');
Route::post('/quiz/answer', [GameApiController::class, 'answer'])->middleware('throttle:30,1');
