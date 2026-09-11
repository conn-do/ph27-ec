<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = $request->user()->favorites()->with('product')->latest()->get();

        return view('favorites.index', [
            'favorites' => $favorites,
        ]);
    }

    public function store(Request $request, Product $product)
    {
        Favorite::firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
        ]);

        return back()->with('message', 'お気に入りに追加しました。');
    }

    public function destroy(Request $request, Product $product)
    {
        Favorite::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->delete();

        return back()->with('message', 'お気に入りから削除しました。');
    }
}
