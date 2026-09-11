<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

// Route::inertia('/', 'welcome', [
//     'canRegister' => Features::enabled(Features::registration()),
// ])->name('home');

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::inertia('dashboard', 'dashboard')->name('dashboard');
// });

require __DIR__.'/settings.php';

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
// {productId}のワイルドカードルートより前に置き、/cart/couponがそちらに吸われないようにする
Route::post(
    '/cart/coupon',
    [CouponController::class, 'store']
);
Route::get(
    '/cart/coupon/remove',
    [CouponController::class, 'destroy']
);
Route::post(
    '/cart/{productId}',
    [CartController::class, 'update']
);
Route::get(
    '/cart/{productId}/remove',
    [CartController::class, 'destroy']
);
Route::get(
    '/search',
    [ProductController::class, 'search']
);
Route::get(
    '/categories/{category}',
    [ProductController::class, 'category']
);

Route::get('/news/{news}', [NewsController::class, 'show']);

// ログイン必須にする
Route::middleware(['auth'])->group(function () {
    Route::get(
        '/orders/create',
        [OrderController::class, 'create']
    );

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
    Route::post(
        '/orders/{order}/cancel',
        [OrderController::class, 'cancel']
    );
    Route::get(
        '/mypage',
        [MyPageController::class, 'index']
    );

    Route::get(
        '/checkout/success',
        [PaymentController::class, 'success']
    )->name('checkout.success');
    Route::get(
        '/checkout/cancel',
        [PaymentController::class, 'cancel']
    )->name('checkout.cancel');

    Route::post(
        '/products/{product}/reviews',
        [ReviewController::class, 'store']
    );
    Route::post(
        '/reviews/{review}/delete',
        [ReviewController::class, 'destroy']
    );

    Route::get(
        '/favorites',
        [FavoriteController::class, 'index']
    );
    Route::post(
        '/products/{product}/favorite',
        [FavoriteController::class, 'store']
    );
    Route::post(
        '/products/{product}/unfavorite',
        [FavoriteController::class, 'destroy']
    );
});

// Stripeサーバーから直接POSTされるため認証・CSRF検証の対象外
Route::post(
    '/stripe/webhook',
    [PaymentController::class, 'webhook']
)->name('stripe.webhook');
