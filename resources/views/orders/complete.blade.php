@extends('layouts.base')

@section('content')
    @if (session('message'))
        <article>{{ session('message') }}</article>
    @endif
    @if ($order)
        <p>注文ID: {{ $order->id }}</p>
        <h2>お届け先</h2>
        <p>{{ $order->shipping_name }} 様</p>
        <p>〒{{ $order->shipping_postal_code }}</p>
        <p>{{ $order->shipping_address }}</p>
        <p>{{ $order->shipping_phone }}</p>
    @endif
@endsection
