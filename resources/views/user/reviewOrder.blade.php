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
    <li class="nav-item position-relative">
        <a class="nav-link fw-bold" aria-current="page" href="{{ route('user.messages') }}">MESSAGES
            @if ($unreadCount > 0)
                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle-y-custom">
                    {{ $unreadCount }}
                </span>
            @endif
        </a>
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
                class="track-order shop-messagess d-flex flex-column border-bottom flex-grow-1 bg-light text-black rounded mb-4">
                <!-- Header -->
                <div class="header-more p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="m-0 text-secondary">Order {{ $delivery->status }}</h5>
                </div>

                <!-- Order Status Body -->
                <div class="track-order-body px-4 py-3">
                    <!-- Top Section -->
                    <div class="bottom pb-3 mb-3">
                        @if ($delivery->status === 'Delivered' || $delivery->status === 'Returned')
                            Your order has been delivered to <span class="fw-bold">{{ $delivery->name }}</span> on
                            <span class="fw-bold">{{ $delivery->created_at->format('M d') }}</span>.
                        @else
                            Your order is currently <span class="fw-bold">{{ $delivery->status }}</span> on
                            <span class="fw-bold">{{ $delivery->created_at->format('M d') }}</span>.
                        @endif
                    </div>


                    <!-- Shipping Details -->

                    <!-- Timeline -->
                    <div class="timeline">
                        <!-- Items -->
                        @foreach ($items as $item)
                            <div class="timeline-item d-flex align-items-start py-2">
                                <div class="order-width d-flex">
                                    <div>
                                        <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('images/logo.jpg') }}"
                                            alt="{{ $item['name'] }} Image" style="max-height: 100px; width: auto;">
                                    </div>
                                    <div class="content ms-3">
                                        <h4>{{ $item['name'] }} (x{{ $item['quantity'] }})</h4>
                                        <p>₱{{ number_format($item['discounted_price'], 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Order Summary -->
                        <div class="timeline-item py-3 d-flex align-items-start bottom top mt-3">
                            <!-- Content -->
                            <div class="content order-width d-flex flex-column gap-2">
                                <div class="status fw-bold">Order summary</div>
                                <div class="details text-muted flex-between">
                                    <div>Subtotal</div>
                                    <div>₱{{ number_format($subtotal, 2) }}</div>
                                </div>
                                <div class="details text-muted flex-between">
                                    <div>Shipping Fee</div>
                                    <div>₱{{ number_format($shippingFee) }}</div>
                                </div>
                                <div class="details text-muted flex-between">
                                    <div>Coupons</div>
                                    <div>₱{{ number_format($coupons, 2) }}</div>
                                </div>
                                <div class="details fw-bold flex-between">
                                    <div>Total Price</div>
                                    <div>₱{{ round($totalDatabase) }}</div>
                                    {{-- <div>₱{{ round($delivery->total_price) }}</div> --}}
                                </div>
                            </div>
                        </div>
                        

                        <!-- Order Details -->
                        <div class="timeline-item py-3 d-flex align-items-start bottom">
                            <!-- Content -->
                            <div class="content order-width d-flex flex-column gap-2">
                                <div class="status fw-bold">Order details</div>
                                <div class="details text-muted flex-between">
                                    <div>Order date</div>
                                    <div>{{ $delivery->created_at->format('M d, h:i A') }}</div>
                                </div>
                                <div class="details text-muted flex-between">
                                    <div>Payment Method</div>
                                    <div>{{ $delivery->mode_of_payment }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Actions -->
                        <div class="timeline-item py-3 d-flex align-items-start">
                            <!-- Content -->
                            <div class="content order-width d-flex gap-2">
                                {{-- <button type="button" class="btn btn-outline-dark w-50" onclick="location.href='{{ route('user.shoppingCart', ['deliveryId' => $delivery->id]) }}'">Buy Again</button> --}}
                                <button type="button" class="btn btn-outline-dark w-50"
                                    onclick="location.href='{{ route('user.orderRepeat', ['deliveryId' => $delivery->id]) }}'">
                                    Buy Again
                                </button>
                                {{-- <button type="button" class="btn btn-outline-dark w-50">View Review</button> --}}
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
