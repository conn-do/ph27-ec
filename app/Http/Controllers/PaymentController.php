<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Services\StripeCheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\SignatureVerificationException;
use UnexpectedValueException;

class PaymentController extends Controller
{
    public function __construct(private StripeCheckoutService $stripeCheckout) {}

    /**
     * Stripeの決済画面から「成功」で戻ってきた時の処理。
     * ユーザーへすぐに完了画面を見せるためのものであり、
     * 実際の確定処理はwebhook()と共通のconfirmOrder()で行う。
     */
    public function success(Request $request)
    {
        $order = Order::where('id', $request->query('order_id'))
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $session = $this->stripeCheckout->retrieveSession($order->stripe_checkout_session_id);

        if ($session->payment_status === 'paid') {
            $this->confirmOrder($order, $session->payment_intent);
            session()->forget('cart');

            return view('orders.complete', [
                'order' => $order->fresh(),
            ]);
        }

        return redirect('/cart')->with('message', '決済が完了していません。');
    }

    /**
     * Stripeの決済画面から「キャンセル」で戻ってきた時の処理。
     * 未払いの仮注文を破棄してカートへ戻す。
     */
    public function cancel(Request $request)
    {
        $order = Order::where('id', $request->query('order_id'))
            ->where('user_id', $request->user()->id)
            ->first();

        if ($order && $order->payment_status === PaymentStatus::Unpaid) {
            $order->details()->delete();
            $order->delete();
        }

        return redirect('/cart')->with('message', '決済がキャンセルされました。');
    }

    /**
     * Stripeからのwebhook。ユーザーがブラウザを閉じてしまった場合でも
     * 確実に注文を確定させるための本命の処理。
     */
    public function webhook(Request $request)
    {
        try {
            $event = $this->stripeCheckout->constructWebhookEvent(
                $request->getContent(),
                $request->header('Stripe-Signature') ?? '',
            );
        } catch (UnexpectedValueException|SignatureVerificationException $e) {
            Log::warning('Stripe webhook signature verification failed: '.$e->getMessage());

            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $order = Order::find($session->metadata->order_id ?? null);

            if ($order && $order->payment_status !== PaymentStatus::Paid) {
                $this->confirmOrder($order, $session->payment_intent);
            }
        }

        return response('OK', 200);
    }

    /**
     * 在庫減算と注文確定を1箇所にまとめる。
     * success()とwebhook()の両方から呼ばれる可能性があるため、
     * 支払い済みなら何もしない（冪等性の確保）。
     */
    private function confirmOrder(Order $order, ?string $paymentIntentId): void
    {
        if ($order->payment_status === PaymentStatus::Paid) {
            return;
        }

        DB::transaction(function () use ($order, $paymentIntentId) {
            foreach ($order->details as $detail) {
                $detail->product->decrement('stock', $detail->quantity);
            }

            $order->update([
                'payment_status' => PaymentStatus::Paid,
                'stripe_payment_intent_id' => $paymentIntentId,
            ]);
        });

        Mail::to($order->user)->send(new OrderConfirmationMail($order));
    }
}
