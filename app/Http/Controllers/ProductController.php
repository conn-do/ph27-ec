<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        $saleproducts = Product::where('price', '<', 100)->get();

        return view('products.index', [
            'products' => $products,
            'saleProducts' => $saleproducts,
        ]);
    }

    public function show(int $id)
    {
        $product = Product::findorFail($id);

        return view('products.show', [
            'product' => $product,
        ]
    );
    }
}
