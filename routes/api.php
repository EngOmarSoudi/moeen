<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TripTrackingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected Routes (require authentication)
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Auth Routes
    Route::prefix('auth')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    });

    // Trip Tracking Routes
    Route::prefix('trips/{trip}')->group(function () {
        Route::post('/tracking', [TripTrackingController::class, 'store']);
        Route::get('/tracking', [TripTrackingController::class, 'index']);
        Route::get('/tracking/analysis', [TripTrackingController::class, 'analysis']);
    });
});
