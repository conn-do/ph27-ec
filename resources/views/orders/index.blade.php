@extends('layouts.base')

@section('title', '注文履歴')

@section('content')
    <h1>注文履歴</h1>

    @foreach ($orders as $order)
        <article>
            <p>
                ご注文日
                {{ $order->created_at->format('Y.m.d') }}
            </p>

            <p>
                ご注文番号
                {{ $order->id }}
            </p>

            <p>
                合計金額
                {{ number_format($order->total_price) }}円
            </p>

            <p>
                ご注文状況
                注文完了
            </p>

            <a href="/orders/{{ $order->id }}">
                詳細を見る
            </a>
        </article>
    @endforeach
@endsection