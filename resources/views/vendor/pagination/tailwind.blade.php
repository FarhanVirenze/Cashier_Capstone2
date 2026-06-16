@if ($paginator->hasPages())
<nav role="navigation" class="flex justify-center mt-6 select-none">

    <ul class="inline-flex items-center space-x-2 text-sm font-medium">

        {{-- Prev --}}
        <li>
            @if ($paginator->onFirstPage())
                <span class="flex items-center gap-1 px-3 py-2 rounded-md bg-blue-700 text-white cursor-not-allowed opacity-70">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="flex items-center gap-1 px-3 py-2 rounded-md bg-blue-700 text-white hover:bg-blue-800 hover:shadow-md transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            @endif
        </li>

        @php
            $current = $paginator->currentPage();
            $last = $paginator->lastPage();
            $range = 2;
        @endphp

        {{-- First Page --}}
        @if ($current > 1 + $range)
            <li>
                <a href="{{ $paginator->url(1) }}"
                    class="px-3 py-2 rounded-md bg-blue-700 text-white hover:bg-blue-800 hover:shadow-md transition-all duration-200">
                    1
                </a>
            </li>
            <li><span class="px-3 py-2 rounded-md bg-blue-600/50 text-white select-none cursor-default">...</span></li>
        @endif

        {{-- Dynamic Middle Pages --}}
        @for ($i = max(1, $current - $range); $i <= min($last, $current + $range); $i++)
            <li>
                @if ($i == $current)
                    <span class="px-3 py-2 rounded-md bg-blue-500 text-white shadow-lg select-none font-semibold">
                        {{ $i }}
                    </span>
                @else
                    <a href="{{ $paginator->url($i) }}"
                        class="px-3 py-2 rounded-md bg-blue-700 text-white hover:bg-blue-800 hover:shadow-md transition-all duration-200">
                        {{ $i }}
                    </a>
                @endif
            </li>
        @endfor

        {{-- Last Page --}}
        @if ($current < $last - $range)
            <li><span class="px-3 py-2 rounded-md bg-blue-600/50 text-white select-none cursor-default">...</span></li>
            <li>
                <a href="{{ $paginator->url($last) }}"
                    class="px-3 py-2 rounded-md bg-blue-700 text-white hover:bg-blue-800 hover:shadow-md transition-all duration-200">
                    {{ $last }}
                </a>
            </li>
        @endif

        {{-- Next --}}
        <li>
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="flex items-center gap-1 px-3 py-2 rounded-md bg-blue-700 text-white hover:bg-blue-800 hover:shadow-md transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <span class="flex items-center gap-1 px-3 py-2 rounded-md bg-blue-700 text-white cursor-not-allowed opacity-70">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @endif
        </li>

    </ul>
</nav>
@endif
