@props([
    'number' => '1',
    'title' => '',
    'color' => 'bg-pop-blue',
])

<section class="rounded-3xl border-4 border-ink bg-white shadow-block">
    <h3 class="flex items-center gap-3 rounded-t-2xl border-b-4 border-ink px-4 py-3 text-lg font-black text-white {{ $color }}">
        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-ink bg-white text-base text-ink">
            {{ $number }}
        </span>
        {{ $title }}
    </h3>

    <div class="px-4 py-4 sm:px-6">
        {{ $slot }}
    </div>
</section>
