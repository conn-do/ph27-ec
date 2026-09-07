<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MyPageController extends Controller
{
    public function index(Request $request): View
    {
        $favorites = $request->user()
            ->favoriteProducts()
            ->with('category')
            ->orderByPivot('created_at', 'desc')
            ->limit(4)
            ->get();
        $favorites->each->setAttribute('is_favorited', true);

        return view('mypage', [
            'favorites' => $favorites,
            'favoritesCount' => $request->user()->favoriteProducts()->count(),
            'ordersCount' => $request->user()->orders()->count(),
        ]);
    }
}
