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
    {{-- <div class="modal fade" id="menuDetailsModal" tabindex="-1" aria-labelledby="menuDetailsModalLabel" aria-hidden="true">
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
    </div> --}}

    <div class="modal fade" id="deliveryDetailsModal" tabindex="-1" aria-labelledby="deliveryDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deliveryDetailsModalLabel">Delivery Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="delivery-details"></div>
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

                <!-- Right Section -->
                <div class="right d-flex gap-3">
                    <!-- Search -->
                    <div class="position-relative custom-search" method="GET" id="search-form">
                        <form action="">
                            <input type="search" placeholder="Search something..." class="form-control" id="search-input"
                                value="{{ request('search') }}">
                            <i class="fas fa-search custom-search-icon"></i> <!-- FontAwesome search icon -->
                        </form>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <table class="table text-center">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Order</th>
                        <th scope="col">Status</th>
                        <th scope="col">View Details</th>
                    </tr>
                </thead>
                <tbody id="menu-table-body">
                    @forelse ($deliveries as $delivery)
                        <tr class="menu-row">
                            <td>{{ $delivery->name }}</td>
                            <td style="max-width: 30vw">{{ $delivery->order }}</td>
                            <td>
                                <select class="form-select delivery-status-select" data-id="{{ $delivery->id }}">
                                    <option value="Pending" {{ $delivery->status === 'Pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="Preparing" {{ $delivery->status === 'Preparing' ? 'selected' : '' }}>
                                        Preparing</option>
                                    <option value="Out for Delivery"
                                        {{ $delivery->status === 'Out for Delivery' ? 'selected' : '' }}>Out for Delivery
                                    </option>
                                    <option value="Delivered" {{ $delivery->status === 'Delivered' ? 'selected' : '' }}>
                                        Delivered</option>
                                    <option value="Returned" {{ $delivery->status === 'Returned' ? 'selected' : '' }}>
                                        Returned</option>
                                </select>
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary view-details-btn" data-id="{{ $delivery->id }}">
                                    <i class="fa-solid fa-eye" title="View Delivery Details"></i>
                                </button>
                                
                            </td>

                        </tr>
                    @empty
                        <tr id="no-menus-row">
                            <td colspan="6">There are no delivery records available.</td>
                        </tr>
                    @endforelse
                    <!-- Always include the "No menus" row, but hide it initially -->
                    <tr id="no-menus-row" style="display: none;">
                        <td colspan="6"></td>
                    </tr>
                </tbody>
            </table>
        </div>


    </div>
@endsection

@section('scripts')
    {{-- Modal Script --}}
    {{-- <script>
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
    </script> --}}

    <script>
        // Function to filter the delivery table based on status and search input
        function filterTable() {
            const selectedStatus = document.getElementById('delivery-filter').value.toLowerCase();
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const deliveryRows = document.querySelectorAll('#menu-table-body .menu-row');
            let hasVisibleRow = false;

            deliveryRows.forEach(row => {
                const status = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const name = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const order = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

                // Check if the row matches the selected status and search term
                const matchesStatus = selectedStatus === "" || status === selectedStatus;
                const matchesSearch = name.includes(searchTerm) || order.includes(searchTerm);

                // Show or hide the row based on the matches
                if (matchesStatus && matchesSearch) {
                    row.style.display = "";
                    hasVisibleRow = true;
                } else {
                    row.style.display = "none";
                }
            });

            // Show or hide the "No deliveries found" row
            const noDeliveriesRow = document.getElementById('no-menus-row');
            if (hasVisibleRow) {
                noDeliveriesRow.style.display = "none";
            } else {
                noDeliveriesRow.style.display = "";
                noDeliveriesRow.innerHTML =
                    `<td colspan="6">There are no delivery records matching your filters.</td>`;
            }
        }

        // Add event listeners to filter and search inputs
        document.getElementById('filter-button').addEventListener('click', filterTable);
        document.getElementById('search-input').addEventListener('input', filterTable);
    </script>

    <script>
        document.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('delivery-status-select')) {
                const select = e.target;
                const deliveryId = select.getAttribute('data-id');
                const newStatus = select.value;

                fetch(`/admin/updateStatus/${deliveryId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                        },
                        body: JSON.stringify({
                            status: newStatus
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Status updated successfully!');
                        } else {
                            alert('Failed to update status.');
                        }
                    })
                    .catch(err => {
                        console.error('Error updating status:', err);
                    });
            }
        });
    </script>

    <script>
        document.addEventListener('click', function (e) {
    if (e.target && e.target.closest('.view-details-btn')) {
        const button = e.target.closest('.view-details-btn');
        const deliveryId = button.getAttribute('data-id');
        const detailsDiv = document.getElementById('delivery-details');

        // Clear existing content
        detailsDiv.innerHTML = '<p>Loading...</p>';

        // Fetch delivery details
        fetch(`/admin/deliveryDetails/${deliveryId}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    detailsDiv.innerHTML = `
                        <p><strong>Name:</strong> ${data.name}</p>
                        <p><strong>Email:</strong> ${data.email}</p>
                        <p><strong>Contact Number:</strong> ${data.contact_number}</p>
                        <p><strong>Address:</strong> ${data.address}</p>
                        <p><strong>Order:</strong> ${data.order}</p>
                        <p><strong>Quantity:</strong> ${data.quantity}</p>
                        <p><strong>Shipping Method:</strong> ${data.shipping_method}</p>
                        <p><strong>Mode of Payment:</strong> ${data.mode_of_payment}</p>
                        <p><strong>Note:</strong> ${data.note || 'N/A'}</p>
                        <p><strong>Status:</strong> ${data.status}</p>
                    `;
                } else {
                    detailsDiv.innerHTML = '<p>Failed to load delivery details.</p>';
                }
            })
            .catch(err => {
                console.error('Error fetching delivery details:', err);
                detailsDiv.innerHTML = '<p>Failed to load delivery details.</p>';
            });
    }
});

    </script>




@endsection
