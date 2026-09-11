@if ($paginator->hasPages())
    <nav class="flex items-center justify-center gap-3" role="navigation" aria-label="ページ おくり">
        @if ($paginator->onFirstPage())
            <span class="rounded-2xl border-4 border-ink/20 bg-white/50 px-5 py-3 text-base font-black text-ink/30">
                ← まえ
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                class="rounded-2xl border-4 border-ink bg-white px-5 py-3 text-base font-black shadow-block-sm block-press">
                ← まえ
            </a>
        @endif

        <span class="rounded-2xl border-4 border-ink bg-pop-yellow px-5 py-3 text-base font-black">
            {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
        </span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                class="rounded-2xl border-4 border-ink bg-white px-5 py-3 text-base font-black shadow-block-sm block-press">
                つぎ →
            </a>
        @else
            <span class="rounded-2xl border-4 border-ink/20 bg-white/50 px-5 py-3 text-base font-black text-ink/30">
                つぎ →
            </span>
        @endif
    </nav>
@endif
