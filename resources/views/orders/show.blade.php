@extends('layouts.base')

@section('title', '注文詳細')

@section('content')
    @if (session('message'))
        <article>{{ session('message') }}</article>
    @endif
    <h1>注文ID: {{ $order->id }}</h1>
    <p>注文日時: {{ $order->created_at->format('Y/m/d H:i') }}</p>
    <p>金額: {{ number_format($order->total_price) }}円</p>
    <p>
        状態:
        @if ($order->is_canceled)
            キャンセル済み
        @else
            注文済み
        @endif
    </p>
    <table>
        @foreach ($order->details as $detail)
            <tr>
                <td>{{ $detail->product->name }}</td>
                <td>{{ $detail->quantity }}個</td>
            </tr>
        @endforeach
    </table>
    @unless ($order->is_canceled)
        <form action="/orders/{{ $order->id }}/cancel" method="post">
            @csrf
            <button type="submit">この注文をキャンセルする</button>
        </form>
    @endunless
@endsection