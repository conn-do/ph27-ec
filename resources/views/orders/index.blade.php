@extends('layouts.base')

@section('title', '注文履歴')

@section('content')
    <h1>注文履歴</h1>
    <table>
        @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->total_price }}円</td>
            </tr>
        @endforeach
    </table>
@endsection
