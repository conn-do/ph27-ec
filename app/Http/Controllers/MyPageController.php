<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyPageController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 投稿レビュー（商品情報付き）の取得
        $reviews = $user->reviews()->with('product')->latest()->get();

        // お気に入り商品の取得
        $favoriteProducts = $user->favoriteProducts()->get();

        return view('mypage', compact('user', 'reviews', 'favoriteProducts'));
    }
}