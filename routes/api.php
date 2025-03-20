<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScreenController;

// Test route to verify API is working
Route::get('/test', function() {
    return response()->json(['message' => 'API is working']);
});

Route::get('/screen1-text', function() {
    return response()->json(['text' => 'This is Screen 1']);
});

Route::get('/screen1', [ScreenController::class, 'screen1']);
Route::get('/screen2', [ScreenController::class, 'screen2']);
Route::get('/screen3', [ScreenController::class, 'screen3']); 