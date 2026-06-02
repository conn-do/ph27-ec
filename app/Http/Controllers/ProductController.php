<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('index', [
            'products' => $products,
        ]);
    }

    public function show(Product $product)
    {
        return view('products.show', [
            'product' => $product,
        ]);
    }
}
