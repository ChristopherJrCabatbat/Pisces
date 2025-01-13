@extends('admin.layout')

@section('title', 'Customer Updates')

@section('styles-links')
    <style>
        /* .table-container {
                    padding: 1rem 2rem 0rem 2rem;
                } */
    </style>
@endsection

@section('sidebar')
    <li>
        <a href="{{ route('admin.dashboard') }}" class="fs-5 sidebar-font">
            <i class="fa-solid fa-house me-3"></i>Dashboard
        </a>
    </li>
    <li>
        <a href="/admin/menu" class="fs-5 sidebar-font">
            <i class="fa-solid fa-utensils me-3"></i> Menu
        </a>
    </li>
    <li>
        <a href="/admin/delivery" class="fs-5 sidebar-font"><i class="fa-solid fa-truck-fast me-3"></i>Delivery</a>
    </li>
    
    <li>
        <a href="/admin/promotions" class="fs-5 sidebar-font"><i class="fa-solid fa-rectangle-ad me-3"></i>Promotions</a>
   </li>

    <li class="sidebar-item" id="customersDropdown">
        <a href="javascript:void(0)" class="fs-5 sidebar-font d-flex active customers justify-content-between">
            <div><i class="fa-solid fa-users me-3"></i>Customers</div>
            <div class="caret-icon">
                <i class="fa-solid fa-caret-right"></i>
            </div>
        </a>
        <!-- Dropdown menu -->
        <ul class="dropdown-customers">
            <li><a href="#"
                    class="{{ request()->routeIs('admin.updates') ? 'active-customer-route' : '' }} active-customer"><i
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
                        class="fa-solid fa-message me-2"></i> Customer Messages
                    <!-- Individual unread messages badge -->
                    @if (isset($totalUnreadCount) && $totalUnreadCount > 0)
                        <span class="badge bg-danger">
                            {{ $totalUnreadCount }}
                        </span>
                    @endif
                </a></li>
        </ul>
    </li>

@endsection


@section('main-content')
    <div class="main-content">

        <div class="current-file mb-3 d-flex">
            <div class="fw-bold"><i class="fa-solid fa-house me-2"></i><a href="{{ route('admin.dashboard') }}"
                    class="navigation">Dashboard</a> / Customers /</div>
            <span class="faded-white ms-1">Updates</span>
        </div>

        <div class="table-container">

            <div class="taas-table mb-3 d-flex justify-content-between align-items-center">
                <!-- Left Section -->
                <div class="left d-flex">
                    <div class="d-flex me-3 gap-2">
                        {{-- inalis form select --}}
                        <select id="delivery-filter" class="form-select" aria-label="Select delivery status">
                            <option value="default" {{ $filter === 'default' ? 'selected' : '' }}>Default</option>
                            <option value="alphabetical" {{ $filter === 'alphabetical' ? 'selected' : '' }}>Alphabetical
                            </option>
                            <option value="new" {{ $filter === 'new' ? 'selected' : '' }}>New customers first</option>
                            <option value="old" {{ $filter === 'old' ? 'selected' : '' }}>Old customers first</option>
                        </select>
                        <button type="button" id="filter-button" class="btn btn-primary custom-filter-btn button-wid-u">
                            <i class="fa-solid fa-sort me-2"></i>Filter
                        </button>
                    </div>
                </div>

                <!-- Right Section -->
                <div class="right d-flex gap-3">
                    <!-- Search -->
                    <div class="position-relative custom-search" method="GET" id="search-form">
                        <form action="#">
                            <input type="search" placeholder="Search something..." class="form-control" id="search-input"
                                value="{{ request('search') }}">
                            <i class="fas fa-search custom-search-icon"></i> <!-- FontAwesome search icon -->
                        </form>
                    </div>
                </div>
            </div>

            <table class="table text-center">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 55%">Name</th>
                        <th scope="col">User Since</th>
                        <th scope="col">View Message</th>
                        <th scope="col">View Orders</th>
                    </tr>
                </thead>
                <tbody id="updates-table-body">
                    @forelse ($users as $user)
                        <tr class="updates-row">
                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                            <td>{{ $user->created_at->format('M. d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.messageUser', ['id' => $user->id]) }}" class="btn btn-primary">
                                    <i class="fa-solid fa-message"></i>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.viewOrders', ['id' => $user->id]) }}" class="btn btn-primary">
                                    <i class="fa-solid fa-bag-shopping"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr id="no-updates-row">
                            <td colspan="5">There are no updates available.</td>
                        </tr>
                    @endforelse
                    <tr id="no-updates-row" style="display: none;">
                        <td colspan="5">There are no updates available.</td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            {{-- <div class="d-flex justify-content-center">
                {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div> --}}

        </div>

    </div>
@endsection

@section('scripts')
    <script>
        function filterUpdatesTable() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const updatesRows = document.querySelectorAll('#updates-table-body .updates-row');
            let hasVisibleRow = false;

            updatesRows.forEach(row => {
                const name = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const date = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

                const matchesSearch = name.includes(searchTerm) || date.includes(searchTerm);

                // Show or hide the row based on the search
                if (matchesSearch) {
                    row.style.display = "";
                    hasVisibleRow = true;
                } else {
                    row.style.display = "none";
                }
            });

            // Show or hide the "No users found" row
            const noUpdatesRow = document.getElementById('no-updates-row');
            if (hasVisibleRow) {
                if (noUpdatesRow) noUpdatesRow.style.display = "none";
            } else {
                if (noUpdatesRow) noUpdatesRow.style.display = "";
                noUpdatesRow.innerHTML =
                    `<td colspan="5">There are no customer updates available.</td>`;
            }
        }

        document.getElementById('search-input').addEventListener('input', filterUpdatesTable);
    </script>

    <script>
        document.getElementById('filter-button').addEventListener('click', function() {
            const filter = document.getElementById('delivery-filter').value;

            const params = new URLSearchParams({
                filter: filter,
            });

            // Reload page with query parameters
            window.location.href = '?' + params.toString();
        });
    </script>

@endsection
