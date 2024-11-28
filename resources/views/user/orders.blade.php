@extends('user.layout')

@section('title', 'Orders')

@section('styles-links')
    <style>
        .main-content {
            margin-top: 13vh;
        }

        select {
            width: 30% !important;
        }
    </style>
@endsection

@section('modals')
    <!-- Feedback Modal -->
    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="feedbackForm" class="text-black">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="feedbackModalLabel">Provide Your Feedback</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Feedback Text -->
                        <div class="mb-3">
                            <label for="feedbackText" class="form-label">Feedback</label>
                            <textarea class="form-control" id="feedbackText" name="feedback_text" rows="3"
                                placeholder="Share your experience..."></textarea>
                        </div>

                        <!-- Star Rating -->
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            <div id="starRating" class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="star{{ $i }}" name="rating"
                                        value="{{ $i }}">
                                    <label for="star{{ $i }}" class="star">★</label>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit Feedback</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('topbar')
    <li class="nav-item">
        <a class="nav-link fw-bold" aria-current="page" href="{{ route('user.dashboard') }}">HOME</a>
    </li>
    <li class="nav-item">
        <a class="nav-link fw-bold" aria-current="page" href="{{ route('user.menu') }}">MENU</a>
    </li>
    <li class="nav-item">
        <a class="nav-link fw-bold active" aria-current="page" href="#">ORDERS</a>
    </li>
    <li class="nav-item">
        <a class="nav-link fw-bold" aria-current="page" href="{{ route('user.messages') }}">MESSAGES</a>
    </li>
@endsection

@section('main-content')

    <div class="container main-content d-flex flex-column align-items-center mb-5">

        {{-- Top Container --}}
        <div class="top-container d-flex w-100 p-4 mb-5 justify-content-between align-items-center">
            <div class="fw-bold h1">Orders</div>

            {{-- Sub-Tabs --}}
            <div class="sub-tabs-container">
                <ul class="nav nav-tabs justify-content-center" id="ordersTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all"
                            type="button" role="tab" aria-controls="all" aria-selected="true">All</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending"
                            type="button" role="tab" aria-controls="pending" aria-selected="false">Pending</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="preparing-tab" data-bs-toggle="tab" data-bs-target="#preparing"
                            type="button" role="tab" aria-controls="preparing" aria-selected="false">Preparing</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="out-for-delivery-tab" data-bs-toggle="tab"
                            data-bs-target="#out-for-delivery" type="button" role="tab"
                            aria-controls="out-for-delivery" aria-selected="false">Out for Delivery</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="delivered-tab" data-bs-toggle="tab" data-bs-target="#delivered"
                            type="button" role="tab" aria-controls="delivered"
                            aria-selected="false">Delivered</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="returns-tab" data-bs-toggle="tab" data-bs-target="#returns"
                            type="button" role="tab" aria-controls="returns" aria-selected="false">Returns</button>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Content --}}
        <div class="tab-content container content user-content p-0 text-black" id="ordersTabContent">
            @foreach (['all', 'pending', 'preparing', 'out-for-delivery', 'delivered', 'returns'] as $status)
                <div class="tab-pane fade {{ $status === 'all' ? 'show active' : '' }}" id="{{ $status }}"
                    role="tabpanel" aria-labelledby="{{ $status }}-tab">
                    @forelse ($statuses[$status] as $order)
                        @include('user.orders-partial', [
                            'order' => $order,
                        ])
                    @empty
                        <div class="order-container border rounded p-4 shadow-sm">
                            <div class="d-flex align-items-center fs-5">
                                <i class="fa-regular fa-circle-question me-2"></i> There are no
                                {{ str_replace('_', ' ', $status) }} orders.
                            </div>
                        </div>
                    @endforelse
                </div>
            @endforeach
        </div>


    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#feedbackForm').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('user.feedback.store') }}",
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        alert("Feedback submitted successfully!");
                        $('#feedbackModal').modal('hide');
                    },
                    error: function(error) {
                        alert("An error occurred. Please try again.");
                    }
                });
            });
        });
    </script>
@endsection
