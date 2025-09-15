@if ($paginator->hasPages())
    <ul class="pagination" style="flex-wrap: wrap;">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
            </li>
        @endif


        {{-- If current page is greater than 3 show the first ellipsis --}}
        @if ($paginator->currentPage() > 3)
            <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}">1</a></li>
        @endif
        @if ($paginator->currentPage() > 4)
            <li class="page-item disabled"><span class="page-link">...</span></li>
        @endif


        {{-- Pagination Elements --}}
        @foreach (range(1, $paginator->lastPage()) as $i)
            @if ($i >= $paginator->currentPage() - 2 && $i <= $paginator->currentPage() + 2)
                @if ($i == $paginator->currentPage())
                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                    </li>
                @endif
            @endif
        @endforeach


        {{-- If current page is less than total pages minus 3 show the last ellipsis --}}
        @if ($paginator->currentPage() < $paginator->lastPage() - 3)
            <li class="page-item disabled"><span class="page-link">...</span></li>
        @endif

        {{-- Last Page --}}
        @if ($paginator->currentPage() < $paginator->lastPage() - 2)
            <li class="page-item"><a class="page-link"
                    href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a></li>
        @endif


        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
            </li>
        @else
            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
        @endif

    </ul>
@endif
