@extends('layouts.base')
@section('title', '注文履歴')
@section('content')
    <div class="page-heading">
        <p class="eyebrow">
            YOUR ORDERS
        </p>
        <h1>
            注文履歴
        </h1>
        <p>
            これまでに選んだ、お気に入りの道具たち。
        </p>
    </div>
    <div class="order-history">
        @forelse($orders as $order)
            <a class="order-row" href="{{ route('orders.show', $order) }}">
                <div>
                    <small>
                        {{ $order->created_at->format('Y.m.d H:i') }}
                    </small>
                    <h2>
                        注文 #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                    </h2>
                </div>
                <span>
                    受付済み
                </span>
                <strong>
                    ¥{{ number_format($order->total_price) }}
                </strong>
                <span>
                    詳細を見る ↗
                </span>
            </a>
        @empty
            <div class="empty-state">
                <h2>
                    まだご注文はありません。
                </h2>
                <p>
                    お気に入りの商品を探してみましょう。
                </p>
                <a class="button" href="{{ route('home') }}#collection">
                    商品を見る ↗
                </a>
            </div>
        @endforelse
    </div>
    <x-shop-pagination :paginator="$orders" />
@endsection
