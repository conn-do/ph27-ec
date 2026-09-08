<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ReviewController;

require __DIR__ . '/settings.php';

Route::get(
    '/chirps',
    [ChirpController::class, 'index']
)->name('chirps.index');

Route::post(
    '/chirps',
    [ChirpController::class, 'store']
)->name('chirps.store');

Route::get('/', [ProductController::class, 'index'])->name('home');

Route::get(
    '/products/{product}',
    [ProductController::class, 'show']
)->name('products.show');

Route::post(
    '/cart',
    [CartController::class, 'store']
)->name('cart.store');

Route::get(
    '/cart',
    [CartController::class, 'index']
)->name('cart.index');

Route::get(
    '/cart/clear',
    [CartController::class, 'clear']
)->name('cart.clear');

Route::delete(
    '/cart/{cart}',
    [CartController::class, 'destroy']
)->name('cart.destroy');

Route::get(
    '/search',
    [ProductController::class, 'search']
)->name('products.search');

Route::get(
    '/categories/{category}',
    [ProductController::class, 'category']
)->name('categories.show');

Route::get(
    '/news/{news}', 
    [NewsController::class, 'show']
)->name('news.show');

Route::middleware(['auth'])->group(function () {
    Route::post(
        '/orders',
        [OrderController::class, 'store']
    )->name('orders.store');

    Route::get(
        '/orders',
        [OrderController::class, 'index']
    )->name('orders.index');

    Route::get(
        '/orders/{order}',
        [OrderController::class, 'show']
    )->name('orders.show');

    Route::get(
        '/mypage',
        [MyPageController::class, 'index']
    )->name('mypage');

    Route::post(
        '/products/{product}/reviews',
        [ReviewController::class, 'store']
    )->name('reviews.store');
});