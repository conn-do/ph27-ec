<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // 一覧表示
    public function index()
    {
        $favorites = Auth::user()->favorites()->paginate(10);
        return view('favorites.index', compact('favorites'));
    }

    // 追加
    public function store(Product $product)
    {
        Auth::user()->favorites()->syncWithoutDetaching([$product->id]);
        return back();
    }

    // 削除
    public function destroy(Product $product)
    {
        Auth::user()->favorites()->detach($product->id);
        return back();
    }
}
