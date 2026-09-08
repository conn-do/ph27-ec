<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;

class FavoriteController extends Controller
{
    public function store(Product $product)
    {
        Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        return back();
    }

    public function destroy(Product $product)
    {
        Favorite::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->delete();

        return back();
    }

    public function index()
    {
        $favorites = Favorite::where('user_id', auth()->id())
            ->with('product')
            ->get();

        return view('favorites', [
            'favorites' => $favorites,
        ]);
    }
}
