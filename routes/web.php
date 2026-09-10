<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('products', [ProductController::class, 'index'])->name('products.index');
Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('search', [ProductController::class, 'search'])->name('products.search');
Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::post('cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('cart', [CartController::class, 'clear'])->name('cart.clear');
Route::get('news/{news}', [NewsController::class, 'show'])->name('news.show');

Route::middleware(['auth'])->group(function () {
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('dashboard', '/chirps')->name('dashboard');
    Route::resource('chirps', ChirpController::class)->only(['index', 'store']);
});

require __DIR__ . '/settings.php';
