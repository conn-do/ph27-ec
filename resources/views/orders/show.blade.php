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
            <div class="mt-3 flex justify-end border-t border-stone-100 pt-3">
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
