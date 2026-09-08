@extends('layouts.base')

@section('title', $category->name . '一覧')

@section('content')

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="margin: 0;">{{ $category->name }}</h2>
        <a href="/" role="button" class="secondary outline" style="padding: 0.3rem 0.8rem; font-size: 0.85rem;">
            ← 全商品一覧に戻る
        </a>
    </div>

    <!-- カテゴリに属する商品一覧 -->
    @if ($category->products && $category->products->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.5rem;">
            @foreach ($category->products as $product)
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
        <p>このカテゴリにはまだ商品がありません。</p>
    @endif

@endsection