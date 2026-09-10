<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Auth API
Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::get('/auth/me', [AuthController::class, 'me']);

// Public API
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{article}', [ArticleController::class, 'show']);
Route::get('/dashboard/summary', [DashboardController::class, 'summary']);
Route::get('/dashboard/report', [DashboardController::class, 'reportData']);

// Admin Protected API
Route::middleware(['auth'])->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    Route::post('/articles', [ArticleController::class, 'store']);
    Route::put('/articles/{article}', [ArticleController::class, 'update']);
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy']);
});
