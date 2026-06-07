@extends('layouts.base')

@section('title', '注文詳細')

@section('content')
    <h2>注文詳細</h2>
    <p>注文番号: {{ $order->id }}</p>
    <p>注文日: {{ $order->created_at->format('Y/m/d H:i:s') }}</p>
    <p>注文金額: {{ $order->total_price }}円</p>
    <table>
        <tr>
            <th>商品名</th>
            <th>数量</th>
        </tr>
        @foreach ($order->details as $detail)
            <tr>
                <td>{{ $detail->product->name }}</td>
                <td>{{ $detail->quantity }}</td>
            </tr>
        @endforeach
    </table>
@endsection
