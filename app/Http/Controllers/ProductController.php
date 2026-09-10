<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')->latest()->get();
        $rankingProducts = Product::with('category')
            ->withSum('orderDetails as sold_quantity', 'quantity')
            ->orderByDesc('sold_quantity')
            ->orderBy('id')
            ->limit(5)
            ->get();

        $news = News::orderBy('id', 'desc')
            ->limit(3)
            ->get();

        $categories = Category::all();

        return view('index', [
            'products' => $products,
            'news' => $news,
            'categories' => $categories,
            'rankingProducts' => $rankingProducts,
        ]);
    }

    public function show(Request $request, Product $product): View
    {
        $product->load(['category', 'reviews' => fn ($query) => $query->with('user')->latest()]);

        return view('products.show', [
            'product' => $product,
            'isFavorite' => $request->user()?->favorites()->whereBelongsTo($product)->exists() ?? false,
            'userReview' => $request->user()?->reviews()->whereBelongsTo($product)->first(),
        ]);
    }

    public function search(Request $request): View
    {
        $keyword = $request->input('keyword');

        $products = Product::with('category')
            ->where('name', 'like', "%{$keyword}%")
            ->latest()
            ->get();

        $news = News::orderBy('id', 'desc')
            ->limit(3)
            ->get();

        $categories = Category::all();

        return view('index', [
            'products' => $products,
            'news' => $news,
            'categories' => $categories,
            'rankingProducts' => collect(),
        ]);
    }

    public function category(Category $category): View
    {
        $category->load(['products' => fn ($query) => $query->latest()]);

        return view('category', [
            'category' => $category,
        ]);
    }
}
