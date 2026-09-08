@extends('layouts.base')

@section('title', '注文詳細')


@section('content')
    @if (session('message'))
        <p>{{ session('message') }}</p>
    @endif

    <h1>注文ID: {{ $order->id }}</h1>
    <p>注文日時: {{ $order->created_at->format('Y/m/d H:i') }}</p>
    <p>金額: {{ number_format($order->total_price) }}円</p>
    <p>ステータス: {{ $order->status->label() }}</p>

    <h2>お届け先</h2>
    <p>{{ $order->shipping_name }} 様</p>
    <p>〒{{ $order->shipping_postal_code }}</p>
    <p>{{ $order->shipping_address }}</p>
    <p>{{ $order->shipping_phone }}</p>

    @if ($order->status === \App\Enums\OrderStatus::Pending)
        <form action="/orders/{{ $order->id }}/cancel" method="POST">
            @csrf
            <button type="submit">注文をキャンセルする</button>
        </form>
    @endif

    <table>
        @foreach ($order->details as $detail)
            <tr>
                <td>{{ $detail->product->name }}</td>
                <td>{{ $detail->quantity }}個</td>
            </tr>
        @endforeach
    </table>
@endsection
