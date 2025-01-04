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
    <li><a href="{{ route('admin.dashboard') }}" class="fs-5 sidebar-font"><i class="fa-solid fa-house me-3"></i>Dashboard</a>
    </li>
    <li><a href="/admin/menu" class="active fs-5 sidebar-font"><i class="fa-solid fa-utensils me-3"></i> Menu</a></li>
    <li class="add-categ"><a href="#" class="sidebar-font"><i class="fa-solid fa-list me-2"></i> Category</a></li>
    <li>
        <a href="/admin/delivery" class="fs-5 sidebar-font"><i class="fa-solid fa-truck-fast me-3"></i>Delivery</a>
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
            <li><a href="{{ route('admin.monitoring') }}"
                    class="{{ request()->routeIs('admin.monitoring') ? 'active-customer-route' : '' }}"><i
                        class="fa-solid fa-users-gear me-2"></i>Customer Activity
                    <span class="monitor-margin">Monitoring</span></a></li>
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
                    class="navigation">Dashboard</a> / <a href="{{ route('admin.menu.index') }}"
                    class="navigation">Menu</a>
                / </div>
            <span class="faded-white ms-1">Category</span>
        </div>

        <div class="table-container">

            <div class="taas-table mb-3 d-flex justify-content-between align-items-center">

                <!-- Left Section -->
                <div class="left d-flex">
                    <div class="d-flex custom-filter me-3">
                        <!-- Category Filter Section -->
                        <form action="{{ route('admin.category.index') }}" method="GET" id="filter-form" class="d-flex">
                            <select name="filter" id="categoryFilter" class="form-select custom-select"
                                aria-label="Category Filter">
                                <option value="" {{ request('filter') == '' ? 'selected' : '' }}>Default</option>
                                <option value="asc" {{ request('filter') == 'asc' ? 'selected' : '' }}>Ascending
                                </option>
                                <option value="desc" {{ request('filter') == 'desc' ? 'selected' : '' }}>Descending
                                </option>
                            </select>
                            <button type="submit" class="btn btn-primary custom-filter-btn button-wid">
                                <i class="fa-solid fa-sort me-2"></i>Apply
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Right Section -->
                <div class="right d-flex gap-3">
                    <div class="position-relative custom-search" id="search-form">
                        <!-- Search Form -->
                        <form action="{{ route('admin.category.index') }}" method="GET">
                            <input type="search" name="search" placeholder="Search something..." class="form-control"
                                id="search-input" value="{{ $search ?? '' }}">
                            <i class="fas fa-search custom-search-icon"></i>
                        </form>
                    </div>

                    {{-- <div class="position-relative custom-search" id="search-form">
                        <form action="{{ route('admin.category.index') }}" method="GET" id="search-form">
                            <input type="search" name="search" placeholder="Search something..." class="form-control"
                                id="search-input" value="{{ $search ?? '' }}" />
                            <i class="fas fa-search custom-search-icon"></i>
                        </form>
                    </div> --}}

                    <div>
                        <a href="{{ route('admin.category.create') }}" class="btn btn-primary">
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
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody id="menu-table-body">
                    @forelse ($categories as $category)
                        <tr class="menu-row">
                            <td>
                                @if ($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                        class="img-fluid" width="50">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                            <td>{{ $category->category }}</td>
                            <td style="width: 16vw;">
                                <a href="{{ route('admin.category.show', $category->id) }}" class="btn btn-sm btn-info"
                                    title="View">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.category.edit', $category->id) }}" class="btn btn-sm btn-warning"
                                    title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.category.destroy', $category->id) }}" method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this category?');">
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
                            <td colspan="6">No categories found.</td>
                        </tr>
                    @endforelse
                    <tr id="no-menus-row" style="display: none">
                        <td colspan="6">No categories found.</td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $categories->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>


            {{-- Table --}}
            {{-- <table class="table text-center">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody id="menu-table-body">
                    @forelse ($categories as $category)
                        <tr class="menu-row">
                            <td>
                                @if ($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                        class="img-fluid" width="50">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                            <td>{{ $category->category }}</td>
                            <td style="width: 16vw;">
                                <a href="{{ route('admin.category.show', $category->id) }}" class="btn btn-sm btn-info"
                                    title="View">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.category.edit', $category->id) }}" class="btn btn-sm btn-warning"
                                    title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.category.destroy', $category->id) }}" method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr id="no-menus-row">
                            <td colspan="6">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            @if (!$search)
                <div class="d-flex justify-content-center">
                    {{ $categories->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            @endif --}}

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
                const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

                // Check if the row matches the search term
                const matchesSearch = name.includes(searchTerm);

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
                    `<td colspan="6">No categories found matching your search.</td>`;
            }
        }

        document.getElementById('search-input').addEventListener('input', filterTable);
    </script>

    {{-- <script>
        const searchInput = document.getElementById('search-input');
        const menuTableBody = document.getElementById('menu-table-body');
        const noCategoriesRow = document.getElementById('no-menus-row');
    
        searchInput.addEventListener('input', function () {
            const searchTerm = searchInput.value.trim();
    
            fetch(`{{ route('admin.category.index') }}?search=${searchTerm}`)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
    
                    // Extract the updated rows and "no categories" row from response
                    const newRows = doc.querySelector('#menu-table-body').innerHTML;
                    const newNoCategoriesRow = doc.querySelector('#no-menus-row');
    
                    // Replace the current table body with the new rows
                    menuTableBody.innerHTML = newRows;
    
                    // Handle the visibility of the "No categories found" row
                    if (newNoCategoriesRow) {
                        noCategoriesRow.style.display = '';
                    } else {
                        noCategoriesRow.style.display = 'none';
                    }
                })
                .catch(err => {
                    console.error('Error fetching search results:', err);
                });
        });
    </script> --}}

@endsection
