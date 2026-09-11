@extends('layouts.base')

@section('title', '注文詳細')

@section('content')

    <section class="order-detail-page">

        <div class="order-detail-heading">
            <h2>ORDER DETAIL</h2>
            <p>注文詳細</p>
        </div>

        <div class="order-detail-content">

            <div class="order-detail-main">

                <div class="order-detail-info">
                    <div class="order-detail-info-row">
                        <span>注文番号</span>
                        <strong>{{ $order->id }}</strong>
                    </div>

                    <div class="order-detail-info-row">
                        <span>注文日時</span>
                        <strong>{{ $order->created_at->format('Y.m.d H:i') }}</strong>
                    </div>

                    <div class="order-detail-info-row">
                        <span>ご注文状況</span>
                        <strong>注文完了</strong>
                    </div>
                </div>

                <section class="order-detail-products">

                    <h3>商品</h3>

                    <div class="order-detail-product-list">

                        @foreach ($order->details as $detail)
                            <article class="order-detail-product">

                                <div class="order-detail-product-image">
                                    <img
                                        src="{{ $detail->product->imageUrl() }}"
                                        alt="{{ $detail->product->name }}"
                                    >
                                </div>

                                <div class="order-detail-product-info">

                                    <div class="order-detail-product-name">
                                        <p>{{ $detail->product->name }}</p>
                                        <span>{{ $detail->quantity }}個</span>
                                    </div>

                                    <p class="order-detail-product-price">
                                        {{ number_format($detail->product->price * $detail->quantity) }}円
                                    </p>

                                </div>

                            </article>
                        @endforeach

                    </div>

                </section>

            </div>

            @php
                $productTotal = 0;

                foreach ($order->details as $detail) {
                    $productTotal += $detail->product->price * $detail->quantity;
                }

                $shippingFee = $order->total_price - $productTotal;
            @endphp

            <aside class="order-detail-summary">

                <h3>注文内容</h3>

                <div class="order-detail-summary-row">
                    <span>商品合計</span>
                    <span>{{ number_format($productTotal) }}円</span>
                </div>

                <div class="order-detail-summary-row">
                    <span>送料</span>
                    <span>{{ number_format($shippingFee) }}円</span>
                </div>

                <div class="order-detail-summary-total">
                    <span>合計</span>
                    <strong>{{ number_format($order->total_price) }}円</strong>
                </div>

            </aside>

        </div>

        <div class="order-detail-back">
            <a href="/orders">
                <span>←</span>
                注文履歴に戻る
            </a>
        </div>

    </section>

@endsection