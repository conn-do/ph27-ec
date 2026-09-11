@extends('layouts.base')

@section('title', '購入確認')

@section('content')

    <section class="order-confirm-page">

        <div class="order-confirm-heading">
            <h2>購入確認</h2>
            <p>ご注文内容をご確認ください</p>
        </div>

        <div class="order-confirm-content">

            <div class="order-confirm-left">

                <section class="order-confirm-section">
                    <h3>配送先</h3>

                    <div class="order-confirm-address">
                        <p>
                            郵便番号
                            <span>{{ $address->postal_code }}</span>
                        </p>

                        <p>
                            住所
                            <span>{{ $address->address }}</span>
                        </p>
                    </div>
                </section>

                <section class="order-confirm-section">
                    <h3>商品</h3>

                    <div class="order-confirm-products">
                        @foreach ($items as $item)
                            <article class="order-confirm-product">

                                <div class="order-confirm-product-name">
                                    <p>{{ $item['product']->name }}</p>
                                    <span>{{ $item['quantity'] }}個</span>
                                </div>

                                <p class="order-confirm-product-price">
                                    {{ number_format($item['product']->price * $item['quantity']) }}円
                                </p>

                            </article>
                        @endforeach
                    </div>
                </section>

            </div>

            <aside class="order-confirm-summary">

                <h3>注文内容</h3>

                <div class="order-confirm-summary-row">
                    <span>商品合計</span>
                    <span>{{ number_format($totalPrice) }}円</span>
                </div>

                <div class="order-confirm-summary-row">
                    <span>送料</span>
                    <span>{{ number_format($shippingFee) }}円</span>
                </div>

                <div class="order-confirm-summary-total">
                    <span>合計</span>
                    <strong>{{ number_format($grandTotal) }}円</strong>
                </div>

                <form action="/orders" method="POST">
                    @csrf

                    <button type="submit">
                        この内容で購入する
                    </button>
                </form>

                <a href="/cart" class="order-confirm-back">
                    カートに戻る
                </a>

            </aside>

        </div>

    </section>

@endsection