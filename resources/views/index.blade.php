@extends('layouts.base')

@section('title', '文房具一覧')

@section('content')

    <div class="stationery-page">

        {{-- =========================
         Hero
    ========================= --}}

        <section class="hero">

            <div class="hero-label">
                PH27 STATIONERY / SELECT SHOP
            </div>

            <h1 class="hero-title">
                WRITE<br>
                YOUR STYLE.
            </h1>

            <p class="hero-subtitle">
                書く。整える。残す。<br>
                毎日の道具に、少しだけこだわりを。
            </p>

        </section>


        {{-- =========================
         Category
    ========================= --}}

        @if (isset($categories))

            <section>

                <div class="section-heading">

                    <h2>カテゴリ</h2>

                    <span class="section-number">
                        01 / CATEGORY
                    </span>

                </div>


                <ul class="category-list">

                    @foreach ($categories as $category)
                        <li class="category-item">

                            <a href="/categories/{{ $category->slug }}">
                                {{ $category->name }}
                            </a>

                        </li>
                    @endforeach

                </ul>

            </section>

        @endif


        {{-- =========================
         Products
    ========================= --}}

        <section>

            <div class="section-heading">

                <h2>商品一覧</h2>

                <span class="section-number">
                    02 / PRODUCTS
                </span>

            </div>


            {{-- Search --}}

            <div class="search-area">

                <form action="/search" method="GET" class="search-form">

                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="商品名を検索"
                        aria-label="商品名を検索">

                    <input type="submit" value="検索">

                </form>


                @if (request('keyword'))
                    <a href="/" class="clear-search">
                        × 検索結果をクリア
                    </a>
                @endif

            </div>


            {{-- Sort --}}

            <div class="sort-area">

                <div class="result-label">

                    @if (request('keyword'))
                        「{{ request('keyword') }}」の検索結果
                    @else
                        ALL PRODUCTS
                    @endif

                </div>


                <form action="{{ request('keyword') ? '/search' : '/' }}" method="GET" class="sort-form">

                    @if (request('keyword'))
                        <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                    @endif


                    <select name="sort" onchange="this.form.submit()" aria-label="並び替え">

                        <option value="">
                            並び替え
                        </option>

                        <option value="newest" @if (request('sort') == 'newest') selected @endif>
                            新着順
                        </option>

                        <option value="price_asc" @if (request('sort') == 'price_asc') selected @endif>
                            価格が安い順
                        </option>

                        <option value="price_desc" @if (request('sort') == 'price_desc') selected @endif>
                            価格が高い順
                        </option>

                    </select>

                </form>

            </div>


            {{-- Product List --}}

            <div class="product-grid">

                @forelse ($products as $product)
                    <article class="product-card">

                        <a href="/products/{{ $product->id }}" class="product-link">

                            <div class="product-image">

                                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">

                            </div>


                            <div class="product-info">

                                <h3 class="product-name">
                                    {{ $product->name }}
                                </h3>

                                <p class="product-price">
                                    ¥{{ number_format($product->price) }}
                                </p>

                            </div>


                            <div class="product-line"></div>

                        </a>

                    </article>

                @empty

                    <div class="empty-products">
                        該当する商品がありません。
                    </div>
                @endforelse

            </div>

        </section>


        {{-- =========================
         News
    ========================= --}}

        <section class="news-section">

            <div class="news-title-wrap">

                <h2 class="news-title">
                    NEWS
                </h2>

                <p class="news-subtitle">
                    お知らせ
                </p>

            </div>


            <div class="news-list">

                @forelse ($news as $item)
                    <article class="news-item">

                        <h3 class="news-item-title">

                            <a href="/news/{{ $item->id }}">
                                {{ $item->title }}
                            </a>

                        </h3>


                        <p class="news-item-body">
                            {!! $item->content !!}
                        </p>

                    </article>

                @empty

                    <article class="news-item">

                        <p class="news-item-body">
                            現在、お知らせはありません。
                        </p>

                    </article>
                @endforelse

            </div>

        </section>

    </div>

@endsection
