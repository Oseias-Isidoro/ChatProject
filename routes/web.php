<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('dashboard')->group(function () {
        Route::resource('numbers', \App\Http\Controllers\NumberController::class);
        Route::get('numbers/connect/{number}', [\App\Http\Controllers\NumberController::class, 'connect'])->name('numbers.connect');
        Route::get('numbers/disconnect/{number}', [\App\Http\Controllers\NumberController::class, 'disconnect'])->name('numbers.disconnect');
    });
});

require __DIR__.'/auth.php';
