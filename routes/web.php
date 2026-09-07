<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

// Route::inertia('/', 'welcome', [
//     'canRegister' => Features::enabled(Features::registration()),
// ])->name('home');

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::inertia('dashboard', 'dashboard')->name('dashboard');
// });

require __DIR__.'/settings.php';

Route::get(
    '/chirps',
    [ChirpController::class, 'index']
);
Route::post(
    '/chirps',
    [ChirpController::class, 'store']
);

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
Route::delete(
    '/cart/clear',
    [CartController::class, 'clear']
)->name('cart.clear');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->whereNumber('product')->name('cart.destroy');
Route::get(
    '/search',
    [ProductController::class, 'search']
)->name('products.search');
Route::get(
    '/categories/{category}',
    [ProductController::class, 'category']
)->name('categories.show');

Route::get('/news/{news}', [NewsController::class, 'show']);

// ログイン必須にする
Route::middleware(['auth'])->group(function () {
    Route::post(
        '/orders',
        [OrderController::class, 'store']
    )->name('orders.store');
    Route::get(
        '/orders',
        [OrderController::class, 'index']
    );
    Route::get(
        '/orders/{order}',
        [OrderController::class, 'show']
    );
    Route::get(
        '/mypage',
        [MyPageController::class, 'index']
    );
});
