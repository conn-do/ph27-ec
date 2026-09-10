@props(['product', 'rank' => null])

<article class="group relative overflow-hidden rounded-[1.75rem] border border-stone-200 bg-white p-3 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
    @if ($rank)
        <span class="absolute left-5 top-5 z-10 grid size-10 place-items-center rounded-full bg-amber-300 font-serif text-lg font-black text-stone-900 shadow">{{ $rank }}</span>
    @endif
    <a href="{{ route('products.show', $product) }}" class="block">
        <div class="aspect-[4/3] overflow-hidden rounded-[1.25rem] bg-stone-100">
            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        </div>
        <div class="p-3 pb-2">
            <p class="text-xs font-bold tracking-wider text-teal-700">{{ $product->category?->name ?? 'STATIONERY' }}</p>
            <h3 class="mt-2 font-serif text-lg font-bold leading-snug">{{ $product->name }}</h3>
            <div class="mt-4 flex items-end justify-between gap-3">
                <p class="text-lg font-black">¥{{ number_format($product->price) }}<span class="ml-1 text-xs font-medium text-stone-500">税込</span></p>
                @if (($product->stock ?? 0) > 0)
                    <span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-bold text-teal-800">在庫あり</span>
                @else
                    <span class="rounded-full bg-stone-200 px-3 py-1 text-xs font-bold text-stone-500">売り切れ</span>
                @endif
            </div>
        </div>
    </a>
</article>
