@extends('layouts.base')

@section('content')
    @if (session('message'))
        <article>{{ session('message') }}</article>
    @endif
    @if ($order)
        <p>注文ID: {{ $order->id }}</p>
    @endif
@endsection
