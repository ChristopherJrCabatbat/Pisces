@extends('admin.layout')

@section('title', 'Add Menu')

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
        <a href="/admin/delivery" class="active fs-5 sidebar-font"><i class="fa-solid fa-truck-fast me-3"></i>Delivery</a>
    </li>
    <li class="add-categ"><a href="/admin/rider" class="sidebar-font"><i
        class="fa-solid fa-motorcycle me-2"></i> Riders</a></li>

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
                class="navigation">Dashboard</a> / <a href="/admin/delivery" class="navigation">Delivery</a> / <a href="/admin/rider" class="navigation">Riders</a> /</div>
        <span class="faded-white ms-1">View Rider</span>
    </div>

    <div class="table-container p-4 text-black mb-4">

        <div class="taas-table d-flex justify-content-center align-items-center">
            <div class="h2"><i class="fa-solid fa-eye me-2"></i>View Rider</div>
        </div>

        <form action="{{ route('admin.rider.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
        
            <!-- Category -->
            <div class="mb-3 d-flex flex-column justify-content-start align-items-start">
                <label for="name" class="form-label">Rider Name:</label>
                <div class="form-control">{{ $rider->name }}</div>
            </div>
        
        </form>

    </div>

</div>
@endsection

@section('scripts')
@endsection
