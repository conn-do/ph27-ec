@extends('layouts.base')

@section('title', '注文詳細')

@section('content')

    <section class="order-detail-page">

        <div class="page-heading">
            <p>ORDER DETAIL</p>
            <h1>注文詳細</h1>
        </div>

        <div class="order-detail-box">

            <div class="order-detail-header">

                <div>
                    <span>ORDER ID</span>
                    <strong>#{{ $order->id }}</strong>
                </div>

                <div>
                    <span>ORDER DATE</span>
                    <strong>{{ $order->created_at->format('Y/m/d H:i') }}</strong>
                </div>

                <div>
                    <span>TOTAL</span>
                    <strong>{{ number_format($order->total_price) }}円</strong>
                </div>

            </div>

            <div class="order-items">

                @foreach ($order->details as $detail)
                    <div class="order-item">

                        <div class="order-item-name">
                            {{ $detail->product->name }}
                        </div>

                        <div class="order-item-quantity">
                            {{ $detail->quantity }}個
                        </div>

                    </div>
                @endforeach

            </div>

            <a href="/orders" class="back-orders-button">
                注文履歴に戻る
            </a>

        </div>

    </section>

@endsection
