{{-- Pagination --}}
@if ($menus->hasPages())
    <nav aria-label="Pagination">
        <ul class="pagination justify-content-end mb-1">
            {{-- Previous Page Link --}}
            @if ($menus->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">Previous</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link custom-pagination-link"
                       href="{{ $menus->appends(['search' => request('search')])->previousPageUrl() }}">
                        Previous
                    </a>
                </li>
            @endif

            {{-- Pagination Links --}}
            @foreach ($menus->links()->elements[0] as $page => $url)
                <li class="page-item {{ $page == $menus->currentPage() ? 'active custom-active' : '' }}">
                    <a class="page-link custom-pagination-link"
                       href="{{ $url }}&search={{ request('search') }}">{{ $page }}</a>
                </li>
            @endforeach

            {{-- Next Page Link --}}
            @if ($menus->hasMorePages())
                <li class="page-item">
                    <a class="page-link custom-pagination-link"
                       href="{{ $menus->appends(['search' => request('search')])->nextPageUrl() }}">
                        Next
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">Next</span>
                </li>
            @endif
        </ul>
    </nav>
@endif