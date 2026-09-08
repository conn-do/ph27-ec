@extends('layouts.base')

@section('title', 'カート')

@section('content')
    @if (session('message'))
        <article>{{ session('message') }}</article>
    @endif
    <table>
        @foreach ($items as $item)
            <tr>
                <td>{{ $item['product']->name }}</td>
                <td>{{ $item['product']->price }}</td>
                <td>
                    <form action="/cart/update" method="post">
                        @csrf
                        <input type="hidden" name="productId" value="{{ $item['product']->id }}">
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="10">
                        <button type="submit">変更</button>
                    </form>
                </td>
                <td>
                    <a href="/cart/remove/{{ $item['product']->id }}">削除</a>
                </td>
            </tr>
        @endforeach
    </table>
    @empty($items)
        <p>カートに商品がありません。</p>
    @else
        <form action="/orders" method="post">
            <button type="submit">購入する</button>
        </form>
    @endempty
    <p>合計: {{ $totalPrice }}円</p>
    <a href="/cart/clear">カートを空にする</a>
@endsection