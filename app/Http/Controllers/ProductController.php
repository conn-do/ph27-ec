<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\News;
use App\Models\Category;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        // 売れ筋ランキングの取得（注文詳細から商品IDごとに数量を合計して降順に並び替え）
        $rankingProductIds = OrderDetail::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(3)
            ->pluck('product_id');

        // 取得したIDの順番を維持して商品データを取得
        $rankingProducts = Product::whereIn('id', $rankingProductIds)
            ->orderByRaw('FIELD(id, ' . implode(',', $rankingProductIds->toArray() ?: [0]) . ')')
            ->get();

        // もし注文データがまだなくてランキングが空の場合のフォールバック
        if ($rankingProducts->isEmpty()) {
            $rankingProducts = Product::latest()->take(3)->get();
        }

        // 新着商品の取得
        $products = Product::latest()->take(6)->get();

        // ニュース情報の取得
        $news = News::orderBy('id', 'desc')
            ->limit(3)
            ->get();

        // カテゴリ一覧の取得
        $categories = Category::all();

        // すべてのデータをまとめてビューに渡す
        return view('index', [
            'rankingProducts' => $rankingProducts,
            'products' => $products,
            'news' => $news,
            'categories' => $categories,
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

    public function category(Category $category)
    {
        return view('category', [
            'category' => $category,
        ]);
    }
}