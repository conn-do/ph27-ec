<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/settings.php';
Route::get('/chirps', [ChirpController::class, 'index']);
Route::post('/chirps', [ChirpController::class, 'store'])->middleware('auth');
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories/{category}', [ProductController::class, 'category'])->name('categories.show');
Route::get('/ranking', [ProductController::class, 'ranking'])->name('products.ranking');
Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index')->block();
Route::post('/cart', [CartController::class, 'store'])->name('cart.store')->block();
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update')->block();
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear')->block();
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy')->block();
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [OrderController::class, 'create'])->name('checkout')->block();
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store')->block();
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{product}', [FavoriteController::class, 'store'])->name('favorites.store')->block();
    Route::delete('/favorites/{product}', [FavoriteController::class, 'destroy'])->name('favorites.destroy')->block();
    Route::inertia('/dashboard', 'dashboard')->name('dashboard');
});
