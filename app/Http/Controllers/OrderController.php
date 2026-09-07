<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
<<<<<<< Updated upstream
use App\Models\OrderDetail;
use Exception;
use Illuminate\Support\Facades\DB;
=======
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
>>>>>>> Stashed changes

class OrderController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
<<<<<<< Updated upstream
        // 例外処理
        // try の中でエラーが起きたら
        // catch の中の処理が実行される
        try {
            DB::beginTransaction();
            // 注文処理
            // [1 => 3, 2 => 5] (商品ID => 数量)
            $cart = session()->get('cart', []);
            $totalPrice = 0;
            foreach ($cart as $productId => $quantity) {
                $product = Product::find($productId);
                $totalPrice += $product->price * $quantity;
            }

            $order = new Order();
            $order->total_price = $totalPrice;
            $order->user_id = $request->user()->id;
            $order->save();

            foreach ($cart as $productId => $quantity) {
                $detail = new OrderDetail();
                $detail->order_id = $order->id;
                $detail->product_id = $productId;
                $detail->quantity = $quantity;
                $detail->save();


                /** @var Product $product */
                $product = Product::find($productId);

                if ($quantity > $product->stock) {
                    // 例外を投げる
                    throw new Exception('在庫がありません');
                }

                $product->stock -= $quantity;
                $product->save();
            }

            // トランザクションが正常に終了したら
            // DBの変更を確定する
            DB::commit();

            session()->forget('cart');

            session()->flash('message', '注文が完了しました！');

            return view('orders.complete', [
                'order' => $order,
            ]);
        } catch (Exception $e) {
            // エラー処理
            // DBの変更を元に戻す
            DB::rollBack();
            $message = '申し訳ございません！エラーが発生しました。最初からやり直してください。<br>';
            $message .= $e->getMessage();
            return redirect('/cart')->with('message', $message);
        }
=======
        $cart = session()->get('cart', []);

        if ($cart === []) {
            return to_route('cart.index')->with('message', 'カートに商品を追加してから購入してください。');
        }

        $products = Product::query()->whereKey(array_keys($cart))->get()->keyBy('id');

        if ($products->count() !== count($cart)) {
            session()->forget('cart');

            return to_route('cart.index')->with('message', '販売終了の商品が含まれていたため、カートを更新しました。');
        }

        $order = DB::transaction(function () use ($cart, $products, $request): Order {
            $totalPrice = collect($cart)->sum(
                fn (int $quantity, int|string $productId): int => $products->get($productId)->price * $quantity,
            );

            $order = Order::query()->create([
                'user_id' => $request->user()->id,
                'total_price' => $totalPrice,
            ]);

            $order->details()->createMany(
                collect($cart)
                    ->map(fn (int $quantity, int|string $productId): array => [
                        'product_id' => $productId,
                        'quantity' => $quantity,
                    ])
                    ->all(),
            );

            return $order;
        });

        session()->forget('cart');

        return to_route('orders.show', $order)->with('message', 'ご注文を受け付けました。');
>>>>>>> Stashed changes
    }

    public function index(Request $request): View
    {
        return view('orders.index', [
            'orders' => $request->user()->orders()->latest()->get(),
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        return view('orders.show', [
            'order' => $order->load('details.product'),
        ]);
    }
}
