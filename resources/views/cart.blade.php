@extends('layouts.base')

@section('title', 'カート')

@section('content')
    @if (session('message'))
        <article>{{ session('message') }}</article>
    @endif

    @empty($items)
        <p>カートに商品がありません。</p>
    @else

        @foreach ($items as $item)
            <article>
                <div>
                    <img src="{{ $item['product']->imageUrl() }}" width="200">
                </div>

                <div>
                    <p>{{ $item['product']->name }}</p>

                    <p>{{ number_format($item['product']->price) }}円</p>

                    <form action="/cart/update" method="POST">
                        @csrf

                        <input
                            type="hidden"
                            name="productId"
                            value="{{ $item['product']->id }}"
                        >

                        <button
                            type="submit"
                            name="quantity"
                            value="{{ $item['quantity'] - 1 }}"
                            @if ($item['quantity'] <= 1) disabled @endif
                        >
                            −
                        </button>

                        <span>{{ $item['quantity'] }}</span>

                        <button
                            type="submit"
                            name="quantity"
                            value="{{ $item['quantity'] + 1 }}"
                            @if ($item['quantity'] >= $item['product']->stock) disabled @endif
                        >
                            ＋
                        </button>
                    </form>

                    <p>
                        小計：
                        {{ number_format($item['product']->price * $item['quantity']) }}円
                    </p>

                    <a href="/cart/remove?productId={{ $item['product']->id }}">
                        削除
                    </a>
                </div>
            </article>
        @endforeach

        <div>
            <h3>注文内容</h3>

            <p>{{ count($items) }} 件</p>

            <p>
                商品合計
                {{ number_format($totalPrice) }}円
            </p>

            <p>
                送料
                {{ number_format($shippingFee) }}円
            </p>

            <p>
                <strong>合計</strong>
                <strong>{{ number_format($grandTotal) }}円</strong>
            </p>
        </div>

        <div>
            <small>
                ※送料は通常500円、北海道は1,000円、沖縄は1,500円です。
            </small>
        </div>

        @auth
            <a href="/orders/confirm">
                購入手続きへ
            </a>
        @else
            <p>購入するにはログインまたは会員登録が必要です。</p>

            <a href="{{ route('login') }}">
                ログインして購入
            </a>

            <a href="{{ route('register') }}">
                新規会員登録
            </a>
        @endauth

    @endempty

    <a href="/cart/clear">カートを空にする</a>
@endsection