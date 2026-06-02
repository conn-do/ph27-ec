<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $id = $request->input('productId');
        $product = Product::find($id);
        echo '商品ID:';
        echo $id;
        echo '<br>';
        echo '商品名:';
        echo $product->name;
        echo '<br>';
        echo 'カートに入れた個数: ';
        echo $request->input('quantity');
    }
}
