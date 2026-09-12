<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProductFeatureController;

// --------------------------------------------------
// 1. 公開ページ（未ログインでも閲覧可）
// --------------------------------------------------
Route::get('/', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search', [ProductController::class, 'search']);
Route::get('/categories/{category}', [ProductController::class, 'category']);

// カート関連
Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart', [CartController::class, 'store']);
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// ニュース
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news}', [NewsController::class, 'show']);

// つぶやき（Chirps）
Route::get('/chirps', [ChirpController::class, 'index']);
Route::post('/chirps', [ChirpController::class, 'store']);


// --------------------------------------------------
// 2. ログイン必須の機能
// --------------------------------------------------
Route::middleware(['auth'])->group(function () {
    // マイページ
    Route::get('/mypage', [MyPageController::class, 'index']);

    // 注文・チェックアウト関連
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/complete', [OrderController::class, 'complete'])->name('orders.complete');
    
    // 注文一覧・詳細・配送追跡
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/tracking', [OrderController::class, 'tracking'])->name('orders.tracking');
    
    // 注文キャンセル
    Route::post('/orders/{order}/cancel', [ProductFeatureController::class, 'cancelOrder'])->name('orders.cancel');

    // レビュー
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/products/{product}/reviews', [ProductFeatureController::class, 'storeReview'])->name('reviews.store_with_point');

    // お気に入り
    Route::post('/products/{product}/favorite', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/products/{product}/favorite', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    // 再入荷リクエスト & クーポン
    Route::post('/products/{product}/restock', [ProductFeatureController::class, 'requestRestock'])->name('products.restock');
    Route::post('/cart/coupon', [ProductFeatureController::class, 'applyCoupon'])->name('cart.coupon');

    // 管理者機能
    Route::get('/admin/orders', [OrderController::class, 'adminIndex'])->name('admin.orders.index');
    Route::patch('/admin/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::get('/admin/sales', [ProductController::class, 'adminIndex'])->name('admin.products.index');
    Route::patch('/admin/products/{product}/sale', [ProductController::class, 'updateSale'])->name('admin.products.updateSale');
});

// 管理者トップへのリダイレクト（1つに集約）
Route::get('/admin', function () {
    return redirect('/admin/orders');
});

require __DIR__ . '/settings.php';

Route::delete('/admin/orders/{order}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');