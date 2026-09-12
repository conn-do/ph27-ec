<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PointHistory;

class MyPageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // 投稿レビュー（商品情報付き）の取得
        $reviews = method_exists($user, 'reviews') 
            ? $user->reviews()->with('product')->latest()->get() 
            : collect();

        // お気に入り商品の取得
        $favoriteProducts = method_exists($user, 'favoriteProducts') 
            ? $user->favoriteProducts 
            : collect();

        // 購入履歴の取得
        $orders = $user->orders()->with('details.product')->latest()->get();

        // 🎁 ポイント履歴の取得
        $pointHistories = PointHistory::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('mypage', compact('user', 'reviews', 'favoriteProducts', 'orders', 'pointHistories'));
    }
}