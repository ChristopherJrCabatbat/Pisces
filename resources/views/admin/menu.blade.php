@extends('admin.layout')

@section('title', 'Menu')

@section('styles-links')
    <style>
        .modal-content {
            color: black;
        }

        /* .table-container {
                min-width: 681px;
                padding: 1rem 2rem 0rem 2rem;
            } */
    </style>

    <style>
        .no-right-radius {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .no-left-radius {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .custom-search {
            position: relative;
        }

        .custom-search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
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

@section('modals')
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="toast" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Categories Modal -->
    <div class="modal fade" id="categoriesModal" tabindex="-1" aria-labelledby="categoriesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="GET" action="{{ route('admin.menu.index') }}">


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
                        <button type="submit" class="btn btn-primary apply-filter" data-filter="categories">Apply</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <!-- Price Modal -->
    <div class="modal fade" id="priceModal" tabindex="-1" aria-labelledby="priceModalLabel" aria-hidden="true">
        <div class="modal-dialog">

            <form method="GET" action="{{ route('admin.menu.index') }}">


                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="priceModalLabel">Filter by Price</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-check">
                            <input class="form-check-input price-option" type="radio" name="priceFilter"
                                id="price-expensive" value="expensive">
                            <label class="form-check-label" for="price-expensive">Most Expensive</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input price-option" type="radio" name="priceFilter"
                                id="price-cheap" value="cheap">
                            <label class="form-check-label" for="price-cheap">Cheapest</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary apply-filter" data-filter="price">Apply</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Date Modal -->
    <div class="modal fade" id="dateModal" tabindex="-1" aria-labelledby="dateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="GET" action="{{ route('admin.menu.index') }}">


                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="dateModalLabel">Filter by Date</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-check">
                            <input class="form-check-input date-option" type="radio" name="dateFilter"
                                id="date-recent" value="recent">
                            <label class="form-check-label" for="date-recent">Recently Added</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input date-option" type="radio" name="dateFilter"
                                id="date-oldest" value="oldest">
                            <label class="form-check-label" for="date-oldest">Oldest Added</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary apply-filter" data-filter="date">Apply</button>
                    </div>
                </div>
            </form>
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
                    <div>
                        <input type="radio" id="best-sellers" name="analyticsFilter" class="analytics-option"
                            value="best-sellers">
                        <label for="best-sellers">Best Sellers</label>
                    </div>
                    <div>
                        <input type="radio" id="customer-favorites" name="analyticsFilter" class="analytics-option"
                            value="customer-favorites">
                        <label for="customer-favorites">Customer Favorites</label>
                    </div>
                    <div>
                        <input type="radio" id="highest-rated" name="analyticsFilter" class="analytics-option"
                            value="highest-rated">
                        <label for="highest-rated">Highest Rated</label>
                    </div>
                </div>
                <div class="modal-footer">
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

        <div class="table-container mb-4">

            <div class="taas-table mb-3 d-flex justify-content-between align-items-center">

                <!-- Left Section -->
                <div class="left d-flex">

                    <!-- Filter Section -->
                    <div class="filter-section d-flex align-items-center">

                        <select id="mainFilter" class="form-select custom-select" aria-label="Main Filter"
                            onchange="applyFilter(this.value)">
                            <option value="filterBy" selected disabled>Filter by</option>
                            <option value="default">Default</option>
                            <option value="categoriesModal">Categories</option>
                            <option value="priceModal">Price</option>
                            <option value="dateModal">Date</option>
                            <option value="analyticsModal">Analytics</option>
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option> <!-- New Filter -->
                        </select>

                    </div>

                </div>

                @if ($activeFilter !== 'Default view')
                    <div class="mid text-black">
                        <span class="fw-bold">Table filtered by</span>
                        <span>{{ $activeFilter }}</span>
                    </div>
                @endif

                <!-- Right Section -->
                <div class="right d-flex gap-3">

                    <!-- Search -->
                    <div class="position-relative custom-search" method="GET" id="search-form">
                        <form action="">
                            <input type="text" id="search-input" class="form-control"
                                placeholder="Search menus..." />
                            <i class="fas fa-search custom-search-icon"></i>
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
                        <th scope="col">Rating</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="menu-table-body">
                    @forelse ($menus as $menu)
                        <tr class="menu-row {{ $menu->availability === 'Unavailable' ? 'table-danger' : '' }}">
                            <!-- Image Column -->
                            <td>
                                @if ($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}"
                                        class="img-fluid" width="50">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                            <td>{{ $menu->name }}</td>
                            <td>{{ $menu->category }}</td>
                            <td>
                                @if (floor($menu->price) == $menu->price)
                                    ₱{{ number_format($menu->price, 0) }}
                                @else
                                    ₱{{ number_format($menu->price, 2) }}
                                @endif
                            </td>
                            <td style="width: 25vw !important;">{{ $menu->description }}</td>
                            <td>
                                @if ($menu->review_count > 0)
                                    {{ number_format($menu->rating, 1) }} <i class="fa-solid fa-star"></i>
                                    ({{ $menu->review_count }})
                                @else
                                    <span>No Rating</span>
                                @endif
                            </td>
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
                        <tr id="">
                            <td colspan="7">No menu available</td>
                        </tr>
                    @endforelse
                    <tr id="no-menus-row" style="display: none">
                        <td colspan="7">No menu available</td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            {{-- <div class="d-flex justify-content-center">
                {{ $menus->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div> --}}

        </div>

    </div>
@endsection

@section('scripts')

    {{-- Open Filter Modal --}}
    <script>
        function openFilterModal(modalId) {
            if (modalId) {
                new bootstrap.Modal(document.getElementById(modalId)).show();
            }
        }

        function applyFilter(value) {
            switch (value) {
                case "default":
                    window.location.href = "/admin/menu?mainFilter=default&default=true";
                    break;
                case "categoriesModal":
                    $('#categoriesModal').modal('show');
                    break;
                case "priceModal":
                    $('#priceModal').modal('show');
                    break;
                case "dateModal":
                    $('#dateModal').modal('show');
                    break;
                case "analyticsModal":
                    $('#analyticsModal').modal('show');
                    break;
                case "unavailable": // New Filter
                    window.location.href = "/admin/menu?mainFilter=unavailable";
                    break;
                case "available": // Handle Available filter
                    window.location.href = "/admin/menu?mainFilter=available";
                    break;
                default:
                    break;
            }
        }

        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('hidden.bs.modal', () => {
                document.getElementById('mainFilter').value = 'filterBy';
            });
        });


        // Toast notification
        function showToast(message) {
            const toastElement = document.getElementById('toast');
            toastElement.textContent = message;
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
        }

        document.querySelectorAll('.apply-filter').forEach(button => {
            button.addEventListener('click', function() {
                const filterType = this.getAttribute('data-filter');
                let selectedValue = '';

                if (filterType === 'analytics') {
                    selectedValue = document.querySelector('.analytics-option:checked')?.value || '';
                }

                // Redirect with filter parameter
                if (selectedValue) {
                    window.location.href = `/admin/menu?analyticsFilter=${selectedValue}`;
                }
            });
        });
    </script>

    {{-- Search / Filter Script --}}
    <script>
        function filterTable(searchTerm, categoryFilter = '', priceFilter = '', dateFilter = '', analyticsFilter = '') {
            const menuRows = document.querySelectorAll('#menu-table-body .menu-row');
            let hasVisibleRow = false;

            menuRows.forEach(row => {
                const category = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const price = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                const description = row.querySelector('td:nth-child(5)').textContent.toLowerCase();

                const matchesCategory = !categoryFilter || category === categoryFilter.toLowerCase();
                const matchesSearch = !searchTerm ||
                    name.includes(searchTerm) ||
                    description.includes(searchTerm) ||
                    price.includes(searchTerm);

                if (matchesCategory && matchesSearch) {
                    row.style.display = '';
                    hasVisibleRow = true;
                } else {
                    row.style.display = 'none';
                }
            });

            // Show or hide the "No menu available" row
            const noMenusRow = document.getElementById('no-menus-row');
            if (noMenusRow) {
                noMenusRow.style.display = hasVisibleRow ? 'none' : '';
            }

        }

        document.getElementById('mainFilter').addEventListener('change', function() {
            const filterType = this.value;
            const searchTerm = document.getElementById('search-input').value.toLowerCase();

            if (filterType) {
                const modalId = `${filterType}`;
                new bootstrap.Modal(document.getElementById(modalId)).show();
            } else {
                filterTable(searchTerm);
            }
        });

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

                if (selectedValue === 'highest-rated') {
                    // Perform the "Highest Rated" filter logic dynamically
                    window.location.href = `/admin/menu?analyticsFilter=highest-rated`;
                } else {
                    bootstrap.Modal.getInstance(document.getElementById(`${filterType}Modal`)).hide();
                    window.location.href = `/admin/menu?analyticsFilter=${selectedValue}`;
                }
            });
        });

        document.getElementById('search-input').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            filterTable(searchTerm);
        });
    </script>

@endsection
