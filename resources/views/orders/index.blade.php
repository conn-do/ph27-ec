@extends('layouts.base')

@section('title', '注文履歴')

@section('content')
    @if (session('message'))
        <article>{{ session('message') }}</article>
    @endif
    <h1>注文履歴</h1>
    <table>
        @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->total_price }}円</td>
                <td>
                    @if ($order->is_canceled)
                        キャンセル済み
                    @else
                        注文済み
                    @endif
                </td>
                <td>
                    <a href="/orders/{{ $order->id }}">詳細</a>
                </td>
            </tr>
        @endforeach
    </table>
@endsection