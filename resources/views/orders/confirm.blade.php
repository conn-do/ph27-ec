@extends('layouts.base')

@section('title', '購入確認')

@section('content')

    <h2>購入確認</h2>

    <h3>配送先</h3>

    <p>郵便番号：{{ $address->postal_code }}</p>
    <p>住所：{{ $address->address }}</p>

    <h3>商品</h3>

    @foreach ($items as $item)
        <p>
            {{ $item['product']->name }}
            {{ $item['quantity'] }}個
        </p>

        <p>
            {{ $item['product']->price * $item['quantity'] }}円
        </p>
    @endforeach

    <p>商品合計：{{ $totalPrice }}円</p>
    <p>送料：{{ $shippingFee }}円</p>
    <p>合計：{{ $grandTotal }}円</p>

    <form action="/orders" method="POST">
        @csrf

        <button type="submit">この内容で購入する</button>
    </form>

    <a href="/cart">カートに戻る</a>

@endsection