@props(['product'])

<a href="{{ route('products.show', $product) }}"
    class="group flex flex-col overflow-hidden rounded-3xl border-4 border-ink bg-white shadow-block block-press">

    <div class="relative aspect-square bg-paper-deep">
        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}"
            class="h-full w-full object-contain p-4 transition-transform group-hover:scale-105" loading="lazy">

        @if ($product->category)
            <span class="absolute top-2 left-2 rounded-full border-2 border-ink bg-white px-2 py-0.5 text-xs font-black">
                {{ $product->category->emoji }} {{ $product->category->name }}
            </span>
        @endif

        @unless ($product->isInStock())
            <span class="absolute inset-0 flex items-center justify-center bg-ink/60 text-2xl font-black text-white">
                うりきれ
            </span>
        @endunless
    </div>

    <div class="flex grow flex-col gap-2 border-t-4 border-ink p-3">
        <h3 class="line-clamp-2 text-base font-black leading-snug">{{ $product->name }}</h3>

        @if ($product->reviews_count)
            <x-star-rating :rating="$product->reviews_avg_rating" :count="$product->reviews_count" size="text-sm" />
        @endif

        <div class="mt-auto">
            <p class="text-2xl font-black text-pop-red">
                {{ number_format($product->price) }}<span class="text-sm">えん</span>
            </p>
            <p class="text-xs font-bold text-ink/60">
                ぜいこみ {{ number_format($product->priceWithTax()) }}えん
            </p>
        </div>
    </div>
</a>
