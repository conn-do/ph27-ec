<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SampleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::get('/sample', [SampleController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
// in the end this means this gave a name for the URL
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
