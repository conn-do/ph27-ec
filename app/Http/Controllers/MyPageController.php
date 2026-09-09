<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyPageController extends Controller
{
    public function index(Request $request)
    {
        $favoriteProducts = $request->user()
            ->favorites()
            ->with('product')
            ->latest()
            ->get()
            ->map(fn ($favorite) => $favorite->product)
            ->filter();

        return view('mypage', [
            'favoriteProducts' => $favoriteProducts,
        ]);
    }
}
