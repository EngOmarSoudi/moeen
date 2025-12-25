<?php

use App\Http\Controllers\Api\TripTrackingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('api')->middleware(['api'])->group(function () {
    // Trip Tracking Routes
    Route::prefix('trips/{trip}')->group(function () {
        Route::post('/tracking', [TripTrackingController::class, 'store']);
        Route::get('/tracking', [TripTrackingController::class, 'index']);
        Route::get('/tracking/analysis', [TripTrackingController::class, 'analysis']);
    });
});
