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
                    <h5 class="m-0 text-secondary">Order Completed</h5>
                </div>

                <!-- Order Status Body -->
                <div class="track-order-body px-4 py-3">
                    <!-- Top Section -->
                    <div class="top fw-bold pb-3">
                        Your order has been delivered on Nov 13.
                    </div>

                    <!-- Shipping Details -->
               

                    <!-- Timeline -->
                    <div class="timeline">
                        <!-- Delivered Section -->
                        <div class="timeline-item d-flex align-items-start py-3 top">
                            <div class="order-width d-flex">
                                <div class="">
                                    <img src="{{ asset('images/logo.jpg') }}" alt="Delivery Image" class=""
                                        style="max-height: 100px; width: auto;">
                                </div>
                                <div class="content ms-3">
                                    <div class="status fs-5">Pisces Pizza</div>
                                    <div class="location text-muted small">Pizza</div>
                                    <div class="location fs-5 flex-between">
                                        <div class="fw-bold">₱199</div>
                                        <div>x1</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                        <!-- Order Summary -->
                        <div class="timeline-item py-3 d-flex align-items-start top">
                            <!-- Content -->
                            <div class="content order-width d-flex flex-column gap-2">
                                <div class="status fw-bold">Order summary</div>
                                <div class="details text-muted flex-between">
                                    <div>Subtotal</div>
                                    <div>₱199</div>
                                </div>
                                <div class="details text-muted flex-between">
                                    <div>Shipping</div>
                                    <div>₱0</div>
                                </div>
                                <div class="details text-muted flex-between">
                                    <div>Coupons</div>
                                    <div>₱0</div>
                                </div>
                                <div class="details fw-bold flex-between">
                                    <div>Total</div>
                                    <div>₱199</div>
                                </div>
                            </div>
                        </div>

                          <!-- Order Details -->
                          <div class="timeline-item py-3 d-flex align-items-start top">
                            <!-- Content -->
                            <div class="content order-width d-flex flex-column gap-2">
                                <div class="status fw-bold">Order details</div>
                                <div class="details text-muted flex-between">
                                    <div>Order date</div>
                                    <div>Nov. 12 1:00 PM</div>
                                </div>
                                <div class="details text-muted flex-between">
                                    <div>Payment Method</div>
                                    <div>GCash</div>
                                </div>
                                <div class="details text-muted flex-between">
                                    <div>Delivery date</div>
                                    <div>Nov. 12 2:00 PM</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Details -->
                          <div class="timeline-item py-3 d-flex align-items-start">
                            <!-- Content -->
                            <div class="content order-width d-flex gap-2">
                                <button type="button" class="btn btn-outline-dark w-50">Buy Again</button>
                                <button type="button" class="btn btn-outline-dark w-50">View Review</button>
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
