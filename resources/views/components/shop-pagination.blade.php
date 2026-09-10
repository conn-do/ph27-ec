@props(['paginator'])
@if ($paginator->hasPages())
    <nav class="pagination" aria-label="ページ切り替え">
        @if ($paginator->onFirstPage())
            <span>
                ← 前へ
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}">
                ← 前へ
            </a>
        @endif
        <span>
            {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
        </span>
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}">
                次へ →
            </a>
        @else
            <span>
                次へ →
            </span>
        @endif
    </nav>
@endif
