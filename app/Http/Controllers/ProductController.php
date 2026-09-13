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
        $products = Product::with('category')->get();
        $categories = Category::all();
        $news = News::orderBy('id', 'desc')
            ->limit(3)
            ->get();

        return view('index', [
            'products' => $products,
            'categories' => $categories,
            'news' => $news,
        ]);
    }

    public function show(Product $product)
    {
        $product->load('category');

        return view('products.show', [
            'product' => $product,
        ]);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $products = Product::with('category')
            ->where('name', 'like', "%{$keyword}%")
            ->get();
        $categories = Category::all();
        $news = News::orderBy('id', 'desc')
            ->limit(3)
            ->get();

        return view('index', [
            'products' => $products,
            'categories' => $categories,
            'news' => $news,
        ]);
    }

    public function category(Category $category)
    {
        $category->load('products');

        return view('category', [
            'category' => $category,
        ]);
    }
}
