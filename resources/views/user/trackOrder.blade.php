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
        <div class="d-flex container flex-column content user-content p-0">

            <!-- Track Order Section -->
            <div
                class="track-order d-flex flex-column border-bottom flex-grow-1 bg-light text-black rounded shadow-sm mb-4">
                <!-- Header -->
                <div class="header-more p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="m-0 text-secondary">Track Order</h5>
                </div>


                {{-- Returned --}}
                <div class="track-order-body px-3 py-3"
                    @if ($deliveries->status == 'Returned') style="display: block;" 
                @else 
                    style="display: none;" @endif>
                    <div class="timeline">
                        <div class="timeline-item d-flex align-items-start py-3">
                            <!-- Time and Date -->
                            <div class="time text-start pe-4">
                                <div>{{ $deliveries->updated_at->format('M d') }}</div>
                                {{-- <div>{{ $deliveries->updated_at->format('h:i A') }}</div> --}}
                            </div>

                            <!-- Icon -->
                            <div class="icon-container">
                                <div class="icon rounded-circle text-white d-flex align-items-center justify-content-center bg-danger"
                                    style="width: 24px; height: 24px;">
                                    <i class="fa-solid fa-times"></i>
                                </div>
                                <div class="line"></div>
                            </div>

                            <!-- Content -->
                            <div class="content ms-3">
                                <!-- Status Header -->
                                <div class="status fw-bold">Returned</div>

                                <!-- Message -->
                                <div class="details text-muted small">
                                    Your order has been returned.
                                </div>

                                <!-- Address -->
                                <div class="location text-muted small">{{ $deliveries->address }}</div>

                                <!-- Rider Information -->
                                @if ($deliveries->rider)
                                    <div class="rider text-muted small mt-2">
                                        Rider: {{ $deliveries->rider }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Delivered --}}
                <div class="track-order-body px-3 py-3"
                    @if ($deliveries->status == 'Delivered' || $deliveries->status == 'Returned') style="display: block;" 
            @else 
                style="display: none;" @endif>

                    <div class="timeline">
                        <div class="timeline-item d-flex align-items-start py-3">
                            <!-- Time and Date -->
                            <div class="time text-start pe-2">
                                <div>{{ $deliveries->updated_at->format('M d') }}</div>
                                <div>{{ $deliveries->updated_at->format('h:i A') }}</div>
                            </div>

                            <!-- Icon -->
                            <div class="icon-container">
                                <div class="icon rounded-circle text-white d-flex align-items-center justify-content-center bg-success"
                                    style="width: 24px; height: 24px;">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <div class="line"></div>
                            </div>

                            <!-- Content -->
                            <div class="content ms-3">
                                <!-- Status Header -->
                                <div class="status fw-bold">Delivered</div>

                                <!-- Message -->
                                <div class="details text-muted small">
                                    Your order {{ $deliveries->order }} has been successfully delivered.
                                </div>

                                <!-- Address -->
                                <div class="location text-muted small">{{ $deliveries->address }}</div>

                                <!-- Rider Information -->
                                @if ($deliveries->rider)
                                    <div class="rider text-muted small mt-2">
                                        Rider: {{ $deliveries->rider }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Out for Delivery --}}
                <div class="track-order-body px-3 py-3"
                    @if (
                        $deliveries->status == 'Out for Delivery' ||
                            $deliveries->status == 'Returned' ||
                            $deliveries->status == 'Delivered') style="display: block;" 
            @else 
                style="display: none;" @endif>

                    <div class="timeline">
                        <div class="timeline-item d-flex align-items-start py-3">
                            <!-- Time and Date -->
                            <div class="time text-start pe-4">
                                <div>{{ $deliveries->updated_at->format('M d') }}</div>
                                {{-- <div>{{ $deliveries->updated_at->format('h:i A') }}</div> --}}
                            </div>

                            <!-- Icon -->
                            <div class="icon-container">
                                <div class="icon rounded-circle text-white d-flex align-items-center justify-content-center bg-primary"
                                    style="width: 24px; height: 24px;">
                                    <i class="fa fa-truck" style="font-size: 0.8rem"></i>
                                </div>
                                <div class="line"></div>
                            </div>

                            <!-- Content -->
                            <div class="content ms-3">
                                <!-- Status Header -->
                                <div class="status fw-bold">Out for Delivery</div>

                                <!-- Message -->
                                <div class="details text-muted small">
                                    Your order {{ $deliveries->order }} is out for delivery.
                                </div>

                                <!-- Address -->
                                <div class="location text-muted small">{{ $deliveries->address }}</div>

                                <!-- Rider Information -->
                                @if ($deliveries->rider)
                                    <div class="rider text-muted small mt-2">
                                        Rider: {{ $deliveries->rider }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Preparing --}}
                <div class="track-order-body px-3 py-3"
                    @if (
                        $deliveries->status == 'Preparing' ||
                            $deliveries->status == 'Out for Delivery' ||
                            $deliveries->status == 'Delivered' ||
                            $deliveries->status == 'Returned') style="display: block;" 
            @else 
                style="display: none;" @endif>

                    <div class="timeline">
                        <div class="timeline-item d-flex align-items-start py-3">
                            <!-- Time and Date -->
                            <div class="time text-start pe-4">
                                <div>{{ $deliveries->updated_at->format('M d') }}</div>
                                {{-- <div>{{ $deliveries->updated_at->format('h:i A') }}</div> --}}
                            </div>

                            <!-- Icon -->
                            <div class="icon-container">
                                <div class="icon rounded-circle text-black d-flex align-items-center justify-content-center bg-warning"
                                    style="width: 24px; height: 24px;">
                                    <i class="fa fa-utensils"></i>
                                </div>
                                <div class="line"></div>
                            </div>

                            <!-- Content -->
                            <div class="content ms-3">
                                <!-- Status Header -->
                                <div class="status fw-bold">Preparing</div>

                                <!-- Message -->
                                <div class="details text-muted small">
                                    Your order {{ $deliveries->order }} is being prepared.
                                </div>

                                <!-- Address -->
                                <div class="location text-muted small">{{ $deliveries->address }}</div>

                                <!-- Rider Information -->
                                @if ($deliveries->rider)
                                    <div class="rider text-muted small mt-2">
                                        Rider: {{ $deliveries->rider }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pending --}}
                <div class="track-order-body px-3 py-3">
                    <div class="timeline">
                        <div class="timeline-item d-flex align-items-start py-3">
                            <!-- Time and Date -->
                            <div class="time text-start pe-4">
                                <div>{{ $deliveries->updated_at->format('M d') }}</div>
                                {{-- <div>{{ $deliveries->updated_at->format('h:i A') }}</div> --}}
                            </div>

                            <!-- Icon -->
                            <div class="icon-container">
                                <div class="icon rounded-circle text-white d-flex align-items-center justify-content-center bg-secondary"
                                    style="width: 24px; height: 24px;">
                                    <i class="fa fa-clock" style="font-size: 0.8rem"></i>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="content ms-3">
                                <!-- Status Header -->
                                <div class="status fw-bold">Pending</div>

                                <!-- Message -->
                                <div class="details text-muted small">
                                    Your order {{ $deliveries->order }} is currently being pending.
                                </div>

                                <!-- Address -->
                                <div class="location text-muted small">{{ $deliveries->address }}</div>

                                <!-- Rider Information -->
                                @if ($deliveries->rider)
                                    <div class="rider text-muted small mt-2">
                                        Rider: {{ $deliveries->rider }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>


    </div>
@endsection

@section('scripts')
@endsection
