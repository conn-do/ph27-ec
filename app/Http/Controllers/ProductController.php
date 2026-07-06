<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\News; // 追加

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $news = News::orderBy('id', 'desc')->limit(3)->get(); // 追加

        return view('index', [
            'products' => $products,
            'news' => $news, // 追加
        ]);
    }

    public function show(Product $product)
    {
        return view('products.show', [
            'product' => $product,
        ]);
    }
}