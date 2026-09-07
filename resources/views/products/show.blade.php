@extends('layouts.base')

@section('title', $product->name)

@section('content')
    <article>
        <header style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
            <div>
                <p style="margin-bottom: 0.5rem; color: #666; font-size: 0.9rem;">
                    カテゴリー: <a href="/categories/{{ $product->category->slug }}">{{ $product->category->name }}</a>
                </p>
                <h2 style="margin-bottom: 0;">{{ $product->name }}</h2>
            </div>
            <!-- 商品一覧に戻るボタン -->
            <a href="/" role="button" class="secondary outline" style="padding: 0.35rem 0.8rem; font-size: 0.85rem; width: auto; margin: 0;">
                ← 商品一覧に戻る
            </a>
        </header>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; align-items: start;">
            <!-- 商品画像 -->
            <div>
                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" style="width: 100%; border-radius: 8px; border: 1px solid #e5e7eb;">
            </div>

            <!-- 商品詳細情報・アクション -->
            <div>
                <h3 style="color: #2563eb; font-size: 1.8rem; margin-bottom: 0.5rem;">¥{{ number_format($product->price) }}</h3>

                <!-- 在庫ステータス -->
                <p>
                    @if ($product->stock <= 0)
                        <mark style="background-color: #fef2f2; color: #dc2626;">売り切れ</mark>
                    @elseif ($product->stock <= 5)
                        <mark style="background-color: #fffbebf; color: #d97706;">残りわずか（在庫: {{ $product->stock }}）</mark>
                    @else
                        <mark style="background-color: #f0fdf4; color: #16a34a;">在庫あり</mark>
                    @endif
                </p>

                <p style="line-height: 1.6;">{{ $product->description }}</p>

                @if ($errors->any())
                    <div style="background-color: #fef2f2; border: 1px solid #fca5a5; padding: 10px; border-radius: 6px; margin-bottom: 1rem;">
                        @foreach ($errors->all() as $error)
                            <p style="color: #dc2626; margin: 0; font-size: 0.9rem;">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- カートフォームとお気に入りボタン -->
                <div style="background: #f9fafb; padding: 1.5rem; border-radius: 8px; margin-top: 1.5rem;">
                    <form action="/cart" method="POST" style="margin-bottom: 1rem;">
                        @csrf
                        <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 1rem;">
                            <label for="quantity" style="margin: 0; min-width: 50px;">数量:</label>
                            <input type="number" id="quantity" name="quantity" min="1" value="{{ old('quantity', 1) }}" style="margin: 0; max-width: 100px;">
                        </div>
                        <input type="hidden" name="productId" value="{{ $product->id }}">
                        <button type="submit" style="width: 100%; font-weight: bold;">カートに入れる</button>
                    </form>

                    <!-- お気に入りボタン -->
                    @auth
                        @if ($product->isFavoritedBy(auth()->user()))
                            <form action="{{ route('favorites.destroy', $product->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="secondary outline" style="width: 100%; margin: 0; border-color: #ef4444; color: #ef4444;">
                                    ♥ お気に入り解除
                                </button>
                            </form>
                        @else
                            <form action="{{ route('favorites.store', $product->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="secondary outline" style="width: 100%; margin: 0;">
                                    ♡ お気に入りに追加
                                </button>
                            </form>
                        @endif
                    @else
                        <p style="text-align: center; margin: 0;"><small>※<a href="{{ route('login') }}">ログイン</a>するとお気に入り登録ができます。</small></p>
                    @endauth
                </div>
            </div>
        </div>
    </article>

    <!-- レビューセクション -->
    <section class="reviews-section" style="margin-top: 3rem;">
        <h3>カスタマーレビュー</h3>

        @if (session('message'))
            <ins style="color: #16a34a; display: block; margin-bottom: 1rem;">{{ session('message') }}</ins>
        @endif

        <!-- レビュー投稿フォーム -->
        <article>
            <header><strong>レビューを投稿する</strong></header>
            @auth
                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <label for="rating">評価
                        <select name="rating" id="rating" required>
                            <option value="5">★★★★★ (5 - 最高)</option>
                            <option value="4">★★★★☆ (4 - 良い)</option>
                            <option value="3">★★★☆☆ (3 - 普通)</option>
                            <option value="2">★★☆☆☆ (2 - イマイチ)</option>
                            <option value="1">★☆☆☆☆ (1 - 悪い)</option>
                        </select>
                    </label>

                    <label for="comment">コメント
                        <textarea name="comment" id="comment" rows="3" placeholder="商品の感想を入力してください..." required></textarea>
                    </label>

                    <button type="submit" style="width: auto;">レビューを投稿</button>
                </form>
            @else
                <p style="margin: 0;"><a href="{{ route('login') }}">ログイン</a>するとレビューを投稿できます。</p>
            @endauth
        </article>

        <!-- レビュー一覧 -->
        <h4>レビュー一覧（{{ $product->reviews ? $product->reviews->count() : 0 }}件）</h4>
        @if ($product->reviews && $product->reviews->count() > 0)
            @foreach ($product->reviews as $review)
                <article style="padding: 1rem; margin-bottom: 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <strong>{{ $review->user->name ?? '名無しさん' }}</strong>
                        <span style="color: #f59e0b; font-size: 1.1rem;">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                    </div>
                    <p style="margin-bottom: 0.5rem;">{{ $review->comment }}</p>
                    <small style="color: #666;">投稿日: {{ $review->created_at->format('Y-m-d H:i') }}</small>
                </article>
            @endforeach
        @else
            <p>まだレビューはありません。</p>
        @endif
    </section>
@endsection