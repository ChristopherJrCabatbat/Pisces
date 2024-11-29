@extends('user.layout')

@section('title', 'Menu')

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
        <a class="nav-link fw-bold active" aria-current="page" href="#">MESSAGES</a>
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
                <div>Messages <i class="fa-solid fa-caret-right mx-1"></i></div>
                <div class="low-opacity-white">
                    {{-- {{ $selectedCategory }} --}}
                    Overview
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
                    @php
                        $reviewShown = false;
                        $trackShown = false;
                    @endphp

                    @forelse ($deliveries as $delivery)
                        @if (!$reviewShown)
                            <!-- Review Order -->
                            <a href="{{ route('user.reviewOrder', ['delivery' => $delivery->id]) }}">
                                <div class="d-flex a-container p-3">
                                    <div class="me-3 d-flex align-items-center justify-content-center rounded-circle border"
                                        style="width: 50px; height: 50px;">
                                        <i class="fa-solid fa-bag-shopping text-primary"></i>
                                    </div>
                                    <div>
                                        <p class="m-0 fw-bold">Review your order</p>
                                        <p class="m-0 text-muted small">Order #{{ $delivery->id }} -
                                            {{ $delivery->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </a>
                            @php $reviewShown = true; @endphp
                        @endif

                        @if (!$trackShown)
                            <!-- Track Order -->
                            <a href="{{ route('user.trackOrder', ['delivery' => $delivery->id]) }}">
                                <div class="d-flex a-container p-3">
                                    <div class="me-3 d-flex align-items-center justify-content-center rounded-circle border"
                                        style="width: 50px; height: 50px;">
                                        <i class="fa-solid fa-box text-success"></i>
                                    </div>
                                    <div>
                                        <p class="m-0 fw-bold">Track order</p>
                                        <p class="m-0 text-muted small">Status: {{ ucfirst($delivery->status) }} <span
                                                class="text-muted small">{{ $delivery->updated_at->diffForHumans() }}</span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                            @php $trackShown = true; @endphp
                        @endif

                        @if ($reviewShown && $trackShown)
                        @break
                    @endif
                @empty
                    <div class="d-flex align-items-center fs-5 p-3">
                        <i class="fa-regular fa-circle-question me-2"></i> There are no orders.
                    </div>
                @endforelse
            </div>

        </div>

        <!-- Messages Section -->
        <div class="d-flex shop-messages flex-column flex-grow-1 bg-light text-black rounded shadow-sm">
            <div class="p-3 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="m-0 text-secondary">Messages</h5>
            </div>

            <div class="shop-updates-body my-2">
                <a href="{{ route('user.messagesPisces') }}">
                    <div class="d-flex align-items-center p-3 a-container">
                        <div class="me-3 position-relative">
                            <img src="{{ asset('images/logo.jpg') }}" class="rounded-circle border" alt="Shop icon"
                                style="width: 50px; height: 50px; object-fit: cover;">
                            @if ($unreadCount > 0)
                                <span
                                    class="badge bg-danger position-absolute top-0 start-100 translate-middle">{{ $unreadCount }}</span>
                            @endif
                        </div>
                        <div>
                            <!-- Bold "Pisces Coffee Hub" if there are unread messages -->
                            <p class="m-0 {{ $unreadCount > 0 ? 'fw-bold' : '' }}">Pisces Coffee Hub</p>

                            @if ($latestMessage)
                                <p class="m-0 {{ $unreadCount > 0 ? 'fw-bold' : 'text-muted' }} small">
                                    @if ($latestMessage->user_id === $user->id)
                                        You: {{ $latestMessage->message_text }}
                                    @else
                                        {{ $latestMessage->message_text }}
                                    @endif
                                    <span class="text-muted small">
                                        {{ $latestMessage->created_at->diffForHumans() }}
                                    </span>
                                </p>
                            @else
                                <p class="m-0 text-muted small">No messages yet</p>
                            @endif
                        </div>
                    </div>
                </a>
            </div>

        </div>

    </div>



</div>
@endsection

@section('scripts')
@endsection
