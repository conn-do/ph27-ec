<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        return view('wishlist', [
            'products' => Product::query()->whereKey($request->session()->get('wishlist', []))
                ->latest('id')->paginate(12),
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $wishlist = $request->session()->get('wishlist', []);
        $wishlist[] = $product->id;
        $request->session()->put('wishlist', array_values(array_unique($wishlist)));

        return back()->with('message', 'お気に入りに追加しました。');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $request->session()->put('wishlist', array_values(array_diff(
            $request->session()->get('wishlist', []), [$product->id],
        )));

        return back()->with('message', 'お気に入りから削除しました。');
    }
}
