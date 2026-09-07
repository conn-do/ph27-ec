@extends('layouts.base')

@section('title', '商品一覧')

@section('content')
<<<<<<< Updated upstream

    {{-- カテゴリ一覧 --}}
    <h3>カテゴリ</h3>
    <ul>
        @foreach ($categories as $category)
            <li>
                <a href="/categories/{{ $category->slug }}">
                    {{ $category->name }}
                </a>
            </li>
        @endforeach
    </ul>

    <h2>商品一覧</h2>

    <form action="/search" method="GET">
        <input type="text" name="keyword" value="{{ request('keyword') }}">
        <input type="submit" value="検索">
    </form>

    @if (request('keyword'))
        <a href="/">検索結果をクリア</a>
    @endif

    {{-- 商品一覧 --}}
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

    {{-- お知らせ --}}
    <h2 class="news-title">NEWS</h2>
    <h3 class="news-subtitle">お知らせ</h3>

    <div class="news-list">
        @foreach ($news as $item)
            <div class="news-item">

                <h4 class="news-item-title">
                    <a href="/news/{{ $item->id }}">
                        {{ $item->title }}
                    </a>
                </h4>

                <p class="news-item-body">
                    {!! $item->content !!}
                </p>

            </div>
        @endforeach
    </div>

=======
    <section class="store-hero">
        <div class="content-width store-hero__content">
            <p class="eyebrow">STATIONERY FOR EVERYDAY IDEAS</p>
            <h1>書く時間を、<br>もっと心地よく。</h1>
            <p>毎日の学びや仕事に寄り添う、使い心地のよい文房具を集めました。</p>
        </div>
    </section>

    <div class="content-width store-layout">
        <aside class="filter-panel">
            <div>
                <p class="eyebrow">FIND ITEMS</p>
                <h2>商品を探す</h2>
            </div>
            <form action="{{ route('home') }}" method="GET" class="search-form">
                <label for="keyword">キーワード</label>
                <input id="keyword" type="search" name="keyword" value="{{ $keyword }}" placeholder="ペン、ノートなど">
                <label for="category">カテゴリー</label>
                <select id="category" name="category">
                    <option value="">すべてのカテゴリー</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected($categorySlug === $category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>
                <button class="button button--primary" type="submit">絞り込む</button>
                @if ($keyword !== '' || $categorySlug !== '')
                    <a class="text-link" href="{{ route('home') }}">条件をクリア</a>
                @endif
            </form>
        </aside>

        <section class="product-section" aria-labelledby="products-title">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">OUR SELECTION</p>
                    <h2 id="products-title">商品一覧</h2>
                </div>
                <p>{{ $products->count() }} items</p>
            </div>

            <div class="product-grid">
                @forelse ($products as $product)
                    <article class="product-card">
                        <a class="product-card__image" href="{{ route('products.show', $product) }}">
                            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">
                        </a>
                        <div class="product-card__body">
                            <p class="product-category">{{ $product->category?->name ?? '文房具' }}</p>
                            <h3><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h3>
                            <p class="price">&yen;{{ number_format($product->price) }}</p>
                            <a class="text-link" href="{{ route('products.show', $product) }}">商品を見る</a>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">
                        <h3>条件に合う商品が見つかりませんでした。</h3>
                        <p>キーワードやカテゴリーを変えて、もう一度探してみてください。</p>
                        <a class="button button--outline" href="{{ route('home') }}">すべての商品を見る</a>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    <section class="news-band">
        <div class="content-width">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">NEWS</p>
                    <h2>お知らせ</h2>
                </div>
            </div>
            <div class="news-list">
                @foreach ($news as $item)
                    <article class="news-item">
                        <div>
                            <p class="news-item__date">{{ $item->created_at->format('Y.m.d') }}</p>
                            <h3><a href="{{ route('news.show', $item) }}">{{ $item->title }}</a></h3>
                        </div>
                        <a class="text-link" href="{{ route('news.show', $item) }}">読む</a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
>>>>>>> Stashed changes
@endsection
