@extends('layouts.base')

@section('title', 'カート')

@section('content')
    <section class="page-heading compact-heading">
        <p class="eyebrow">SHOPPING CART</p>
        <h1>カート</h1>
    </section>

    @if (session('message'))
        <article class="notice">{{ session('message') }}</article>
    @endif
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <article class="error">{{ $error }}</article>
        @endforeach
    @endif

    @if (empty($items))
        <p>カートに商品がありません。</p>
    @else
        <table class="cart-table">
            <thead>
                <tr>
                    <th>商品</th>
                    <th>価格</th>
                    <th>数量</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item['product']->name }}</td>
                        <td>{{ number_format($item['product']->price) }}円</td>
                        <td>{{ $item['quantity'] }}個</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="cart-total">合計: {{ number_format($totalPrice) }}円</p>
        <form action="/orders" method="post">
            @csrf
            <button type="submit">購入する</button>
        </form>
    @endif

    <a class="clear-link" href="/cart/clear">カートを空にする</a>
@endsection
