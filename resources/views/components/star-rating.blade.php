@props([
    'rating' => 0,
    'count' => null,
    'size' => 'text-lg',
])

@php
    $filled = (int) round($rating);
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1 {$size}"]) }}>
    <span aria-hidden="true" class="tracking-tight">
        @for ($i = 1; $i <= 5; $i++)
            <span class="{{ $i <= $filled ? 'text-pop-yellow' : 'text-ink/20' }}">★</span>
        @endfor
    </span>
    <span class="sr-only">5だんかいちゅう {{ $filled }}</span>
    @if ($count !== null)
        <span class="text-sm font-bold text-ink/60">({{ $count }})</span>
    @endif
</span>
