<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\News;
use App\Models\Category;

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
        return view('products.show', [
            'product' => $product,
        ]);
    }

public function search(Request $request)
{
    $keyword = $request->input('keyword');

    $products = Product::where('name', 'like', "%{$keyword}%")
        ->orWhere('description', 'like', "%{$keyword}%")
        ->latest()
        ->get();

    $news = News::orderBy('id', 'desc')
        ->limit(3)
        ->get();

    // THIS WAS MISSING: Fetch categories so index.blade.php doesn't crash on Line 10
    $categories = Category::all();

    return view('index', [
        'products'   => $products,
        'news'       => $news,
        'categories' => $categories,
        'keyword'    => $keyword,
    ]);
}

    public function category(Category $category)
    {
        return view('category', [
            'category' => $category,
        ]);
    }
}
