<?php

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\ProductSeeder;

beforeEach(function () {
    $this->seed(ProductSeeder::class);
});

test('customers can combine category keyword and inclusive price filters', function () {
    $pen = Product::query()->where('name', 'すごいペン')->firstOrFail();

    $this->get(route('products.search', [
        'keyword' => 'とても', 'category' => $pen->category_id,
        'min_price' => 770, 'max_price' => 814, 'sort' => 'price_asc',
    ]))->assertOk()->assertViewHas('products', function ($products) {
        return $products->pluck('price')->all() === [770, 814];
    })->assertSee('お気に入りに追加');

    $category = Category::query()->create(['name' => '別のカテゴリー', 'slug' => 'other']);
    $this->get(route('products.index', ['category' => $category->id]))
        ->assertOk()->assertSee('該当する商品はありません。');
});

test('customers can sort products', function (string $sort, array $prices) {
    $this->get(route('products.index', ['sort' => $sort]))
        ->assertOk()->assertViewHas('products', fn ($products) => $products->pluck('price')->all() === $prices);
})->with([
    ['price_asc', [600, 770, 814]],
    ['price_desc', [814, 770, 600]],
    ['newest', [770, 600, 814]],
]);

test('invalid catalog filters are rejected', function (array $filters, string $field) {
    $this->from(route('products.index'))->get(route('products.search', $filters))
        ->assertRedirect(route('products.index'))->assertSessionHasErrors($field);
})->with([
    [['min_price' => -1], 'min_price'],
    [['min_price' => 900, 'max_price' => 100], 'max_price'],
    [['sort' => 'price; DROP TABLE products'], 'sort'],
    [['category' => 99999], 'category'],
    [['keyword' => ['unexpected']], 'keyword'],
    [['page' => 0], 'page'],
]);

test('pagination preserves filters without repeating products', function () {
    $product = Product::query()->firstOrFail();
    for ($index = 0; $index < 13; $index++) {
        $copy = $product->replicate();
        $copy->name = '限定商品'.$index;
        $copy->save();
    }

    $response = $this->get(route('products.search', ['keyword' => '限定商品', 'sort' => 'price_asc']));
    $response->assertOk()->assertViewHas('products', fn ($products) => $products->count() === 12 && $products->total() === 13);
    $firstPage = $response->viewData('products');
    expect($firstPage->nextPageUrl())->toContain('keyword=', 'sort=price_asc', 'page=2');
    $secondPage = $this->get($firstPage->nextPageUrl())->assertOk()->viewData('products');
    expect($secondPage->count())->toBe(1)
        ->and($firstPage->pluck('id')->intersect($secondPage->pluck('id')))->toBeEmpty();
});

test('visitors can save unique wishlist products view them and remove them', function () {
    $product = Product::query()->firstOrFail();
    for ($index = 0; $index < 2; $index++) {
        $this->from(route('products.show', $product))->post(route('wishlist.store', $product))
            ->assertRedirect(route('products.show', $product))->assertSessionHasNoErrors();
    }
    expect(session('wishlist'))->toBe([$product->id]);
    $this->get(route('wishlist.index'))->assertOk()->assertSee($product->name)->assertSee('お気に入りから削除');
    $this->from(route('wishlist.index'))->delete(route('wishlist.destroy', $product))
        ->assertRedirect(route('wishlist.index'));
    $this->get(route('wishlist.index'))->assertOk()->assertSee('お気に入りはまだありません。');
    expect(session('wishlist'))->toBe([]);
});

test('wishlist rejects missing products and ignores stale entries', function () {
    $this->post(route('wishlist.store', 99999))->assertNotFound();
    $this->withSession(['wishlist' => [99999]])->get(route('wishlist.index'))
        ->assertOk()->assertSee('お気に入りはまだありません。');
});

test('wishlist is isolated to the visitor session', function () {
    $product = Product::query()->firstOrFail();
    $this->post(route('wishlist.store', $product));
    $this->flushSession();
    $this->get(route('wishlist.index'))->assertOk()->assertSee('お気に入りはまだありません。');
});
