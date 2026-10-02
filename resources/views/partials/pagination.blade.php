@if ($paginator->hasPages())
    <nav class="pg" role="navigation" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="pg-item pg-disabled" aria-disabled="true">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pg-item" rel="prev" aria-label="Previous">‹</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="pg-item pg-disabled">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pg-item pg-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pg-item">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pg-item" rel="next" aria-label="Next">›</a>
        @else
            <span class="pg-item pg-disabled" aria-disabled="true">›</span>
        @endif
    </nav>
@endif
