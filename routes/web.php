<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\RankingController;

require __DIR__ . '/settings.php';

// Public Routes
Route::get('/chirps', [ChirpController::class, 'index']);
Route::post('/chirps', [ChirpController::class, 'store']);

Route::get('/', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/search', [ProductController::class, 'search']);
Route::get('/categories/{category}', [ProductController::class, 'category']);
Route::get('/news/{news}', [NewsController::class, 'show']);

// Rankings
Route::get('/ranking', [RankingController::class, 'index'])->name('ranking');

// Cart
Route::post('/cart', [CartController::class, 'store']);
Route::get('/cart', [CartController::class, 'index']);
Route::get('/cart/clear', [CartController::class, 'clear']);

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    // MyPage & Profile
    Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage');
    Route::get('/mypage/profile/edit', [MyPageController::class, 'editProfile'])->name('profile.edit_form');
    Route::put('/mypage/profile', [MyPageController::class, 'updateProfile'])->name('profile.update_data');

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.list');
    Route::post('/products/{product}/favorite', [FavoriteController::class, 'toggle'])->name('favorites.toggle_item');
});