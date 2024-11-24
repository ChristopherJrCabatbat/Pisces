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
                {{-- {{ $selectedCategory }} --}}
                Review Order
            </div>
            <div class="menu-chosen d-flex justify-content-center align-items-center gap-2 fs-5">
                <div><a href="{{ route('user.messages') }}" class="navigation">Overview</a> <i
                        class="fa-solid fa-caret-right mx-1"></i> <a href="{{ route('user.shopUpdates') }}"
                        class="navigation">Order Updates</a> <i class="fa-solid fa-caret-right mx-1"></i></div>
                <div class="low-opacity-white">
                    {{-- {{ $selectedCategory }} --}}
                    Review Order
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="d-flex container flex-column content user-content p-0">

            <!-- Review Order Section -->
            <div
                class="track-order d-flex flex-column border-bottom flex-grow-1 bg-light text-black rounded shadow-sm mb-4">
                <!-- Header -->
                <div class="header-more p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="m-0 text-secondary">Review Order</h5>
                </div>

                <!-- Order Status Body -->
                <div class="track-order-body px-3 py-3">
                    <!-- Top Section -->
                    <div class="top fw-bold pb-3">
                        Delivered Nov 13
                    </div>

                    <!-- Shipping Details -->
                    {{-- <div class="shipping-details mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <img src="path_to_jt_express_logo.png" alt="J&T Express" class="me-2" style="height: 30px;">
                            <div>
                                <div>J&T Express Standard shipping</div>
                                <div class="tracking-number text-muted small">972437104315</div>
                            </div>
                        </div>
                    </div> --}}

                    <!-- Timeline -->
                    <div class="timeline">
                        <!-- Delivered Section -->
                        <div class="timeline-item d-flex align-items-start py-3">
                            <!-- Time and Date -->
                            <div class="time text-end pe-4">
                                Nov 3<br>3:15 PM
                            </div>
                            <!-- Icon -->
                            <div class="icon-container">
                                <div class="icon bg-success rounded-circle text-white d-flex align-items-center justify-content-center"
                                    style="width: 24px; height: 24px;">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                            </div>
                            <!-- Content -->
                            <div class="content ms-3">
                                <div class="status fw-bold">Delivered</div>
                                <div class="details text-muted small">Your order has been delivered.<br>Recipient:
                                    Customer</div>
                                <div class="location text-muted small">PANGASINAN-SAN-CARLOS-CITY</div>
                                <img src="{{ asset('images/logo.jpg') }}" alt="Delivery Image" class="mt-2"
                                    style="max-height: 100px; width: auto;">
                            </div>
                        </div>

                        <!-- Out for Delivery Section -->
                        <div class="timeline-item py-3 d-flex align-items-start">
                            <!-- Time and Date -->
                            <div class="time text-end pe-4">
                                Nov 3<br>1:03 PM
                            </div>
                            <!-- Icon -->
                            <div class="icon-container">
                                <div class="icon bg-secondary rounded-circle text-white d-flex align-items-center justify-content-center"
                                    style="width: 24px; height: 24px;">
                                    <i class="fa-solid fa-truck"></i>
                                </div>
                            </div>
                            <!-- Content -->
                            <div class="content ms-3">
                                <div class="status fw-bold">Out for delivery</div>
                                <div class="details text-muted small">Your order is out for delivery.</div>
                                <div class="location text-muted small">PANGASINAN-SAN-CARLOS-CITY</div>
                                <div class="carrier text-muted small">Carrier: OCW Gian Carlo S. Doria<br>639927569649</div>
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
