@props([
    'emoji' => '📭',
    'actionLabel' => null,
    'actionHref' => null,
])

<div class="rounded-3xl border-4 border-dashed border-ink/30 bg-white/60 px-6 py-16 text-center">
    <p class="text-6xl" aria-hidden="true">{{ $emoji }}</p>
    <p class="mt-4 text-xl font-black">{{ $slot }}</p>

    @if ($actionLabel && $actionHref)
        <div class="mt-6">
            <x-big-button :href="$actionHref" color="bg-pop-blue">{{ $actionLabel }}</x-big-button>
        </div>
    @endif
</div>
