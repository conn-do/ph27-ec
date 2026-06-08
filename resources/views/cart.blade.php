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
                <td>{{ $item['quantity'] }}個</td>
            </tr>
        @endforeach
    </table>
    @empty($items)
        <p>カートに商品がありません。</p>
    @endempty
    <p>合計: {{ $totalPrice }}円</p>
    <a href="/cart/clear">カートを空にする</a>
@endsection
