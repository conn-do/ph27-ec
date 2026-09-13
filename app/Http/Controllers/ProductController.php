<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $categories = Category::all();
        $news = News::orderBy('id', 'desc')->limit(3)->get();
        $ranking = $this->ranking();

        return view('index', [
            'products' => $products,
            'categories' => $categories,
            'news' => $news,
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
        $products = Product::where('name', 'like', "%$keyword%")->get();
        $ranking = $this->ranking();

        return view('index', [
            'products' => $products,
            'ranking' => $ranking,
        ]);
    }

    public function category(Category $category)
    {
        $products = Product::where('category_id', $category->id)->orderBy('id', 'desc')->get();

        return view('category', [
            'category' => $category,
            'products' => $products,
        ]);
    }

    /**
     * @return array<int, Product>
     */
    private function ranking(): array
    {
        $productIds = Cache::get('product-ranking', []);
        $products = [];
        foreach ($productIds as $id) {
            $product = Product::find($id);
            if ($product) {
                $products[] = $product;
            }
        }

        return $products;
    }
}
