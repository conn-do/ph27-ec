@props(['product'])

@if (! $product->isInStock())
    <span {{ $attributes->merge(['class' => 'inline-block rounded-full border-2 border-ink bg-ink px-3 py-1 text-xs font-black text-white']) }}>
        うりきれ
    </span>
@elseif ($product->isLowStock())
    <span {{ $attributes->merge(['class' => 'inline-block rounded-full border-2 border-ink bg-pop-orange px-3 py-1 text-xs font-black text-white']) }}>
        のこり {{ $product->stock }}こ
    </span>
@else
    <span {{ $attributes->merge(['class' => 'inline-block rounded-full border-2 border-ink bg-pop-green px-3 py-1 text-xs font-black text-white']) }}>
        ざいこ あり
    </span>
@endif
