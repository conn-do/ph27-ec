@extends('layouts.base')

@section('title', '注文詳細')

@section('content')
    <section class="page-heading compact-heading">
        <p class="eyebrow">ORDER DETAIL</p>
        <h1>注文ID: #{{ $order->id }}</h1>
        <p>注文日時: {{ $order->created_at->format('Y/m/d H:i') }}</p>
        <p class="cart-total">金額: {{ number_format($order->total_price) }}円</p>
    </section>

    <table class="data-table">
        <thead>
            <tr>
                <th>商品</th>
                <th>数量</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->details as $detail)
                <tr>
                    <td>{{ $detail->product->name }}</td>
                    <td>{{ $detail->quantity }}個</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a class="clear-link" href="/orders">注文履歴へ戻る</a>
@endsection
