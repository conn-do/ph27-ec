@extends('layouts.base')

@section('title', 'カート')

@section('content')
    <h1 class="page-title">カート</h1>
    @if (session('message'))
        <article>{{ session('message') }}</article>
    @endif
    <table>
        @foreach ($items as $item)
            <tr>
                <td>{{ $item['product']->name }}</td>
                <td>{{ number_format($item['product']->price) }}円</td>
                <td>{{ $item['quantity'] }}個</td>
            </tr>
        @endforeach
    </table>
    @empty($items)
        <p class="empty-note">カートに商品がありません。</p>
    @else
        <form action="/orders" method="post">
            @csrf
            <button type="submit">購入する</button>
        </form>
    @endempty
    <p>合計: {{ number_format($totalPrice) }}円</p>
    <a href="/cart/clear">カートを空にする</a>
@endsection
