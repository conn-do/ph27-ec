<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

#[Signature('ranking:cache')]
#[Description('売れ筋ランキングを集計してキャッシュする')]
class CacheProductRankingCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $products = Product::query()
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
            ->select('products.id')
            ->selectRaw('SUM(order_details.quantity) as sales_quantity')
            ->groupBy('products.id')
            ->orderByDesc('sales_quantity')
            ->limit(5)
            ->get();

        $productIds = [];
        foreach ($products as $product) {
            $productIds[] = $product->id;
        }

        Cache::put('product-ranking', $productIds, now()->addHours(24));

        $this->info('ランキングをキャッシュしました。');

        return self::SUCCESS;
    }
}
