<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/settings.php';

Route::get(
    '/chirps',
    [ChirpController::class, 'index']
);

Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::get('/categories/{category}', [ProductController::class, 'category'])->name('products.category');
Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');
Route::middleware(['auth'])->group(function () {
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-page', [MyPageController::class, 'index'])->name('home');
});
