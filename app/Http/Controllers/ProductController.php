<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('index', [
            'news' => News::query()
                ->latest('id')
                ->limit(3)
                ->get(),
            'products' => Product::query()->get(),
        ]);
    }

    public function show(Product $product): View
    {
        return view('products.show', [
            'product' => $product,
        ]);
    }

    public function search(Request $request): View
    {
        $keyword = $request->string('keyword')->toString();

        return view('index', [
            'news' => News::query()
                ->latest('id')
                ->limit(3)
                ->get(),
            'products' => Product::query()
                ->where('name', 'like', "%{$keyword}%")
                ->get(),
        ]);
    }
}
