@extends('admin.layout')

@section('title', 'Customers')

@section('styles-links')
    <style>
        .modal-content {
            color: black;
            position: absolute;
            top: -1vh;
        }

        /* .table-container {
                padding: 1rem 2rem 0rem 2rem;
            } */
             
    </style>
@endsection

@section('modals')

    {{-- View Modal --}}
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" id="modasldfkj">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Feedback Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-2 d-flex gap-3">
                        <div class="d-flex flex-column" style="width: 30%;">
                            <label for="view-order-number" class="fw-bold form-label">Order Number:</label>
                            <div id="view-order-number" class="form-control"></div>
                        </div>
                        <div class="d-flex flex-column" style="width: 70%">
                            <label for="view-customer-name" class="fw-bold form-label">Customer Name:</label>
                            <div id="view-customer-name" class="form-control"></div>
                        </div>
                    </div>

                    <div class="mb-2 d-flex gap-3">
                        <div class="d-flex flex-column" style="width: 80%">
                            <label for="view-menu-items" class="fw-bold form-label">Menu:</label>
                            <div id="view-menu-items" class="form-control"></div>
                        </div>
                        <div class="d-flex flex-column align-items-center" style="width: 20%">
                            <label for="view-rating" class="fw-bold form-label">Rating:</label>
                            <div id="view-rating" class="text-center form-control"></div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="view-feedback-text" class="fw-bold form-label">Menu Feedback:</label>
                        <textarea readonly id="view-feedback-text" rows="2" class="form-control"></textarea>
                    </div>

                    <div class="mb-2 d-flex gap-3">
                        <div class="d-flex flex-column" style="width: 80%">
                            <label for="view-rider" class="fw-bold form-label">Rider:</label>
                            <div id="view-rider" class="form-control"></div>
                        </div>
                        <div class="d-flex flex-column align-items-center" style="width: 20%">
                            <label for="view-rider-rating" class="fw-bold form-label">Rating:</label>
                            <div id="view-rider-rating" class="text-center form-control"></div>
                        </div>
                    </div>
                  
                    <div class="mb-2">
                        <label for="view-feedback-rider" class="fw-bold form-label">Rider Feedback:</label>
                        <textarea readonly id="view-feedback-rider" rows="2" class="form-control"></textarea>
                    </div>

                    <div class="mb-2">
                        <label for="view-response" class="fw-bold form-label">Response:</label>
                        <textarea name="response" readonly id="view-response" class="form-control" rows="2" required
                            placeholder="No response yet."></textarea>
                    </div>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div> --}}
            </div>
        </div>
    </div>

    {{-- Respond Modal --}}
    <div class="modal fade" id="respondModal" tabindex="-1" aria-labelledby="respondModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="respondModalLabel">Respond to <span id="customer-name"></span>'s Feedback
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.respondFeedback') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        
                        <div class="mb-2 d-flex gap-3">
                            <div class="d-flex flex-column" style="width: 80%">
                                <label for="respond-menu-items" class="fw-bold form-label">Menu:</label>
                                <div id="respond-menu-items" class="form-control"></div>
                            </div>
                            <div class="d-flex flex-column align-items-center" style="width: 20%">
                                <label for="respond-rating" class="fw-bold form-label">Rating:</label>
                            <div id="respond-rating" class="form-control text-center"></div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="respond-feedback-text" class="fw-bold form-label">Menu Feedback:</label>
                            <textarea rows="2" id="respond-feedback-text" class="form-control"></textarea>
                        </div>
                       
                        <div class="mb-2 d-flex gap-3">
                            <div class="d-flex flex-column" style="width: 80%">
                                <label for="respond-rider" class="fw-bold form-label">Rider:</label>
                                <div id="respond-rider" class="form-control"></div>
                            </div>
                            <div class="d-flex flex-column align-items-center" style="width: 20%">
                                <label for="respond-rider-rating" class="fw-bold form-label">Rating:</label>
                            <div id="respond-rider-rating" class="form-control text-center"></div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="respond-feedback-rider" class="fw-bold form-label">Rider Feedback:</label>
                            <textarea rows="2" id="respond-feedback-rider" class="form-control"></textarea>
                        </div>

                        <div class="mb-2">
                            <label for="response-text" class="fw-bold form-label">Response:</label>
                            <textarea name="response" autofocus id="response-text" class="form-control" rows="2" required
                                placeholder="Type response here..."></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="feedback_id" id="feedback-id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Response</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                <span class="badge position-absolute bg-danger translate-middle"
                      style="left: 9.1rem; top: 1rem;">
                    {{ $deliveryBadgeCount }}
                </span>
            @endif
        </a>
    </li>

    <li>
        <a href="/admin/promotions" class="fs-5 sidebar-font"><i class="fa-solid fa-rectangle-ad me-3"></i>Promotions</a>
    </li>

    <li class="sidebar-item" id="customersDropdown">
        <a href="javascript:void(0)"
            class="fs-5 sidebar-font d-flex customers justify-content-between {{ request()->is('admin/updates', 'admin/feedback', 'admin/monitoring') ? 'active' : '' }}">
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
            <li><a href="{{ route('admin.updates') }}"
                    class="{{ request()->routeIs('admin.updates') ? 'active-customer-route' : '' }}"><i
                        class="fa-solid fa-user-pen me-2"></i>Customer Updates</a>
            </li>
            <li><a href="{{ route('admin.feedback') }}"
                    class="{{ request()->routeIs('admin.feedback') ? 'active-customer-route' : '' }} active-customer"><i
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


@section('main-content')
    <div class="main-content">

        <div class="current-file mb-3 d-flex">
            <div class="fw-bold"><i class="fa-solid fa-house me-2"></i><a href="{{ route('admin.dashboard') }}"
                    class="navigation">Dashboard</a> / <a href="#" class="navigation">Customers</a>
                / </div>
            <span class="faded-white ms-1">Feedback Collection</span>
        </div>

        <div class="table-container">

            <div class="taas-table mb-3 d-flex justify-content-between align-items-center">
                <!-- Left Section -->
                <div class="left d-flex">
                    <div class="d-flex custom-filter me-3">
                        <!-- Category Filter Section -->
                        <select id="categoryFilter" class="form-select custom-select" aria-label="Category select">
                            <option value="default" {{ $filter === 'default' ? 'selected' : '' }}>Default</option>
                            <option value="name" {{ $filter === 'name' ? 'selected' : '' }}>Customer</option>
                            <option value="menu" {{ $filter === 'menu' ? 'selected' : '' }}>Menu</option>
                            <option value="rating" {{ $filter === 'rating' ? 'selected' : '' }}>Rating</option>
                            <option value="withoutResponse" {{ $filter === 'withoutResponse' ? 'selected' : '' }}>Without
                                Response</option>
                        </select>
                        <button id="filterButton" class="btn btn-primary custom-filter-btn button-wid">
                            <i class="fa-solid fa-sort me-2"></i>Filter
                        </button>
                    </div>
                </div>

                <!-- Right Section -->
                <div class="right d-flex gap-3">
                    <!-- Search -->
                    <div class="position-relative custom-search" method="GET" id="search-form">
                        <form action="">
                            <input type="search" placeholder="Search something..." class="form-control"
                                id="search-input" value="{{ request('search') }}">
                            <i class="fas fa-search custom-search-icon"></i> <!-- FontAwesome search icon -->
                        </form>
                    </div>
                </div>

            </div>

            {{-- Feedback Table --}}
            <table class="table text-center">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Menu</th>
                        <th scope="col" style="width: 35vw;">Feedback</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="feedback-table-body">
                    @forelse ($feedbacks as $feedback)
                        <tr class="feedback-row">
                            <td>{{ $feedback->customer_name }}</td>
                            <td>{{ $feedback->menu_items }}</td>
                            <td>
                                {{-- {{ $feedback->feedback_text ?: 'No feedback' }} --}}
                                {{ Str::words($feedback->feedback_text ?: 'No Feedback', 13, '...') }}
                            </td>
                            <td>
                                @if ($feedback->rating > 0)
                                    {{ $feedback->rating }} <i class="fa-solid fa-star"></i>
                                @else
                                    <span>No Rating</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary view-feedback"
                                    data-feedback="{{ $feedback }}">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-primary respond-feedback"
                                    data-feedback="{{ $feedback }}">
                                    <i class="fa-solid fa-reply"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr id="">
                            <td colspan="5" class="text-center">No feedback available.</td>
                        </tr>
                    @endforelse
                    <tr id="no-feedback-row" style="display: none;">
                        <td colspan="5" class="text-center">No feedback available.</td>
                    </tr>
                </tbody>
            </table>

            {{-- <!-- Pagination -->
             <div class="d-flex justify-content-center">
                {{ $feedbacks->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div> --}}


        </div>
    @endsection

    @section('scripts')

        <script>
            document.querySelectorAll('.view-feedback').forEach(button => {
                button.addEventListener('click', function() {
                    const feedback = JSON.parse(this.dataset.feedback);
                    document.getElementById('view-customer-name').textContent = feedback.customer_name;
                    document.getElementById('view-order-number').textContent = feedback.order_number;
                    document.getElementById('view-menu-items').textContent = feedback.menu_items;
                    document.getElementById('view-feedback-text').textContent = feedback.feedback_text;
                    document.getElementById('view-rider').textContent = feedback.rider_name;
                    document.getElementById('view-rider-rating').textContent = feedback.rider_rating;
                    document.getElementById('view-feedback-rider').textContent = feedback.feedback_rider;
                    // document.getElementById('view-sentiment').textContent = feedback.sentiment;
                    document.getElementById('view-rating').textContent = feedback.rating;
                    document.getElementById('view-response').textContent = feedback.response ||
                        'No response yet';
                    new bootstrap.Modal(document.getElementById('viewModal')).show();
                });
            });

            document.querySelectorAll('.respond-feedback').forEach(button => {
                button.addEventListener('click', function() {
                    const feedback = JSON.parse(this.dataset.feedback);
                    document.getElementById('customer-name').textContent = feedback.customer_name;
                    document.getElementById('respond-menu-items').textContent = feedback.menu_items;
                    document.getElementById('respond-feedback-text').textContent = feedback.feedback_text;
                    document.getElementById('respond-rider').textContent = feedback.rider_name;
                    document.getElementById('respond-rider-rating').textContent = feedback.rider_rating;
                    document.getElementById('respond-feedback-rider').textContent = feedback.feedback_rider;
                    document.getElementById('respond-rating').textContent = feedback.rating;
                    document.getElementById('feedback-id').value = feedback.id;
                    new bootstrap.Modal(document.getElementById('respondModal')).show();
                });
            });
        </script>

        {{-- Auto Sentiment --}}
        <script>
            document.querySelectorAll('.sentiment-select').forEach(select => {
                select.addEventListener('change', function() {
                    const form = this.closest('form'); // Get the closest form
                    const formData = new FormData(form); // Prepare form data
                    const url = form.action; // Get the form's action URL
                    const spinner = document.getElementById('loadingSpinner'); // Reference to spinner

                    // Show the spinner
                    spinner.classList.remove('d-none');

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
                                showToast(data.message || 'Failed to update sentiment.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error updating sentiment:', error);
                            showToast('An error occurred while updating the sentiment.', 'error');
                        })
                        .finally(() => {
                            // Hide the spinner
                            spinner.classList.add('d-none');
                        });
                });
            });
        </script>

        {{-- Auto Search --}}
        <script>
            function filterFeedbackTable() {
                const searchTerm = document.getElementById('search-input').value.toLowerCase();
                const feedbackRows = document.querySelectorAll('#feedback-table-body .feedback-row');
                let hasVisibleRow = false;

                feedbackRows.forEach(row => {
                    const orderNumber = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                    const customerName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const menuItems = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const feedback = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

                    const matchesSearch = orderNumber.includes(searchTerm) || customerName.includes(searchTerm) ||
                        menuItems.includes(searchTerm) || feedback.includes(searchTerm);

                    // Show or hide the row based on the search
                    if (matchesSearch) {
                        row.style.display = "";
                        hasVisibleRow = true;
                    } else {
                        row.style.display = "none";
                    }
                });

                // Show or hide the "No feedback found" row
                const noFeedbackRow = document.getElementById('no-feedback-row');
                if (hasVisibleRow) {
                    noFeedbackRow.style.display = "none";
                } else {
                    noFeedbackRow.style.display = "";
                    noFeedbackRow.innerHTML =
                        `<td colspan="7">There are no feedback records matching your filters.</td>`;
                }
            }

            document.getElementById('search-input').addEventListener('input', filterFeedbackTable);
        </script>

        <script>
            document.getElementById('filterButton').addEventListener('click', function() {
                const filterValue = document.getElementById('categoryFilter').value;
                // const searchValue = document.getElementById('search-input').value;

                // Redirect to the same page with query parameters for filtering and searching
                const queryParams = new URLSearchParams({
                    filter: filterValue,
                    // search: searchValue
                });
                window.location.href = `{{ route('admin.feedback') }}?${queryParams.toString()}`;
            });
        </script>

    @endsection
