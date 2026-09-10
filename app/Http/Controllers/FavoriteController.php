<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $request->user()->favorites()->firstOrCreate(['product_id' => $product->id]);

        return back()->with('message', 'お気に入りに追加しました。');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $request->user()->favorites()->whereBelongsTo($product)->delete();

        return back()->with('message', 'お気に入りから削除しました。');
    }
}
