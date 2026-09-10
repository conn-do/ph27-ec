<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $request->user()->favoriteProducts()->syncWithoutDetaching([$product->id]);

        return back()->with('message', 'わたしの文具棚に保存しました。');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $request->user()->favoriteProducts()->detach($product->id);

        return back()->with('message', '文具棚から取り外しました。');
    }
}
