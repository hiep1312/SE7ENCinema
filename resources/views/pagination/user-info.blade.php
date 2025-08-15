@if ($paginator->hasPages())
<nav class="d-flex justify-items-center justify-content-between">
    <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
        <div>
            <ul class="pagination1">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                <li class="page-item1 disabled" aria-disabled="true" aria-label="@lang('pagination1.previous')">
                    <span class="page-link1" aria-hidden="true">&lsaquo;</span>
                </li>
                @else
                <li class="page-item1">
                    <a class="page-link1" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        aria-label="@lang('pagination1.previous')">&lsaquo;</a>
                </li>
                @endif

                {{-- pagination1 Elements --}}
                @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                <li class="page-item1 disabled" aria-disabled="true"><span class="page-link1">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                <li class="page-item1 active" aria-current="page"><span class="page-link1">{{ $page }}</span></li>
                @else
                <li class="page-item1"><a class="page-link1" href="{{ $url }}">{{ $page }}</a></li>
                @endif
                @endforeach
                @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                <li class="page-item1">
                    <a class="page-link1" href="{{ $paginator->nextPageUrl() }}" rel="next"
                        aria-label="@lang('pagination1.next')">&rsaquo;</a>
                </li>
                @else
                <li class="page-item1 disabled" aria-disabled="true" aria-label="@lang('pagination1.next')">
                    <span class="page-link1" aria-hidden="true">&rsaquo;</span>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
@endif