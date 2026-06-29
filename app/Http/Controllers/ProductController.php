<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\News;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $categories = Category::all();
        $news = News::orderBy('id', 'desc')->limit(3)->get();
        return view('index', [
            'products' => $products,
            'categories' => $categories,
            'news' => $news,
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
        $products = Product::where('name', 'like', "%$keyword%")->get();
        return view('index', [
            'products' => $products,
        ]);
    }

    public function category(Category $category)
    {
        $products = Product::where('category_id', $category->id)->orderBy('id', 'desc')->get();
        return view('category', [
            'category' => $category,
            'products' => $products,
        ]);
    }
}
