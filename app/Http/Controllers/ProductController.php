<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductSearchRequest;
use App\Models\Category;
use App\Models\News;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(ProductSearchRequest $request, ?Category $category = null): View
    {
        $keyword = $request->string('keyword')->trim()->toString();
        $sort = $request->input('sort') ?: 'newest';
        $query = Product::with('category')
            ->when($category, fn ($query) => $query->where('category_id', $category->id))
            ->when($keyword !== '', fn ($query) => $query->where('name', 'like', '%'.$keyword.'%'));
        match ($sort) {
            'price_asc' => $query->orderBy('price')->orderBy('id'),
            'price_desc' => $query->orderByDesc('price')->orderBy('id'),
            default => $query->orderByDesc('id'),
        };

        return view('index', [
            'products' => $query->paginate(9)->withQueryString(),
            'categories' => Category::withCount('products')->get(),
            'category' => $category,
            'keyword' => $keyword,
            'sort' => $sort,
            'news' => News::latest('id')->limit(3)->get(),
        ]);
    }

    public function show(Product $product): View
    {
        $product->load('category');

        return view('products.show', [
            'product' => $product,
            'related' => Product::with('category')->where('category_id', $product->category_id)->whereKeyNot($product->id)->limit(3)->get(),
        ]);
    }

    public function search(ProductSearchRequest $request): View
    {
        return $this->index($request);
    }

    public function category(ProductSearchRequest $request, Category $category): View
    {
        return $this->index($request, $category);
    }
}
