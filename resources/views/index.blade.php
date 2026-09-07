@extends('layouts.base')

@section('title', isset($keyword) && $keyword ? "「{$keyword}」の検索結果" : '商品一覧')

@section('content')

    <!-- カテゴリ一覧 -->
    @if (isset($categories) && $categories->count() > 0)
        <section style="margin-bottom: 2.5rem;">
            <h3 style="font-size: 1.2rem; margin-bottom: 0.8rem;">カテゴリ</h3>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <a href="/" role="button" class="{{ !request()->route('category') && !request('keyword') ? '' : 'outline' }}" style="padding: 0.3rem 0.8rem; font-size: 0.85rem;">
                    すべて
                </a>
                @foreach ($categories as $category)
                    <a href="/categories/{{ $category->slug }}" role="button" class="outline" style="padding: 0.3rem 0.8rem; font-size: 0.85rem;">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- 商品一覧セクション -->
    <section style="margin-bottom: 3rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem; flex-wrap: wrap; gap: 10px;">
            <h2 style="margin: 0;">
                {{ request('keyword') ? "「".request('keyword')."」の検索結果" : '商品一覧' }}
            </h2>
            
            @if (request('keyword'))
                <a href="/" role="button" class="secondary outline" style="padding: 0.3rem 0.8rem; font-size: 0.85rem;">
                    検索結果をクリア
                </a>
            @endif
        </div>

        @if (isset($products) && $products->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.5rem;">
                @foreach ($products as $product)
                    <article style="padding: 1rem; margin: 0; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <a href="/products/{{ $product->id }}" style="text-decoration: none;">
                                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" style="width: 100%; height: 160px; object-fit: cover; border-radius: 6px; margin-bottom: 0.8rem;">
                                <h4 style="font-size: 1.05rem; margin-bottom: 0.4rem; color: var(--pico-color);">{{ $product->name }}</h4>
                            </a>
                            <p style="font-weight: bold; color: #2563eb; font-size: 1.1rem; margin-bottom: 0.5rem;">
                                ¥{{ number_format($product->price) }}
                            </p>
                        </div>
                        <a href="/products/{{ $product->id }}" role="button" class="outline" style="width: 100%; text-align: center; padding: 0.4rem 0; margin-top: 0.5rem; font-size: 0.9rem;">
                            詳細を見る
                        </a>
                    </article>
                @endforeach
            </div>
        @else
            <p>該当する商品が見つかりませんでした。</p>
        @endif
    </section>

    <!-- NEWS（お知らせ） -->
    @if (isset($news) && $news->count() > 0)
        <section style="margin-top: 3rem; margin-bottom: 3rem;">
            {{-- 見出しと一覧リンクのエリア --}}
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.5rem; border-bottom: 2px solid var(--pico-muted-border-color); padding-bottom: 0.5rem;">
                <h2 style="margin: 0; font-size: 1.8rem; line-height: 1.2;">
                    NEWS <span style="font-size: 1rem; color: #666; font-weight: normal; margin-left: 0.5rem;">お知らせ</span>
                </h2>
                <a href="/news" role="button" class="secondary outline" style="padding: 0.35rem 0.8rem; font-size: 0.85rem; margin: 0; width: auto;">
                    お知らせ一覧を見る →
                </a>
            </div>

            {{-- ニュースリスト --}}
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach ($news as $item)
                    <article style="padding: 1.2rem; margin: 0; border-radius: 8px;">
                        @if (isset($item->created_at))
                            <small style="color: #6b7280; display: block; margin-bottom: 0.3rem;">
                                {{ $item->created_at->format('Y.m.d') }}
                            </small>
                        @endif
                        <h4 style="font-size: 1.1rem; margin-bottom: 0.4rem;">
                            <a href="/news/{{ $item->id }}" style="text-decoration: none; font-weight: bold;">
                                {{ $item->title }}
                            </a>
                        </h4>
                        <p style="margin: 0; color: #4b5563; font-size: 0.95rem;">
                            {{ Str::limit(strip_tags($item->content ?? $item->body ?? ''), 80) }}
                        </p>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

@endsection