@extends('layouts.base')

@section('title', 'カート')

@section('content')

    <div class="page-wrapper">

        <div class="cart-card">

            <h1 class="cart-title">
                ショッピングカート
            </h1>

            @if (session('message'))
                <div class="cart-message">
                    {{ session('message') }}
                </div>
            @endif

            @if (empty($items))
                <div class="empty-cart">
                    カートに商品がありません。
                </div>
            @else
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>商品名</th>
                            <th>価格</th>
                            <th>数量</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item['product']->name }}</td>
                                <td>¥{{ number_format($item['product']->price) }}</td>
                                <td>{{ $item['quantity'] }}個</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="cart-total">
                    合計 ¥{{ number_format($totalPrice) }}
                </div>

                <div class="cart-buttons">

                    <form action="/orders" method="POST">
                        @csrf
                        <button type="submit" class="buy-btn">
                            購入する
                        </button>
                    </form>

                    <a href="/cart/clear" class="clear-btn">
                        カートを空にする
                    </a>

                </div>

            @endif

        </div>
    </div>

@endsection
