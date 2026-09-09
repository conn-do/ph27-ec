@extends('layouts.base')

@section('title', '注文完了')

@section('content')
    <div class="content-width narrow-page">
        <section class="empty-state">
            <p class="eyebrow">ORDER COMPLETE</p>
            <h1>ご注文を受け付けました。</h1>
            <p>注文番号: {{ $order->id }}</p>
            <a class="button button--primary" href="{{ route('orders.show', $order) }}">注文内容を見る</a>
        </section>
    </div>
@endsection
