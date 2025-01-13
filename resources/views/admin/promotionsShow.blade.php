@extends('admin.layout')

@section('title', 'Add Category')

@section('styles-links')
    <style>
        /* Remove right border radius for the search input */
        .no-right-radius {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        /* Remove left border radius for the search button */
        .no-left-radius {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /* .table-container {
            padding: 1rem 2rem 0rem 2rem;
        } */
    </style>
@endsection

@section('sidebar')
    <li><a href="{{ route('admin.dashboard') }}" class="fs-5 sidebar-font"><i class="fa-solid fa-house me-3"></i>Dashboard</a>
    </li>

    <li><a href="/admin/menu" class="fs-5 sidebar-font"><i class="fa-solid fa-utensils me-3"></i> Menu</a></li>

    <li>
        <a href="/admin/delivery" class="fs-5 sidebar-font"><i class="fa-solid fa-truck-fast me-3"></i>Delivery</a>
    </li>
    
    <li>
        <a href="/admin/promotions" class="active fs-5 sidebar-font"><i class="fa-solid fa-rectangle-ad me-3"></i>Promotions</a>
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

@section('main-content')
    <div class="main-content">

        <div class="current-file mb-3 d-flex">
            <div class="fw-bold"><i class="fa-solid fa-house me-2"></i><a href="{{ route('admin.dashboard') }}"
                    class="navigation">Dashboard</a> / <a
                    href="/admin/promotions" class="navigation">Promotions</a> /</div>
            <span class="faded-white ms-1">Add Promotion</span>
        </div>

        <div class="table-container p-4 text-black mb-4">

            <div class="taas-table d-flex justify-content-center align-items-center">
                <div class="h2"><i class="fa-solid fa-eye me-2"></i>View Promotion</div>
            </div>

            <form action="{{ route('admin.promotions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Name -->
                <div class="mb-3 d-flex flex-column justify-content-start align-items-start">
                    <label for="name" class="form-label">Promotion Name:</label>
                    <div class="form-control">{{ $promotion->name }}</div>
                </div>
               
                <!-- How Often -->
                <div class="mb-3 d-flex flex-column justify-content-start align-items-start">
                    <label for="how_often" class="form-label">How often will this be shown to the user (by days):</label>
                    <div class="form-control">{{ $promotion->how_often }}</div>
                </div>

                <!-- Current Image -->
                <div class="mb-3 d-flex flex-column justify-content-start align-items-start">
                    <label for="current_image" class="form-label">Image:</label>
                    <img src="{{ Storage::url($promotion->image) }}" alt="{{ $promotion->name }}" class="img-fluid"
                        width="150">
                </div>

            </form>

        </div>

    </div>
@endsection

@section('scripts')
@endsection