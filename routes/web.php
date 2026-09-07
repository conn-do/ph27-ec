<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/settings.php';

Route::get('/chirps', [ChirpController::class, 'index']);
Route::post('/chirps', [ChirpController::class, 'store']);

<<<<<<< Updated upstream
Route::get('/', [ProductController::class, 'index']);
Route::get(
    '/products/{product}',
    [ProductController::class, 'show']
);
Route::post(
    '/cart',
    [CartController::class, 'store']
);
Route::get(
    '/cart',
    [CartController::class, 'index']
);
Route::get(
    '/cart/clear',
    [CartController::class, 'clear']
);
Route::get(
    '/search',
    [ProductController::class, 'search']
);
Route::get(
    '/categories/{category}',
    [ProductController::class, 'category']
);
=======
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
>>>>>>> Stashed changes

Route::get('/news/{news}', [NewsController::class, 'show']);

<<<<<<< Updated upstream
// ログイン必須にする
Route::middleware(['auth'])->group(function () {
    Route::post(
        '/orders',
        [OrderController::class, 'store']
    );
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
=======
Route::middleware('auth')->group(function (): void {
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/mypage', [MyPageController::class, 'index'])->name('dashboard');
>>>>>>> Stashed changes
});
