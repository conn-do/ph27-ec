<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\News;
use App\Models\Category;

class ProductController extends Controller
{
    // トップページ・商品一覧（カテゴリー絞り込み対応）
    // ProductController.php

    public function index(Request $request)
    {
        $query = Product::query();

        // 📁 カテゴリーIDが指定されている場合
        if ($request->has('category_id') && $request->category_id) {
            $categoryId = $request->category_id;
            $category = Category::find($categoryId);

            if ($category) {
                // 親カテゴリーの場合（子カテゴリーのID一覧を取得してまとめて絞り込み）
                if ($category->children && $category->children->count() > 0) {
                    $subCategoryIds = $category->children->pluck('id')->toArray();
                    // 親自身のIDと子カテゴリーのIDのどちらかに該当する商品を取得
                    $query->whereIn('category_id', array_merge([$categoryId], $subCategoryIds));
                } else {
                    // 子カテゴリーの場合
                    $query->where('category_id', $categoryId);
                }
            }
        }

        $products = $query->latest()->get();

        // ニュース一覧（トップ用）
        $news = News::orderBy('id', 'desc')
            ->limit(3)
            ->get();

        // 最新のニュース1件
        $latestNews = News::latest()->first();

        // 親カテゴリーとその配下の子カテゴリーを取得
        $categories = Category::whereNull('parent_id')->with('children')->get();

        $rankingProducts = Product::inRandomOrder()->take(4)->get();

        return view('index', [
            'products'        => $products,
            'news'            => $news,
            'latestNews'      => $latestNews,
            'categories'      => $categories,
            'rankingProducts' => $rankingProducts,
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

        // 商品名であいまい検索（キーワードが空の場合は全件取得）
        $products = Product::where('name', 'like', "%{$keyword}%")->get();

        $news = News::orderBy('id', 'desc')
            ->limit(3)
            ->get();

        // indexビューで必要なカテゴリ一覧も一緒に渡す
        $categories = Category::all();

        return view('index', [
            'products'   => $products,
            'news'       => $news,
            'categories' => $categories,
            'keyword'    => $keyword,
        ]);
    }

    public function category(Category $category)
    {
        return view('category', [
            'category' => $category,
        ]);
    }

    public function adminIndex()
    {
        $products = Product::all();
        return view('admin.sales.index', compact('products'));
    }

    // セール設定の更新処理（%OFFから自動計算）
    public function updateSale(Request $request, Product $product)
    {
        $request->validate([
            'discount_percent' => 'nullable|integer|min:0|max:99',
        ]);

        $discountPercent = $request->input('discount_percent');
        $salePrice = null;

        // 割引率（%）が入力されている場合、セール価格を計算（端数切り捨て）
        if (!is_null($discountPercent) && $discountPercent > 0) {
            $salePrice = floor($product->price * (1 - ($discountPercent / 100)));
        }

        $product->update([
            'sale_price' => $salePrice,
            'is_sale'    => $request->has('is_sale'),
        ]);

        return back()->with('message', "「{$product->name}」のセール情報を更新しました。");
    }
}