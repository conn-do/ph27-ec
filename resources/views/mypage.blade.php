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