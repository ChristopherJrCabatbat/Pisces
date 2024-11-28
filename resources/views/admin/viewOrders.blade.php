@extends('admin.layout')

@section('title', 'Customer Updates')

@section('styles-links')
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
            <div class="fw-bold"><i class="fa-solid fa-house me-2"></i><a href="{{ route('admin.dashboard') }}"
                    class="navigation">Dashboard</a> / Customers / <a href="{{ route('admin.updates') }}"
                    class="navigation">Customer Updates</a> /</div>
            <span class="faded-white ms-1">View Orders</span>
        </div>

        <div class="table-container mb-3">

            <!-- Filter and Search Section -->
            <div class="taas-table mb-3 d-flex justify-content-between align-items-center">
                <!-- Left Section: Filter -->
                <div class="left d-flex">
                    <div class="d-flex custom-filter me-3">
                        <select id="delivery-filter" class="form-select custom-select" aria-label="Select delivery status">
                            <option value="" selected>All Statuses</option>
                            <option value="Pending">Pending</option>
                            <option value="Preparing">Preparing</option>
                            <option value="Out for Delivery">Out for Delivery</option>
                            <option value="Delivered">Delivered</option>
                            <option value="Returned">Returned</option>
                        </select>
                        <button type="button" id="filter-button" class="btn btn-primary custom-filter-btn button-wid">
                            <i class="fa-solid fa-sort me-2"></i>Filter
                        </button>
                    </div>
                </div>

                <!-- Right Section: Search -->
                <div class="right d-flex gap-3">
                    <div class="position-relative custom-search" method="GET" id="search-form">
                        <form action="#">
                            <input type="search" placeholder="Search your orders" class="form-control" id="search-input"
                                value="{{ request('search') }}">
                            <i class="fas fa-search custom-search-icon"></i> <!-- FontAwesome search icon -->
                        </form>
                    </div>
                </div>
            </div>

            <!-- Orders Section -->
            <div class="orders-list">
                @if ($deliveriesWithImages->isNotEmpty())
                    <!-- Display the name of the first delivery -->
                    <h4 class="m-3 text-black text-center h4">
                        {{ $deliveriesWithImages->first()->name }}'s Order/s
                    </h4>
                @endif

                @forelse ($deliveriesWithImages as $delivery)
                    <div class="order-card bg-light text-black d-flex align-items-center mb-3 p-3 border rounded shadow-sm">
                        <!-- Left Image Section -->
                        <div class="order-image me-3">
                            @if ($delivery->image_url)
                                <img src="{{ $delivery->image_url }}" alt="Order Image" class="rounded"
                                    style="width: 80px; height: 80px;">
                            @else
                                <img src="{{ asset('default-image.jpg') }}" alt="Default Image" class="rounded"
                                    style="width: 80px; height: 80px;">
                            @endif
                        </div>

                        <!-- Middle Details Section -->
                        <div class="order-details flex-grow-1">
                            <p class="m-0 text-truncate fw-bold">Order Summary: {{ $delivery->order }}</p>
                            <!-- Format total_price using number_format -->
                            <p class="m-0 text-truncate">₱{{ number_format($delivery->total_price, 2) }}</p>
                            <p class="text-muted small m-0">{{ $delivery->address }}</p>
                            <p class="text-muted small m-0">{{ $delivery->created_at->format('M. d, Y') }}</p>
                        </div>

                        <!-- Right Action Section -->
                        <div class="order-actions text-end">
                            <a href="#" class="btn btn-primary mb-2">View Order</a>
                        </div>
                    </div>
                @empty
                    <div class="order-card bg-light text-black d-flex align-items-center mb-3 p-3 border rounded shadow-sm">
                        No orders.
                    </div>
                @endforelse
            </div>




        </div>


    </div>
@endsection

@section('scripts')
@endsection
