@extends('layouts.base')

@section('title', '注文履歴')

@section('content')
    <section class="page-heading compact-heading">
        <p class="eyebrow">ORDER HISTORY</p>
        <h1>注文履歴</h1>
    </section>

    @if ($orders->isEmpty())
        <p>注文履歴はまだありません。</p>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>注文ID</th>
                    <th>金額</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ number_format($order->total_price) }}円</td>
                        <td>
                            <a class="table-link" href="/orders/{{ $order->id }}">詳細</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
