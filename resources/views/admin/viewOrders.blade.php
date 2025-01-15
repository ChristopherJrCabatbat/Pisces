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
    
    <li class="position-relative">
        <a href="/admin/delivery" class="fs-5 sidebar-font">
            <i class="fa-solid fa-truck-fast me-3"></i>Delivery
            <!-- Badge for delivery statuses -->
            @if (isset($deliveryBadgeCount) && $deliveryBadgeCount > 0)
                <span class="badge position-absolute bg-danger translate-middle" style="left: 9.1rem; top: 1rem;">
                    {{ $deliveryBadgeCount }}
                </span>
            @endif
        </a>
    </li>
    
    <li>
        <a href="/admin/promotions" class="fs-5 sidebar-font"><i class="fa-solid fa-rectangle-ad me-3"></i>Promotions</a>
   </li>

    <li class="sidebar-item" id="customersDropdown">
        <a href="javascript:void(0)" class="fs-5 sidebar-font d-flex active customers justify-content-between">
            <div><i class="fa-solid fa-users me-3"></i>Customers</div>
             <!-- Unread messages badge -->
             @if (isset($totalUnreadCount) && $totalUnreadCount > 0)
             <span class="badge position-absolute translate-middle"
                 style="left: 10.5rem; top: 1rem;  background-color: white !important; color: #DC3545 !important;">
                 {{ $totalUnreadCount }}
             </span>
         @endif
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
                        @if (isset($totalUnreadCount) && $totalUnreadCount > 0)
                        <span class="badge bg-danger">
                            {{ $totalUnreadCount }}
                        </span>
                    @endif
                    </a></li>
        </ul>
    </li>

@endsection

@section('modals')

    <!-- Order Details Modal -->
    <div class="modal fade text-black" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="viewOrderModalLabel">Order Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="orderDetailsContent" class="table-responsive">
                        <p class="text-center text-muted">Loading...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('main-content')
    <div class="main-content">

        <div class="current-file mb-3 d-flex">
            <div class="fw-bold"><i class="fa-solid fa-house me-2"></i><a href="{{ route('admin.dashboard') }}"
                    class="navigation">Dashboard</a> / Customers / <a href="{{ route('admin.updates') }}"
                    class="navigation">Updates</a> /</div>
            <span class="faded-white ms-1">View Orders</span>
        </div>

        <div class="table-container mb-3">

            <!-- Filter and Search Section -->
            <div class="taas-table mb-3 d-flex justify-content-between align-items-center">
                <!-- Left Section: Filter -->
                <div class="left d-flex">
                    <div class="d-flex custom-filter me-3">
                        <select id="delivery-filter" class="form-select custom-select" aria-label="Select delivery status">
                            <option value="newest" selected>Newest</option>
                            <option value="oldest">Oldest</option>
                        </select>
                        <button type="button" id="filter-button" class="btn btn-primary custom-filter-btn button-wid">
                            <i class="fa-solid fa-sort me-2"></i>Filter
                        </button>
                    </div>
                </div>

                <!-- Right Section: Search -->
                <div class="right d-flex gap-3">
                    <div class="position-relative custom-search">
                        <input type="search" placeholder="Search your orders" class="form-control" id="search-input">
                        <i class="fas fa-search custom-search-icon"></i> <!-- FontAwesome search icon -->
                    </div>
                </div>
            </div>

            <!-- Orders Section -->
            <div class="orders-list">
                @if ($deliveriesWithImages->isNotEmpty())
                    <h4 class="m-3 text-black text-center h4">
                        {{ $deliveriesWithImages->first()->name }}'s Order/s
                    </h4>
                @endif

                <div id="orders-container">
                    @forelse ($deliveriesWithImages as $delivery)
                        <div
                            class="order-card bg-light text-black d-flex align-items-center mb-3 p-3 border rounded shadow-sm">
                            <!-- Left Image Section -->
                            <div class="order-image me-3">
                                <img src="{{ $delivery->image_url ?? asset('default-image.jpg') }}" alt="Order Image"
                                    class="rounded" style="width: 80px; height: 80px;">
                            </div>

                            <!-- Middle Details Section -->
                            <div class="order-details flex-grow-1">
                                <p class="m-0 text-truncate fw-bold">{{ $delivery->order }}</p>
                                <p class="m-0 text-truncate">₱{{ number_format($delivery->total_price, 2) }}</p>
                                <p class="text-muted small m-0">{{ $delivery->address }}</p>
                                <p class="text-muted small m-0">{{ $delivery->status }} - {{ $delivery->created_at->format('M. d, Y') }}</p>
                            </div>

                            <!-- Right Action Section -->
                            <div class="order-actions text-end">
                                <button type="button" class="btn btn-primary mb-2 view-order-btn"
                                    data-id="{{ $delivery->id }}" data-bs-toggle="modal"
                                    data-bs-target="#viewOrderModal">View
                                    Order</button>
                            </div>
                        </div>
                    @empty
                        <div
                            class="order-card bg-light text-black d-flex align-items-center mb-3 p-3 fs-5 border rounded shadow-sm">
                            <i class="fa-regular fa-circle-question me-2"></i> No orders.
                        </div>
                    @endforelse
                </div>
            </div>


        </div>


    </div>
@endsection

@section('scripts')

    {{-- Modal Script --}}
    <script>
        const initializeModalListeners = () => {
            document.querySelectorAll('.view-order-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const deliveryId = this.getAttribute('data-id');
                    const contentDiv = document.getElementById('orderDetailsContent');
        
                    // Show loading state
                    contentDiv.innerHTML = '<p class="text-center text-muted">Loading...</p>';
        
                    // Fetch order details via AJAX
                    fetch(`/admin/getOrderDetails/${deliveryId}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Fetched order details:', data); // Log entire response
    
                        if (data.success) {
                            const orders = data.delivery.order.split(', ');
                            const quantities = data.delivery.quantity.split(', ');
                            const fallbackImageUrl = "{{ asset('images/logo.jpg') }}";
        
                            // Generate order rows
                            const orderRows = orders.map((order, index) => {
                                // Extract only the menu name by removing the quantity suffix
                                const cleanedOrderName = order.replace(/\s*\(x\d+\)$/, '').trim();
                                const imageUrl = data.menu_images[cleanedOrderName] || fallbackImageUrl;
    
                                console.log(`Order: ${cleanedOrderName}, Image URL: ${imageUrl}`); // Log each image URL
        
                                return `
                                    <tr>
                                        <td>
                                            <img src="${imageUrl}" style="width: 70px; height: auto;" class="img-fluid" alt="Order Image">
                                        </td>
                                        <td>${order}</td>
                                    </tr>
                                `;
                            }).join("");
        
                            // Populate modal content
                            contentDiv.innerHTML = `
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr><th colspan="2">Order Details</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Name:</strong> ${data.delivery.name}</td>
                                            <td><strong>Email:</strong> ${data.delivery.email}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Contact:</strong> ${data.delivery.contact_number}</td>
                                            <td><strong>Address:</strong> ${data.delivery.address}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Price:</strong> ₱${Number(data.delivery.total_price).toFixed(2)}</td>
                                            <td><strong>Status:</strong> ${data.delivery.status}</td>
                                        </tr>
                                    </tbody>
                                    <thead class="table-light">
                                        <tr><th>Image</th><th>Order</th></tr>
                                    </thead>
                                    <tbody>${orderRows}</tbody>
                                </table>
                            `;
                        } else {
                            console.error('Failed to load order details:', data); // Log failure case
                            contentDiv.innerHTML = '<p class="text-center text-danger">Failed to load order details.</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching order details:', error); // Log error details
                        contentDiv.innerHTML = '<p class="text-center text-danger">An error occurred while fetching order details.</p>';
                    });
                });
            });
        };
    </script>

    {{-- Search Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filterButton = document.getElementById('filter-button');
            const deliveryFilter = document.getElementById('delivery-filter');
            const searchInput = document.getElementById('search-input');
            const ordersContainer = document.getElementById('orders-container');
            let deliveries = @json($deliveriesWithImages); // Pass PHP data to JavaScript

            const renderDeliveries = (filteredDeliveries) => {
                if (filteredDeliveries.length === 0) {
                    ordersContainer.innerHTML = `
                    <div class="order-card bg-light text-black d-flex align-items-center mb-3 p-3 fs-5 border rounded shadow-sm">
                        <i class="fa-regular fa-circle-question me-2"></i> No orders found.
                    </div>
                `;
                } else {
                    ordersContainer.innerHTML = filteredDeliveries
                        .map(
                            (delivery) => `
                    <div class="order-card bg-light text-black d-flex align-items-center mb-3 p-3 border rounded shadow-sm">
                        <div class="order-image me-3">
                            <img src="${delivery.image_url || '{{ asset('default-image.jpg') }}'}" 
                                 alt="Order Image" class="rounded" style="width: 80px; height: 80px;">
                        </div>
                        <div class="order-details flex-grow-1">
                            <p class="m-0 text-truncate fw-bold">${delivery.order}</p>
                            <p class="m-0 text-truncate">₱${parseFloat(delivery.total_price).toFixed(2)}</p>
                            <p class="text-muted small m-0">${delivery.address}</p>
                            <p class="text-muted small m-0">${delivery.status} - ${new Date(delivery.created_at).toLocaleDateString('en-US', {
                                month: 'short',
                                day: 'numeric',
                                year: 'numeric',
                            })}</p>
                        </div>
                        <div class="order-actions text-end">
                            <button type="button" class="btn btn-primary mb-2 view-order-btn" 
                                data-id="${delivery.id}" data-bs-toggle="modal" data-bs-target="#viewOrderModal">
                                View Order
                            </button>
                        </div>
                    </div>
                `
                        )
                        .join('');

                    // Reinitialize modal event listeners after rendering
                    initializeModalListeners();
                }
            };

            const filterAndSearchDeliveries = () => {
                const filterValue = deliveryFilter.value;
                const searchTerm = searchInput.value.toLowerCase();

                let filteredDeliveries = [...deliveries];

                if (filterValue === 'newest') {
                    filteredDeliveries.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                } else if (filterValue === 'oldest') {
                    filteredDeliveries.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                }

                if (searchTerm) {
                    filteredDeliveries = filteredDeliveries.filter(
                        (delivery) =>
                        delivery.order.toLowerCase().includes(searchTerm) ||
                        delivery.address.toLowerCase().includes(searchTerm) ||
                        delivery.status.toLowerCase().includes(searchTerm) ||
                        new Date(delivery.created_at).toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric',
                            year: 'numeric',
                        }).toLowerCase().includes(searchTerm)
                    );
                }

                renderDeliveries(filteredDeliveries);
            };

            filterButton.addEventListener('click', filterAndSearchDeliveries);
            searchInput.addEventListener('input', filterAndSearchDeliveries);

            renderDeliveries(deliveries);
        });
    </script>


@endsection
