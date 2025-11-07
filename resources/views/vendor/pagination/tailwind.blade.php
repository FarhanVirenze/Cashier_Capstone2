@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-6">

    <!-- Info jumlah item -->
    <div>
        <p class="text-sm text-gray-800 dark:text-gray-800">
            {!! __('Showing') !!}
            @if ($paginator->firstItem())
                <span class="font-medium">{{ $paginator->firstItem() }}</span> {!! __('to') !!}
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
            @else
                {{ $paginator->count() }}
            @endif
            {!! __('of') !!}
            <span class="font-medium">{{ $paginator->total() }}</span> {!! __('results') !!}
        </p>
    </div>

    <!-- Links pagination -->
    <div class="flex flex-wrap items-center gap-2">

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-700 border border-navy-800 rounded-md cursor-not-allowed select-none opacity-70">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" 
               class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-700 border border-navy-700 rounded-md hover:bg-blue-800 hover:shadow-md transition-all duration-200">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600/50 border border-navy-700 rounded-md cursor-default select-none">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-800 border border-navy-700 rounded-md select-none">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600/80 border border-navy-700 rounded-md hover:bg-blue-700 hover:shadow-lg transition-all duration-200">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" 
               class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-700 border border-navy-700 rounded-md hover:bg-blue-600 hover:shadow-md transition-all duration-200">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-700 border border-navy-800 rounded-md cursor-not-allowed select-none opacity-70">
                {!! __('pagination.next') !!}
            </span>
        @endif
    </div>
</nav>
@endif
