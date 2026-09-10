<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const SESSION_KEY = 'paperloop.cart';

    /** @return array{items: array<int, array{product: Product, quantity: int, subtotal: int}>, total: int, quantity: int} */
    public function summary(): array
    {
        $quantities = $this->quantities();

        /** @var Collection<int, Product> $products */
        $products = Product::query()
            ->with('category:id,name,slug')
            ->where('is_active', true)
            ->whereIn('id', array_keys($quantities))
            ->get()
            ->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($quantities as $productId => $quantity) {
            $product = $products->get($productId);

            if (! $product instanceof Product) {
                unset($quantities[$productId]);

                continue;
            }

            $subtotal = $product->price * $quantity;
            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
            $total += $subtotal;
        }

        $this->storeQuantities($quantities);

        return [
            'items' => $items,
            'total' => $total,
            'quantity' => array_sum($quantities),
        ];
    }

    public function add(Product $product, int $quantity): ?string
    {
        if (! $product->is_active) {
            return 'この商品は現在カートに追加できません。';
        }

        $quantities = $this->quantities();
        $updatedQuantity = ($quantities[$product->id] ?? 0) + $quantity;

        if ($updatedQuantity > $product->stock) {
            return $product->stock === 0
                ? 'この商品は在庫切れです。'
                : '在庫数を超える数量はカートに追加できません。';
        }

        $quantities[$product->id] = $updatedQuantity;
        $this->storeQuantities($quantities);

        return null;
    }

    public function update(Product $product, int $quantity): ?string
    {
        if (! $product->is_active) {
            return 'この商品は現在カートに追加できません。';
        }

        if ($quantity > $product->stock) {
            return $product->stock === 0
                ? 'この商品は在庫切れです。'
                : '在庫数を超える数量には変更できません。';
        }

        $quantities = $this->quantities();
        $quantities[$product->id] = $quantity;
        $this->storeQuantities($quantities);

        return null;
    }

    public function remove(Product $product): void
    {
        $quantities = $this->quantities();
        unset($quantities[$product->id]);
        $this->storeQuantities($quantities);
    }

    /** @return array<int, int> */
    public function quantitiesForCheckout(): array
    {
        return $this->quantities();
    }

    public function clear(): void
    {
        $this->storeQuantities([]);
    }

    public function quantity(): int
    {
        return array_sum($this->quantities());
    }

    /** @return array<int, int> */
    private function quantities(): array
    {
        $sessionItems = Session::get(self::SESSION_KEY, []);

        if (! is_array($sessionItems)) {
            return [];
        }

        $quantities = [];

        foreach ($sessionItems as $productId => $quantity) {
            if (filter_var($productId, FILTER_VALIDATE_INT) === false || ! is_numeric($quantity)) {
                continue;
            }

            $productId = (int) $productId;
            $quantity = (int) $quantity;

            if ($productId > 0 && $quantity > 0) {
                $quantities[$productId] = $quantity;
            }
        }

        return $quantities;
    }

    /** @param array<int, int> $quantities */
    private function storeQuantities(array $quantities): void
    {
        if ($quantities === []) {
            Session::forget(self::SESSION_KEY);

            return;
        }

        Session::put(self::SESSION_KEY, $quantities);
    }
}
