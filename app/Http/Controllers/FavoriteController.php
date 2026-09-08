<?php

namespace App\Http\Controllers;

use App\Models\Product;

class FavoriteController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        // Fetch fresh favorites directly from the query builder
        $favorites = $user->favorites()->latest()->get();

        return view('favorites', compact('favorites'));
    }

    public function toggle(Product $product)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Toggle favorite status using the pivot table
        $user->favorites()->toggle($product->id);

        return back();
    }
}