@extends('user.layout')

@section('title', 'Pisces Coffee Hub')

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
        <a class="nav-link fw-bold" aria-current="page" href="{{ route('user.orders') }}">ORDERS</a>
    </li>
    <li class="nav-item">
        <a class="nav-link fw-bold active" aria-current="page" href="{{ route('user.messages') }}">MESSAGES</a>
    </li>
@endsection

@section('main-content')
    <div class="container main-content d-flex flex-column align-items-center mb-5">

        {{-- Top Container --}}
        <div class="top-container d-flex w-100 p-4 mb-5 justify-content-between align-items-center">
            <div class="fw-bold h1">
                Track Order
            </div>
            <div class="menu-chosen d-flex justify-content-center align-items-center gap-2 fs-5">
                <div><a href="{{ route('user.messages') }}" class="navigation">Overview</a> <i
                        class="fa-solid fa-caret-right mx-1"></i> <a href="{{ route('user.shopUpdates') }}"
                        class="navigation">Order Updates</a> <i class="fa-solid fa-caret-right mx-1"></i></div>
                <div class="low-opacity-white">
                    Track Order
                </div>
            </div>
        </div>

        {{-- Content --}}
        {{-- <div class="d-flex container flex-column content user-content p-0">

            <!-- Track Order Section -->
            <div
                class="track-order d-flex flex-column border-bottom flex-grow-1 bg-light text-black rounded shadow-sm mb-4">
                <!-- Header -->
                <div class="header-more p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="m-0 text-secondary">Track Order</h5>
                </div>

                <!-- Order Status Body -->
                <div class="track-order-body px-3 py-3">
                    <div class="timeline">
                        @foreach ($statuses as $delivery)
                            <div class="timeline-item d-flex align-items-start py-3">
                                <!-- Time and Date -->
                                <div class="time text-end pe-4">
                                    {{ $delivery->updated_at->format('M d') }}<br>{{ $delivery->updated_at->format('h:i A') }}
                                </div>
                                <!-- Icon -->
                                <div class="icon-container">
                                    <div class="icon rounded-circle text-white d-flex align-items-center justify-content-center
                                {{ $delivery->status === 'Delivered'
                                    ? 'bg-success'
                                    : ($delivery->status === 'Returned'
                                        ? 'bg-danger'
                                        : 'bg-secondary') }}"
                                        style="width: 24px; height: 24px;">
                                        <i
                                            class="fa-solid 
                                    {{ $delivery->status === 'Delivered'
                                        ? 'fa-check'
                                        : ($delivery->status === 'Returned'
                                            ? 'fa-times'
                                            : 'fa-truck') }}"></i>
                                    </div>
                                </div>
                                <!-- Content -->
                                <div class="content ms-3">
                                    <div class="status fw-bold">{{ ucfirst($delivery->status) }}</div>
                                    <div class="details text-muted small">
                                        @if ($delivery->status === 'Delivered')
                                            Your order has been delivered.<br>Recipient: {{ $delivery->name }}
                                        @elseif ($delivery->status === 'Returned')
                                            Your order has been returned to sender.<br>Reason: {{ $delivery->note }}
                                        @else
                                            Your order is currently {{ strtolower($delivery->status) }}.
                                        @endif
                                    </div>
                                    <div class="location text-muted small">{{ $delivery->address }}</div>
                                    @if ($delivery->rider)
                                        <div class="rider text-muted small mt-2">
                                            Courier: {{ $delivery->rider }}
                                        </div>
                                    @endif
                                    @if ($delivery->status === 'Delivered')
                                        <img src="{{ asset('images/logo.jpg') }}" alt="Delivery Image" class="mt-2"
                                            style="max-height: 100px; width: auto;">
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div> --}}

        {{-- Content --}}
        <div class="d-flex container flex-column content user-content p-0">

            <!-- Track Order Section -->
            <div
                class="track-order d-flex flex-column border-bottom flex-grow-1 bg-light text-black rounded shadow-sm mb-4">
                <!-- Header -->
                <div class="header-more p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="m-0 text-secondary">Track Order</h5>
                </div>

                <!-- Order Status Body -->
                <div class="track-order-body px-3 py-3">
                    <div class="timeline">
                        @foreach ($timeline as $event)
                            <div class="timeline-item d-flex align-items-start py-3">
                                <!-- Time and Date -->
                                <div class="time text-end pe-4">
                                    {{ $event['timestamp'] }}
                                </div>

                                <!-- Icon -->
                                <div class="icon-container">
                                    <div class="icon rounded-circle text-white d-flex align-items-center justify-content-center
                                {{ $event['status'] === 'Delivered'
                                    ? 'bg-success'
                                    : ($event['status'] === 'Returned'
                                        ? 'bg-danger'
                                        : 'bg-secondary') }}"
                                        style="width: 24px; height: 24px;">
                                        <i class="fa-solid {{ $event['icon'] }}"></i>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="content ms-3">
                                    <!-- Status Header -->
                                    <div class="status fw-bold">{{ ucfirst($event['status']) }}</div>

                                    <!-- Message -->
                                    <div class="details text-muted small">
                                        {{ $event['message'] }}
                                    </div>

                                    <!-- Address -->
                                    <div class="location text-muted small">{{ $event['address'] }}</div>

                                    <!-- Rider Information -->
                                    @if ($event['rider'])
                                        <div class="rider text-muted small mt-2">
                                            Rider: {{ $event['rider'] }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>


    </div>
@endsection

@section('scripts')
@endsection
