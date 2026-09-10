<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\News;
use App\Models\Category;

class ProductController extends Controller
{
    // 商品一覧
    public function index()
    {
        $products = Product::all();

        // 最新ニュース3件
        $news = News::orderBy('id', 'desc')
            ->limit(3)
            ->get();

        // カテゴリ一覧
        $categories = Category::all();

        // 売上数が多い順にランキング
        $ranking = Product::orderBy('sales_count', 'desc')
            ->limit(3)
            ->get();

        return view('index', [
            'products' => $products,
            'news' => $news,
            'categories' => $categories,
            'ranking' => $ranking,
        ]);
    }

    // 商品詳細
    public function show(Product $product)
    {
        return view('products.show', [
            'product' => $product,
        ]);
    }

    // 商品検索
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $products = Product::where(
            'name',
            'like',
            "%{$keyword}%"
        )->get();

        // 最新ニュース3件
        $news = News::orderBy('id', 'desc')
            ->limit(3)
            ->get();

        // カテゴリ一覧
        $categories = Category::all();

        // 売上数が多い順にランキング
        $ranking = Product::orderBy('sales_count', 'desc')
            ->limit(3)
            ->get();

        return view('index', [
            'products' => $products,
            'news' => $news,
            'categories' => $categories,
            'ranking' => $ranking,
        ]);
    }

    // カテゴリ
    public function category(Category $category)
    {
        return view('category', [
            'category' => $category,
        ]);
    }
}