<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PostalCodeController;
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
Route::view('/guide', 'guide')->name('guide');
Route::view('/contact', 'contact')->name('contact');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
Route::get(
    '/search',
    [ProductController::class, 'search']
);
Route::get('/categories/{category}', [ProductController::class, 'category'])->name('categories.show');

Route::get('/news/{news}', [NewsController::class, 'show']);

// ログイン必須にする
Route::middleware(['auth'])->group(function () {
    Route::get('/postal-code/{postalCode}', PostalCodeController::class)
        ->where('postalCode', '[0-9-]+')
        ->name('postal-code.show');
    Route::get('/checkout', [OrderController::class, 'create'])->name('checkout.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage');
    Route::patch('/mypage/delivery-address', [MyPageController::class, 'updateDeliveryAddress'])
        ->name('mypage.delivery-address.update');
});
