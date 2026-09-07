@extends('layouts.base')

@section('title', '注文履歴')

@section('content')
    <div class="content-width narrow-page">
        <div class="page-heading">
            <div>
                <p class="eyebrow">ORDER HISTORY</p>
                <h1>注文履歴</h1>
            </div>
        </div>

        <section class="order-list">
            @forelse ($orders as $order)
                <article class="order-row">
                    <div>
                        <p class="product-category">ORDER #{{ $order->id }}</p>
                        <h2>{{ $order->created_at->format('Y年m月d日 H:i') }}</h2>
                    </div>
                    <p class="price">&yen;{{ number_format($order->total_price) }}</p>
                    <a class="button button--outline" href="{{ route('orders.show', $order) }}">詳細を見る</a>
                </article>
            @empty
                <section class="empty-state">
                    <h2>まだ注文はありません。</h2>
                    <p>最初の注文をすると、ここで履歴を確認できます。</p>
                    <a class="button button--primary" href="{{ route('home') }}">商品を見る</a>
                </section>
            @endforelse
        </section>
    </div>
@endsection
