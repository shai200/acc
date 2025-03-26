<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScreenController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\InterviewAnalysisController;

// Test route at the very top
Route::get('/test-api', function() {
    return response()->json(['message' => 'API routes are working']);
});

Route::get('/books', [BookController::class, 'index']);

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

// Interview Analysis Route
Route::post('/analyze-interview', [InterviewAnalysisController::class, 'analyze']); 