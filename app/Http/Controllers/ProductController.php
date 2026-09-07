<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Category;
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

    // 💡 關鍵修正：把 show 放進 class 的大括號裡面！
    public function show($id)
    {
        // 1. 去資料庫把點擊的那筆商品資料撈出來
        $product = Product::findOrFail($id);

        // 2. 把商品資料傳給 products 資料夾底下的 show.blade.php 畫面
        return view('products.show', [
            'product' => $product,
        ]);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $products = Product::where('name', 'like', "%{$keyword}%")->get();

        $news = News::orderBy('id', 'desc')
            ->limit(3)
            ->get();

        return view('index', [
            'products' => $products,
            'news' => $news,
        ]);
    }

    public function category(Category $category)
    {
        return view('category', [
            'category' => $category,
        ]);
    }
}
