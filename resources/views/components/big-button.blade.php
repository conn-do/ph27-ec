@props([
    'color' => 'bg-pop-blue',
    'text' => 'text-white',
    'type' => 'submit',
    'href' => null,
    'disabled' => false,
])

@php
    $classes = "inline-flex items-center justify-center gap-2 rounded-3xl border-4 border-ink px-6 py-4 text-xl font-black shadow-block {$color} {$text}"
        . ($disabled ? ' cursor-not-allowed opacity-50' : ' block-press');
@endphp

@if ($href && ! $disabled)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" @disabled($disabled) {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
