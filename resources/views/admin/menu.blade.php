@extends('admin.layout')

@section('title', 'Menu')

@section('styles-links')
@endsection

@section('sidebar')
    <li><a href="{{ route('admin.dashboard') }}" class="fs-5 sidebar-font"><i class="fa-solid fa-house me-3"></i>Dashboard</a>
    </li>
    <li><a href="#" class="active fs-5 sidebar-font"><i class="fa-solid fa-utensils me-3"></i> Menu</a></li>
    <li class="add-categ"><a href="{{ route('admin.menuCreateCategory') }}" class="sidebar-font"><i
                class="fa-solid fa-plus me-2"></i> Add Category</a></li>

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

@section('main-content')
    <div class="main-content">

        <div class="current-file mb-3 d-flex">
            <div class="fw-bold"><i class="fa-solid fa-house me-2"></i>Dashboard /</div>
            <span class="faded-white ms-1">Menu</span>
        </div>

        <div class="table-container">

            <div class="taas-table mb-3 d-flex justify-content-between align-items-center">
                <!-- Left Section -->
                <div class="left d-flex">
                    <div class="d-flex custom-filter me-3">
                        <select class="form-select custom-select" aria-label="Default select example">
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                        <button type="submit" class="btn btn-primary custom-filter-btn button-wid">
                            <i class="fa-solid fa-sort me-2"></i>Filter
                        </button>
                    </div>

                    <!-- Search -->
                    {{-- <div class="position-relative custom-search" method="GET" id="search-form">
                        <form action="{{ route('admin.menuSearch') }}">
                            <input type="search" placeholder="Search something..." class="form-control" id="search-input"
                                value="{{ request('search') }}">
                            <i class="fas fa-search custom-search-icon"></i> <!-- FontAwesome search icon -->
                        </form>
                    </div> --}}

                </div>

                <!-- Right Section -->
                <div class="right d-flex gap-3">
                    <!-- Search -->
                    <div class="position-relative custom-search" method="GET" id="search-form">
                        <form action="{{ route('admin.menuSearch') }}">
                            <input type="search" placeholder="Search something..." class="form-control" id="search-input"
                                value="{{ request('search') }}">
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
                                    ₱{{ number_format($menu->price, 0) }} <!-- Show without decimals -->
                                @else
                                    ₱{{ number_format($menu->price, 2) }} <!-- Show with decimals -->
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
                            <td colspan="6">There are no menus available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            {{-- @include('admin.components.pagination', ['menus' => $menus]) --}}

        </div>

    </div>
@endsection

@section('scripts')

    <!-- Updated Script -->
    <script>
        document.getElementById('search-input').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#menu-table-body tr.menu-row');
            let rowCount = 0; // To track visible rows

            // Loop through all rows
            tableRows.forEach(row => {
                // Get the text from the columns (Name, Category, Price, Description)
                const name = row.cells[1].textContent.toLowerCase();
                const category = row.cells[2].textContent.toLowerCase();
                const price = row.cells[3].textContent.toLowerCase();
                const description = row.cells[4].textContent.toLowerCase();

                // Check if the search term matches any column
                if (name.includes(searchTerm) || category.includes(searchTerm) || description.includes(
                        searchTerm) || price.includes(searchTerm)) {
                    row.style.display = ''; // Show matching row
                    rowCount++;
                } else {
                    row.style.display = 'none'; // Hide non-matching row
                }
            });

            // Handle "No results found"
            const noResultsRow = document.getElementById('no-results-row');
            if (rowCount === 0) {
                // If no "No results" row exists, create one
                if (!noResultsRow) {
                    const newRow = document.createElement('tr');
                    newRow.id = 'no-results-row';
                    newRow.innerHTML = `<td colspan="6">No results found</td>`;
                    document.getElementById('menu-table-body').appendChild(newRow);
                }
            } else {
                // If there are results, remove "No results" row
                if (noResultsRow) {
                    noResultsRow.remove();
                }
            }
        });
    </script>


@endsection
