<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\RestockRequest;
use App\Models\Coupon;
use App\Models\Review;

class ProductFeatureController extends Controller
{
    // 1. 注文キャンセル処理
    public function cancelOrder(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return back()->with('error', '権限がありません。');
        }

        // 発送前（ordered または shipping_preparing）のみキャンセル可能
        if (in_array($order->status, ['ordered', 'shipping_preparing'])) {
            $order->status = 'cancelled';
            $order->save();
            return back()->with('message', 'ご注文をキャンセルしました。');
        }

        return back()->with('error', '発送準備完了後または発送済みの注文はキャンセルできません。');
    }

    // 2. 再入荷リクエスト登録
    public function requestRestock(Request $request, Product $product)
    {
        RestockRequest::updateOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $product->id],
            ['is_notified' => false]
        );

        return back()->with('message', '再入荷通知リクエストを受け付けました。');
    }

    // 3. クーポン適用処理（カート・決済時）
    public function applyCoupon(Request $request)
    {
        $code = $request->input('coupon_code');
        $coupon = Coupon::where('code', $code)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })->first();

        if (!$coupon) {
            return back()->with('error', '無効または期限切れのクーポンコードです。');
        }

        session()->put('coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount_amount' => $coupon->discount_amount,
            'discount_rate' => $coupon->discount_rate,
        ]);

        return back()->with('message', 'クーポンを適用しました。');
    }

    // 4. レビュー投稿処理（ポイント100pt進呈機能付き）
    public function storeReview(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // レビュー特典としてユーザーに100ポイント進呈
        $user = auth()->user();
        $user->points += 100;
        $user->save();

        return back()->with('message', 'レビューを投稿しました！100ポイントを獲得しました🎁');
    }
}