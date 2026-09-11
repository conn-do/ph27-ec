<?php

namespace App\Actions\Shop;

use App\Models\Product;

/**
 * カートの中身から「お会計の計算式」を組み立てる。
 *
 * 画面に計算のとちゅうしきをそのまま出したいので、
 * 合計だけでなく 1 行ずつの小計・消費税もぜんぶ返す。
 *
 * @phpstan-type CartLine array{
 *     product: Product,
 *     quantity: int,
 *     unit_price: int,
 *     subtotal: int,
 *     tax_rate: int,
 *     tax_amount: int,
 *     total: int,
 *     stock: int,
 *     is_purchasable: bool,
 * }
 * @phpstan-type TaxGroup array{rate: int, subtotal: int, tax: int}
 * @phpstan-type CartCalculation array{
 *     lines: list<CartLine>,
 *     tax_groups: list<TaxGroup>,
 *     subtotal: int,
 *     tax_total: int,
 *     total: int,
 *     item_count: int,
 *     total_quantity: int,
 *     is_purchasable: bool,
 * }
 */
class CartCalculator
{
    public function __construct(private readonly Cart $cart) {}

    /**
     * いまのカートを計算する。
     *
     * @return CartCalculation
     */
    public function calculate(): array
    {
        $products = $this->cart->products();
        $lines = [];

        foreach ($this->cart->contents() as $productId => $quantity) {
            $product = $products->get($productId);

            if ($product === null) {
                continue;
            }

            $lines[] = $this->buildLine($product, (int) $quantity);
        }

        return $this->summarise($lines);
    }

    /**
     * 1 行ぶんの計算。
     *
     * ねだん × こすう = 小計
     * 小計 × 税率 ÷ 100 = 消費税（1円未満は切り捨て）
     *
     * @return CartLine
     */
    private function buildLine(Product $product, int $quantity): array
    {
        $subtotal = $product->price * $quantity;
        $taxAmount = intdiv($subtotal * $product->tax_rate, 100);

        return [
            'product' => $product,
            'quantity' => $quantity,
            'unit_price' => $product->price,
            'subtotal' => $subtotal,
            'tax_rate' => $product->tax_rate,
            'tax_amount' => $taxAmount,
            'total' => $subtotal + $taxAmount,
            'stock' => $product->stock,
            'is_purchasable' => $product->stock >= $quantity,
        ];
    }

    /**
     * 行をぜんぶ足して合計と税率ごとのまとめを作る。
     *
     * @param  list<CartLine>  $lines
     * @return CartCalculation
     */
    private function summarise(array $lines): array
    {
        $subtotal = array_sum(array_column($lines, 'subtotal'));
        $taxTotal = array_sum(array_column($lines, 'tax_amount'));

        $taxGroups = [];

        foreach ($lines as $line) {
            $rate = $line['tax_rate'];

            $taxGroups[$rate] ??= ['rate' => $rate, 'subtotal' => 0, 'tax' => 0];
            $taxGroups[$rate]['subtotal'] += $line['subtotal'];
            $taxGroups[$rate]['tax'] += $line['tax_amount'];
        }

        krsort($taxGroups);

        return [
            'lines' => $lines,
            'tax_groups' => array_values($taxGroups),
            'subtotal' => $subtotal,
            'tax_total' => $taxTotal,
            'total' => $subtotal + $taxTotal,
            'item_count' => count($lines),
            'total_quantity' => array_sum(array_column($lines, 'quantity')),
            'is_purchasable' => $lines !== []
                && ! in_array(false, array_column($lines, 'is_purchasable'), true),
        ];
    }
}
