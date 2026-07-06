<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\News; // 追加：News

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        
        // 追加：最新のNewsを3件取得する
        $news = News::orderBy('id', 'desc')->limit(3)->get();
        
        return view('index', [
            'products' => $products,
            'news' => $news, // 追加：viewにnewsデータを渡す
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

        $products = Product::where('name', 'like', "%{$keyword}%")->get();

        $news = News::orderBy('id', 'desc')->limit(3)->get();

        return view('index', [
            'products' => $products,
            'news' => $news,
        ]);
    }
}