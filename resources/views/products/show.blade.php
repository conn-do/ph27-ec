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
            <!-- 商品画像（IDを設定してJavaScriptから画像URLを差し替えられるようにしています） -->
            <div>
                <img id="main-product-image" src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" style="width: 100%; border-radius: 8px; border: 1px solid #e5e7eb;">
            </div>

            <!-- 商品詳細情報・アクション -->
            <div>
                {{-- セール価格表示エリア --}}
                <div style="margin-bottom: 0.5rem;">
                    @if ($product->is_sale && $product->sale_price)
                        @php
                            $discountRate = round((($product->price - $product->sale_price) / $product->price) * 100);
                        @endphp
                        <div style="display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;">
                            <span style="text-decoration: line-through; color: #9ca3af; font-size: 1rem;">
                                ¥{{ number_format($product->price) }}
                            </span>
                            <strong style="color: #ef4444; font-size: 1.8rem;">
                                ¥{{ number_format($product->sale_price) }}
                            </strong>
                            <span style="color: #ef4444; font-size: 0.85rem; font-weight: bold; background: #fee2e2; padding: 0.2rem 0.5rem; border-radius: 4px;">
                                {{ $discountRate }}% OFF
                            </span>
                        </div>
                    @else
                        <h3 style="color: #2563eb; font-size: 1.8rem; margin-bottom: 0.5rem;">¥{{ number_format($product->price) }}</h3>
                    @endif
                </div>

                <!-- 在庫ステータス -->
                <p>
                    @if (($product->stock ?? 0) <= 0)
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

                <!-- 在庫切れ時の「再入荷リクエスト」ボタン -->
                @if (($product->stock ?? 0) <= 0)
                    <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 1rem; border-radius: 8px; margin-top: 1rem;">
                        <p style="color: #dc2626; font-weight: bold; margin-bottom: 0.5rem;">現在在庫切れです</p>
                        <form action="{{ route('products.restock', $product->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="secondary" style="width: 100%; margin: 0;">
                                🔔 再入荷通知をリクエストする
                            </button>
                        </form>
                    </div>
                @endif

                <!-- カートフォームとお気に入りボタン -->
                <div style="background: #f9fafb; padding: 1.5rem; border-radius: 8px; margin-top: 1.5rem;">
                    <form action="/cart" method="POST" style="margin-bottom: 1rem;">
                        @csrf
                        
                        {{-- 🎨 カラー選択エリア --}}
                        @if (!empty($product->colors))
                            <div style="margin-bottom: 1.5rem;">
                                <label style="font-weight: bold; font-size: 0.95rem; margin-bottom: 0.5rem; display: block;">
                                    カラーを選択: <span id="selected-color-name" style="font-weight: normal; color: #2563eb;">{{ array_key_first($product->colors) }}</span>
                                </label>
                                
                                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                                    @foreach ($product->colors as $colorName => $colorData)
                                        @php
                                            // カラーコードと画像の判定処理
                                            $colorCode  = is_array($colorData) ? ($colorData['code'] ?? '#ccc') : $colorData;
                                            $colorImage = is_array($colorData) ? ($colorData['image'] ?? null) : null;
                                            
                                            $imageUrl = null;
                                            if ($colorImage) {
                                                $imageUrl = \Illuminate\Support\Str::startsWith($colorImage, 'http') ? $colorImage : asset('storage/' . $colorImage);
                                            }
                                        @endphp

                                        <label style="cursor: pointer; margin: 0;">
                                            <input type="radio" 
                                                   name="color" 
                                                   value="{{ $colorName }}" 
                                                   {{ $loop->first ? 'checked' : '' }} 
                                                   style="display: none;" 
                                                   onchange="changeProductColor('{{ $colorName }}', '{{ $imageUrl }}')">
                                            <span class="color-option" style="
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                width: 32px;
                                                height: 32px;
                                                border-radius: 50%;
                                                background-color: {{ $colorCode }};
                                                border: 2px solid #e2e8f0;
                                                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                                                transition: all 0.2s ease;
                                            " title="{{ $colorName }}">
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 1rem;">
                            <label for="quantity" style="margin: 0; min-width: 50px;">数量:</label>
                            <input type="number" id="quantity" name="quantity" min="1" value="{{ old('quantity', 1) }}" style="margin: 0; max-width: 100px;">
                        </div>
                        <input type="hidden" name="productId" value="{{ $product->id }}">
                        <button type="submit" style="width: 100%; font-weight: bold;" @if(($product->stock ?? 0) <= 0) disabled @endif>
                            🛒 カートに入れる
                        </button>
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
    <section class="reviews-section" style="margin-top: 3rem; background: #fafafa; padding: 1.5rem; border-radius: 8px;">
        <h3>💬 カスタマーレビュー</h3>

        <div style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; padding: 0.8rem; border-radius: 6px; margin-bottom: 1.5rem;">
            🎁 <strong>レビュー投稿特典：</strong> レビューを書くと次回使える <strong>100pt</strong> をプレゼント！
        </div>

        @if (session('message'))
            <ins style="color: #16a34a; display: block; margin-bottom: 1rem;">{{ session('message') }}</ins>
        @endif

        <!-- レビュー投稿フォーム -->
        <article style="background: #ffffff; padding: 1.2rem; border-radius: 8px; border: 1px solid #e5e7eb;">
            <header style="margin-bottom: 1rem;"><strong>レビューを投稿する</strong></header>
            @auth
                <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <label for="rating">評価
                        <select name="rating" id="rating" required style="max-width: 280px; width: 100%; margin-bottom: 1rem;">
                            <option value="5">★★★★★ （5 - 最高）</option>
                            <option value="4">★★★★☆ （4 - 良い）</option>
                            <option value="3">★★★☆☆ （3 - 普通）</option>
                            <option value="2">★★☆☆☆ （2 - イマイチ）</option>
                            <option value="1">★☆☆☆☆ （1 - 悪い）</option>
                        </select>
                    </label>

                    <label for="comment">コメント
                        <textarea name="comment" id="comment" rows="3" placeholder="商品の感想をご記入ください..." required style="margin-bottom: 1rem;"></textarea>
                    </label>

                    <button type="submit" style="width: auto;">レビューを投稿してポイントを受け取る</button>
                </form>
            @else
                <p style="margin: 0;"><a href="{{ route('login') }}">ログイン</a> するとレビューを投稿できます。</p>
            @endauth
        </article>

        <!-- レビュー一覧 -->
        <h4 style="margin-top: 2rem;">レビュー一覧（{{ $product->reviews ? $product->reviews->count() : 0 }}件）</h4>
        @if ($product->reviews && $product->reviews->count() > 0)
            @foreach ($product->reviews as $review)
                <article style="padding: 1rem; margin-bottom: 1rem; background: #ffffff; border: 1px solid #e5e7eb;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <strong>{{ $review->user->name ?? '名無しさん' }}</strong>
                        <span style="color: #f59e0b; font-size: 1.1rem;">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                    </div>
                    <p style="margin-bottom: 0.5rem; white-space: pre-wrap;">{{ $review->comment }}</p>
                    <small style="color: #666;">投稿日: {{ $review->created_at->format('Y-m-d H:i') }}</small>
                </article>
            @endforeach
        @else
            <p>まだレビューはありません。</p>
        @endif
    </section>

    <!-- 関連商品・おすすめ表示（クロスセル） -->
    @if (isset($relatedProducts) && $relatedProducts->count() > 0)
        <section style="margin-top: 3rem;">
            <h3>✨ こちらの商品もおすすめです（関連商品）</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem; margin-top: 1rem;">
                @foreach ($relatedProducts as $relProduct)
                    <article style="padding: 0.8rem; margin: 0; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <a href="/products/{{ $relProduct->id }}" style="text-decoration: none;">
                            <img src="{{ $relProduct->imageUrl() }}" alt="{{ $relProduct->name }}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 6px;">
                            <h4 style="font-size: 0.9rem; margin: 0.5rem 0 0.2rem 0; color: #111;">{{ $relProduct->name }}</h4>
                            <p style="font-weight: bold; color: #2563eb; margin: 0;">¥{{ number_format($relProduct->price) }}</p>
                        </a>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    {{-- 🎨 カラー選択用のスタイルおよびスクリプト --}}
    <style>
        input[type="radio"]:checked + .color-option {
            border-color: #2563eb !important;
            transform: scale(1.15);
            box-shadow: 0 0 0 2px #93c5fd;
        }
    </style>

    <script>
        function changeProductColor(colorName, imagePath) { // ← changeProductColor に合わせる
            // カラー名テキストの変更
            document.getElementById('selected-color-name').textContent = colorName;

            const mainImage = document.getElementById('main-product-image');

            // 画像パスが存在し、http や / から始まらない場合にパスを補正
            if (imagePath && imagePath !== 'null' && imagePath !== '') {
                if (!imagePath.startsWith('http') && !imagePath.startsWith('/')) {
                    imagePath = '/' + imagePath;
                }
                mainImage.src = imagePath;
            }
        }
    </script>
@endsection