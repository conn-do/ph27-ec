@extends('layouts.base')

@section('title', '注文履歴')

@section('content')
    <section class="orders-page" aria-labelledby="orders-heading">
        <div class="page-heading">
            <h1 id="orders-heading">注文履歴</h1>
            <a href="{{ route('mypage') }}">マイページに戻る</a>
        </div>

        @if ($orders->isEmpty())
            <div class="empty-state">
                <p>注文履歴はまだありません。</p>
                <a href="{{ route('home') }}">商品一覧を見る</a>
            </div>
        @else
            <div class="order-history-list">
                @foreach ($orders as $order)
                    <article class="order-history-card">
                        <div class="order-card-heading">
                            <div>
                                <h2>注文番号 #{{ $order->id }}</h2>
                                <time datetime="{{ $order->created_at->toDateString() }}">
                                    注文日：{{ $order->created_at->format('Y.m.d') }}
                                </time>
                            </div>
                            <a href="{{ route('orders.show', $order) }}">詳細を見る</a>
                        </div>

                        <div class="order-card-items">
                            @foreach ($order->details as $detail)
                                <div class="order-card-item">
                                    <a href="{{ route('products.show', $detail->product) }}">
                                        <img src="{{ $detail->product->imageUrl() }}" alt="{{ $detail->product->name }}">
                                    </a>
                                    <div class="order-card-item-info">
                                        <h3>
                                            <a href="{{ route('products.show', $detail->product) }}">
                                                {{ $detail->product->name }}
                                            </a>
                                        </h3>
                                        <span>数量：{{ $detail->quantity }}点</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="order-card-total">
                            <span>合計金額</span>
                            <strong>¥{{ number_format($order->total_price) }}</strong>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
