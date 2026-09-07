<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\News;
use App\Models\Category;

class ProductController extends Controller
{
<<<<<<< Updated upstream
    public function index()
=======
    public function index(Request $request): View
>>>>>>> Stashed changes
    {
        $keyword = trim((string) $request->query('keyword', ''));
        $categorySlug = (string) $request->query('category', '');

        $products = Product::query()
            ->with('category')
            ->when($keyword !== '', function ($query) use ($keyword): void {
                $query->where(function ($query) use ($keyword): void {
                    $query->where('name', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%");
                });
            })
            ->when($categorySlug !== '', function ($query) use ($categorySlug): void {
                $query->whereHas('category', function ($query) use ($categorySlug): void {
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

    public function show(Product $product)
    {
        return view('products.show', [
            'product' => $product->load('category'),
        ]);
    }

    public function search(Request $request)
    {
<<<<<<< Updated upstream
        $keyword = $request->input('keyword');

        $products = Product::where('name', 'like', "%{$keyword}%")->get();

        $news = News::orderBy('id', 'desc')
            ->limit(3)
            ->get();

        return view('index', [
            'products' => $products,
            'news' => $news,
        ]);
    }

    public function category(Category $category)
    {
        return view('category', [
            'category' => $category,
        ]);
=======
        return $this->index($request);
>>>>>>> Stashed changes
    }
}
