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

            <!-- Shop Updates Section -->
            <div
                class="shop-updates d-flex flex-column border-bottom flex-grow-1 bg-light text-black rounded shadow-sm mb-4">
                <div class="header-more p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="m-0 text-secondary">Order updates</h5>
                    <a href="{{ route('user.shopUpdates') }}" class="text-muted small more">More<i class="fa-solid fa-caret-right ms-1"></i></a>
                </div>
                <div class="shop-updates-body my-2">
                    <a href="{{ route('user.reviewOrder') }}">
                        <div class="d-flex a-container p-3">
                            <div class="me-3 d-flex align-items-center justify-content-center rounded-circle border"
                                style="width: 50px; height: 50px;">
                                <i class="fa-solid fa-bag-shopping text-primary"></i>
                            </div>
                            <div>
                                <p class="m-0 fw-bold">Review your order</p>
                                <p class="m-0 text-muted small">Enjoying your recent purchase? Share your thoughts to help
                                    other shoppers. <span class="text-muted small">4d</span></p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('user.trackOrder') }}">
                        <div class="d-flex a-container p-3">
                            <div class="me-3 d-flex align-items-center justify-content-center rounded-circle border"
                                style="width: 50px; height: 50px;">
                                <i class="fa-solid fa-box text-success"></i>
                            </div>
                            <div>
                                <p class="m-0 fw-bold">Order delivered</p>
                                <p class="m-0 text-muted small">Your order 972437104315 was delivered. <span
                                        class="text-muted small">5d</span></p>
                            </div>
                        </div>
                    </a>

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
                                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">1</span>
                            </div>
                            <div>
                                <p class="m-0 fw-bold">Pisces Coffee Hub</p>
                                <p class="m-0 text-muted small">💕 Hello! <span class="text-muted small">3d</span></p>
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
