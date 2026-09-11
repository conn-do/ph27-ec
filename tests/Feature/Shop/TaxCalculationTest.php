<?php

use App\Actions\Shop\Cart;
use App\Actions\Shop\CartCalculator;
use App\Models\Product;
use Tests\TestCase;

/**
 * お会計の けいさんが 正しいか。
 * このサイトの 中心の きのうなので こまかく たしかめる。
 */
function calculateCartFor(array $items): array
{
    /** @var Cart $cart */
    $cart = app(Cart::class);

    foreach ($items as [$product, $quantity]) {
        $cart->update($product, $quantity);
    }

    return app(CartCalculator::class)->calculate();
}

test('multiplies unit price by quantity for each line', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create(['price' => 120, 'stock' => 10, 'tax_rate' => 10]);

    $calculation = calculateCartFor([[$product, 2]]);

    expect($calculation['lines'][0]['subtotal'])->toBe(240)
        ->and($calculation['subtotal'])->toBe(240);
});

test('calculates 10 percent consumption tax', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create(['price' => 200, 'stock' => 10, 'tax_rate' => 10]);

    $calculation = calculateCartFor([[$product, 3]]);

    // 200 × 3 = 600、600 × 10 ÷ 100 = 60、600 + 60 = 660
    expect($calculation['subtotal'])->toBe(600)
        ->and($calculation['tax_total'])->toBe(60)
        ->and($calculation['total'])->toBe(660);
});

test('rounds fractions of a yen down', function () {
    /** @var TestCase $this */
    // 105 × 10 ÷ 100 = 10.5 → きりすてて 10
    $product = Product::factory()->create(['price' => 105, 'stock' => 10, 'tax_rate' => 10]);

    $calculation = calculateCartFor([[$product, 1]]);

    expect($calculation['tax_total'])->toBe(10)
        ->and($calculation['total'])->toBe(115);
});

test('groups the tax by rate so 8 and 10 percent are shown separately', function () {
    /** @var TestCase $this */
    $stationery = Product::factory()->create(['price' => 200, 'stock' => 10, 'tax_rate' => 10]);
    $snack = Product::factory()->reducedTax()->create(['price' => 50, 'stock' => 10]);

    $calculation = calculateCartFor([[$stationery, 1], [$snack, 2]]);

    // ぶんぼうぐ: 200 → ぜい 20 ／ おかし: 100 → ぜい 8
    expect($calculation['subtotal'])->toBe(300)
        ->and($calculation['tax_total'])->toBe(28)
        ->and($calculation['total'])->toBe(328)
        ->and($calculation['tax_groups'])->toHaveCount(2);

    $rates = array_column($calculation['tax_groups'], 'rate');

    expect($rates)->toBe([10, 8]);
});

test('is empty when nothing is in the cart', function () {
    /** @var TestCase $this */
    $calculation = calculateCartFor([]);

    expect($calculation['lines'])->toBe([])
        ->and($calculation['total'])->toBe(0)
        ->and($calculation['is_purchasable'])->toBeFalse();
});

test('marks the cart as not purchasable when stock runs short', function () {
    /** @var TestCase $this */
    $product = Product::factory()->create(['price' => 100, 'stock' => 5, 'tax_rate' => 10]);

    /** @var Cart $cart */
    $cart = app(Cart::class);
    $cart->update($product, 5);

    // あとから ざいこが へった ばあい
    $product->update(['stock' => 2]);

    $calculation = app(CartCalculator::class)->calculate();

    expect($calculation['lines'][0]['is_purchasable'])->toBeFalse()
        ->and($calculation['is_purchasable'])->toBeFalse();
});
