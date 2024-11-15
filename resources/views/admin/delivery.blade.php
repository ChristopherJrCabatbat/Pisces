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
                        class="fa-solid fa-users-gear me-2"></i><span class="monitor-margin">Customer Activity</span>
                    <span class="monitor-margin">Monitoring</span></a></li>
        </ul>
    </li>

@endsection

@section('modals')
    <!-- Product Details Modal -->
    <div class="modal fade" id="menuDetailsModal" tabindex="-1" aria-labelledby="menuDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content text-black">
                <div class="modal-header">
                    <h5 class="modal-title" id="menuDetailsModalLabel">Menu Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="product-page">
                        <form action="" method="POST" enctype="multipart/form-data">
                            @csrf
            
                            <!-- Name -->
                            <div class="mb-3 d-flex flex-column justify-content-start align-items-start">
                                <div>
                                    <label for="name" class="form-label">Name:</label>
                                    <input type="text" name="name" class="form-control-plaintext" id="category" value="{{ old('name') }}"
                                        required placeholder="e.g. Coffee" autofocus>
                                </div>
                            </div>
            
                            <!-- Image -->
                            <div class="mb-3 d-flex flex-column justify-content-start align-items-start">
                                <label for="image" class="form-label">Image:</label>
                                <input type="file" name="image" class="form-control" id="image" accept="image/*"
                                    onchange="previewImage(event)" required>
                                <img id="imagePreview" src="#" alt="Selected Image Preview"
                                    style="display:none; width:150px; margin-top:10px;">
                                @error('image')
                                    <div class="error alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
            
            
                            <div class="d-grid my-2">
                                <button class="btn btn-primary dark-blue" type="submit">Add Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('main-content')
    <div class="main-content">

        <div class="current-file mb-3 d-flex">
            <div class="fw-bold"><i class="fa-solid fa-house me-2"></i>Dashboard /</div>
            <span class="faded-white ms-1">Delivery</span>
        </div>

        <div class="table-container mb-4">
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
                            <input type="search" placeholder="Search something..." class="form-control"
                                id="search-input" value="{{ request('search') }}">
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
                        {{-- <th scope="col">Contact Number</th>
                        <th scope="col">Address</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Mode of Payment</th> --}}
                        <th scope="col">Status</th>
                        <th scope="col">View Details</th>
                    </tr>
                </thead>
                <tbody id="menu-table-body">
                    @forelse ($deliveries as $delivery)
                        <tr class="menu-row">
                            <td>{{ $delivery->name }}</td>
                            <td>{{ $delivery->order }}</td>
                            {{-- <td>{{ $delivery->contact_number }}</td>
                            <td>{{ $delivery->address }}</td>
                            <td>{{ $delivery->quantity }}</td>
                            <td>{{ $delivery->mode_of_payment }}</td> --}}
                            <td>{{ $delivery->status }}</td>
                            <td>
                                <form action="" method="GET">
                                    @csrf
                                    <button type="button" class="btn btn-primary"><i
                                            class="fa-solid fa-eye view-details-btn" title="View Menu"
                                            data-id="{{ $delivery->id }}"></i></button>
                                </form>
                            </td>
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
    {{-- Modal Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewButtons = document.querySelectorAll('.view-menu-btn');

            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const menuId = this.getAttribute('data-id');

                    // Fetch menu details via AJAX
                    fetch(`/user/menuView/${menuId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Populate the modal with menu details
                            document.getElementById('menuName').textContent = data.name;
                            document.getElementById('menuCategory').textContent = data.category;
                            document.getElementById('menuDescription').textContent = data
                                .description;
                            document.getElementById('discountedPrice').textContent =
                                `₱${parseFloat(data.price).toLocaleString()}`;
                            document.getElementById('menuRating').textContent =
                                `⭐ ${data.rating}`;
                            document.getElementById('ratingCount').textContent =
                                `(${data.ratingCount} Ratings)`;

                            // Reset the quantity input for each new modal view
                            document.getElementById('modalQuantityInput').value = 1;
                            document.getElementById('modalHiddenQuantity').value = 1;

                            // Set button destination for "Order Now"
                            document.querySelector('.modal-button.order-now').onclick =
                                function() {
                                    const quantity = document.getElementById(
                                        'modalHiddenQuantity').value;
                                    window.location.href =
                                        `/user/orderView/${menuId}?quantity=${quantity}`;
                                };



                            // Show the modal
                            const menuDetailsModal = new bootstrap.Modal(document
                                .getElementById('menuDetailsModal'));
                            menuDetailsModal.show();
                        })
                        .catch(error => {
                            console.error('Error fetching menu details:', error);
                            alert('Failed to fetch menu details. Please try again.');
                        });
                });
            });
        });
    </script>
@endsection
