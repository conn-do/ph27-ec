@extends('layouts.base')

@section('title', '注文履歴')

@section('content')

    <section class="orders-page">

        <div class="page-heading">
            <p>ORDER HISTORY</p>
            <h1>注文履歴</h1>
        </div>

        <div class="orders-box">

            @foreach ($orders as $order)
                <div class="order-card">

                    <div class="order-info">
                        <div>
                            <span>ORDER ID</span>
                            <strong>#{{ $order->id }}</strong>
                        </div>

                        <div>
                            <span>PRICE</span>
                            <strong>{{ $order->total_price }}円</strong>
                        </div>
                    </div>

                    <a href="/orders/{{ $order->id }}" class="order-detail-button">
                        詳細を見る
                    </a>

                </div>
            @endforeach

        </div>

    </section>

@endsection
