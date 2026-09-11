<?php

namespace Database\Seeders;

use App\Models\AllowanceTransaction;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'test@example.com')->first();

        if ($user === null) {
            return;
        }

        // 商品名 => こすう の かいもの を 2かい ぶん つくる。
        $baskets = [
            ['ドラゴンえんぴつ' => 2, 'ほしぞらノート' => 1, 'ラムネ' => 3],
            ['ブロックけしゴム' => 1, 'とうめいじょうぎ' => 1],
        ];

        foreach ($baskets as $index => $basket) {
            $this->createOrder($user, $basket, daysAgo: ($index + 1) * 4);
        }
    }

    /**
     * @param  array<string, int>  $basket  商品名 => こすう
     */
    private function createOrder(User $user, array $basket, int $daysAgo): void
    {
        $products = Product::whereIn('name', array_keys($basket))->get();

        if ($products->isEmpty()) {
            return;
        }

        $createdAt = now()->subDays($daysAgo);

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $user->id,
            'subtotal' => 0,
            'tax_total' => 0,
            'total_price' => 0,
            'paid_amount' => 0,
            'change_amount' => 0,
            'status' => 'paid',
        ]);

        $order->forceFill(['created_at' => $createdAt, 'updated_at' => $createdAt])->save();

        $subtotal = 0;
        $taxTotal = 0;

        foreach ($products as $product) {
            $quantity = $basket[$product->name];
            $lineSubtotal = $product->price * $quantity;
            $lineTax = intdiv($lineSubtotal * $product->tax_rate, 100);

            $order->details()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'unit_price' => $product->price,
                'tax_rate' => $product->tax_rate,
                'quantity' => $quantity,
                'subtotal' => $lineSubtotal,
                'tax_amount' => $lineTax,
            ]);

            $stockAfter = max(0, $product->stock - $quantity);
            $product->forceFill(['stock' => $stockAfter])->save();

            StockMovement::create([
                'product_id' => $product->id,
                'order_id' => $order->id,
                'quantity_change' => -$quantity,
                'stock_after' => $stockAfter,
                'reason' => StockMovement::REASON_SOLD,
            ]);

            $subtotal += $lineSubtotal;
            $taxTotal += $lineTax;
        }

        $total = $subtotal + $taxTotal;

        // 1000えん さつ の まいすう で はらった ことにする。
        $paid = (int) (ceil($total / 1000) * 1000);

        $order->update([
            'subtotal' => $subtotal,
            'tax_total' => $taxTotal,
            'total_price' => $total,
            'paid_amount' => $paid,
            'change_amount' => $paid - $total,
        ]);

        $balanceAfter = max(0, $user->allowance_balance - $total);
        $user->forceFill(['allowance_balance' => $balanceAfter])->save();

        $transaction = AllowanceTransaction::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'amount' => -$total,
            'balance_after' => $balanceAfter,
            'reason' => AllowanceTransaction::REASON_SHOPPING,
        ]);

        $transaction->forceFill(['created_at' => $createdAt, 'updated_at' => $createdAt])->save();
    }
}
