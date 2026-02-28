@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}"
     class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-2">

    {{-- Mobile: Prev / Next only --}}
    <div class="flex justify-between w-full sm:hidden gap-3">
        @if ($paginator->onFirstPage())
            <span class="flex-1 text-center px-4 py-2 text-sm text-gray-400 bg-gray-100 rounded-xl cursor-not-allowed select-none">
                {!! __('front.pagination_prev') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               rel="prev"
               class="flex-1 text-center px-4 py-2 text-sm text-primary-700 bg-primary-50
                      border border-primary-200 rounded-xl hover:bg-primary-100 transition-colors">
                {!! __('front.pagination_prev') !!}
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               rel="next"
               class="flex-1 text-center px-4 py-2 text-sm text-primary-700 bg-primary-50
                      border border-primary-200 rounded-xl hover:bg-primary-100 transition-colors">
                {!! __('front.pagination_next') !!}
            </a>
        @else
            <span class="flex-1 text-center px-4 py-2 text-sm text-gray-400 bg-gray-100 rounded-xl cursor-not-allowed select-none">
                {!! __('front.pagination_next') !!}
            </span>
        @endif
    </div>

    {{-- Desktop: full page window --}}
    <div class="hidden sm:flex items-center gap-1">

        {{-- Prev arrow --}}
        @if ($paginator->onFirstPage())
            <span class="w-9 h-9 flex items-center justify-center rounded-xl text-gray-300 cursor-not-allowed text-lg select-none">
                ‹
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               rel="prev"
               class="w-9 h-9 flex items-center justify-center rounded-xl text-gray-500 text-lg
                      hover:bg-primary-50 hover:text-primary-600 transition-colors">
                ‹
            </a>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="w-9 h-9 flex items-center justify-center text-gray-400 text-sm select-none">
                    {{ $element }}
                </span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page"
                              class="w-9 h-9 flex items-center justify-center rounded-xl text-sm font-semibold
                                     bg-primary-600 text-white shadow-sm">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="w-9 h-9 flex items-center justify-center rounded-xl text-sm text-gray-600
                                  hover:bg-primary-50 hover:text-primary-700 transition-colors
                                  border border-transparent hover:border-primary-200">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next arrow --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               rel="next"
               class="w-9 h-9 flex items-center justify-center rounded-xl text-gray-500 text-lg
                      hover:bg-primary-50 hover:text-primary-600 transition-colors">
                ›
            </a>
        @else
            <span class="w-9 h-9 flex items-center justify-center rounded-xl text-gray-300 cursor-not-allowed text-lg select-none">
                ›
            </span>
        @endif

    </div>

    {{-- Results summary --}}
    @if ($paginator->firstItem())
    <div class="hidden sm:block text-sm text-gray-500">
        {!! __('front.pagination_info', [
            'first' => $paginator->firstItem(),
            'last'  => $paginator->lastItem(),
            'total' => $paginator->total(),
        ]) !!}
    </div>
    @endif

</nav>
@endif
