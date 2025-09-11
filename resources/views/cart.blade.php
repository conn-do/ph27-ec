@extends('layouts.base')

@section('content')
    <div class="cart-container">
        <h1 class="cart-title">ショッピングカート</h1>

        @if (count($items) > 0)
            <div class="cart-items">
                @foreach ($items as $item)
                    <div class="cart-item">
                        <div class="item-image">
                            <img src="{{ $item['product']->image }}" alt="{{ $item['product']->name }}">
                        </div>
                        <div class="item-details">
                            <h3 class="item-name">{{ $item['product']->name }}</h3>
                            <p class="item-price">{{ $item['product']->price }}円</p>
                            <p class="item-quantity">数量: {{ $item['quantity'] }}個</p>
                            <p class="item-subtotal">小計: {{ $item['product']->price * $item['quantity'] }}円</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="cart-summary">
                <div class="total-section">
                    <h2 class="total-label">合計金額</h2>
                    <p class="total-price">{{ $totalPrice }}円</p>
                </div>

                <div class="cart-actions">
                    <form action="{{ route('cart.destroy') }}" method="POST" class="clear-cart-form">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="カートを空にする" class="clear-cart-btn">
                    </form>

                    <form action="{{ route('order') }}" method="POST" class="checkout-form">
                        @csrf
                        <input type="submit" value="購入する" class="checkout-btn">
                    </form>
                </div>
            </div>
        @else
            <div class="empty-cart">
                <h2>カートが空です</h2>
                <p>商品をカートに追加してください</p>
                <a href="{{ route('products.index') }}" class="continue-shopping-btn">商品一覧に戻る</a>
            </div>
        @endif
    </div>
@endsection
