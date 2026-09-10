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
        $products = Product::all();

        $news = News::orderBy('id', 'desc')
            ->limit(3)
            ->get();

        $categories = Category::all();

        $ranking = OrderDetail::select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity')
            )
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(3)
            ->get();

        return view('index', [
            'products' => $products,
            'news' => $news,
            'categories' => $categories,
            'ranking' => $ranking,
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
    }
}
