@extends('layouts.base')

@section('title', isset($keyword) && $keyword ? "「{$keyword}」の検索結果" : '商品一覧')

@section('content')

    {{-- Amazon風：1行ニュースバー --}}
    <div style="
        background: var(--pico-card-background-color);
        border: 1px solid var(--pico-muted-border-color);
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    ">
        <div style="display: flex; align-items: center; gap: 1rem; overflow: hidden; white-space: nowrap;">
            <span style="
                background: #2563eb;
                color: #ffffff;
                font-size: 0.75rem;
                font-weight: bold;
                padding: 0.2rem 0.6rem;
                border-radius: 4px;
                flex-shrink: 0;
                letter-spacing: 0.05em;
            ">
                NEWS
            </span>

            @if (isset($latestNews) && $latestNews)
                <span style="color: var(--pico-muted-color); font-size: 0.85rem; flex-shrink: 0;">
                    {{ $latestNews->created_at->format('Y.m.d') }}
                </span>
                <a href="/news/{{ $latestNews->id }}" style="
                    color: var(--pico-color);
                    text-decoration: none;
                    font-size: 0.9rem;
                    font-weight: 500;
                    overflow: hidden;
                    text-overflow: ellipsis;
                ">
                    {{ $latestNews->title }}
                </a>
            @else
                <span style="color: var(--pico-muted-color); font-size: 0.85rem; flex-shrink: 0;">2026.09.10</span>
                <a href="/news" style="
                    color: var(--pico-color);
                    text-decoration: none;
                    font-size: 0.9rem;
                    font-weight: 500;
                    overflow: hidden;
                    text-overflow: ellipsis;
                ">
                    【新商品】レトロポップな新作シャープペンシルが入荷しました！
                </a>
            @endif
        </div>

        <a href="/news" style="
            font-size: 0.85rem;
            color: #2563eb;
            text-decoration: none;
            flex-shrink: 0;
            font-weight: bold;
        ">
            お知らせ一覧 →
        </a>
    </div>

    <!-- ランキングセクション -->
    @if (isset($rankingProducts) && $rankingProducts->count() > 0)
        <section style="margin-bottom: 3.5rem;">
            <div style="display: flex; align-items: flex-end; gap: 0.5rem; margin-bottom: 1.5rem; border-bottom: 2px solid var(--pico-muted-border-color); padding-bottom: 0.5rem;">
                <h2 style="margin: 0; font-size: 1.8rem; line-height: 1.2;">
                    👑 人気商品ランキング
                </h2>
                <span style="font-size: 0.95rem; color: #6b7280; font-weight: normal; margin-bottom: 2px;">
                    POPULAR RANKING
                </span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.5rem;">
                @foreach ($rankingProducts as $index => $product)
                    @php
                        $badgeBg = match($index) {
                            0 => 'linear-gradient(135deg, #f59e0b, #d97706)',
                            1 => 'linear-gradient(135deg, #94a3b8, #64748b)',
                            2 => 'linear-gradient(135deg, #d97706, #92400e)',
                            default => '#4b5563',
                        };
                    @endphp

                    <article style="padding: 1rem; margin: 0; display: flex; flex-direction: column; justify-content: space-between; position: relative; overflow: hidden; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
                        
                        {{-- 順位バッジ --}}
                        <div style="position: absolute; top: 12px; left: 12px; background: {{ $badgeBg }}; color: white; font-weight: bold; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; box-shadow: 0 2px 6px rgba(0,0,0,0.15); z-index: 2;">
                            {{ $index + 1 }}
                        </div>

                        {{-- SALEバッジ --}}
                        @if ($product->is_sale)
                            <div style="position: absolute; top: 12px; right: 12px; background: #ef4444; color: white; font-weight: bold; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; z-index: 2;">
                                SALE
                            </div>
                        @endif

                        <div>
                            <a href="/products/{{ $product->id }}" style="text-decoration: none;">
                                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" style="width: 100%; height: 160px; object-fit: cover; border-radius: 8px; margin-bottom: 0.8rem;">
                                <h4 style="font-size: 1.05rem; margin-bottom: 0.4rem; color: var(--pico-color); line-height: 1.4;">
                                    {{ $product->name }}
                                </h4>
                            </a>

                            {{-- セール価格表示 --}}
                            <div style="margin-bottom: 0.5rem;">
                                @if ($product->is_sale && $product->sale_price)
                                    @php
                                        $discountRate = round((($product->price - $product->sale_price) / $product->price) * 100);
                                    @endphp
                                    <div style="display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap;">
                                        <span style="text-decoration: line-through; color: #9ca3af; font-size: 0.85rem;">
                                            ¥{{ number_format($product->price) }}
                                        </span>
                                        <strong style="color: #ef4444; font-size: 1.15rem;">
                                            ¥{{ number_format($product->sale_price) }}
                                        </strong>
                                        <span style="color: #ef4444; font-size: 0.75rem; font-weight: bold; background: #fee2e2; padding: 0.1rem 0.35rem; border-radius: 4px;">
                                            {{ $discountRate }}% OFF
                                        </span>
                                    </div>
                                @else
                                    <strong style="color: #2563eb; font-size: 1.15rem;">
                                        ¥{{ number_format($product->price) }}
                                    </strong>
                                @endif
                            </div>
                        </div>

                        <a href="/products/{{ $product->id }}" role="button" class="outline" style="width: 100%; text-align: center; padding: 0.4rem 0; margin-top: 0.5rem; font-size: 0.85rem; border-radius: 6px;">
                            詳細を見る
                        </a>
                    </article>
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
                    <article style="padding: 1rem; margin: 0; display: flex; flex-direction: column; justify-content: space-between; position: relative;">
                        
                        {{-- SALEバッジ --}}
                        @if ($product->is_sale)
                            <div style="position: absolute; top: 12px; right: 12px; background: #ef4444; color: white; font-weight: bold; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; z-index: 2;">
                                SALE
                            </div>
                        @endif

                        <div>
                            <a href="/products/{{ $product->id }}" style="text-decoration: none;">
                                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" style="width: 100%; height: 160px; object-fit: cover; border-radius: 6px; margin-bottom: 0.8rem;">
                                <h4 style="font-size: 1.05rem; margin-bottom: 0.4rem; color: var(--pico-color);">{{ $product->name }}</h4>
                            </a>

                            {{-- セール価格表示 --}}
                            <div style="margin-bottom: 0.5rem;">
                                @if ($product->is_sale && $product->sale_price)
                                    @php
                                        $discountRate = round((($product->price - $product->sale_price) / $product->price) * 100);
                                    @endphp
                                    <div style="display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap;">
                                        <span style="text-decoration: line-through; color: #9ca3af; font-size: 0.85rem;">
                                            ¥{{ number_format($product->price) }}
                                        </span>
                                        <strong style="color: #ef4444; font-size: 1.1rem;">
                                            ¥{{ number_format($product->sale_price) }}
                                        </strong>
                                        <span style="color: #ef4444; font-size: 0.75rem; font-weight: bold; background: #fee2e2; padding: 0.1rem 0.35rem; border-radius: 4px;">
                                            {{ $discountRate }}% OFF
                                        </span>
                                    </div>

                                    @else
                                        <strong style="color: #2563eb; font-size: 1.1rem;">
                                            ¥{{ number_format($product->price) }}
                                        </strong>
                                    @endif

                                    {{-- カラーアイコン（カラーチップ）表示エリア --}}
                                    @if (!empty($product->colors))
                                        <div style="display: flex; gap: 0.35rem; align-items: center; margin-bottom: 0.8rem; flex-wrap: wrap;">
                                            @foreach ($product->colors as $colorName => $colorData)
                                                @php
                                                    // 配列（新構造）と文字列（旧構造）の両方に対応
                                                    $colorCode = is_array($colorData) ? ($colorData['code'] ?? '#ccc') : $colorData;
                                                @endphp
                                                <span title="{{ $colorName }}" style="
                                                    width: 14px;
                                                    height: 14px;
                                                    background-color: {{ $colorCode }};
                                                    border-radius: 50%;
                                                    border: 1px solid #e2e8f0;
                                                    display: inline-block;
                                                "></span>
                                            @endforeach
                                        </div>
                                    @endif
                            </div>
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

@endsection