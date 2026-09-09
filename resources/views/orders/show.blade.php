@extends('layouts.base')

@section('title', '注文詳細')

@section('content')
    <div class="content-width narrow-page">
        <a class="back-link" href="{{ route('orders.index') }}">注文履歴に戻る</a>
        <div class="page-heading">
            <div>
                <p class="eyebrow">ORDER #{{ $order->id }}</p>
                <h1>ご注文内容</h1>
                <p>{{ $order->created_at->format('Y年m月d日 H:i') }}</p>
            </div>
        </div>

        @if (session('message'))
            <div class="notice" role="status">{{ session('message') }}</div>
        @endif

        <section class="order-detail-list">
            @foreach ($order->details as $detail)
                <article class="order-detail-row">
                    <img src="{{ $detail->product->imageUrl() }}" alt="{{ $detail->product->name }}">
                    <div>
                        <h2>{{ $detail->product->name }}</h2>
                        <p>{{ $detail->quantity }}個</p>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="order-total">
            <p>お支払い合計</p>
            <p class="price price--large">&yen;{{ number_format($order->total_price) }}</p>
        </section>
    </div>
@endsection
