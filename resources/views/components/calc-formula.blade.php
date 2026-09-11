{{--
    けいさんしきを 1ぎょう 大きく見せる。
    例: 120 × 2 = 240
    numbers は ['120', '×', '2'] のように わたし、answer に こたえを わたす。
--}}
@props([
    'parts' => [],
    'answer' => null,
    'unit' => 'えん',
    'highlight' => false,
])

<div
    {{ $attributes->merge([
        'class' => 'flex flex-wrap items-baseline gap-x-2 gap-y-1 rounded-2xl px-3 py-2 ' .
            ($highlight ? 'bg-pop-yellow/40 ring-4 ring-pop-yellow' : ''),
    ]) }}>
    @foreach ($parts as $part)
        <span class="calc-number text-2xl sm:text-3xl">{{ $part }}</span>
    @endforeach

    @if ($answer !== null)
        <span class="calc-number text-2xl text-ink/50 sm:text-3xl">=</span>
        <span class="calc-number text-3xl text-pop-red sm:text-4xl">{{ $answer }}</span>
        <span class="text-lg font-black text-pop-red">{{ $unit }}</span>
    @endif
</div>
