<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\News;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = trim((string) $request->query('keyword', ''));
        $categorySlug = (string) $request->query('category', '');

        $products = Product::query()
            ->with('category')
            ->when($keyword !== '', function (Builder $query) use ($keyword): void {
                $query->where(function (Builder $query) use ($keyword): void {
                    $query->where('name', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%");
                });
            })
            ->when($categorySlug !== '', function (Builder $query) use ($categorySlug): void {
                $query->whereHas('category', function (Builder $query) use ($categorySlug): void {
                    $query->where('slug', $categorySlug);
                });
            })
            ->orderBy('name')
            ->get();

        return view('index', [
            'products' => $products,
            'news' => News::query()->latest('id')->limit(3)->get(),
            'categories' => Category::query()->orderBy('name')->get(),
            'keyword' => $keyword,
            'categorySlug' => $categorySlug,
        ]);
    }

    public function show(Product $product): View
    {
        return view('products.show', [
            'product' => $product->load('category'),
        ]);
    }

    public function search(Request $request): View
    {
        return $this->index($request);
    }

    public function category(Category $category): View
    {
        return view('category', [
            'category' => $category->load('products'),
        ]);
    }
}
