@extends('admin.layout')

@section('title', 'Delivery')

@section('styles-links')
@endsection

@section('sidebar')
    <li>
        <a href="{{ route('admin.dashboard') }}" class="fs-5 sidebar-font"><i class="fa-solid fa-house me-3"></i>Dashboard</a>
    </li>
    <li>
        <a href="/admin/menu" class="fs-5 sidebar-font"><i class="fa-solid fa-utensils me-3"></i> Menu</a>
    </li>
    <li>
        <a href="#" class="active fs-5 sidebar-font"><i class="fa-solid fa-truck-fast me-3"></i>Delivery</a>
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
            <span class="faded-white ms-1">Delivery</span>
        </div>

        <div class="table-container">
            <div class="taas-table mb-3 d-flex justify-content-between align-items-center">
                <!-- Left Section -->
                <div class="left d-flex">
                    <div class="d-flex custom-filter me-3">
                        <select class="form-select custom-select" aria-label="Default select example">
                            <option selected>Open this select delivery</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                        <button type="submit" class="btn btn-primary custom-filter-btn button-wid">
                            <i class="fa-solid fa-sort me-2"></i>Filter
                        </button>
                    </div>

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
                        <th scope="col">Name</th>
                        <th scope="col">Order</th>
                        <th scope="col">Contact Number</th>
                        <th scope="col">Address</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Mode of Payment</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody id="menu-table-body">
                    @forelse ($deliveries as $delivery)
                        <tr class="menu-row">
                            <td>{{ $delivery->name }}</td>
                            <td>{{ $delivery->order }}</td>
                            <td>{{ $delivery->contact_number }}</td>
                            <td>{{ $delivery->address }}</td>
                            <td>{{ $delivery->quantity }}</td>
                            <td>{{ $delivery->mode_of_payment }}</td>
                            <td>{{ $delivery->status }}</td>
                        </tr>
                    @empty
                        <tr id="no-menus-row">
                            <td colspan="6">There are no delivery available.</td>
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
@endsection
