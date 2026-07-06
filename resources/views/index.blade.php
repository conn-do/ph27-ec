@extends('layouts.base')


@section('title', '商品一覧')

@section('content')
    <section class="news-section">
        <h2>News</h2>
        @forelse ($news as $newsItem)
            <article class="news-card">
                <h3>
                    <a href="{{ route('news.show', $newsItem) }}">
                        {{ $newsItem->title }}
                    </a>
                </h3>
                <time datetime="{{ $newsItem->created_at->toDateString() }}">
                    {{ $newsItem->created_at->format('Y年m月d日') }}
                </time>
            </article>
        @empty
            <p>現在お知らせはありません。</p>
        @endforelse
    </section>

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
@endsection
