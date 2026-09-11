<?php

use App\Models\Product;
use Tests\TestCase;

test('shows an empty cart message when nothing has been added', function () {
    /** @var TestCase $this */
    $this->get(route('cart.index'))
        ->assertSuccessful()
        ->assertSee('カートは まだ からっぽだよ。');
});

test('guests can put a product in the cart', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create(['name' => 'ドラゴンえんぴつ', 'stock' => 10]);

    $this->post(route('cart.store'), [
        'product_id' => $product->id,
        'quantity' => 2,
    ])->assertRedirect(route('cart.index'));

    $this->assertSame([$product->id => 2], session('cart'));
});

test('adding the same product again adds up the quantity', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create(['stock' => 10]);

    $this->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 2]);
    $this->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 3]);

    $this->assertSame([$product->id => 5], session('cart'));
});

test('cannot add more than the stock', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create(['stock' => 2]);

    $this->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 5]);

    $this->assertSame([$product->id => 2], session('cart'));
});

test('cannot add a sold out product', function () {
    /** @var TestCase $this */
    $product = Product::factory()->outOfStock()->create(['name' => 'うちゅうふでばこ']);

    $this->from(route('products.show', $product))
        ->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 1])
        ->assertRedirect(route('products.show', $product))
        ->assertSessionHas('error');

    expect(session('cart'))->toBeNull();
});

test('rejects a quantity above the per item limit', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create(['stock' => 100]);

    $this->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 11])
        ->assertSessionHasErrors('quantity');
});

test('can change the quantity of a line', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create(['stock' => 10]);

    $this->withSession(['cart' => [$product->id => 1]])
        ->patch(route('cart.update', $product), ['quantity' => 4])
        ->assertRedirect(route('cart.index'));

    $this->assertSame([$product->id => 4], session('cart'));
});

test('setting the quantity to zero removes the line', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create(['stock' => 10]);

    $this->withSession(['cart' => [$product->id => 3]])
        ->patch(route('cart.update', $product), ['quantity' => 0]);

    $this->assertSame([], session('cart'));
});

test('can take a product out of the cart', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create(['stock' => 10]);
    $other = Product::factory()->create(['stock' => 10]);

    $this->withSession(['cart' => [$product->id => 1, $other->id => 2]])
        ->delete(route('cart.destroy', $product));

    $this->assertSame([$other->id => 2], session('cart'));
});

test('can empty the whole cart', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create(['stock' => 10]);

    $this->withSession(['cart' => [$product->id => 1]])
        ->delete(route('cart.clear'));

    expect(session('cart'))->toBeNull();
});

test('drops products that no longer exist', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create(['stock' => 10]);

    $this->withSession(['cart' => [$product->id => 1, 999999 => 2]])
        ->get(route('cart.index'))
        ->assertSuccessful();

    $this->assertSame([$product->id => 1], session('cart'));
});

test('shows the calculation steps on the cart page', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create(['price' => 200, 'stock' => 10, 'tax_rate' => 10]);

    $this->withSession(['cart' => [$product->id => 2]])
        ->get(route('cart.index'))
        ->assertSuccessful()
        ->assertSee('ねだん × こすう を けいさんする')
        ->assertSee('しょうひぜい を けいさんする')
        ->assertSee('440'); // 400 + 40
});
