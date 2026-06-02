<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

// Route::inertia('/', 'welcome', [
//     'canRegister' => Features::enabled(Features::registration()),
// ])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__ . '/settings.php';

Route::get(
    '/chirps',
    [ChirpController::class, 'index']
);
Route::post(
    '/chirps',
    [ChirpController::class, 'store']
);

Route::get('/', [ProductController::class, 'index']);
Route::get(
    '/products/{product}',
    [ProductController::class, 'show']
);
Route::post(
    '/cart',
    [CartController::class, 'store']
);
