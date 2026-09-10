@extends('layouts.base')

@section('title', 'カート')

@section('content')

    <div class="cart-page">

        {{-- =========================
         Header
    ========================= --}}

        <section class="cart-header">

            <p class="cart-label">
                PH27 STATIONERY / SHOPPING CART
            </p>

            <h1 class="cart-title">
                CART
            </h1>

            <p class="cart-subtitle">
                お選びいただいた商品
            </p>

        </section>


        {{-- =========================
         Message
    ========================= --}}

        @if (session('message'))
            <div class="cart-message">
                {!! session('message') !!}
            </div>
        @endif


        {{-- =========================
         Empty Cart
    ========================= --}}

        @if (empty($items))

            <section class="empty-cart">

                <p class="empty-cart-title">
                    カートに商品がありません。
                </p>

                <p class="empty-cart-text">
                    気になる文房具を探してみてください。
                </p>

            </section>

            <div class="cart-empty-back">

                <a href="/" class="cart-back-link">
                    ← BACK TO CATALOG
                </a>

            </div>
        @else
            {{-- =========================
             Cart Items
        ========================= --}}

            <div class="cart-table-wrap">

                <table class="cart-table">

                    <thead>
                        <tr>

                            <th>
                                PRODUCT
                            </th>

                            <th>
                                PRICE
                            </th>

                            <th>
                                QUANTITY
                            </th>

                            <th>
                                ACTION
                            </th>

                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($items as $item)
                            <tr>

                                {{-- Product --}}

                                <td data-label="PRODUCT">

                                    <div class="cart-product-name">
                                        {{ $item['product']->name }}
                                    </div>

                                </td>


                                {{-- Price --}}

                                <td data-label="PRICE">

                                    <span class="cart-price">
                                        ¥{{ number_format($item['product']->price) }}
                                    </span>

                                </td>


                                {{-- Quantity --}}

                                <td data-label="QUANTITY">

                                    <form action="/cart/item/{{ $item['product']->id }}" method="POST"
                                        class="quantity-form">

                                        @csrf
                                        @method('PATCH')

                                        <div class="quantity-control">

                                            <button type="button" class="quantity-button"
                                                onclick="changeQuantity(this, -1)" aria-label="数量を減らす">
                                                −
                                            </button>


                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                                                min="1" max="10" class="cart-quantity-input" aria-label="数量">


                                            <button type="button" class="quantity-button" onclick="changeQuantity(this, 1)"
                                                aria-label="数量を増やす">
                                                ＋
                                            </button>

                                        </div>

                                    </form>

                                </td>


                                {{-- Delete --}}

                                <td data-label="ACTION">

                                    <form action="/cart/item/{{ $item['product']->id }}" method="POST" class="delete-form">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="delete-button">
                                            <span class="delete-icon">×</span>
                                            削除
                                        </button>

                                    </form>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- =========================
             Bottom
        ========================= --}}

            <div class="cart-bottom">

                <div class="cart-actions-left">

                    <a href="/" class="cart-back-link">
                        ← BACK TO CATALOG
                    </a>


                    <a href="/cart/clear" class="clear-cart-link">
                        カートを空にする
                    </a>

                </div>


                <div class="cart-summary">

                    <div class="summary-row">

                        <span class="summary-label">
                            TOTAL
                        </span>

                        <span class="summary-price">
                            ¥{{ number_format($totalPrice) }}
                        </span>

                    </div>


                    <form action="/orders" method="POST" class="purchase-form">

                        @csrf

                        <button type="submit" class="purchase-button">
                            CHECKOUT
                        </button>

                    </form>

                </div>

            </div>

        @endif

    </div>


    <script>
        function changeQuantity(button, change) {

            const form = button.closest('.quantity-form');
            const input = form.querySelector('.cart-quantity-input');

            let quantity = parseInt(input.value) || 1;

            quantity += change;

            if (quantity < 1) {
                quantity = 1;
            }

            if (quantity > 10) {
                quantity = 10;
            }

            input.value = quantity;

            clearTimeout(form.updateTimer);

            form.updateTimer = setTimeout(() => {
                form.submit();
            }, 400);
        }
    </script>

@endsection
