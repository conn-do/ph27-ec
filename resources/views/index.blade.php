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

    {{-- ===== 最新ニュース ===== --}}
    <h2>最新ニュース</h2>

    @foreach ($news as $item)
        <div class="news-item">
            <a href="/news/{{ $item->id }}">
                {{ $item->title }}
            </a>
        </div>
    @endforeach

    <hr>

    {{-- ===== 商品一覧 ===== --}}
    @foreach ($products as $product)
        <ul>
            <li>
                <a href="/products/{{ $product->id }}">
                    {{ $product->name }}
                    <img src="{{ $product->imageUrl() }}" width="200">
                </a>
            </li>
        </ul>
    @endforeach

@endsection