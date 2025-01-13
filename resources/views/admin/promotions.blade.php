@extends('admin.layout')

@section('title', 'Add Category')

@section('styles-links')
    <style>
        /* Remove right border radius for the search input */
        .no-right-radius {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        /* Remove left border radius for the search button */
        .no-left-radius {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /* .table-container {
                    padding: 1rem 2rem 0rem 2rem;
                } */
    </style>
@endsection

@section('sidebar')
    <li><a href="{{ route('admin.dashboard') }}" class="fs-5 sidebar-font"><i class="fa-solid fa-house me-3"></i>Dashboard</a>
    </li>

    <li><a href="/admin/menu" class="fs-5 sidebar-font"><i class="fa-solid fa-utensils me-3"></i> Menu</a></li>

    <li>
        <a href="/admin/delivery" class="fs-5 sidebar-font"><i class="fa-solid fa-truck-fast me-3"></i>Delivery</a>
    </li>

    <li>
        <a href="/admin/promotions" class="active fs-5 sidebar-font"><i
                class="fa-solid fa-rectangle-ad me-3"></i>Promotions</a>
    </li>

    <li class="sidebar-item position-relative" id="customersDropdown">
        <a href="javascript:void(0)"
            class="fs-5 sidebar-font d-flex customers justify-content-between {{ request()->is('admin/updates', 'admin/feedback', 'admin/monitoring') ? 'active' : '' }}">
            <div>
                <i class="fa-solid fa-users me-3"></i>Customers
            </div>
            <!-- Unread messages badge -->
            @if (isset($totalUnreadCount) && $totalUnreadCount > 0)
                <span class="badge bg-danger position-absolute translate-middle" style="left: 10.5rem; top: 1rem;">
                    {{ $totalUnreadCount }}
                </span>
            @endif
            <div class="caret-icon">
                <i class="fa-solid fa-caret-right"></i>
            </div>
        </a>
        <!-- Dropdown menu -->
        <ul class="dropdown-customers" style="display: none;">
            <li>
                <a href="{{ route('admin.updates') }}"
                    class="{{ request()->routeIs('admin.updates') ? 'active-customer-route' : '' }}">
                    <i class="fa-solid fa-user-pen me-2"></i>Customer Updates
                </a>
            </li>
            <li>
                <a href="{{ route('admin.feedback') }}"
                    class="{{ request()->routeIs('admin.feedback') ? 'active-customer-route' : '' }}">
                    <i class="fa-solid fa-comments me-2"></i>Feedback Collection
                </a>
            </li>
            <li>
                <a href="{{ route('admin.customerMessages') }}"
                    class="{{ request()->routeIs('admin.customerMessages') ? 'active-customer-route' : '' }}">
                    <i class="fa-solid fa-message"></i> Customer Messages
                    <!-- Individual unread messages badge -->
                    @if (isset($totalUnreadCount) && $totalUnreadCount > 0)
                        <span class="badge bg-danger">
                            {{ $totalUnreadCount }}
                        </span>
                    @endif
                </a>
            </li>
        </ul>
    </li>

@endsection

@section('main-content')
    <div class="main-content">

        <div class="current-file mb-3 d-flex">
            <div class="fw-bold"><i class="fa-solid fa-house me-2"></i><a href="{{ route('admin.dashboard') }}"
                    class="navigation">Dashboard</a> /
            </div>
            <span class="faded-white ms-1">Promotions</span>
        </div>

        <div class="table-container">
            {{-- <h2 class="text-black">Promotions</h2> --}}

            <div class="taas-table mb-3 d-flex justify-content-between align-items-center">

                <!-- Left Section -->
                <div class="left d-flex">
                    <div class="d-flex justify-content-between">
                        <!-- Filter Form -->
                        <form action="{{ route('admin.promotions.index') }}" method="GET" id="filter-form"
                            class="d-flex">
                            <select name="filter" id="categoryFilter" class="form-select custom-select">
                                <option value="" {{ request('filter') == '' ? 'selected' : '' }}>Default</option>
                                <option value="asc" {{ request('filter') == 'asc' ? 'selected' : '' }}>A - Z</option>
                                <option value="desc" {{ request('filter') == 'desc' ? 'selected' : '' }}>Z - A</option>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Right Section -->
                <div class="right d-flex gap-2">
                    <div class="position-relative custom-search" id="search-form">
                        <!-- Search Form -->
                        <input type="search" name="search" placeholder="Search something..." class="form-control"
                            id="search-input" value="{{ $search ?? '' }}">
                        <i class="fas fa-search custom-search-icon"></i>
                    </div>

                    <div>
                        <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary">
                            <i class="fa-solid fa-plus me-2"></i>Add
                        </a>
                    </div>
                </div>

            </div>

            {{-- Table --}}
            <table class="table text-center">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">How Often</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody id="menu-table-body">
                    @forelse ($promotions as $promotion)
                        <tr class="menu-row">
                            <td>
                                @if ($promotion->image)
                                    <img src="{{ asset('storage/' . $promotion->image) }}" alt="{{ $promotion->name }}"
                                        class="img-fluid" width="50">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                            <td>{{ $promotion->name }}</td>
                            <td>
                                @if ($promotion->how_often > 1)
                                    Every {{ $promotion->how_often }} days.
                                @else
                                    Once a day.
                                @endif
                            </td>
                            <td style="width: 16vw;">
                                <a href="{{ route('admin.promotions.show', $promotion->id) }}" class="btn btn-sm btn-info"
                                    title="View">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.promotions.edit', $promotion->id) }}"
                                    class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this promotion?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr id="">
                            <td colspan="6">No promotions found.</td>
                        </tr>
                    @endforelse
                    <tr id="no-menus-row" style="display: none">
                        <td colspan="6">No promotions found.</td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            {{-- <div class="d-flex justify-content-center">
                {{ $categories->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div> --}}

        </div>

    </div>
@endsection

@section('scripts')
    <!-- Add JavaScript -->
    <script>
        document.getElementById('categoryFilter').addEventListener('change', function() {
            document.getElementById('filter-form')
                .submit(); // Auto-submit the filter form when a new option is selected
        });
    </script>

    <!-- Filter-Search Script -->
    <script>
        function filterTable() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const categoryRows = document.querySelectorAll('#menu-table-body .menu-row');
            let hasVisibleRow = false;

            categoryRows.forEach(row => {
                const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const howOften = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

                // Check if the row matches the search term
                const matchesSearch = name.includes(searchTerm) || howOften.includes(searchTerm);

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
                    `<td colspan="6">No promotions found.</td>`;
            }
        }

        document.getElementById('search-input').addEventListener('input', filterTable);
    </script>

@endsection
