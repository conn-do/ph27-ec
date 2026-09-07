@extends('layouts.base')
@section('title', '商品一覧')
@section('content')
    <section class="hero">
        <div><p class="eyebrow">TOOLS FOR YOUR NEXT IDEA</p><h1>いつもの机に、<br>新しいひらめき。</h1><p>手になじむペン。思いを残すノート。<br>毎日使いたくなる、お気に入りを見つけよう。</p><a class="button" href="#products">文房具を見つける ↗</a></div>
        <div class="hero-image"><span class="hero-label">WRITE YOUR STORY</span><img src="{{ asset('images/products/note.png') }}" alt="毎日のアイデアを書き留めるノート"><span class="hero-caption">暮らしに寄り添う、文具のかたち。</span></div>
    </section>
    <section id="products">
        <div class="section-heading"><div><p class="eyebrow">OUR COLLECTION</p><h2>お気に入りの文房具</h2></div><span>{{ $products->count() }} items</span></div>
        <div class="catalog-toolbar"><div class="categories"><a class="chip" href="{{ route('home') }}#products">すべて</a>@foreach ($categories as $category)<a class="chip" href="{{ route('categories.show', $category) }}">{{ $category->name }}</a>@endforeach</div>
        <form class="search" action="{{ route('products.search') }}" method="GET"><label class="sr-only" for="keyword">商品名で検索</label><input id="keyword" type="search" name="keyword" value="{{ request('keyword') }}" placeholder="商品名で検索"><button type="submit">検索</button></form></div>
        @if (request('keyword'))<p>「{{ request('keyword') }}」の検索結果 <a href="{{ route('home') }}">検索をクリア</a></p>@endif
        <div class="product-grid">@forelse ($products as $product)<x-product-card :product="$product" />@empty<div class="empty-state"><h3>商品が見つかりませんでした</h3><p>別のキーワードで検索してみてください。</p></div>@endforelse</div>
    </section>
    <section class="news-section"><div class="section-heading"><div><p class="eyebrow">JOURNAL & NEWS</p><h2>お店からのお知らせ</h2></div></div>@forelse ($news as $item)<a class="news-row" href="/news/{{ $item->id }}"><time>{{ $item->created_at->format('Y.m.d') }}</time><span>{{ $item->title }}</span><span>↗</span></a>@empty<p>お知らせはまだありません。</p>@endforelse</section>
@endsection
