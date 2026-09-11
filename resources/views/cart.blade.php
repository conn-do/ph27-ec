@extends('layouts.base')

@section('title', 'カート')

@section('content')
    <section class="cart-page">

        <div class="cart-heading">
            <h2>CART</h2>
            <p>カート</p>
        </div>

        @if (session('message'))
            <div class="cart-message">
                <p>{{ session('message') }}</p>
            </div>
        @endif

        @empty($items)
            <div class="cart-empty">
                <p>カートに商品がありません。</p>
                <a href="/">商品一覧へ戻る</a>
            </div>
        @else

            <div class="cart-content">

                <div class="cart-items">
                    @foreach ($items as $item)
                        <article class="cart-item">

                            <div class="cart-item-image">
                                <img
                                    src="{{ $item['product']->imageUrl() }}"
                                    alt="{{ $item['product']->name }}"
                                >
                            </div>

                            <div class="cart-item-info">

                                <div class="cart-item-name">
                                    <p>{{ $item['product']->name }}</p>
                                </div>

                                <p class="cart-item-price">
                                    {{ number_format($item['product']->price) }}円
                                </p>

                                <form
                                    action="/cart/update"
                                    method="POST"
                                    class="cart-item-quantity"
                                >
                                    @csrf

                                    <input
                                        type="hidden"
                                        name="productId"
                                        value="{{ $item['product']->id }}"
                                    >

                                    <div class="quantity-control">
                                        <span class="quantity-label">数量</span>

                                        <div class="quantity-input">
                                            <button
                                                type="submit"
                                                name="quantity"
                                                value="{{ $item['quantity'] - 1 }}"
                                                class="quantity-button"
                                                @if ($item['quantity'] <= 1) disabled @endif
                                            >
                                                −
                                            </button>

                                            <span class="quantity-value">
                                                {{ $item['quantity'] }}
                                            </span>

                                            <button
                                                type="submit"
                                                name="quantity"
                                                value="{{ $item['quantity'] + 1 }}"
                                                class="quantity-button"
                                                @if ($item['quantity'] >= $item['product']->stock) disabled @endif
                                            >
                                                ＋
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <p class="cart-item-subtotal">
                                    小計
                                    <span>
                                        {{ number_format($item['product']->price * $item['quantity']) }}円
                                    </span>
                                </p>

                                <a
                                    href="/cart/remove?productId={{ $item['product']->id }}"
                                    class="cart-item-remove"
                                >
                                    削除
                                </a>

                            </div>

                        </article>
                    @endforeach
                </div>

                <aside class="cart-summary">

                    <h3>ORDER SUMMARY</h3>

                    <div class="cart-summary-row">
                        <span>商品</span>
                        <span>{{ count($items) }} 件</span>
                    </div>

                    <div class="cart-summary-row">
                        <span>商品合計</span>
                        <span>{{ number_format($totalPrice) }}円</span>
                    </div>

                    <div class="cart-summary-row">
                        <span>送料</span>
                        <span>{{ number_format($shippingFee) }}円</span>
                    </div>

                    <div class="cart-summary-total">
                        <span>合計</span>
                        <strong>{{ number_format($grandTotal) }}円</strong>
                    </div>

                    <p class="cart-shipping-note">
                        ※送料は通常500円、北海道は1,000円、沖縄は1,500円です。
                    </p>

                    @auth
                        <a href="/orders/confirm" class="cart-purchase-button">
                            購入手続きへ
                        </a>
                    @else
                        <p class="cart-login-message">
                            購入するにはログインまたは会員登録が必要です。
                        </p>

                        <a
                            href="{{ route('login') }}"
                            class="cart-purchase-button"
                        >
                            ログインして購入
                        </a>

                        <a
                            href="{{ route('register') }}"
                            class="cart-register-button"
                        >
                            新規会員登録
                        </a>
                    @endauth

                    <a href="/cart/clear" class="cart-clear">
                        カートを空にする
                    </a>

                </aside>

            </div>

        @endempty

    </section>
@endsection