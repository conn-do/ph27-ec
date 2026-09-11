@extends('layouts.base')

@section('title', $product->name)

@section('content')

<div style="max-width: 900px; margin: 0 auto; color: #e5e7eb;">

    <div style="margin-bottom: 20px;">
        <a href="/" style="color: #38bdf8; text-decoration: none; font-size: 13px;">← トップページへ戻る</a>
    </div>

    @if(session('success'))
        <div style="background-color: #065f46; color: #a7f3d0; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 13px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background-color: #7f1d1d; color: #fca5a5; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 13px;">
            {{ session('error') }}
        </div>
    @endif

    <div style="background: #13161E; border: 1px solid #2a3245; padding: 30px; border-radius: 4px; display: grid; grid-template-columns: 1fr 1fr; gap: 30px; align-items: start; margin-bottom: 40px;">
        
        <div>
            @php
                $imageFile = 'note.png';
                $pName = $product->name ?? '';
                if (str_contains($pName, 'ペン') || $product->id == 1) {
                    $imageFile = 'pen.png';
                } elseif (str_contains($pName, 'ノート') || $product->id == 2) {
                    $imageFile = 'note.png';
                } elseif (str_contains($pName, '鉛筆') || $product->id == 3) {
                    $imageFile = 'pencil.png';
                } elseif (method_exists($product, 'imageUrl') && !empty($product->imageUrl())) {
                    $imageFile = basename($product->imageUrl());
                }
            @endphp
            <div style="width: 100%; height: 300px; background: #0e1117; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 1px solid #2a3245; border-radius: 4px;">
                <img src="{{ asset('images/products/' . $imageFile) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        </div>

        <div style="display: flex; flex-direction: column; height: 100%;">
            
            @if(isset($product->category))
                <div style="margin-bottom: 10px;">
                    <a href="/categories/{{ $product->category->slug }}" style="display: inline-block; background-color: #1f293d; color: #38bdf8; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 3px; border: 1px solid #2a3245; text-decoration: none;">
                        {{ $product->category->name }}
                    </a>
                </div>
            @endif

            <h2 style="font-size: 18px; font-weight: 600; color: #e5e7eb; margin-top: 0; margin-bottom: 10px;">
                {{ $product->name }}
            </h2>

            @php
                $avgRating = $product->reviews->avg('rating');
                $reviewCount = $product->reviews->count();
            @endphp
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 15px; font-size: 13px;">
                @if($reviewCount > 0)
                    <span style="color: #f59e0b; font-weight: bold;">★ {{ number_format($avgRating, 1) }}</span>
                    <span style="color: #9ca3af;">({{ $reviewCount }}件のレビュー)</span>
                @else
                    <span style="color: #9ca3af;">まだレビューはありません</span>
                @endif
            </div>

            <p style="color: #38bdf8; font-weight: 600; font-size: 20px; margin: 0 0 15px 0;">
                ¥{{ number_format($product->price) }}
            </p>

            <div style="margin-bottom: 20px; font-size: 13px; font-weight: 500;">
                @if ($product->stock <= 0)
                    <span style="color: #ef4444;">● 売り切れ</span>
                @elseif ($product->stock <= 5)
                    <span style="color: #f59e0b;">● 残りわずか (在庫: {{ $product->stock }})</span>
                @else
                    <span style="color: #10b981;">● 在庫あり</span>
                @endif
            </div>

            <p style="font-size: 13px; color: #d1d5db; line-height: 1.6; margin-bottom: 25px; border-top: 1px solid #2a3245; border-bottom: 1px solid #2a3245; padding: 15px 0;">
                {{ $product->description }}
            </p>

            @if ($errors->any())
                <div style="margin-bottom: 15px;">
                    @foreach ($errors->all() as $error)
                        <article class="error" style="background-color: #7f1d1d; color: #fca5a5; padding: 8px 12px; font-size: 12px; border-radius: 4px; margin-bottom: 5px;">
                            {{ $error }}
                        </article>
                    @endforeach
                </div>
            @endif

\            <form action="/cart" method="POST" style="margin-top: auto; display: flex; gap: 10px; align-items: center;">
                @csrf
                <div style="display: flex; align-items: center; gap: 5px;">
                    <label for="quantity" style="font-size: 12px; color: #9ca3af;">個数:</label>
                    <input type="number" id="quantity" name="quantity" min="1" class="@error('quantity') error @enderror" value="{{ old('quantity', 1) }}" style="width: 60px; padding: 8px; background: #0e1117; border: 1px solid #2a3245; color: #fff; font-size: 14px; border-radius: 4px; outline: none;">
                </div>
                <input type="hidden" name="productId" value="{{ $product->id }}">
                <button type="submit" style="flex: 1; padding: 10px 20px; background-color: #1f293d; color: #38bdf8; border: 1px solid #2a3245; font-weight: 600; font-size: 14px; cursor: pointer; border-radius: 4px; transition: background 0.2s;">
                    カートに入れる
                </button>
            </form>

        </div>

    </div>

    <div style="background: #13161E; border: 1px solid #2a3245; padding: 30px; border-radius: 4px;">
        <h3 style="font-size: 14px; font-weight: 600; color: #e5e7eb; margin-top: 0; margin-bottom: 20px; border-left: 3px solid #38bdf8; padding-left: 10px;">
            カスタマーレビュー
        </h3>

        @if($reviewCount > 0)
            <div style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 30px;">
                @foreach($product->reviews as $review)
                    <div style="background: #0e1117; border: 1px solid #2a3245; padding: 15px; border-radius: 4px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; font-size: 12px;">
                            <span style="color: #f59e0b; font-weight: bold; font-size: 14px;">
                                {!! str_repeat('★', $review->rating) . str_repeat('☆', 5 - $review->rating) !!}
                            </span>
                            <span style="color: #9ca3af;">{{ $review->created_at->format('Y.m.d') }}</span>
                        </div>
                        <p style="color: #d1d5db; font-size: 13px; margin: 0; line-height: 1.5;">
                            {{ $review->comment }}
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <p style="color: #9ca3af; font-size: 13px; margin-bottom: 30px;">まだレビューが投稿されていません。</p>
        @endif

        @auth
            @if(Auth::user()->hasPurchased($product->id))
                <div style="border-top: 1px solid #2a3245; padding-top: 20px;">
                    <h4 style="font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 15px;">レビューを投稿する</h4>
                    <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; font-size: 12px; color: #9ca3af; margin-bottom: 5px;">評価 (星)</label>
                            <select name="rating" style="background: #0e1117; border: 1px solid #2a3245; color: #fff; padding: 8px; border-radius: 4px; font-size: 13px; outline: none;">
                                <option value="5">★★★★★ (5)</option>
                                <option value="4">★★★★☆ (4)</option>
                                <option value="3">★★★☆☆ (3)</option>
                                <option value="2">★★☆☆☆ (2)</option>
                                <option value="1">★☆☆☆☆ (1)</option>
                            </select>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; font-size: 12px; color: #9ca3af; margin-bottom: 5px;">コメント</label>
                            <textarea name="comment" rows="4" placeholder="商品の感想を入力してください..." style="width: 100%; background: #0e1117; border: 1px solid #2a3245; color: #fff; padding: 10px; border-radius: 4px; font-size: 13px; outline: none; box-sizing: border-box;"></textarea>
                        </div>
                        <button type="submit" style="padding: 10px 20px; background-color: #1f293d; color: #38bdf8; border: 1px solid #2a3245; font-weight: 600; font-size: 13px; cursor: pointer; border-radius: 4px;">
                            レビューを送信する
                        </button>
                    </form>
                </div>
            @else
                <div style="border-top: 1px solid #2a3245; padding-top: 20px; font-size: 13px; color: #9ca3af;">
                    ※ この商品を購入したお客様のみレビューを投稿できます。
                </div>
            @endif
        @else
            <div style="border-top: 1px solid #2a3245; padding-top: 20px; font-size: 13px; color: #9ca3af;">
                <a href="{{ route('login') }}" style="color: #38bdf8; text-decoration: none;">ログイン</a>すると、購入済み商品にレビューを投稿できます。
            </div>
        @endauth

    </div>

</div>

@endsection