<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => true,
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('trips/{trip}/print', \App\Http\Controllers\TripPrintController::class)
    ->middleware(['auth'])
    ->name('trips.print');

Route::get('lang/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])
    ->name('lang.switch');

require __DIR__.'/settings.php';
