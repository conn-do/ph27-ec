<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\News;
use App\Models\Category;

class ProductController extends Controller
{
    // トップページ・商品一覧（カテゴリー絞り込み対応）
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

    // --- 管理者・在庫管理用メソッド ---

    // 新規登録画面の表示
    public function adminCreate()
    {
        return view('admin.products.create');
    }

    // 新規登録の保存処理
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        Product::create($validated);

        return redirect()->route('admin.products.manage')->with('success', '商品を追加しました。');
    }

    // 編集画面の表示
    public function adminEdit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    // 更新処理
    public function adminUpdate(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.manage')->with('success', '商品を更新しました。');
    }

    // 削除処理
    public function adminDestroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.manage')->with('success', '商品を削除しました。');
    }

    // --- 在庫管理専用ページの表示 ---
    public function inventoryIndex()
    {
        $products = Product::all();
        return view('admin.inventory.index', compact('products'));
    }

    // 在庫数の単体（またはカラー別）更新
    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
            'color_name' => 'nullable|string',
        ]);

        $colorName = $request->input('color_name');
        $newStock = (int)$request->input('stock');

        // カラー別の更新の場合
        if (!empty($colorName) && !empty($product->colors) && isset($product->colors[$colorName])) {
            $colors = $product->colors;
            $colors[$colorName]['stock'] = $newStock; // 指定されたカラーの在庫を更新

            // 合計在庫数を全体の stock に自動反映させる場合
            $totalStock = collect($colors)->sum(fn($c) => $c['stock'] ?? 0);

            $product->update([
                'colors' => $colors,
                'stock'  => $totalStock, // 全体の在庫も連動して更新
            ]);

            return back()->with('message', "「{$product->name} ({$colorName})」の在庫数を更新しました。");
        }

        // 通常商品（カラーなし）の場合
        $product->update([
            'stock' => $newStock,
        ]);

        return back()->with('message', "「{$product->name}」の在庫数を更新しました。");
    }
}