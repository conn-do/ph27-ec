<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request): View
    {
        $products = $request->user()
            ->favoriteProducts()
            ->published()
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('favorites.created_at')
            ->paginate(12);

        return view('favorites.index', [
            'products' => $products,
        ]);
    }

    /**
     * お気に入りに入れる / はずす（おなじボタンで切りかえ）。
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        $favorites = $request->user()->favorites();
        $existing = $favorites->where('product_id', $product->id)->first();

        if ($existing !== null) {
            $existing->delete();

            return back()->with('message', "「{$product->name}」を おきにいりから はずしたよ。");
        }

        $favorites->create(['product_id' => $product->id]);

        return back()->with('message', "「{$product->name}」を おきにいりに いれたよ！");
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $request->user()->favorites()->where('product_id', $product->id)->delete();

        return back()->with('message', "「{$product->name}」を おきにいりから はずしたよ。");
    }
}
