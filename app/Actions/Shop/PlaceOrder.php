<?php

namespace App\Actions\Shop;

use App\Models\AllowanceTransaction;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * お会計をして注文を作る。
 *
 * 在庫・おこづかい・はらったお金のチェックをまとめて 1 つのトランザクションで行い、
 * とちゅうで失敗したらぜんぶ元にもどす。
 */
class PlaceOrder
{
    public function __construct(
        private readonly Cart $cart,
        private readonly CartCalculator $calculator,
    ) {}

    /**
     * @param  int  $paidAmount  レジにだしたお金
     *
     * @throws ValidationException
     */
    public function handle(User $user, int $paidAmount): Order
    {
        $calculation = $this->calculator->calculate();

        $this->guardAgainstEmptyCart($calculation['lines']);
        $this->guardAgainstShortPayment($calculation['total'], $paidAmount);
        $this->guardAgainstEmptyWallet($user, $paidAmount);

        $order = DB::transaction(function () use ($user, $calculation, $paidAmount) {
            $order = $this->createOrder($user, $calculation, $paidAmount);

            foreach ($calculation['lines'] as $line) {
                $this->sellProduct($order, $line);
            }

            $this->payFromAllowance($user, $order);

            return $order;
        });

        $this->cart->clear();

        return $order->load('details');
    }

    /**
     * @param  array<string, mixed>  $calculation
     */
    private function createOrder(User $user, array $calculation, int $paidAmount): Order
    {
        return Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $user->id,
            'subtotal' => $calculation['subtotal'],
            'tax_total' => $calculation['tax_total'],
            'total_price' => $calculation['total'],
            'paid_amount' => $paidAmount,
            'change_amount' => $paidAmount - $calculation['total'],
            'status' => 'paid',
        ]);
    }

    /**
     * 明細を作り、在庫をへらして、その記録をのこす。
     *
     * 在庫は lockForUpdate で行ロックしてから読むので、
     * 同時に注文されても在庫がマイナスにならない。
     *
     * @param  array<string, mixed>  $line
     *
     * @throws ValidationException
     */
    private function sellProduct(Order $order, array $line): void
    {
        /** @var Product $locked */
        $locked = Product::query()
            ->whereKey($line['product']->id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($locked->stock < $line['quantity']) {
            throw ValidationException::withMessages([
                'cart' => "「{$locked->name}」は のこり {$locked->stock}こ です。かずを へらしてね。",
            ]);
        }

        $order->details()->create([
            'product_id' => $locked->id,
            'product_name' => $locked->name,
            'unit_price' => $line['unit_price'],
            'tax_rate' => $line['tax_rate'],
            'quantity' => $line['quantity'],
            'subtotal' => $line['subtotal'],
            'tax_amount' => $line['tax_amount'],
        ]);

        $stockAfter = $locked->stock - $line['quantity'];

        $locked->decrement('stock', $line['quantity']);

        StockMovement::create([
            'product_id' => $locked->id,
            'order_id' => $order->id,
            'quantity_change' => -$line['quantity'],
            'stock_after' => $stockAfter,
            'reason' => StockMovement::REASON_SOLD,
        ]);
    }

    /**
     * おこづかいから代金をひく。
     */
    private function payFromAllowance(User $user, Order $order): void
    {
        $balanceAfter = $user->allowance_balance - $order->total_price;

        $user->forceFill(['allowance_balance' => $balanceAfter])->save();

        AllowanceTransaction::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'amount' => -$order->total_price,
            'balance_after' => $balanceAfter,
            'reason' => AllowanceTransaction::REASON_SHOPPING,
        ]);
    }

    /**
     * @param  array<int, mixed>  $lines
     *
     * @throws ValidationException
     */
    private function guardAgainstEmptyCart(array $lines): void
    {
        if ($lines === []) {
            throw ValidationException::withMessages([
                'cart' => 'カートが からっぽだよ。さきに しょうひんを えらんでね。',
            ]);
        }
    }

    /**
     * @throws ValidationException
     */
    private function guardAgainstShortPayment(int $total, int $paidAmount): void
    {
        if ($paidAmount < $total) {
            $shortage = $total - $paidAmount;

            throw ValidationException::withMessages([
                'paid_amount' => "お金が {$shortage}えん たりないよ。もうすこし だしてね。",
            ]);
        }
    }

    /**
     * @throws ValidationException
     */
    private function guardAgainstEmptyWallet(User $user, int $paidAmount): void
    {
        if ($user->allowance_balance < $paidAmount) {
            throw ValidationException::withMessages([
                'paid_amount' => "おさいふには {$user->allowance_balance}えん しか ないよ。",
            ]);
        }
    }
}
