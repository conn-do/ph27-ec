<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

#[Signature('app:ranking-cache')]
#[Description('ランキングのキャッシュを作成')]
class RankingCache extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rankingProducts = Product::query()
            ->select('products.*')
            ->join(
                'order_details',
                'products.id',
                '=',
                'order_details.product_id'
            )
            ->groupBy('products.id')
            ->selectRaw('SUM(order_details.quantity) as quantity')
            ->orderByRaw('quantity DESC')
            ->limit(5)
            ->get();

        $productIds = [];
        foreach ($rankingProducts as $product) {
            $productIds[] = $product->id;
        }
        Cache::put(
            'ranking_products',
            $productIds,
            now()->addMinutes(120)
        );

        $this->info('キャッシュを作成しました');
        return self::SUCCESS;
    }
}
