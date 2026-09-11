<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\News;
use App\Models\Category;
use App\Models\OrderDetail;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->get();

        $news = News::orderBy('id', 'desc')
            ->limit(3)
            ->get();

        $categories = Category::whereIn('slug', [
            'writing',
            'notebook',
            'rubber-stamps',
            'clips-pins',
        ])->get();

        $orderDetails = OrderDetail::all();

        $sales = [];

        foreach ($orderDetails as $orderDetail) {
            if (!isset($sales[$orderDetail->product_id])) {
                $sales[$orderDetail->product_id] = 0;
            }

            $sales[$orderDetail->product_id] += $orderDetail->quantity;
        }

        arsort($sales);

        $ranking = [];

        foreach ($sales as $productId => $quantity) {
            $product = Product::find($productId);

            if (!$product || !$product->is_active) {
                continue;
            }

            $ranking[] = [
                'product' => $product,
                'quantity' => $quantity,
            ];

            if (count($ranking) >= 3) {
                break;
            }
        }

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

        $products = Product::where('is_active', true)
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            })
            ->get();

        return view('search', [
            'products' => $products,
        ]);
    }

    public function category(Category $category)
    {
        return view('category', [
            'category' => $category,
        ]);
    }
}