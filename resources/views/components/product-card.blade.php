@props(['product', 'rank' => null])
<article class="product-card">
    <div class="product-image">
        <a href="{{ route('products.show', $product) }}" aria-label="{{ $product->name }}の詳細を見る">
            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" width="600" height="450" loading="lazy">
        </a>
        @if ($rank)
            <span class="rank-tag" aria-label="ランキング{{ $rank }}位">
                {{ str_pad((string) $rank, 2, '0', STR_PAD_LEFT) }}
            </span>
        @elseif ($product->stock === 0)
            <span class="stock-tag">
                SOLD OUT
            </span>
        @endif
        @auth
            <form class="favorite-form" action="{{ $product->is_favorited ? route('favorites.destroy', $product) : route('favorites.store', $product) }}" method="post" data-submit>
                @csrf
                @if ($product->is_favorited)
                    @method('delete')
                @endif
                <button class="favorite-button" type="submit" aria-label="{{ $product->is_favorited ? $product->name.'をお気に入りから外す' : $product->name.'をお気に入りに追加する' }}" aria-pressed="{{ $product->is_favorited ? 'true' : 'false' }}">
                    <span aria-hidden="true">
                        {{ $product->is_favorited ? '♥' : '♡' }}
                    </span>
                </button>
            </form>
        @else
            <a class="favorite-button" href="{{ route('login') }}" aria-label="ログインして{{ $product->name }}をお気に入りに追加する">
                <span aria-hidden="true">♡</span>
            </a>
        @endauth
    </div>
    <div class="product-meta">
        <p class="eyebrow">
            {{ $product->category?->name ?? '文房具' }}
        </p>
        <h3>
            <a href="{{ route('products.show', $product) }}">
                {{ $product->name }}
            </a>
        </h3>
        @if ($rank)
            <p class="sales-count">
                {{ number_format((int) ($product->sold_quantity ?? 0)) }}点購入されています
            </p>
        @endif
        <p class="price">
            ¥{{ number_format($product->price) }}
            <small>
                税込
            </small>
            <a href="{{ route('products.show', $product) }}" aria-label="{{ $product->name }}の詳細を見る">
                ↗
            </a>
        </p>
    </div>
</article>
