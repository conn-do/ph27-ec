<?php

use App\Models\Product;

test('product detail page shows image from public storage', function () {
    $this->withoutVite();

    $product = Product::create([
        'name' => 'ガラスペン',
        'price' => 1200,
        'description' => '透明感のあるガラスペンです。',
        'image' => 'images/products/glass-pen.jpg',
    ]);

    $this->get('/products/'.$product->id)
        ->assertSuccessful()
        ->assertSee('src="'.asset('storage/images/products/glass-pen.jpg').'"', false)
        ->assertDontSee('src="'.asset('images/products/glass-pen.jpg').'"', false);
});
