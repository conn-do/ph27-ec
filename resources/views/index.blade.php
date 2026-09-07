@extends('layouts.base')
@section('title', $category?->name ?? ($keyword !== '' ? '検索結果' : '暮らしに、書く余白を。'))
@section('content')
    @if (!$category && $keyword === '' && !request('page') && !request('sort'))
        <section class="hero" aria-labelledby="hero-title">
            <div class="hero-copy">
                <p class="eyebrow">
                    LITTLE TOOLS, GOOD DAYS.
                </p>
                <h1 id="hero-title">
                    暮らしに、
                    <br>
                    書く余白を。
                </h1>
                <p>
                    思いつきを書き留める。大切な人に想いを贈る。
                    <br>
                    何気ない毎日が、少し好きになる文房具。
                </p>
                <a class="button" href="#collection">
                    お気に入りを見つける
                    <span aria-hidden="true">
                        ↗
                    </span>
                </a>
                <div class="hero-footnote">
                    01 / THE EVERYDAY COLLECTION
                </div>
            </div>
            <div class="hero-image">
                <img src="{{ asset('images/products/note.png') }}" alt="柔らかな光の中に置かれたブルーのノートとペン" width="1536" height="1024" fetchpriority="high">
                <span>
                    日々に寄り添う、一冊を。
                </span>
            </div>
        </section>
        <div class="values-strip">
            <span>
                長く使いたくなる道具
            </span>
            <span>
                すべて税込価格でご案内
            </span>
            <span>
                ¥{{ number_format(config('shop.free_shipping_threshold')) }}以上で送料無料
            </span>
        </div>
    @else
        <div class="page-heading">
            <p class="eyebrow">
                THE COLLECTION
            </p>
            <h1>
                {{ $category?->name ?? '検索結果' }}
            </h1>
            <p>
                {{ $keyword !== '' ? '「'.$keyword.'」にぴったりの文房具を。' : '毎日のために、選びたいもの。' }}
            </p>
        </div>
    @endif
    <section id="collection" class="collection" aria-labelledby="collection-title">
        <div class="section-heading">
            <div>
                <p class="eyebrow">
                    OUR SELECTION
                </p>
                <h2 id="collection-title">
                    お気に入りの文房具
                </h2>
            </div>
            <span>
                {{ $products->total() }} items
            </span>
        </div>
        <nav class="category-nav" aria-label="商品カテゴリー">
            <a href="{{ route('home') }}#collection" @if(!$category) aria-current="page" @endif>
                すべて
            </a>
            @foreach($categories as $item)
                <a href="{{ route('categories.show', $item) }}#collection" @if($category?->
                    id === $item->id) aria-current="page"
                @endif
                >{{ $item->name }}
                <small>
                    {{ $item->products_count }}
                </small>
            </a>
        @endforeach
    </nav>
    <form class="catalog-tools" method="get" action="{{ $category ? route('categories.show', $category) : route('products.search') }}#collection" role="search">
        <div class="search-field">
            <label class="sr-only" for="keyword">
                商品名で検索
            </label>
            <input id="keyword" type="search" name="keyword" value="{{ $keyword }}" placeholder="ノート、ペンなどを検索" maxlength="100">
            <button type="submit" aria-label="商品を検索">
                検索
                <span aria-hidden="true">
                    ↗
                </span>
            </button>
        </div>
        <div class="sort-field">
            <label for="sort">
                表示順
            </label>
            <select id="sort" name="sort">
                <option value="newest" @selected($sort === 'newest')>
                    新着順
                </option>
                <option value="price_asc" @selected($sort === 'price_asc')>
                    価格の低い順
                </option>
                <option value="price_desc" @selected($sort === 'price_desc')>
                    価格の高い順
                </option>
            </select>
            <button class="text-button" type="submit">
                適用
            </button>
        </div>
    </form>
    @if($keyword !== '')
        <p class="filter-summary">
            「{{ $keyword }}」の検索結果
            <a href="{{ $category ? route('categories.show', $category) : route('home') }}#collection">
                検索をクリア ×
            </a>
        </p>
    @endif
    <div class="product-grid">
        @forelse($products as $product)
            <x-product-card :product="$product" />
        @empty
            <div class="empty-state">
                <p class="eyebrow">
                    NO ITEMS FOUND
                </p>
                <h3>
                    商品が見つかりませんでした。
                </h3>
                <p>
                    別のキーワードやカテゴリーで探してみてください。
                </p>
                <a class="button button-outline" href="{{ route('home') }}#collection">
                    すべての商品を見る
                </a>
            </div>
        @endforelse
    </div>
    <x-shop-pagination :paginator="$products" />
</section>
<section class="journal" id="journal" aria-labelledby="journal-title">
    <div>
        <p class="eyebrow">
            FROM YOHAKU
        </p>
        <h2 id="journal-title">
            お店からのお便り
        </h2>
        <p class="muted">
            新しい道具と、お店のこと。
        </p>
    </div>
    <div class="news-list">
        @forelse($news as $item)
            <a class="news-row" href="{{ route('news.show', $item) }}">
                <time datetime="{{ $item->created_at->toDateString() }}">
                    {{ $item->created_at->format('Y.m.d') }}
                </time>
                <span>
                    {{ $item->title }}
                </span>
                <span aria-hidden="true">
                    ↗
                </span>
            </a>
        @empty
            <p>
                新しいお知らせをお待ちください。
            </p>
        @endforelse
    </div>
</section>
@endsection
