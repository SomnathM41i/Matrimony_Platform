@if ($paginator->hasPages())
<nav class="lookup-pagination">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span class="page-btn page-btn-disabled">
            <i class="fas fa-chevron-left"></i>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="page-btn">
            <i class="fas fa-chevron-left"></i>
        </a>
    @endif

    {{-- Page Numbers --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="page-dots">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="page-btn page-btn-active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="page-btn">
            <i class="fas fa-chevron-right"></i>
        </a>
    @else
        <span class="page-btn page-btn-disabled">
            <i class="fas fa-chevron-right"></i>
        </span>
    @endif

    <span class="page-info">
        {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}
    </span>
</nav>

<style>
.lookup-pagination {
    display: flex; align-items: center; gap: .375rem; flex-wrap: wrap;
}
.page-btn {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 32px; height: 32px; padding: 0 .5rem;
    border-radius: 8px; border: 1px solid var(--border);
    background: var(--card-bg); color: var(--text-secondary);
    font-size: .78rem; font-weight: 600; text-decoration: none;
    transition: all .2s ease; cursor: pointer;
}
.page-btn:hover { background: var(--bg-secondary); color: var(--text-primary); }
.page-btn-active {
    background: var(--rose-grad); color: #fff; border-color: transparent;
    box-shadow: 0 3px 10px rgba(200,113,90,.3);
}
.page-btn-disabled {
    opacity: .4; cursor: not-allowed;
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 32px; height: 32px; border-radius: 8px;
    border: 1px solid var(--border); background: var(--card-bg);
    color: var(--text-muted); font-size: .78rem;
}
.page-dots { color: var(--text-muted); font-size: .8rem; padding: 0 .25rem; }
.page-info { margin-left: .5rem; font-size: .75rem; color: var(--text-muted); white-space: nowrap; }
</style>
@endif