<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScreenController;

// API Routes
Route::get('/api/screen1', [ScreenController::class, 'screen1']);
Route::get('/api/screen2', [ScreenController::class, 'screen2']);
Route::get('/api/screen3', [ScreenController::class, 'screen3']);

// Catch-all route for the SPA
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');
