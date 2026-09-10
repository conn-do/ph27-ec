<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'productId' => 'required|integer',
        ]);

        // すでにお気に入りに入っていないか確認
        $exists = Favorite::where('user_id', $request->user()->id)
            ->where('product_id', $validated['productId'])
            ->exists();

        if (! $exists) {
            $favorite = new Favorite();
            $favorite->user_id = $request->user()->id;
            $favorite->product_id = $validated['productId'];
            $favorite->save();
        }

        return redirect()->back()->with('message', 'お気に入りに追加しました。');
    }

    public function index(Request $request)
    {
        return view('favorites.index', [
            'favorites' => $request->user()->favorites,
        ]);
    }

    public function remove(Request $request, $productId)
    {
        Favorite::where('user_id', $request->user()->id)
            ->where('product_id', $productId)
            ->delete();

        return redirect('/favorites')->with('message', 'お気に入りから削除しました。');
    }
}