@extends('layouts.base')

@section('title', '注文履歴')

@section('content')
    <h2>注文履歴</h2>
    <table>
        @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->total_price }}</td>
                <td>{{ $order->created_at }}</td>
            </tr>
        @endforeach
    </table>
@endsection
