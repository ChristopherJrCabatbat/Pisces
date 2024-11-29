@extends('admin.layout')

@section('title', 'Delivery')

@section('styles-links')
    <style>
        .modal-header {
            background-color: #0d6efd;
            /* Bootstrap primary color */
            color: white;
        }

        .table-bordered {
            border-color: #dee2e6;
        }

        .modal-body {
            font-size: 1rem;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>
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
    <li class="add-categ"><a href="/admin/rider" class="sidebar-font"><i class="fa-solid fa-motorcycle me-2"></i> Riders</a>
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
            <li><a href="{{ route('admin.customerMessages') }}"
                    class="{{ request()->routeIs('admin.customerMessages') ? 'active-customer-route' : '' }}"><i
                        class="fa-solid fa-message me-2"></i> Customer Messages</a></li>
        </ul>
    </li>

@endsection

@section('modals')
    <!-- Delivery Details Modal -->
    <div class="modal fade text-black" id="deliveryDetailsModal" tabindex="-1" aria-labelledby="deliveryDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Add 'modal-lg' for wider modal -->
            <div class="modal-content content-d">
                <div class="modal-header bg-primary text-white"> <!-- Add some color to the header -->
                    <h5 class="modal-title" id="deliveryDetailsModalLabel">Delivery Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>

                </div>
                <div class="modal-body">
                    <div id="delivery-details" class="table-responsive"> <!-- Add responsive table wrapper -->
                        <p>Loading...</p>
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
                        <form action="#">
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

                            <td class="td-select">
                                <form action="{{ route('admin.updateStatus', $delivery->id) }}" method="POST"
                                    class="status-form d-flex justify-content-between">
                                    @csrf
                                    @method('PUT')

                                    <select name="status" class="form-select delivery-status-select">
                                        <option value="Pending" {{ $delivery->status === 'Pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="Preparing"
                                            {{ $delivery->status === 'Preparing' ? 'selected' : '' }}>Preparing</option>
                                        <option value="Out for Delivery"
                                            {{ $delivery->status === 'Out for Delivery' ? 'selected' : '' }}>Out for
                                            Delivery</option>
                                        <option value="Delivered"
                                            {{ $delivery->status === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="Returned" {{ $delivery->status === 'Returned' ? 'selected' : '' }}>
                                            Returned</option>
                                    </select>
                                </form>
                            </td>

                            <td>
                                <button type="button" class="btn btn-primary view-details-btn"
                                    data-id="{{ $delivery->id }}" data-bs-toggle="modal"
                                    data-bs-target="#deliveryDetailsModal">
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

    {{-- Auto Update Status Script --}}
    <script>
        document.querySelectorAll('.delivery-status-select').forEach(select => {
            select.addEventListener('change', function() {
                const form = this.closest('form'); // Get the closest form
                const formData = new FormData(form); // Prepare form data
                const url = form.action; // Get the form's action URL
                const spinner = document.getElementById('loadingSpinner'); // Reference to the spinner

                // Show spinner and disable dropdown
                spinner.classList.remove('d-none');
                this.disabled = true;

                // Perform the AJAX request
                fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success toast
                            showToast(data.message, 'success');
                        } else {
                            // Show error toast
                            showToast(data.message || 'Failed to update status.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error updating status:', error);
                        showToast('An error occurred while updating the status.', 'error');
                    })
                    .finally(() => {
                        // Hide spinner and re-enable dropdown
                        spinner.classList.add('d-none');
                        this.disabled = false;
                    });
            });
        });
    </script>



    {{-- Filter-Search Table --}}
    <script>
        function filterTable() {
            const selectedStatus = document.getElementById('delivery-filter').value.toLowerCase();
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const deliveryRows = document.querySelectorAll('#menu-table-body .menu-row');
            let hasVisibleRow = false;

            deliveryRows.forEach(row => {
                const statusSelect = row.querySelector('td:nth-child(3) select'); // Get the <select> element
                const status = statusSelect ? statusSelect.value.toLowerCase() : ''; // Get the selected value
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

        document.getElementById('filter-button').addEventListener('click', filterTable);
        document.getElementById('search-input').addEventListener('input', filterTable);
    </script>

    {{-- Modal --}}
    <script>
        document.addEventListener('click', function(e) {
            if (e.target && e.target.closest('.view-details-btn')) {
                const button = e.target.closest('.view-details-btn');
                const deliveryId = button.getAttribute('data-id');
                const detailsDiv = document.getElementById('delivery-details');

                // Clear existing content
                detailsDiv.innerHTML = '<p>Loading...</p>';

                // Fetch delivery details
                fetch(`/admin/deliveryDetails/${deliveryId}`)
                    .then((response) => response.json())
                    .then((data) => {
                        if (data) {
                            // Parse the `order` and `quantity` strings
                            const orders = data.order.split(', '); // Split orders by comma
                            const quantities = data.quantity.split(', '); // Split quantities by comma

                            // Construct the rows for each order and quantity
                            const orderRows = orders.map((order, index) => `
                                <tr>
                                    <td>${order.replace(/\s\(x\d+\)/, '')}</td> <!-- Remove (x1) from the order name -->
                                    <td>${quantities[index]}</td>
                                </tr>
                            `).join("");

                            // Generate the modal content
                            detailsDiv.innerHTML = `
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <td><strong>Name:</strong> ${data.name}</td>
                                            <td><strong>Email:</strong> ${data.email}</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Contact Number:</strong> ${data.contact_number}</td>
                                            <td><strong>Address:</strong> ${data.address}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Shipping Method:</strong> ${data.shipping_method}</td>
                                            <td><strong>Mode of Payment:</strong> ${data.mode_of_payment}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><strong>Note:</strong> ${data.note || 'N/A'}</td>
                                        </tr>
                                        <thead class="table-light">
                                            <tr>
                                                <th>Order</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        ${orderRows}
                                        <thead class="table-light">
                                            <tr>
                                                <td colspan="2"><strong>Status:</strong> ${data.status}</td>
                                            </tr>
                                        </thead>
                                    </tbody>
                                </table>
                            `;
                        } else {
                            detailsDiv.innerHTML = '<p>Failed to load delivery details.</p>';
                        }
                    })
                    .catch((err) => {
                        console.error('Error fetching delivery details:', err);
                        detailsDiv.innerHTML = '<p>Failed to load delivery details.</p>';
                    });
            }
        });
    </script>


@endsection
