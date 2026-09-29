<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        $news = News::orderBy('id', 'desc')
            ->limit(3)
            ->get();

        $categories = Category::all();

        // キャッシュから商品IDを取得
        $rankingIds = Cache::get('ranking_products', []);
        // 商品IDから商品を取得
        $rankingProducts = Product::whereIn('id', $rankingIds)->get();


        return view('index', [
            'products' => $products,
            'news' => $news,
            'categories' => $categories,
            'rankingProducts' => $rankingProducts,
        ]);
    }

    public function show(Product $product)
    {
        $product->load(['favorites', 'reviews.user']);

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
            'categories' => Category::all(),
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
