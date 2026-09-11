@extends('layouts.base')

@section('title', '注文履歴')

@section('content')

    <section class="order-history">

        <div class="order-history-heading">
            <h2>ORDER HISTORY</h2>
            <p>注文履歴</p>
        </div>

        <div class="order-history-list">

            @foreach ($orders as $order)
                <article class="order-history-item">

                    <div class="order-history-info">

                        <div class="order-history-row">
                            <span>ご注文日</span>
                            <strong>{{ $order->created_at->format('Y.m.d') }}</strong>
                        </div>

                        <div class="order-history-row">
                            <span>ご注文番号</span>
                            <strong>{{ $order->id }}</strong>
                        </div>

                        <div class="order-history-row">
                            <span>合計金額</span>
                            <strong>{{ number_format($order->total_price) }}円</strong>
                        </div>

                        <div class="order-history-row">
                            <span>ご注文状況</span>
                            <strong>注文完了</strong>
                        </div>

                    </div>

                    <a
                        href="/orders/{{ $order->id }}"
                        class="order-history-detail"
                    >
                        <span>詳細を見る</span>
                        <span class="order-history-arrow">→</span>
                    </a>

                </article>
            @endforeach

        </div>

    </section>

@endsection