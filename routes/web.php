<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/settings.php';

Route::get('/chirps', [ChirpController::class, 'index'])->name('chirps.index');
Route::post('/chirps', [ChirpController::class, 'store'])->name('chirps.store');

/*
|--------------------------------------------------------------------------
| おみせ（だれでも見られる）
|--------------------------------------------------------------------------
*/

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');

/*
|--------------------------------------------------------------------------
| カート（ログインしなくても つかえる）
|--------------------------------------------------------------------------
*/

Route::controller(CartController::class)->prefix('cart')->name('cart.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::patch('/{product}', 'update')->name('update');
    Route::delete('/{product}', 'destroy')->name('destroy');
    Route::delete('/', 'clear')->name('clear');
});

/*
|--------------------------------------------------------------------------
| ログインが ひつような ところ
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');

    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{product}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{product}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/products/{product}/reviews', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage');
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
});
