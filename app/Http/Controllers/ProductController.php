<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('index', [
            'products' => $products,
        ]);
    }

    // 💡 關鍵修正：把 show 放進 class 的大括號裡面！
    public function show($id)
    {
        // 1. 去資料庫把點擊的那筆商品資料撈出來
        $product = \App\Models\Product::findOrFail($id);

        // 2. 把商品資料傳給 products 資料夾底下的 show.blade.php 畫面
        return view('products.show', [
            'product' => $product
        ]);
    }
} // <-- 這一個才是整棟大房子結束的最外層大括號
