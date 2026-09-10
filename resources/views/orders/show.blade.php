@extends('layouts.base')

@section('title', '注文詳細')

@section('content')
    <h1>注文ID: {{ $order->id }}</h1>

    <p>注文日時: {{ $order->created_at->format('Y/m/d H:i') }}</p>

    <h3>商品</h3>

    <table>
        @foreach ($order->details as $detail)
            <tr>
                <td>{{ $detail->product->name }}</td>
                <td>{{ $detail->quantity }}個</td>
                <td>{{ number_format($detail->product->price * $detail->quantity) }}円</td>
            </tr>
        @endforeach
    </table>

    @php
        $productTotal = 0;

        foreach ($order->details as $detail) {
            $productTotal += $detail->product->price * $detail->quantity;
        }

        $shippingFee = $order->total_price - $productTotal;
    @endphp

    <p>商品合計: {{ number_format($productTotal) }}円</p>
    <p>送料: {{ number_format($shippingFee) }}円</p>
    <p>合計: {{ number_format($order->total_price) }}円</p>

    <a href="/orders">注文履歴に戻る</a>
@endsection