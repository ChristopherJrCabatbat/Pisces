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
            {{-- lagyan mb-4 --}}
            <div class="sub-tabs-container">
                <ul class="nav nav-tabs justify-content-center" id="ordersTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all"
                            type="button" role="tab" aria-controls="all" aria-selected="true">All</button>
                    </li>
                    {{-- <li class="nav-item" role="presentation">
                        <button class="nav-link" id="unpaid-tab" data-bs-toggle="tab" data-bs-target="#unpaid"
                            type="button" role="tab" aria-controls="unpaid" aria-selected="false">Unpaid</button>
                    </li> --}}
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="to-ship-tab" data-bs-toggle="tab" data-bs-target="#to-ship"
                            type="button" role="tab" aria-controls="to-ship" aria-selected="false">Preparing</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="shipped-tab" data-bs-toggle="tab" data-bs-target="#shipped"
                            type="button" role="tab" aria-controls="shipped" aria-selected="false">Out for Delivery</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="to-review-tab" data-bs-toggle="tab" data-bs-target="#to-review"
                            type="button" role="tab" aria-controls="to-review" aria-selected="false">Delivered</button>
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
            {{-- All Orders --}}
            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">

                {{-- Example Order --}}
                <div class="order-container border rounded mb-4 p-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <p class="text-muted mb-1">Order Delivered</p>
                            <p class="fw-bold mb-0">Nov 13</p>
                        </div>
                        <div>
                            <span class="badge bg-success">Delivered</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        {{-- Menu Image --}}
                        <img src="{{ asset('images/logo.jpg') }}" alt="Menu Image" class="rounded me-3"
                            style="width: 80px; height: 80px; object-fit: cover;">
                        <div>
                            <p class="fw-bold mb-1">Pansit Sisig</p>
                            <p class="text-muted mb-0">₱350</p>
                        </div>
                    </div>
                </div>

                {{-- Repeat for another order --}}
                <div class="order-container border rounded mb-4 p-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <p class="text-muted mb-1">Order Delivered</p>
                            <p class="fw-bold mb-0">Nov 10</p>
                        </div>
                        <div>
                            <span class="badge bg-warning text-dark">To Review</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        {{-- Menu Image --}}
                        <img src="{{ asset('images/logo.jpg') }}" alt="Menu Image" class="rounded me-3"
                            style="width: 80px; height: 80px; object-fit: cover;">
                        <div>
                            <p class="fw-bold mb-1">Adobo Rice Meal</p>
                            <p class="text-muted mb-0">₱180</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- To Ship --}}
            <div class="tab-pane fade" id="to-ship" role="tabpanel" aria-labelledby="to-ship-tab">
                {{-- Example Order --}}
                <div class="order-container border rounded mb-4 p-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <p class="text-muted mb-1">Order Delivered</p>
                            <p class="fw-bold mb-0">Nov 13</p>
                        </div>
                        <div>
                            <span class="badge bg-success">Preparing</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        {{-- Menu Image --}}
                        <img src="{{ asset('images/logo.jpg') }}" alt="Menu Image" class="rounded me-3"
                            style="width: 80px; height: 80px; object-fit: cover;">
                        <div>
                            <p class="fw-bold mb-1">Pansit Sisig</p>
                            <p class="text-muted mb-0">₱350</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Shipped --}}
            <div class="tab-pane fade" id="shipped" role="tabpanel" aria-labelledby="shipped-tab">
                {{-- Example Order --}}
                <div class="order-container border rounded mb-4 p-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <p class="text-muted mb-1">Order Delivered</p>
                            <p class="fw-bold mb-0">Nov 13</p>
                        </div>
                        <div>
                            <span class="badge bg-success">Out for Delivery</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        {{-- Menu Image --}}
                        <img src="{{ asset('images/logo.jpg') }}" alt="Menu Image" class="rounded me-3"
                            style="width: 80px; height: 80px; object-fit: cover;">
                        <div>
                            <p class="fw-bold mb-1">Pansit Sisig</p>
                            <p class="text-muted mb-0">₱350</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- To Review --}}
            <div class="tab-pane fade" id="to-review" role="tabpanel" aria-labelledby="to-review-tab">
                {{-- Repeat for another order --}}
                <div class="order-container border rounded mb-4 p-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <p class="text-muted mb-1">Order Delivered</p>
                            <p class="fw-bold mb-0">Nov 10</p>
                        </div>
                        <div>
                            <span class="badge bg-warning text-dark">To Review</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        {{-- Menu Image --}}
                        <img src="{{ asset('images/logo.jpg') }}" alt="Menu Image" class="rounded me-3"
                            style="width: 80px; height: 80px; object-fit: cover;">
                        <div>
                            <p class="fw-bold mb-1">Adobo Rice Meal</p>
                            <p class="text-muted mb-0">₱180</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Returns --}}
            <div class="tab-pane fade" id="returns" role="tabpanel" aria-labelledby="returns-tab">
                {{-- Example Order --}}
                <div class="order-container border rounded mb-4 p-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <p class="text-muted mb-1">Order Delivered</p>
                            <p class="fw-bold mb-0">Nov 13</p>
                        </div>
                        <div>
                            <span class="badge bg-danger">Returned</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        {{-- Menu Image --}}
                        <img src="{{ asset('images/logo.jpg') }}" alt="Menu Image" class="rounded me-3"
                            style="width: 80px; height: 80px; object-fit: cover;">
                        <div>
                            <p class="fw-bold mb-1">Pansit Sisig</p>
                            <p class="text-muted mb-0">₱350</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
@endsection
