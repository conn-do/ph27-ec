<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(Request $request): View
    {
        $products = $request->user()
            ->favoriteProducts()
            ->with('category')
            ->orderByPivot('created_at', 'desc')
            ->paginate(9);

        $products->getCollection()->each->setAttribute('is_favorited', true);

        return view('favorites.index', ['products' => $products]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $request->user()->favoriteProducts()->syncWithoutDetaching([$product->id]);

        return back()->with('message', 'お気に入りに追加しました。');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $request->user()->favoriteProducts()->detach($product->id);

        return back()->with('message', 'お気に入りから外しました。');
    }
}
