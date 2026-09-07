@extends('layouts.base')
@section('title', 'ご注文ありがとうございます')
@section('content')
    <div class="empty-state"><p class="eyebrow">THANK YOU</p><h1>ご注文ありがとうございます。</h1><p>注文番号：{{ $order->id }} · 合計 ¥{{ number_format($order->total_price) }}</p><p>注文内容は注文履歴から確認できます。</p><a class="button" href="/orders/{{ $order->id }}">注文内容を確認する →</a></div>
@endsection
