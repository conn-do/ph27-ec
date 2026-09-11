@props([
    'href',
    'emoji',
    'label',
    'color' => 'bg-pop-yellow',
    'badge' => 0,
])

<a href="{{ $href }}"
    {{ $attributes->merge(['class' => "relative flex flex-col items-center justify-center rounded-2xl border-4 border-ink px-2 py-1 shadow-block-sm block-press {$color}"]) }}>
    <span class="text-xl leading-none" aria-hidden="true">{{ $emoji }}</span>
    <span class="text-[10px] font-black whitespace-nowrap sm:text-xs">{{ $label }}</span>

    @if ($badge > 0)
        <span
            class="absolute -top-2 -right-2 flex h-6 min-w-6 items-center justify-center rounded-full border-2 border-ink bg-pop-red px-1 text-xs font-black text-white">
            {{ $badge }}
        </span>
    @endif
</a>
