@extends('layouts.base')

@section('title', '購入完了')

@section('content')

    <section class="order-complete-page">

        <div class="order-complete-heading">
            <h2>THANK YOU</h2>
            <p>ご購入ありがとうございました</p>
        </div>

        <div class="order-complete-content">

            <p class="order-complete-message">
                ご注文を受け付けました。<br>
                商品の到着を楽しみにお待ちください。
            </p>

            @if ($order)
                <div class="order-complete-order">
                    <p>注文ID</p>
                    <span>{{ $order->id }}</span>
                </div>
            @endif

            @if (session('message'))
                <p class="order-complete-session">
                    {{ session('message') }}
                </p>
            @endif

            <a href="/" class="order-complete-button">
                TOPへ戻る
            </a>

        </div>

    </section>

@endsection