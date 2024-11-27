@extends('admin.layout')

@section('title', 'Menu')

@section('styles-links')
<style>
    .modal-content {
        color: black;
    }
</style>
@endsection

@section('sidebar')
    <li><a href="{{ route('admin.dashboard') }}" class="fs-5 sidebar-font"><i class="fa-solid fa-house me-3"></i>Dashboard</a>
    </li>
    <li><a href="#" class="active fs-5 sidebar-font"><i class="fa-solid fa-utensils me-3"></i> Menu</a></li>
    <li class="add-categ"><a href="/admin/category" class="sidebar-font"><i class="fa-solid fa-list me-2"></i> Category</a></li>

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
                        class="fa-solid fa-users-gear me-2"></i><span class="monitor-margin">Customer Activity</span>
                    <span class="monitor-margin">Monitoring</span></a></li>
        </ul>
    </li>

@endsection

@section('modals')
    <!-- Categories Modal -->
    <div class="modal fade" id="categoriesModal" tabindex="-1" aria-labelledby="categoriesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoriesModalLabel">Filter by Categories</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach ($categories as $category)
                        <div class="form-check">
                            <input class="form-check-input category-option" type="radio" name="categoryFilter"
                                id="category-{{ $category->category }}" value="{{ $category->category }}">
                            <label class="form-check-label" for="category-{{ $category->category }}">
                                {{ $category->category }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary apply-filter" data-filter="categories">Apply</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Price Modal -->
    <div class="modal fade" id="priceModal" tabindex="-1" aria-labelledby="priceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="priceModalLabel">Filter by Price</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input price-option" type="radio" name="priceFilter" id="price-expensive"
                            value="expensive">
                        <label class="form-check-label" for="price-expensive">Most Expensive</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input price-option" type="radio" name="priceFilter" id="price-cheap"
                            value="cheap">
                        <label class="form-check-label" for="price-cheap">Cheapest</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary apply-filter" data-filter="price">Apply</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Modal -->
    <div class="modal fade" id="dateModal" tabindex="-1" aria-labelledby="dateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dateModalLabel">Filter by Date</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input date-option" type="radio" name="dateFilter" id="date-recent"
                            value="recent">
                        <label class="form-check-label" for="date-recent">Recently Added</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input date-option" type="radio" name="dateFilter" id="date-oldest"
                            value="oldest">
                        <label class="form-check-label" for="date-oldest">Oldest Added</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary apply-filter" data-filter="date">Apply</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Modal -->
    <div class="modal fade" id="analyticsModal" tabindex="-1" aria-labelledby="analyticsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="analyticsModalLabel">Filter by Analytics</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input analytics-option" type="radio" name="analyticsFilter"
                            id="analytics-best-sellers" value="best_sellers">
                        <label class="form-check-label" for="analytics-best-sellers">Best Sellers</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input analytics-option" type="radio" name="analyticsFilter"
                            id="analytics-customer-favorites" value="customer_favorites">
                        <label class="form-check-label" for="analytics-customer-favorites">Customer Favorites</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary apply-filter" data-filter="analytics">Apply</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('main-content')
    <div class="main-content">

        <div class="current-file mb-3 d-flex">
            <div class="fw-bold"><i class="fa-solid fa-house me-2"></i>Dashboard /</div>
            <span class="faded-white ms-1">Menu</span>
        </div>

        <div class="table-container">

            <div class="taas-table mb-3 d-flex justify-content-between align-items-center">

                <!-- Left Section -->
                {{-- <div class="left d-flex">
                    <div class="d-flex custom-filter me-3">
                        <!-- Category Filter Section -->
                        <select id="categoryFilter" class="form-select custom-select" aria-label="Category select">
                            <option value="" selected>Default</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->category }}">{{ $category->category }}</option>
                            @endforeach
                        </select>
                        <button id="filterButton" class="btn btn-primary custom-filter-btn button-wid">
                            <i class="fa-solid fa-sort me-2"></i>Filter
                        </button>
                    </div>
                </div> --}}

                <!-- Left Section -->
                <div class="left d-flex">
                    <!-- Filter Section -->
                    <div class="filter-section d-flex align-items-center mb-3">
                        <select id="mainFilter" class="form-select custom-select" aria-label="Main Filter">
                            <option value="" selected>Filter By</option>
                            <option value="categories">Categories</option>
                            <option value="price">Price</option>
                            <option value="date">Date</option>
                            <option value="analytics">Analytics</option>
                        </select>
                    </div>
                </div>

                <!-- Right Section -->
                <div class="right d-flex gap-3">
                    <!-- Search -->
                    <div class="position-relative custom-search" method="GET" id="search-form">
                        <form action="">
                            <input type="search" placeholder="Search something..." class="form-control"
                                id="search-input" value="{{ request('search') }}">
                            <i class="fas fa-search custom-search-icon"></i> <!-- FontAwesome search icon -->
                        </form>
                    </div>

                    <div><a href="menu/create" class="btn btn-primary"><i class="fa-solid fa-plus me-2"></i>Add</a></div>
                </div>

            </div>

            {{-- Table --}}
            <table class="table text-center">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Category</th>
                        <th scope="col">Price</th>
                        <th scope="col">Description</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="menu-table-body">
                    @forelse ($menus as $menu)
                        <tr class="menu-row">
                            <!-- Image Column -->
                            <td>
                                @if ($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}"
                                        class="img-fluid" width="50">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                            <!-- Name, Category, Description -->
                            <td>{{ $menu->name }}</td>
                            <td>{{ $menu->category }}</td>
                            <!-- Price (Remove trailing .00 if present) -->
                            <td>
                                @if (floor($menu->price) == $menu->price)
                                    ₱{{ number_format($menu->price, 0) }}
                                @else
                                    ₱{{ number_format($menu->price, 2) }}
                                @endif
                            </td>
                            <td>{{ $menu->description }}</td>
                            <!-- Action Column (View, Edit, Delete) -->
                            <td>
                                <a href="{{ route('admin.menu.show', $menu->id) }}" class="btn btn-sm btn-info"
                                    title="View">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.menu.edit', $menu->id) }}" class="btn btn-sm btn-warning"
                                    title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.menu.destroy', $menu->id) }}" method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this menu?');">
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
                            <td colspan="6">No menus found.</td>
                        </tr>
                    @endforelse
                    <!-- Always include the "No menus" row, but hide it initially -->
                    <tr id="no-menus-row" style="display: none;">
                        <td colspan="6"></td>
                    </tr>

                </tbody>


            </table>

            {{-- Pagination --}}
            {{-- @include('admin.components.pagination', ['menus' => $menus]) --}}

        </div>

    </div>
@endsection

@section('scripts')

    {{-- Search / Filter Script --}}
    {{-- <script>
        // Function to filter table based on category and search term
        function filterTable() {
            const selectedCategory = document.getElementById('categoryFilter').value.toLowerCase();
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const menuRows = document.querySelectorAll('#menu-table-body .menu-row');
            let hasVisibleRow = false;

            menuRows.forEach(row => {
                const category = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const name = row.cells[1].textContent.toLowerCase();
                const price = row.cells[3].textContent.toLowerCase();
                const description = row.cells[4].textContent.toLowerCase();

                // Determine if the row matches the filter and search term
                const matchesCategory = selectedCategory === "" || category === selectedCategory;
                const matchesSearch = name.includes(searchTerm) || price.includes(searchTerm) || description
                    .includes(searchTerm);

                // Show the row if it matches both the selected category and the search term
                if (matchesCategory && matchesSearch) {
                    row.style.display = "";
                    hasVisibleRow = true;
                } else {
                    row.style.display = "none";
                }
            });

            // Handle the unified "No menus" or "No results" message
            const noMenusRow = document.getElementById('no-menus-row');
            if (hasVisibleRow) {
                noMenusRow.style.display = "none";
            } else {
                noMenusRow.style.display = "";
                noMenusRow.innerHTML =
                    `<td colspan="6">There are no menu found.</td>`;
            }
        }

        // Event listeners for category filter and search input
        document.getElementById('filterButton').addEventListener('click', filterTable);
        document.getElementById('search-input').addEventListener('input', filterTable);
    </script> --}}

    <script>
        // Filter Table Function
        function filterTable(searchTerm, categoryFilter, priceFilter, dateFilter, analyticsFilter) {
            const menuRows = document.querySelectorAll('#menu-table-body .menu-row');
            let hasVisibleRow = false;

            menuRows.forEach(row => {
                const category = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const name = row.cells[1].textContent.toLowerCase();
                const price = row.cells[3].textContent.toLowerCase();
                const description = row.cells[4].textContent.toLowerCase();

                // Conditions for matching filters
                const matchesCategory = !categoryFilter || category === categoryFilter.toLowerCase();
                const matchesSearch = !searchTerm || name.includes(searchTerm) || description.includes(
                    searchTerm) || price.includes(searchTerm);
                const matchesPrice = !priceFilter; // You can expand for price logic
                const matchesDate = !dateFilter; // Expand for date logic
                const matchesAnalytics = !analyticsFilter; // Expand for analytics logic

                if (matchesCategory && matchesSearch && matchesPrice && matchesDate && matchesAnalytics) {
                    row.style.display = '';
                    hasVisibleRow = true;
                } else {
                    row.style.display = 'none';
                }
            });

            // Show 'No menus found' row if no rows match
            const noMenusRow = document.getElementById('no-menus-row');
            noMenusRow.style.display = hasVisibleRow ? 'none' : '';
        }

        // Open modal on filter selection
        document.getElementById('mainFilter').addEventListener('change', function() {
            const selectedFilter = this.value;

            if (selectedFilter) {
                const modalId = `${selectedFilter}Modal`;
                new bootstrap.Modal(document.getElementById(modalId)).show();
            }
        });

        // Apply filter and close modal
        document.querySelectorAll('.apply-filter').forEach(button => {
            button.addEventListener('click', function() {
                const filterType = this.getAttribute('data-filter');
                let selectedValue = '';

                if (filterType === 'categories') {
                    selectedValue = document.querySelector('.category-option:checked')?.value || '';
                } else if (filterType === 'price') {
                    selectedValue = document.querySelector('.price-option:checked')?.value || '';
                } else if (filterType === 'date') {
                    selectedValue = document.querySelector('.date-option:checked')?.value || '';
                } else if (filterType === 'analytics') {
                    selectedValue = document.querySelector('.analytics-option:checked')?.value || '';
                }

                // Close modal
                bootstrap.Modal.getInstance(document.getElementById(`${filterType}Modal`)).hide();

                // Update table
                const searchTerm = document.getElementById('search-input').value.toLowerCase();
                const categoryFilter = filterType === 'categories' ? selectedValue : '';
                filterTable(searchTerm, categoryFilter);
            });
        });

        // Search input event listener
        document.getElementById('search-input').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const categoryFilter = document.querySelector('.category-option:checked')?.value || '';
            filterTable(searchTerm, categoryFilter);
        });
    </script>

@endsection
