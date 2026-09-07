@extends('layouts.base')

@section('title', 'マイページ')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>マイページ</h2>
        <a href="/orders" role="button" class="outline">📦 注文履歴を見る</a>
    </div>

    <!-- フラッシュメッセージ -->
    @if (session('message'))
        <ins style="color: #16a34a; display: block; margin-bottom: 1rem;">{{ session('message') }}</ins>
    @endif
    @if (session('error'))
        <del style="color: #dc2626; display: block; margin-bottom: 1rem;">{{ session('error') }}</del>
    @endif

    <!-- 購入履歴セクション -->
    <section style="margin-bottom: 3rem;">
        <h3 style="font-size: 1.3rem; margin-bottom: 1rem; border-bottom: 2px solid var(--pico-muted-border-color); padding-bottom: 0.4rem;">
            📦 購入履歴
        </h3>

        @if (isset($orders) && $orders->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 1.2rem;">
                @foreach ($orders as $order)
                    <article style="padding: 1rem; margin: 0; border-radius: 8px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem; margin-bottom: 0.8rem; flex-wrap: wrap; gap: 0.5rem;">
                            <div>
                                <small style="color: #6b7280; display: block;">注文日</small>
                                <strong>{{ $order->created_at->format('Y/m/d H:i') }}</strong>
                            </div>
                            <div>
                                <small style="color: #6b7280; display: block;">合計金額</small>
                                <strong style="color: #2563eb; font-size: 1.05rem;">
                                    ¥{{ number_format($order->total_price ?? $order->total) }}
                                </strong>
                            </div>
                        </div>

                        {{-- $order->orderItems から $order->details に変更 --}}
                        <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                            @foreach ($order->details as $item)
                                <div style="display: flex; align-items: center; gap: 0.8rem;">
                                    @if (optional($item->product)->image)
                                        <img src="{{ $item->product->imageUrl() }}" alt="{{ $item->product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                                    @endif
                                    <div style="flex-grow: 1;">
                                        <h4 style="font-size: 0.95rem; margin: 0 0 0.2rem 0;">
                                            {{ optional($item->product)->name ?? '削除された商品' }}
                                        </h4>
                                        <span style="font-size: 0.85rem; color: #6b7280;">
                                            ¥{{ number_format($item->price) }} × {{ $item->quantity }}個
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <p style="color: #6b7280; font-size: 0.95rem;">まだ購入履歴はありません。</p>
        @endif
    </section>

    <!-- お気に入り商品一覧エリア -->
    <section style="margin-bottom: 3rem;">
        <h3>♡ お気に入り商品</h3>
        @if (isset($favoriteProducts) && $favoriteProducts->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
                @foreach ($favoriteProducts as $favProduct)
                    <article style="padding: 1rem; margin: 0; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <a href="/products/{{ $favProduct->id }}" style="text-decoration: none;">
                                <img src="{{ $favProduct->imageUrl() }}" alt="{{ $favProduct->name }}" style="width: 100%; height: 140px; object-fit: cover; border-radius: 6px; margin-bottom: 0.5rem;">
                                <h4 style="font-size: 1rem; margin-bottom: 0.5rem; color: #111827;">{{ $favProduct->name }}</h4>
                            </a>
                            <p style="font-weight: bold; color: #2563eb; margin-bottom: 0.5rem;">¥{{ number_format($favProduct->price) }}</p>
                        </div>
                        <form action="{{ route('favorites.destroy', $favProduct->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="secondary outline" style="padding: 4px 8px; font-size: 0.8rem; width: 100%; margin: 0;">
                                削除
                            </button>
                        </form>
                    </article>
                @endforeach
            </div>
        @else
            <p>お気に入りに登録した商品はまだありません。</p>
        @endif
    </section>

    <!-- 投稿レビュー一覧エリア -->
    <section>
        <h3>💬 投稿したレビュー</h3>
        @if (isset($reviews) && $reviews->count() > 0)
            <div style="display: grid; gap: 1rem;">
                @foreach ($reviews as $review)
                    <article style="padding: 1.2rem; margin: 0;">
                        <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 10px;">
                            <div>
                                <h4 style="margin-bottom: 0.3rem;">
                                    @if ($review->product)
                                        <a href="/products/{{ $review->product->id }}">{{ $review->product->name }}</a>
                                    @else
                                        商品情報なし
                                    @endif
                                </h4>
                                <div style="color: #f59e0b; font-size: 1rem; margin-bottom: 0.5rem;">
                                    {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                </div>
                            </div>

                            <!-- レビュー削除ボタン -->
                            <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('本当にこのレビューを削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="secondary outline" style="padding: 4px 12px; font-size: 0.85rem; margin: 0; color: #dc2626; border-color: #fca5a5;">
                                    レビューを削除
                                </button>
                            </form>
                        </div>

                        <p style="margin-bottom: 0.5rem; white-space: pre-wrap;">{{ $review->comment }}</p>
                        <small style="color: #666;">投稿日時: {{ $review->created_at->format('Y-m-d H:i') }}</small>
                    </article>
                @endforeach
            </div>
        @else
            <p>まだ投稿したレビューはありません。</p>
        @endif
    </section>
@endsection