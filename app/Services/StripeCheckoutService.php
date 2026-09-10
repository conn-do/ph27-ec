<?php

namespace App\Services;

use App\Models\Order;
use Stripe\Checkout\Session;
use Stripe\Event;
use Stripe\StripeClient;
use Stripe\Webhook;

class StripeCheckoutService
{
    private StripeClient $client;

    public function __construct()
    {
        $this->client = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * 注文の内容からStripe Checkout Sessionを作成し、決済画面のURLを返す。
     */
    public function createSession(Order $order): string
    {
        $lineItems = $order->details->map(fn ($detail) => [
            'price_data' => [
                'currency' => 'jpy',
                'product_data' => [
                    'name' => $detail->product->name,
                ],
                'unit_amount' => (int) $detail->product->price,
            ],
            'quantity' => $detail->quantity,
        ])->all();

        $session = $this->client->checkout->sessions->create([
            'mode' => 'payment',
            'line_items' => $lineItems,
            'success_url' => route('checkout.success').'?order_id='.$order->id,
            'cancel_url' => route('checkout.cancel').'?order_id='.$order->id,
            'metadata' => [
                'order_id' => (string) $order->id,
            ],
        ]);

        $order->update(['stripe_checkout_session_id' => $session->id]);

        return $session->url;
    }

    public function retrieveSession(string $sessionId): Session
    {
        return $this->client->checkout->sessions->retrieve($sessionId);
    }

    /**
     * Webhookの署名を検証し、Stripeイベントに変換する。
     * 署名が不正な場合はStripe SDK側の例外がそのまま投げられる。
     */
    public function constructWebhookEvent(string $payload, string $signature): Event
    {
        return Webhook::constructEvent(
            $payload,
            $signature,
            config('services.stripe.webhook_secret'),
        );
    }
}
