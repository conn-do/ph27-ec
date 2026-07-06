<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::all();
        $news = News::orderBy('id', 'desc')->limit(3)->get();

        return view('index', [
            'products' => $products,
            'news' => $news,
        ]);
    }

    public function show(Product $product): View
    {
        return view('products.show', [
            'product' => $product,
        ]);
    }

    public function search(Request $request): View
    {
        $keyword = $request->input('keyword');
        $products = Product::where('name', 'like', "%{$keyword}%")->get();
        $news = News::orderBy('id', 'desc')->limit(3)->get();

        return view('index', [
            'products' => $products,
            'news' => $news,
        ]);
    }
}
