@extends('layouts.base')


@section('title', '商品一覧')

@section('content')
    <h2>商品一覧</h2>
    <form action="/search" method="GET">
        <input type="text" name="keyword" value="{{ request('keyword') }}">
        <input type="submit" value="検索">
    </form>
    @if (request('keyword'))
        <a href="/">検索結果をクリア</a>
    @endif
    @foreach ($products as $product)
        <ul>
            <li>
                <a href="/products/{{ $product->id }}">
                    {{ $product['name'] }}
                    <img src="{{ $product->imageUrl() }}" width="200">
                </a>
            </li>
        </ul>
    @endforeach
    <h3>最新情報</h3>
    <ul>
        <li>
            <a href="/news/1">ニュース1</a>
        </li>
        <li>
            <a href="/news/2">ニュース2</a>
        </li>
    </ul>
    </li>
    </ul>
@endsection
