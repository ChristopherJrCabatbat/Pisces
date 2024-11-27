@extends('admin.layout')

@section('title', 'Dashboard')

@section('styles-links')
    <style>
        /* Card hover effect */
        .hover-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
@endsection

@section('sidebar')
    <li>
        <a href="#" class="active fs-5 sidebar-font"><i class="fa-solid fa-house me-3"></i>Dashboard</a>
    </li>
    <li>
        <a href="/admin/menu" class="fs-5 sidebar-font"><i class="fa-solid fa-utensils me-3"></i> Menu</a>
    </li>
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
            <div class="fw-bold"><i class="fa-solid fa-house me-2"></i>Dashboard</div>
        </div>

        <!-- Dashboard Cards -->
        <div class="table-container">

            {{-- Summary --}}
            <div class="row summary">
                <!-- Users Card -->
                <div class="col-md-3">
                    <div class="card text-white bg-info mb-3 hover-card">
                        <div class="card-body">
                            <span class="icon-background"><i class="fas fa-users"></i></span>
                            <h2 class="card-title">{{ $userCount ?? 0 }}</h2>
                            <p class="card-text">Users</p>
                        </div>
                    </div>
                </div>

                <!-- Deliveries Card -->
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3 hover-card">
                        <div class="card-body">
                            <span class="icon-background"><i class="fas fa-truck"></i></span>
                            <h2 class="card-title">{{ $deliveryCount ?? 0 }}</h2>
                            <p class="card-text">Deliveries</p>
                        </div>
                    </div>
                </div>

                <!-- Menus Card -->
                <div class="col-md-3">
                    <div class="card text-white bg-warning mb-3 hover-card">
                        <div class="card-body">
                            <span class="icon-background"><i class="fas fa-utensils"></i></span>
                            <h2 class="card-title">{{ $menuCount ?? 0 }}</h2>
                            <p class="card-text">Menus</p>
                        </div>
                    </div>
                </div>

                <!-- Categories Card -->
                <div class="col-md-3">
                    <div class="card text-white bg-dark mb-3 hover-card">
                        <div class="card-body">
                            <span class="icon-background"><i class="fas fa-list"></i></span>
                            <h2 class="card-title">{{ $categoryCount ?? 0 }}</h2>
                            <p class="card-text">Categories</p>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Analytics --}}
            <div class="analytics text-black">
                <h3 class="h3 mb-3">Top Picks</h3>
                {{-- Table --}}
                <table class="table text-center">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Image</th>
                            <th scope="col">Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Price</th>
                            {{-- <th scope="col">Description</th> --}}
                            <th scope="col">Total Orders</th>
                        </tr>
                    </thead>
                    <tbody id="menu-table-body">
                        @forelse ($topPicks as $menu)
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
                                {{-- <td>{{ $menu->description }}</td> --}}
                                <!-- Total Orders Column -->
                                <td>{{ $menu->total_order_count }}</td>
                            </tr>
                        @empty
                            <tr id="no-menus-row">
                                <td colspan="6">No popular menus found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>
@endsection

@section('scripts')
@endsection
