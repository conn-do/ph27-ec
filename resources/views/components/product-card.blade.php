@props(['product'])
<article class="product-card">
    <a href="{{ route('products.show', $product) }}">
        <div class="product-image">
            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" width="600" height="450" loading="lazy">
            @if ($product->stock === 0)
                <span class="stock-tag">
                    SOLD OUT
                </span>
            @endif
        </div>
        <div class="product-meta">
            <p class="eyebrow">
                {{ $product->category?->name ?? '文房具' }}
            </p>
            <h3>
                {{ $product->name }}
            </h3>
            <p class="price">
                ¥{{ number_format($product->price) }}
                <small>
                    税込
                </small>
                <span aria-hidden="true">
                    ↗
                </span>
            </p>
        </div>
    </a>
</article>
