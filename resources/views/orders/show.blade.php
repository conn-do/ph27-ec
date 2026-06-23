@extends('layouts.base')

@section('title', '注文詳細')

@section('content')
    <h1>注文ID: {{ $order->id }}</h1>
    <table>
        @foreach ($order->details as $detail)
            <tr>
                <td>{{ $detail->product->name }}</td>
                <td>{{ $detail->quantity }}個</td>
            </tr>
        @endforeach
    </table>
@endsection
