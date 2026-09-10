@props(['product'])
<article class="product-card">
    <a class="product-image" href="{{ route('products.show', $product) }}"><img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" loading="lazy">@if ($product->stock <= 0)<span class="stock-label">SOLD OUT</span>@endif</a>
    <div class="product-meta"><h3><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h3><span>¥{{ number_format($product->price) }}</span></div>
    <p>{{ $product->description }}</p>
</article>
