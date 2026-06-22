@extends('layouts.base')

@section('title', 'カート')

@section('content')
    <h2>カート</h2>
    @if (session('message'))
        <article>
            {!! session('message') !!}
        </article>
    @endif
    <table>
        @foreach ($cart as $item)
            <tr>
                <td>{{ $item['product']->name }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ $item['total'] }}円</td>
            </tr>
        @endforeach
    </table>
    <form action="{{ route('orders.store') }}" method="post">
        @csrf
        <button type="submit">注文する</button>
    </form>
    <form action="{{ route('cart.clear') }}" method="post">
        @csrf
        <button type="submit">カートを空にする</button>
    </form>
    <a href="{{ route('products.index') }}">買い物を続ける</a>
@endsection
