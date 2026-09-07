<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller
{
    public function index()
    {
        // Query products ordered by sales
        $rankedProducts = Product::withCount(['orderDetails as total_sold' => function ($query) {
            $query->select(DB::raw('SUM(quantity)'));
        }])
        ->orderByDesc('total_sold')
        ->take(10)
        ->get();

        // Fallback: If no orders exist yet, show all current products as a default ranking
        if ($rankedProducts->isEmpty() || $rankedProducts->first()->total_sold == 0) {
            $rankedProducts = Product::inRandomOrder()->take(10)->get();
        }

        return view('ranking', compact('rankedProducts'));
    }
}