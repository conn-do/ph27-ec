<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    // お気に入り登録処理
    public function store(Product $product)
    {
        $user = auth()->user();

        // 未登録の場合のみ登録
        if (!$product->isFavoritedBy($user)) {
            Favorite::create([
                'user_id'    => $user->id,
                'product_id' => $product->id,
            ]);
        }

        return back()->with('message', 'お気に入りに追加しました！');
    }

    // お気に入り解除処理
    public function destroy(Product $product)
    {
        $user = auth()->user();

        // 登録されている場合は削除
        Favorite::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->delete();

        return back()->with('message', 'お気に入りを解除しました。');
    }
}