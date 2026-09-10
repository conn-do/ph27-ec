<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\NewsController;

require __DIR__ . '/settings.php';


// ========================================
// Chirp
// ========================================

Route::get(
    '/chirps',
    [ChirpController::class, 'index']
);

Route::post(
    '/chirps',
    [ChirpController::class, 'store']
);


// ========================================
// 商品
// ========================================

// 商品一覧
Route::get(
    '/',
    [ProductController::class, 'index']
)->name('home');

// Dashboard
// Laravel標準の認証テスト用。
// ECサイトの商品一覧を表示する。
Route::get(
    '/dashboard',
    [ProductController::class, 'index']
)->middleware(['auth', 'verified'])
  ->name('dashboard');

// 商品詳細
Route::get(
    '/products/{product}',
    [ProductController::class, 'show']
);

// 商品検索
Route::get(
    '/search',
    [ProductController::class, 'search']
);

// カテゴリ別商品
Route::get(
    '/categories/{category}',
    [ProductController::class, 'category']
);


// ========================================
// カート
// ========================================

// カートに商品を追加
Route::post(
    '/cart',
    [CartController::class, 'store']
);

// カートを表示
Route::get(
    '/cart',
    [CartController::class, 'index']
);

// カート内の商品数量を変更
Route::patch(
    '/cart/item/{productId}',
    [CartController::class, 'update']
)->name('cart.update');

// カートから特定の商品を削除
Route::delete(
    '/cart/item/{productId}',
    [CartController::class, 'destroy']
)->name('cart.destroy');

// カートを空にする
Route::get(
    '/cart/clear',
    [CartController::class, 'clear']
);


// ========================================
// ニュース
// ========================================

Route::get(
    '/news/{news}',
    [NewsController::class, 'show']
);


// ========================================
// ログインが必要なページ
// ========================================

Route::middleware(['auth'])->group(function () {

    // 注文
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


    // ----------------------------------------
    // マイページ
    // ----------------------------------------

    Route::get(
        '/mypage',
        [MyPageController::class, 'index']
    );

    Route::get(
        '/mypage/edit',
        [MyPageController::class, 'edit']
    );

    Route::put(
        '/mypage',
        [MyPageController::class, 'update']
    );
});