<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\News;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->input('sort');

        $query = Product::query();

        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'newest') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $products = $query->get();

        $news = News::orderBy('id', 'desc')
            ->limit(3)
            ->get();

        $categories = Category::all();

        return view('index', [
            'products' => $products,
            'news' => $news,
            'categories' => $categories,
            'sort' => $sort,
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
        $sort = $request->input('sort');

        $query = Product::where('name', 'like', "%{$keyword}%");

        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'newest') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $products = $query->get();

        $news = News::orderBy('id', 'desc')
            ->limit(3)
            ->get();

        // ▼ 検索結果画面（index）でもカテゴリ一覧を使えるように追加
        $categories = Category::all();

        return view('index', [
            'products' => $products,
            'news' => $news,
            'categories' => $categories,
            'sort' => $sort,
        ]);
    }

    public function category(Category $category)
    {
        // もし category.blade.php 側でもサイドバー等で全カテゴリ一覧が必要ならここで取得できます
        $categories = Category::all();

        return view('category', [
            'category' => $category,
            'categories' => $categories,
        ]);
    }
}