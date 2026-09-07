@extends('layouts.base')

@section('title', '商品一覧')

@section('content')

    <div class="container">

        {{-- カテゴリ一覧 --}}
        <section style="margin-bottom: 30px;">
            <h3 style="font-size: 18px; margin-bottom: 10px;">カテゴリから探す</h3>
            <ul style="display: flex; list-style: none; padding: 0; gap: 15px; flex-wrap: wrap;">
                @foreach ($categories as $category)
                    <li>
                        <a href="/categories/{{ $category->slug }}"
                            style="background: #fff; padding: 8px 15px; border: 1px solid #ddd; border-radius: 20px; text-decoration: none; color: #333; font-size: 14px; display: inline-block;">
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>

        {{-- 売れ筋ランキング --}}
        <section>
            <h2 class="section-title">売れ筋ランキング</h2>
            <div class="product-grid">
                @foreach ($rankingProducts as $index => $product)
                    <div class="product-card">
                        <span class="ranking-badge">第{{ $index + 1 }}位</span>
                        <a href="{{ route('products.show', $product->id) }}">
                            @if (isset($product->image))
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            @else
                                <img src="https://via.placeholder.com/150" alt="no image">
                            @endif
                            <h3 class="product-name">{{ $product->name }}</h3>
                            <p class="product-price">¥{{ number_format($product->price) }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- 新着商品一覧 --}}
        <section>
            <h2 class="section-title">新着商品一覧</h2>
            <div class="product-grid">
                @foreach ($products as $product)
                    <div class="product-card">
                        <a href="{{ route('products.show', $product->id) }}">
                            @if (isset($product->image))
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            @else
                                <img src="https://via.placeholder.com/150" alt="no image">
                            @endif
                            <h3 class="product-name">{{ $product['name'] }}</h3>
                            <p class="product-price">¥{{ number_format($product->price ?? 0) }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- お知らせ (NEWS) --}}
        <section style="margin-top: 50px;">
            <h2 class="news-title"
                style="font-size: 22px; border-bottom: 2px solid #333; padding-bottom: 5px; margin-bottom: 20px;">NEWS</h2>
            <div class="news-list" style="margin-left: 0;">
                @foreach ($news as $item)
                    <div class="news-item">
                        <h4 class="news-item-title">
                            <a href="/news/{{ $item->id }}" style="color: #333; text-decoration: none;">
                                {{ $item->title }}
                            </a>
                        </h4>
                        <p class="news-item-body">
                            {!! $item->content !!}
                        </p>
                    </div>
                @endforeach
            </div>
        </section>

    </div>

@endsection
