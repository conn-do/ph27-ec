@extends('layouts.base')

@section('title', 'カート')

@section('content')
    @if (session('message'))
        <article>{{ session('message') }}</article>
    @endif
    @error('quantity')
        <p style="color: red;">{{ $message }}</p>
    @enderror
    <table>
        @foreach ($items as $item)
            <tr>
                <td>{{ $item['product']->name }}</td>
                <td>{{ $item['product']->price }}</td>
                <td>
                    <form action="/cart/{{ $item['product']->id }}" method="post">
                        @csrf
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="10">
                        <button type="submit">変更</button>
                    </form>
                </td>
                <td>
                    <a href="/cart/{{ $item['product']->id }}/remove">削除</a>
                </td>
            </tr>
        @endforeach
    </table>
    @empty($items)
        <p>カートに商品がありません。</p>
    @else
        <a href="/orders/create">購入する</a>
    @endempty
    <p>合計: {{ $totalPrice }}円</p>
    <a href="/cart/clear">カートを空にする</a>
@endsection
