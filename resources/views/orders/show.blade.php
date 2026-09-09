@extends('layouts.base')

@section('title', '注文詳細')

@section('content')
    <section class="orders-page order-detail" aria-labelledby="order-heading">
        @if (session('message'))
            <p class="store-message">{{ session('message') }}</p>
        @endif
        <div class="page-heading">
            <h1 id="order-heading">注文詳細 #{{ $order->id }}</h1><a href="{{ route('orders.index') }}">注文履歴に戻る</a>
        </div>
        <dl class="order-summary">
            <div>
                <dt>注文日</dt>
                <dd>{{ $order->created_at->format('Y.m.d H:i') }}</dd>
            </div>
            <div>
                <dt>合計金額</dt>
                <dd>¥{{ number_format($order->total_price) }}</dd>
            </div>
            <div>
                <dt>お支払い方法</dt>
                <dd>{{ $order->payment_method?->label() ?? '—' }}</dd>
            </div>
        </dl>

        @if ($order->shipping_address)
            <section class="order-delivery" aria-labelledby="order-delivery-heading">
                <h2 id="order-delivery-heading">配送先</h2>
                <p>{{ $order->shipping_name }}</p>
                <p>〒{{ $order->shipping_postal_code }} {{ $order->shipping_address }}</p>
                <p>{{ $order->shipping_phone }}</p>
            </section>
        @endif

        <div class="order-lines">
            @foreach ($order->details as $detail)
                <article><img src="{{ $detail->product->imageUrl() }}" alt="{{ $detail->product->name }}">
                    <div>
                        <h2>{{ $detail->product->name }}</h2>
                        <p>数量：{{ $detail->quantity }}点</p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
