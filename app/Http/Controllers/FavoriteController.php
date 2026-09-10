<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        $favorite = Favorite::where('user_id', $request->user()->id)
            ->where('product_id', $request->productId)
            ->first();

        if ($favorite) {
            $favorite->delete();
        } else {
            $favorite = new Favorite();
            $favorite->user_id = $request->user()->id;
            $favorite->product_id = $request->productId;
            $favorite->save();
        }

        return redirect()->back();
    }

    public function index(Request $request)
    {
        $favorites = $request->user()->favorites;

        return view('favorites', [
            'favorites' => $favorites,
        ]);
    }
}