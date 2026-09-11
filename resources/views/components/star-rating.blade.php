@props(['rating' => 0])

<span {{ $attributes->class(['inline-flex text-amber-500']) }}>
    @for ($i = 1; $i <= 5; $i++)
        <svg class="h-4 w-4 {{ $i <= round($rating) ? 'fill-amber-500' : 'fill-stone-200' }}" viewBox="0 0 20 20">
            <path
                d="M10 1.5l2.6 5.6 6.1.6-4.6 4.2 1.3 6.1L10 15l-5.4 3 1.3-6.1L1.3 7.7l6.1-.6L10 1.5z" />
        </svg>
    @endfor
</span>
