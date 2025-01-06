@extends('admin.layout')

@section('title', 'Add Category')

@section('styles-links')
    <style>
        /* .table-container {
            padding: 1rem 2rem 0rem 2rem;
        } */
    </style>
@endsection

@section('sidebar')
    <li>
        <a href="{{ route('admin.dashboard') }}" class="fs-5 sidebar-font"><i class="fa-solid fa-house me-3"></i>Dashboard</a>
    </li>
    <li>
        <a href="/admin/menu" class="fs-5 sidebar-font"><i class="fa-solid fa-utensils me-3"></i> Menu</a>
    </li>
    <li>
        <a href="/admin/delivery" class="active fs-5 sidebar-font"><i class="fa-solid fa-truck-fast me-3"></i>Delivery</a>
    </li>
    <li class="add-categ"><a href="/admin/rider" class="sidebar-font"><i class="fa-solid fa-motorcycle me-2"></i> Riders</a>
    </li>

    <li class="sidebar-item" id="customersDropdown">
        <a href="javascript:void(0)"
            class="fs-5 sidebar-font d-flex customers justify-content-between {{ request()->is('admin/updates', 'admin/feedback', 'admin/monitoring') ? 'active' : '' }}">
            <div><i class="fa-solid fa-users me-3"></i>Customers</div>
            <div class="caret-icon">
                <i class="fa-solid fa-caret-right"></i>
            </div>
        </a>
        <!-- Dropdown menu -->
        <ul class="dropdown-customers" style="display: none;">
            <li><a href="{{ route('admin.updates') }}"
                    class="{{ request()->routeIs('admin.updates') ? 'active-customer-route' : '' }}"><i
                        class="fa-solid fa-user-pen me-2"></i>Customer Updates</a>
            </li>
            <li><a href="{{ route('admin.feedback') }}"
                    class="{{ request()->routeIs('admin.feedback') ? 'active-customer-route' : '' }}"><i
                        class="fa-solid fa-comments me-2"></i>Feedback
                    Collection</a></li>
            {{-- <li><a href="{{ route('admin.monitoring') }}"
                    class="{{ request()->routeIs('admin.monitoring') ? 'active-customer-route' : '' }}"><i
                        class="fa-solid fa-users-gear me-2"></i><span class="monitor-margin">Customer Activity</span>
                    <span class="monitor-margin">Monitoring</span></a></li> --}}
            <li><a href="{{ route('admin.customerMessages') }}"
                    class="{{ request()->routeIs('admin.customerMessages') ? 'active-customer-route' : '' }}"><i
                        class="fa-solid fa-message me-2"></i> Customer Messages</a></li>
        </ul>
    </li>

@endsection

@section('main-content')
    <div class="main-content">

        <div class="current-file mb-3 d-flex">
            <div class="fw-bold"><i class="fa-solid fa-house me-2"></i><a href="{{ route('admin.dashboard') }}"
                    class="navigation">Dashboard</a> / <a href="{{ route('admin.delivery.index') }}"
                    class="navigation">Delivery</a>
                / </div>
            <span class="faded-white ms-1">Riders</span>
        </div>

        <div class="table-container">

            <div class="taas-table mb-3 d-flex justify-content-between align-items-center">
                <!-- Left Section -->

                {{-- Filter Section --}}
                <div class="left d-flex">
                    <div class="d-flex custom-filter me-3">
                        <form action="{{ route('admin.rider.index') }}" method="GET" id="filter-form" class="d-flex">
                            <select name="filter" id="categoryFilter" class="form-select custom-select"
                                aria-label="Filter Riders">
                                <option value="default" {{ request('filter') == 'default' ? 'selected' : '' }}>Default
                                </option>
                                <option value="alphabetically"
                                    {{ request('filter') == 'alphabetically' ? 'selected' : '' }}>
                                    Alphabetical
                                </option>
                                <option value="byRating" {{ request('filter') == 'byRating' ? 'selected' : '' }}>By Rating
                                </option>
                            </select>
                            <button type="submit" class="btn btn-primary custom-filter-btn button-wid">
                                <i class="fa-solid fa-sort me-2"></i>Filter
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Right Section -->
                <div class="right d-flex gap-3">
                    <!-- Search -->
                    <div class="position-relative custom-search" method="GET" id="search-form">
                        <!-- Search Form -->
                        <form action="#">
                            <input type="search" placeholder="Search something..." class="form-control" id="search-input"
                                value="{{ request('search') }}">
                            <i class="fas fa-search custom-search-icon"></i> <!-- FontAwesome search icon -->
                        </form>
                    </div>

                    <div><a href="{{ route('admin.rider.create') }}" class="btn btn-primary"><i
                                class="fa-solid fa-plus me-2"></i>Add</a></div>
                </div>

            </div>

            {{-- Riders Table --}}
            <table class="table text-center">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody id="menu-table-body">
                    @forelse ($riders as $rider)
                        <tr class="menu-row">
                            <td>{{ $rider->name }}</td>
                            <td>
                                @if ($rider->rating > 0)
                                    {{ number_format($rider->rating, 1) }} <i class="fa-solid fa-star"></i>
                                @else
                                    <span>No Rating</span>
                                @endif
                            </td>
                            <td style="width: 18vw;">
                                <a href="{{ route('admin.rider.show', $rider->id) }}" class="btn btn-sm btn-info"
                                    title="View">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.rider.edit', $rider->id) }}" class="btn btn-sm btn-warning"
                                    title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.rider.destroy', $rider->id) }}" method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this rider?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No riders found.</td>
                        </tr>
                    @endforelse
                    <tr class="" id="no-menus-row" style="display: none;">
                        <td colspan="3">No riders found.</td>
                    </tr>
                </tbody>
            </table>


            {{-- Pagination Links --}}
            {{-- <div class="d-flex justify-content-center">
                {{ $riders->links('pagination::bootstrap-4') }}
            </div> --}}

        </div>

    </div>
@endsection

@section('scripts')

    <!-- Filter-Search Script -->
    <script>
        function filterTable() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const categoryRows = document.querySelectorAll('#menu-table-body .menu-row');
            let hasVisibleRow = false;

            categoryRows.forEach(row => {
                const name = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const rating = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

                // Check if the row matches the search term
                const matchesSearch = name.includes(searchTerm) ||
                    rating.includes(searchTerm);

                // Show or hide the row based on the match
                if (matchesSearch) {
                    row.style.display = "";
                    hasVisibleRow = true;
                } else {
                    row.style.display = "none";
                }
            });

            // Show or hide the "No categories found" row
            const noCategoriesRow = document.getElementById('no-menus-row');
            if (hasVisibleRow) {
                noCategoriesRow.style.display = "none";
            } else {
                noCategoriesRow.style.display = "";
                noCategoriesRow.innerHTML =
                    `<td colspan="6">No rider found matching your search.</td>`;
            }
        }

        document.getElementById('search-input').addEventListener('input', filterTable);
    </script>

@endsection
