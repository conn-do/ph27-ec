@extends('layouts.base')

@section('title', '注文履歴')

@section('content')

    <div class="order-card">

        <h1 class="cart-title">
            注文履歴
        </h1>

        <table class="cart-table">
            <thead>
                <tr>
                    <th>注文番号</th>
                    <th>合計金額</th>
                    <th>詳細</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>¥{{ number_format($order->total_price) }}</td>
                        <td>
                            <a href="/orders/{{ $order->id }}" class="order-link">
                                詳細を見る
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

@endsection
