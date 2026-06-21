@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center justify-between w-full gap-2 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="flex items-center justify-center px-3 h-9 rounded-xl text-sm font-medium text-gray-300 bg-white border border-gray-200 cursor-default">
                    &lsaquo; Prev
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="flex items-center justify-center px-3 h-9 rounded-xl text-sm font-medium text-gray-500 bg-white border border-gray-200 hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-600 transition-all duration-150">
                    &lsaquo; Prev
                </a>
            @endif

            <span class="text-xs text-gray-400 px-2">Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="flex items-center justify-center px-3 h-9 rounded-xl text-sm font-medium text-gray-500 bg-white border border-gray-200 hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-600 transition-all duration-150">
                    Next &rsaquo;
                </a>
            @else
                <span class="flex items-center justify-center px-3 h-9 rounded-xl text-sm font-medium text-gray-300 bg-white border border-gray-200 cursor-default">
                    Next &rsaquo;
                </span>
            @endif
        </div>

        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between gap-4 w-full">
            <p class="text-sm text-gray-500">
                {!! __('Showing') !!}
                @if ($paginator->firstItem())
                    <span class="font-semibold text-gray-700">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-semibold text-gray-700">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                {!! __('of') !!}
                <span class="font-semibold text-gray-700">{{ $paginator->total() }}</span>
                {!! __('results') !!}
            </p>

            <div class="flex items-center gap-1.5">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="flex items-center justify-center px-3 h-9 rounded-xl text-sm font-medium text-gray-300 bg-white border border-gray-200 cursor-default">
                        &lsaquo; Prev
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="flex items-center justify-center px-3 h-9 rounded-xl text-sm font-medium text-gray-500 bg-white border border-gray-200 hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-600 transition-all duration-150">
                        &lsaquo; Prev
                    </a>
                @endif

                {{-- Current Page --}}
                <span class="flex items-center justify-center w-9 h-9 rounded-xl text-sm font-semibold text-white bg-gradient-to-br from-indigo-500 to-purple-600 shadow-sm shadow-indigo-200">
                    {{ $paginator->currentPage() }}
                </span>

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="flex items-center justify-center px-3 h-9 rounded-xl text-sm font-medium text-gray-500 bg-white border border-gray-200 hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-600 transition-all duration-150">
                        Next &rsaquo;
                    </a>
                @else
                    <span class="flex items-center justify-center px-3 h-9 rounded-xl text-sm font-medium text-gray-300 bg-white border border-gray-200 cursor-default">
                        Next &rsaquo;
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
