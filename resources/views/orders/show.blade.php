@extends('layouts.base')

@section('title', '注文詳細')

@section('content')
    <div class="max-w-2xl">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-bold text-stone-900">注文ID: {{ $order->id }}</h1>
            <span class="inline-block rounded-full bg-stone-100 px-3 py-1 text-xs font-medium text-stone-600">
                {{ $order->status->label() }}
            </span>
        </div>
        <p class="mt-1 text-sm text-stone-500">注文日時: {{ $order->created_at->format('Y/m/d H:i') }}</p>

        <div class="mt-6 rounded-xl border border-stone-200 bg-white p-5">
            <h2 class="mb-3 text-sm font-bold text-stone-900">ご注文商品</h2>
            <ul class="divide-y divide-stone-100">
                @foreach ($order->details as $detail)
                    <li class="flex items-center justify-between py-2 text-sm">
                        <span class="text-stone-700">{{ $detail->product->name }}</span>
                        <span class="text-stone-500">{{ $detail->quantity }}個</span>
                    </li>
                @endforeach
            </ul>
            <div class="mt-3 border-t border-stone-100 pt-3 text-right">
                @if ($order->discount_amount > 0)
                    <p class="text-sm text-stone-500">小計: {{ number_format($order->total_price + $order->discount_amount) }}円</p>
                    <p class="text-sm text-red-600">
                        割引: -{{ number_format($order->discount_amount) }}円
                        @if ($order->coupon_code)
                            （{{ $order->coupon_code }}）
                        @endif
                    </p>
                @endif
                <p class="text-base font-bold text-stone-900">合計: {{ number_format($order->total_price) }}円</p>
            </div>
        </div>

        <div class="mt-6 rounded-xl border border-stone-200 bg-white p-5">
            <h2 class="mb-3 text-sm font-bold text-stone-900">お届け先</h2>
            <p class="text-sm text-stone-700">{{ $order->shipping_name }} 様</p>
            <p class="text-sm text-stone-500">〒{{ $order->shipping_postal_code }}</p>
            <p class="text-sm text-stone-500">{{ $order->shipping_address }}</p>
            <p class="text-sm text-stone-500">{{ $order->shipping_phone }}</p>
        </div>

        @if ($order->status === \App\Enums\OrderStatus::Shipped)
            <div class="mt-6 rounded-xl border border-stone-200 bg-white p-5">
                <h2 class="mb-3 text-sm font-bold text-stone-900">配送状況</h2>
                @if ($order->shipped_at)
                    <p class="text-sm text-stone-500">発送日時: {{ $order->shipped_at->format('Y/m/d H:i') }}</p>
                @endif
                @if ($order->carrier)
                    <p class="text-sm text-stone-500">配送業者: {{ $order->carrier }}</p>
                @endif
                @if ($order->tracking_number)
                    <p class="text-sm text-stone-500">追跡番号: {{ $order->tracking_number }}</p>
                @endif
                @if (! $order->carrier && ! $order->tracking_number)
                    <p class="text-sm text-stone-500">発送済みです。追跡情報は準備中です。</p>
                @endif
            </div>
        @endif

        @if ($order->status === \App\Enums\OrderStatus::Pending)
            <form action="/orders/{{ $order->id }}/cancel" method="POST" class="mt-6">
                @csrf
                <button type="submit"
                    class="rounded-lg border border-red-300 px-5 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50">
                    注文をキャンセルする
                </button>
            </form>
        @endif
    </div>
@endsection
