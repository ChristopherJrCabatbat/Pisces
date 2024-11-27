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
                            type="button" role="tab" aria-controls="delivered" aria-selected="false">Delivered</button>
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
                @forelse ($statuses['all'] as $order)
                    <div class="order-container border rounded mb-4 p-4 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="text-muted mb-1">Order Date</p>
                                <p class="fw-bold mb-0">{{ $order->created_at->format('M d') }}</p>
                            </div>
                            <div>
                                <span class="badge bg-secondary">{{ $order->status }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            {{-- Menu Image --}}
                            <img src="{{ asset('images/logo.jpg') }}" alt="Menu Image" class="rounded me-3"
                                style="width: 80px; height: 80px; object-fit: cover;">
                            <div>
                                <p class="fw-bold mb-1">{{ $order->order }}</p>
                                <p class="text-muted mb-0">₱{{ number_format($order->total_price, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                <div class="order-container border rounded p-4 shadow-sm">
                    <div class="d-flex align-items-center fs-5">
                        <i class="fa-regular fa-circle-question me-2"></i> There are no orders available.
                    </div>
                </div>
                @endforelse
            </div>

            {{-- Pending --}}
            <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                @forelse ($statuses['pending'] as $order)
                    @include('user.orders-partial', [
                        'order' => $order,
                        'statusClass' => 'bg-secondary',
                    ])
                @empty
                <div class="order-container border rounded p-4 shadow-sm">
                    <div class="d-flex align-items-center fs-5">
                        <i class="fa-regular fa-circle-question me-2"></i> There are no pending orders.
                    </div>
                </div>
                @endforelse
            </div>

            {{-- Preparing --}}
            <div class="tab-pane fade" id="preparing" role="tabpanel" aria-labelledby="preparing-tab">
                @forelse ($statuses['preparing'] as $order)
                    @include('user.orders-partial', [
                        'order' => $order,
                        'statusClass' => 'bg-warning text-dark',
                    ])
                @empty
                <div class="order-container border rounded p-4 shadow-sm">
                    <div class="d-flex align-items-center fs-5">
                        <i class="fa-regular fa-circle-question me-2"></i> There are no orders being prepared.
                    </div>
                </div>
                @endforelse
            </div>

            {{-- Out for Delivery --}}
            <div class="tab-pane fade" id="out-for-delivery" role="tabpanel" aria-labelledby="out-for-delivery-tab">
                @forelse ($statuses['out_for_delivery'] as $order)
                    @include('user.orders-partial', ['order' => $order, 'statusClass' => 'bg-info'])
                @empty
                <div class="order-container border rounded p-4 shadow-sm">
                    <div class="d-flex align-items-center fs-5">
                        <i class="fa-regular fa-circle-question me-2"></i> There are no orders out for delivery.
                    </div>
                </div>
                @endforelse
            </div>

            {{-- Delivered --}}
            <div class="tab-pane fade" id="delivered" role="tabpanel" aria-labelledby="delivered-tab">
                @forelse ($statuses['delivered'] as $order)
                    @include('user.orders-partial', ['order' => $order, 'statusClass' => 'bg-success'])
                @empty
                <div class="order-container border rounded p-4 shadow-sm">
                    <div class="d-flex align-items-center fs-5">
                        <i class="fa-regular fa-circle-question me-2"></i> There are no delivered orders.
                    </div>
                </div>
                @endforelse
            </div>

            {{-- Returns --}}
            <div class="tab-pane fade" id="returns" role="tabpanel" aria-labelledby="returns-tab">
                @forelse ($statuses['returns'] as $order)
                    @include('user.orders-partial', ['order' => $order, 'statusClass' => 'bg-danger'])
                @empty
                <div class="order-container border rounded p-4 shadow-sm">
                    <div class="d-flex align-items-center fs-5">
                        <i class="fa-regular fa-circle-question me-2"></i> There are no returned orders.
                    </div>
                </div>
                @endforelse
            </div>
        </div>

    </div>

@endsection

@section('scripts')
@endsection
