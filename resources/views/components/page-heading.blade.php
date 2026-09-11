@props([
    'emoji' => '⭐',
    'color' => 'bg-pop-blue',
])

<div class="mb-8 flex items-center gap-4">
    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border-4 border-ink text-3xl shadow-block-sm {{ $color }}"
        aria-hidden="true">{{ $emoji }}</span>
    <div>
        <h1 class="text-2xl font-black sm:text-3xl">{{ $slot }}</h1>
        @isset($description)
            <p class="mt-1 text-sm font-bold text-ink/70">{{ $description }}</p>
        @endisset
    </div>
</div>
