<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        $news = News::orderBy('id', 'desc')
            ->limit(3)
            ->get();

        $categories = Category::all();

        return view('index', [
            'products' => $products,
            'news' => $news,
            'categories' => $categories,
        ]);
    }

    public function show(Product $product)
    {
        $reviews = $product->reviews()->with('user')->latest()->get();

        return view('products.show', [
            'product' => $product,
            'reviews' => $reviews,
            'averageRating' => round($reviews->avg('rating'), 1),
            'myReview' => auth()->check()
                ? $reviews->firstWhere('user_id', auth()->id())
                : null,
            'isFavorited' => auth()->check()
                ? $product->favorites()->where('user_id', auth()->id())->exists()
                : false,
        ]);
    }

    public function search(Request $request)
    {
        $query = Product::query();

        if ($keyword = $request->input('keyword')) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (int) $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (int) $request->input('max_price'));
        }

        if ($request->boolean('in_stock_only')) {
            $query->where('stock', '>', 0);
        }

        $products = $query->get();

        $news = News::orderBy('id', 'desc')
            ->limit(3)
            ->get();

        $categories = Category::all();

        return view('index', [
            'products' => $products,
            'news' => $news,
            'categories' => $categories,
        ]);
    }

    public function category(Category $category)
    {
        return view('category', [
            'category' => $category,
        ]);
    }
}
