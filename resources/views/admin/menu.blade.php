@extends('admin.layout')

@section('title', 'Menu')

@section('styles-links')
@endsection

@section('sidebar')
    <li><a href="{{ route('admin.dashboard') }}" class="fs-5 sidebar-font"><i class="fa-solid fa-house me-3"></i>Dashboard</a>
    </li>
    <li><a href="#" class="active fs-5 sidebar-font"><i class="fa-solid fa-utensils me-3"></i> Menu</a></li>
    <li>
        <a href="{{ route('admin.delivery') }}" class="fs-5 sidebar-font"><i
                class="fa-solid fa-truck-fast me-3"></i>Delivery</a>
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

                    <!-- Search Input with Icon -->
                    <div class="position-relative custom-search">
                        <form action="">
                            <input type="search" placeholder="Search something..." class="form-control" id="search-input">
                            <i class="fas fa-search custom-search-icon"></i> <!-- FontAwesome search icon -->
                        </form>
                    </div>
                </div>

                <!-- Right Section -->
                <div class="right">
                    <a href="menu/create" class="btn btn-primary"><i class="fa-solid fa-plus me-2"></i>Add</a>
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
                <tbody id="search-results">
                    @include('admin.tables.menu_table', ['menus' => $menus])
                </tbody>
            </table>

        </div>

    </div>
@endsection

@section('scripts')
    <script>
        let debounceTimer;

        function debounce(func, delay) {
            return function(...args) {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => func.apply(this, args), delay);
            };
        }

        function performSearch(query) {
            fetch(`{{ route('admin.menuSearch') }}?query=${query}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('search-results').innerHTML = html;
            });
        }

        document.getElementById('search-input').addEventListener('input', debounce(function() {
            let query = this.value.trim();
            performSearch(query);
        }, 500));

        document.addEventListener('click', function(e) {
            if (e.target.closest('.pagination a')) {
                e.preventDefault();
                let page = e.target.getAttribute('href').split('page=')[1];
                let query = document.getElementById('search-input').value.trim();
                fetch(`{{ route('admin.menuSearch') }}?query=${query}&page=${page}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('search-results').innerHTML = html;
                });
            }
        });
    </script>
     {{-- <script>
        let debounceTimer;

        function debounce(func, delay) {
            return function(...args) {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => func.apply(this, args), delay);
            };
        }

        function performSearch(query) {
            fetch(`{{ route('admin.menuSearch') }}?query=${query}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('search-results').innerHTML = html;
                });
        }

        document.getElementById('search-input').addEventListener('input', debounce(function() {
            let query = this.value.trim();
            if (query.length > 0) {
                performSearch(query);
            } else {
                // Optional: Handle the case when the search box is empty
                // You may need to ensure the server-side handles an empty query correctly
                fetch(`{{ route('admin.menuSearch') }}?query=`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('search-results').innerHTML = html;
                    });
            }
        }, 500)); // Adjust the debounce delay as needed

        document.addEventListener('click', function(e) {
            if (e.target.closest('.pagination a')) {
                e.preventDefault();
                let page = e.target.getAttribute('href').split('page=')[1];
                let query = document.getElementById('search-input').value.trim();
                fetch(`{{ route('admin.menuSearch') }}?query=${query}&page=${page}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('search-results').innerHTML = html;
                    });
            }
        });
    </script> --}}
@endsection