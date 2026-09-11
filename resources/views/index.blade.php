@extends('layouts.base')

@section('title', 'YOHaku')

@section('content')

    {{-- HERO --}}
    <section class="hero">

        {{-- スライダー --}}
        <div class="hero-slider">

            {{-- 左矢印 --}}
            <button
                type="button"
                class="hero-arrow hero-prev"
                aria-label="前の画像"
            >
                <svg viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path
                        d="M100 0 0 50 100 100"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="0.75"
                        vector-effect="non-scaling-stroke"
                    ></path>
                </svg>
            </button>

            {{-- スライダー画像 --}}
            <div class="hero-image swiper">

                <div class="swiper-wrapper">

                    <div class="swiper-slide hero-slide">
                        <img
                            src="{{ asset('images/hero/hero-01.jpg') }}"
                            alt="YOHaku"
                        >
                    </div>

                    <div class="swiper-slide hero-slide">
                        <img
                            src="{{ asset('images/hero/hero-02.jpg') }}"
                            alt="YOHaku"
                        >
                    </div>

                    <div class="swiper-slide hero-slide">
                        <img
                            src="{{ asset('images/hero/hero-03.jpg') }}"
                            alt="YOHaku"
                        >
                    </div>

                    <div class="swiper-slide hero-slide">
                        <img
                            src="{{ asset('images/hero/hero-04.jpg') }}"
                            alt="YOHaku"
                        >
                    </div>

                </div>

            </div>

            {{-- 右矢印 --}}
            <button
                type="button"
                class="hero-arrow hero-next"
                aria-label="次の画像"
            >
                <svg viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path
                        d="M0 0 100 50 0 100"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="0.75"
                        vector-effect="non-scaling-stroke"
                    ></path>
                </svg>
            </button>

        </div>

        {{-- ドット --}}
        <div class="hero-dots swiper-pagination"></div>

    </section>


    {{-- ABOUT --}}
    <section id="about" class="about">
        <div class="about-item about-logo">
            <img src="{{ asset('images/ec-logo.png') }}" alt="YOHaku">
            <p>日常に、余白を。</p>
        </div>

        <div class="about-item about-copy">
            <p>
                YOHakuでは、日常の中で長く使いたいと思えるものを、一つひとつ選んでいます。<br>
                商品数は多くないですが、使い心地や佇まいまでじっくりと向き合えるものだけを揃えました。
            </p>
        </div>

        <div class="about-item about-copy about-copy-en">
            <p>
                <strong>YOHaku</strong> is a stationery label that brings a little more space into everyday life.<br>
                We carefully select items that we believe are worth using and keeping for a long time.<br>
                Our collection may not be large, but every item is chosen with careful attention to its feel, function, and presence.
            </p>
        </div>
    </section>

    <div class="ranking-space">
        <div class="ranking-space-column"></div>
        <div class="ranking-space-column"></div>
        <div class="ranking-space-column"></div>
    </div>

    {{-- RANKING --}}
    <section id="ranking" class="ranking">
        <div class="section-heading">
            <h2>RANKING</h2>
            <p class="section-subtitle">人気ランキング</p>
        </div>

        <div class="ranking-list">
            @foreach ($ranking as $item)
                <article class="ranking-item">
                    <a href="/products/{{ $item['product']->id }}">
                        <div class="ranking-image">
                            <div class="ranking-label">
                                <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>

                            <img
                                src="{{ $item['product']->imageUrl() }}"
                                alt="{{ $item['product']->name }}"
                            >
                        </div>

                        <div class="ranking-info">
                            <h3>{{ $item['product']->name }}</h3>
                            <p class="ranking-price">
                                ¥{{ number_format($item['product']->price) }}
                            </p>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>
    </section>

    <div class="ranking-space">
        <div class="ranking-space-column"></div>
        <div class="ranking-space-column"></div>
        <div class="ranking-space-column"></div>
    </div>

    {{-- CATEGORY --}}
    <section id="category" class="category">
        <div class="section-heading">
            <h2>CATEGORY</h2>
            <p class="section-subtitle">カテゴリー</p>
        </div>

        <div class="category-list">
            @foreach ($categories as $category)
                <article class="category-item {{ $loop->iteration % 2 === 0 ? 'category-item-reverse' : '' }}">
                    <div class="category-image">
                        @if ($category->slug === 'writing')
                            <img src="{{ asset('images/products/fountain-pen.webp') }}" alt="{{ $category->name }}">
                        @elseif ($category->slug === 'notebook')
                            <img src="{{ asset('images/products/custard-slice-notebook.webp') }}" alt="{{ $category->name }}">
                        @elseif ($category->slug === 'rubber-stamps')
                            <img src="{{ asset('images/products/alphabet-stamp.webp') }}" alt="{{ $category->name }}">
                        @elseif ($category->slug === 'clips-pins')
                            <img src="{{ asset('images/products/butterfly-clips.webp') }}" alt="{{ $category->name }}">
                        @endif
                    </div>

                    <div class="category-info">
                        <p class="category-number">
                            OBJECT {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </p>

                        <h3>{{ $category->name }}</h3>

                        <p class="category-description">
                            @if ($category->slug === 'writing')
                                書く時間を心地よくする、こだわりの筆記具。
                            @elseif ($category->slug === 'notebook')
                                日々の記録や思考を残すための、ノートと手帳。
                            @elseif ($category->slug === 'rubber-stamps')
                                日常に小さなアクセントを添える、スタンプ。
                            @elseif ($category->slug === 'clips-pins')
                                書類やメモをすっきりまとめる、クリップとピン。
                            @endif
                        </p>

                        <a href="{{ url('/categories/' . $category->slug) }}" class="category-button">
                            このカテゴリーを見る
                        </a>
                    </div>
                </article>

                @if (!$loop->last)
                    @if (!$loop->last)
                        <div class="category-space category-space-divider">
                            <div class="category-space-column"></div>
                            <div class="category-space-column"></div>
                            <div class="category-space-column"></div>
                        </div>
                    @endif
                @endif
            @endforeach
        </div>
    </section>

    <div class="ranking-space">
        <div class="ranking-space-column"></div>
        <div class="ranking-space-column"></div>
        <div class="ranking-space-column"></div>
    </div>

    {{-- ALL PRODUCTS --}}
    <section id="products" class="products">
        <div class="section-heading">
            <h2>ALL PRODUCTS</h2>
            <p class="section-subtitle">すべての商品</p>
        </div>

        <div class="product-list">
            @foreach ($products as $product)
                <article class="product-item">
                    <a href="/products/{{ $product->id }}">
                        <div class="product-image">
                            <img
                                src="{{ $product->imageUrl() }}"
                                alt="{{ $product->name }}"
                            >
                        </div>

                        <div class="product-info">
                            <h3>{{ $product->name }}</h3>

                            <p class="product-price">
                                ¥{{ number_format($product->price) }}
                            </p>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>
    </section>

    <div class="ranking-space">
        <div class="ranking-space-column"></div>
        <div class="ranking-space-column"></div>
        <div class="ranking-space-column"></div>
    </div>

    {{-- NEWS --}}
    <section id="news" class="news">
        <div class="section-heading">
            <h2>NEWS</h2>
            <p class="section-subtitle">お知らせ</p>
        </div>

        <div class="news-list">
            @foreach ($news as $item)
                <article class="news-item">
                    <a href="/news/{{ $item->id }}">
                        <div class="news-item-date">
                            {{ $item->created_at->format('Y.m.d') }}
                        </div>

                        <h3 class="news-item-title">
                            {{ $item->title }}
                        </h3>
                    </a>
                </article>
            @endforeach
        </div>
    </section>

    <div class="ranking-space">
        <div class="ranking-space-column"></div>
        <div class="ranking-space-column"></div>
        <div class="ranking-space-column"></div>
    </div>

  

@endsection