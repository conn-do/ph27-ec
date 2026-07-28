@extends('layouts.base')

@section('title', '注文詳細')

@section('content')
    <h1>注文ID: {{ $order->id }}</h1>
    <p>注文日時: {{ $order->created_at->format('Y/m/d H:i') }}</p>
    <p>金額: {{ number_format($order->total_price) }}円</p>
    <table>
        @foreach ($order->details as $detail)
            <tr>
                <td>{{ $detail->product->name }}</td>
                <td>{{ $detail->quantity }}個</td>
            </tr>
        @endforeach
    </table>
@endsection
