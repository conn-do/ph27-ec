<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = $this->applySort(Product::query(), $request->input('sort'))->get();

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

    public function show(Product $product)
    {
        $reviews = $product->reviews()->with('user')->latest()->get();

        return view('products.show', [
            'product' => $product,
            'reviews' => $reviews,
            'averageRating' => round($reviews->avg('rating'), 1),
            'myReview' => auth()->check()
                ? $reviews->firstWhere('user_id', auth()->id())
                : null,
            'isFavorited' => auth()->check()
                ? $product->favorites()->where('user_id', auth()->id())->exists()
                : false,
        ]);
    }

    public function search(Request $request)
    {
        $query = Product::query();

        if ($keyword = $request->input('keyword')) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (int) $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (int) $request->input('max_price'));
        }

        if ($request->boolean('in_stock_only')) {
            $query->where('stock', '>', 0);
        }

        $products = $this->applySort($query, $request->input('sort'))->get();

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

    public function category(Category $category, Request $request)
    {
        $products = $this->applySort($category->products(), $request->input('sort'))->get();

        return view('category', [
            'category' => $category,
            'products' => $products,
        ]);
    }

    /**
     * 並び替え指定に応じてクエリへorderByを適用する。
     */
    private function applySort(Builder|Relation $query, ?string $sort): Builder|Relation
    {
        return match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default => $query->orderBy('id', 'desc'),
        };
    }
}
