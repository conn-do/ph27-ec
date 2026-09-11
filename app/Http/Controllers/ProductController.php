<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * しょうひん一覧。カテゴリしぼりこみ・キーワード検索・ならびかえに対応。
     */
    public function index(Request $request): View
    {
        $categories = Category::query()
            ->withCount(['products' => fn ($query) => $query->published()])
            ->orderBy('sort_order')
            ->get();

        $category = $request->filled('category')
            ? $categories->firstWhere('slug', $request->string('category')->toString())
            : null;

        $products = Product::query()
            ->published()
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->when($category, fn ($query) => $query->where('category_id', $category->id))
            ->search($request->string('keyword')->toString())
            ->tap(fn ($query) => $this->applySort($query, $request->string('sort')->toString()))
            ->paginate(12)
            ->withQueryString();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'activeCategory' => $category,
            'keyword' => $request->string('keyword')->toString(),
            'sort' => $request->string('sort')->toString(),
        ]);
    }

    /**
     * しょうひんの詳細。レビューとお気に入りのじょうたいもいっしょに出す。
     */
    public function show(Request $request, Product $product): View
    {
        abort_unless($product->is_published, 404);

        $product->loadCount('reviews')->loadAvg('reviews', 'rating');

        $relatedProducts = Product::query()
            ->published()
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->limit(4)
            ->get();

        return view('products.show', [
            'product' => $product->load('category'),
            'reviews' => $product->reviews()->with('user')->latestFirst()->limit(10)->get(),
            'relatedProducts' => $relatedProducts,
            'isFavorited' => $product->isFavoritedBy($request->user()),
            'canReview' => $request->user()?->hasPurchased($product) ?? false,
            'myReview' => $request->user()
                ? $product->reviews()->where('user_id', $request->user()->id)->first()
                : null,
        ]);
    }

    /**
     * 検索は一覧と同じ見た目にしたいので index にまとめる。
     */
    public function search(Request $request): View
    {
        return $this->index($request);
    }

    /**
     * @param  Builder<Product>  $query
     */
    private function applySort($query, string $sort): void
    {
        match ($sort) {
            'cheap' => $query->orderBy('price'),
            'expensive' => $query->orderByDesc('price'),
            'popular' => $query->orderByDesc('reviews_avg_rating')->orderByDesc('reviews_count'),
            default => $query->orderByDesc('id'),
        };
    }
}
