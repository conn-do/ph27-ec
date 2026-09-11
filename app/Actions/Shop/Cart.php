<?php

namespace App\Actions\Shop;

use App\Models\Product;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Collection;

/**
 * セッションに入っているカート（商品ID => こすう）をあつかう。
 */
class Cart
{
    public function __construct(private readonly Session $session) {}

    /**
     * カートの中身。
     *
     * @return array<int, int> 商品ID => こすう
     */
    public function contents(): array
    {
        /** @var array<int, int> $cart */
        $cart = $this->session->get($this->key(), []);

        return $cart;
    }

    /**
     * カートに商品を入れる（すでにあれば足し算する）。
     */
    public function add(Product $product, int $quantity): void
    {
        $cart = $this->contents();
        $current = $cart[$product->id] ?? 0;

        $cart[$product->id] = $this->clamp($current + $quantity, $product);

        $this->session->put($this->key(), $cart);
    }

    /**
     * こすうを直接書きかえる。0 いかなら取りのぞく。
     */
    public function update(Product $product, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->remove($product->id);

            return;
        }

        $cart = $this->contents();
        $cart[$product->id] = $this->clamp($quantity, $product);

        $this->session->put($this->key(), $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->contents();
        unset($cart[$productId]);

        $this->session->put($this->key(), $cart);
    }

    public function clear(): void
    {
        $this->session->forget($this->key());
    }

    public function isEmpty(): bool
    {
        return $this->contents() === [];
    }

    /**
     * カートに入っている合計こすう（ヘッダーのバッジ用）。
     */
    public function totalQuantity(): int
    {
        return array_sum($this->contents());
    }

    /**
     * カートの商品をまとめて取得する（N+1 をさけるため 1 クエリ）。
     *
     * @return Collection<int, Product>
     */
    public function products(): Collection
    {
        $ids = array_keys($this->contents());

        if ($ids === []) {
            return collect();
        }

        return Product::query()
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');
    }

    /**
     * もう売っていない商品をカートから取りのぞく。
     *
     * @return list<int> 取りのぞいた商品ID
     */
    public function removeMissingProducts(): array
    {
        $products = $this->products();
        $removed = [];

        foreach (array_keys($this->contents()) as $productId) {
            if (! $products->has($productId)) {
                $this->remove($productId);
                $removed[] = $productId;
            }
        }

        return $removed;
    }

    private function clamp(int $quantity, Product $product): int
    {
        $max = min(
            (int) config('shop.cart.max_quantity_per_item'),
            max($product->stock, 1),
        );

        return max(1, min($quantity, $max));
    }

    private function key(): string
    {
        return (string) config('shop.cart.session_key');
    }
}
