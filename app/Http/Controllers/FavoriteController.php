<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Product;

class FavoriteController extends Controller
{
    // お気に入りに追加
    public function store(Request $request, Product $product)
    {
        $user = $request->user();

        // すでにお気に入り登録されているか確認
        $favorite = Favorite::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        // まだ登録されていなければ追加
        if (!$favorite) {
            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
        }

        return back()->with(
            'message',
            'お気に入りに追加しました！'
        );
    }

    // お気に入りから削除
    public function destroy(Request $request, Product $product)
    {
        $user = $request->user();

        Favorite::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->delete();

        return back()->with(
            'message',
            'お気に入りから削除しました！'
        );
    }

    // お気に入り一覧
    public function index(Request $request)
    {
        $favorites = Favorite::where(
            'user_id',
            $request->user()->id
        )
        ->with('product')
        ->latest()
        ->get();

        return view('favorites.index', [
            'favorites' => $favorites,
        ]);
    }
}