@extends('admin.layout')

@section('title', 'View Menu')

@section('styles-links')

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
            {{-- <li><a href="{{ route('admin.monitoring') }}"
                    class="{{ request()->routeIs('admin.monitoring') ? 'active-customer-route' : '' }}"><i
                        class="fa-solid fa-users-gear me-2"></i><span class="monitor-margin">Customer Activity</span>
                    <span class="monitor-margin">Monitoring</span></a></li> --}}
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
                    class="navigation">Dashboard</a> / <a href="/admin/menu" class="navigation">Menu</a> /</div>
            <span class="faded-white ms-1">View Menu</span>
        </div>

        <div class="table-container p-4 text-black mb-4">

            <div class="taas-table d-flex justify-content-center align-items-center">
                <div class="h2"><i class="fa fa-eye me-2"></i>View Menu</div>
            </div>

            <form method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Name -->
                <div class="mb-3 d-flex flex-column justify-content-start align-items-start">
                    <label for="name" class="form-label">Name:</label>
                    <div class="form-control">{{ $menus->name }}</div>
                </div>

                <div class="mb-3 w-100 d-flex justify-content-center align-items-start gap-2">
                    <!-- Price -->
                    <div class="w-50">
                        <label for="price" class="form-label">Price:</label>
                        <div class="form-control">
                            @if (floor($menus->price) == $menus->price)
                                {{ number_format($menus->price, 0) }} <!-- No decimals if it's a whole number -->
                            @else
                                {{ number_format($menus->price, 2) }} <!-- Show two decimals if it's not a whole number -->
                            @endif
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="w-50">
                        <label for="category" class="form-label">Category:</label>
                        <div class="form-control">{{ $menus->category }}</div>
                    </div>

                    <!-- Availability -->
                    <div class="w-100">
                        <label for="category" class="form-label">Availability:</label>
                        <div class="form-control">
                            {{ $menus->availability === 'Available' ? 'This menu is available.' : 'This menu is currently unavailable.' }}
                        </div>
                    </div>
                </div>

                <!-- Image -->
                <div class="mb-3 d-flex flex-column justify-content-start align-items-start">
                    <label for="image" class="form-label">Image:</label>
                    <img src="{{ Storage::url($menus->image) }}" alt="{{ $menus->name }}" class="" width="150">
                </div>

                <!-- Description -->
                <div class="mb-3 d-flex flex-column justify-content-start align-items-start">
                    <label for="description" class="form-label">Description:</label>
                    <textarea readonly class="form-control">{{ $menus->description }}</textarea>
                </div>

            </form>

        </div>

    </div>
@endsection

@section('scripts')
@endsection
