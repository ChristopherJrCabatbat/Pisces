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
     <li class="nav-item position-relative">
        <a class="nav-link fw-bold" aria-current="page" href="{{ route('user.orders') }}">
            ORDERS
            @if ($pendingOrdersCount > 0)
                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle-y-custom">
                    {{ $pendingOrdersCount }}
                </span>
            @endif
        </a>
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
                {{-- {{ $selectedCategory }} --}}
                Order Updates
            </div>
            <div class="menu-chosen d-flex justify-content-center align-items-center gap-2 fs-5">
                <div><a href="{{ route('user.messages') }}" class="navigation">Overview</a> <i
                        class="fa-solid fa-caret-right mx-1"></i></div>
                <div class="low-opacity-white">
                    {{-- {{ $selectedCategory }} --}}
                    Order Updates
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="d-flex container flex-column content user-content p-0">
            <!-- Order Updates Section -->
            <div
                class="shop-updates d-flex flex-column border-bottom flex-grow-1 bg-light text-black rounded shadow-sm mb-4">
                <div class="header-more p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="m-0 text-secondary">Order Updates</h5>
                    <a href="{{ route('user.shopUpdates') }}" class="text-muted small more">More<i
                            class="fa-solid fa-caret-right ms-1"></i></a>
                </div>

                <div class="shop-updates-body my-2">
                    @foreach ($deliveries as $delivery)
                        <!-- Review Order -->
                        <a href="{{ route('user.reviewOrder', ['delivery' => $delivery->id]) }}">
                            <div class="d-flex a-container p-3">
                                <div class="me-3 d-flex align-items-center justify-content-center rounded-circle border"
                                    style="width: 50px; height: 50px;">
                                    <i class="fa-solid fa-bag-shopping text-primary"></i>
                                </div>
                                <div>
                                    <p class="m-0 fw-bold">Review your order</p>
                                    <p class="m-0 text-muted small">{{ $delivery->order }}</p>
                                    <p class="m-0 text-muted small">Status: {{ ucfirst($delivery->status) }} <span
                                            class="text-muted small">{{ $delivery->updated_at->diffForHumans() }}</span></p>
                                    <img src="{{ $delivery->menuImage }}" width="60" class="mt-2 img-fluid"
                                        alt="Order Image">
                                </div>
                            </div>
                        </a>

                        <!-- Track Order -->
                        <a href="{{ route('user.trackOrder', ['delivery' => $delivery->id]) }}">
                            <div class="d-flex a-container p-3">
                                <div class="me-3 d-flex align-items-center justify-content-center rounded-circle border"
                                    style="width: 50px; height: 50px;">
                                    <i class="fa-solid fa-box text-success"></i>
                                </div>
                                <div>
                                    <p class="m-0 fw-bold">Track order</p>
                                    <p class="m-0 text-muted small">{{ $delivery->order }}</p>
                                    <p class="m-0 text-muted small">Status: {{ ucfirst($delivery->status) }} <span
                                            class="text-muted small">{{ $delivery->updated_at->diffForHumans() }}</span>
                                    </p>
                                    <img src="{{ $delivery->menuImage }}" width="60" class="mt-2 img-fluid"
                                        alt="Order Image">
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>



    </div>
@endsection

@section('scripts')
@endsection
