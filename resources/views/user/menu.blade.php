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
        <a class="nav-link fw-bold active" aria-current="page" href="{{ route('user.menu') }}">MENU</a>
    </li>
@endsection

@section('main-content')
    <div class="container main-content d-flex flex-column align-items-center">

        {{-- Top Container --}}
        <div class="top-container d-flex w-100 p-4 mb-5 justify-content-between  align-items-center">
            <div class="fw-bold h1">
                Pizza
            </div>
            <div class="menu-chosen d-flex gap-1 fs-5">
                <div>Menu > </div>
                <div class="low-opacity-white">Pizza</div>
            </div>
        </div>

        {{-- Content --}}
        <div class="d-flex container gap-5 p-0">

            {{-- Categories --}}
            <div class="categories d-flex flex-column">
                <div class="h3 mb-4">Categories</div>
                <div class="category-lists d-flex flex-column gap-2">
                    <!-- All Menus -->
                    <div>
                        {{-- <a href="{{ route('menus.index') }}" class="text-decoration-none">> All Menus</a> --}}
                        <a href="" class="text-decoration-none">> All Menus</a>
                    </div>

                    <!-- Dynamically generated categories with counts -->
                    @foreach ($categories as $category)
                        <div class="d-flex justify-content-between">
                            {{-- <a href="{{ route('menus.category', ['category' => $category->category]) }}" --}}
                            <a href=""
                                class="text-decoration-none">> {{ $category->category }}</a>
                            <div>({{ $category->menu_count }})</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Menus --}}
            <div class="menus d-flex flex-column gap-4 mb-5">

                <div class="top-menus">
                    <select class="form-select" aria-label="Default select example">
                        <option selected value="Default">Default</option>
                        <option value="Expensive">Expensive</option>
                        <option value="Cheap">Cheap</option>
                    </select>
                </div>

                <div class="menu-list">
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        @forelse($menus as $menu)
                            <div class="col">
                                <div class="card h-100">
                                    <div class="img-container">
                                        @if ($menu->image)
                                            <img src="{{ asset('storage/' . $menu->image) }}" class="card-img-top darken"
                                                alt="{{ $menu->name }}">
                                        @else
                                            <img src="{{ asset('images/logo.jpg') }}" class="card-img-top darken"
                                                alt="No Image">
                                        @endif
                                        <div class="icon-overlay">
                                            <i class="fa-solid fa-cart-plus"></i>
                                            <i class="fa-solid fa-share"></i>
                                            <i class="fa-solid fa-search"></i>
                                            <i class="fa-solid fa-heart"></i>
                                        </div>
                                    </div>
                                    <div class="card-body card-body-mt">
                                        <h5 class="card-title">{{ $menu->name }}</h5>
                                        <div class="price fw-bold mb-2">
                                            @if (floor($menu->price) == $menu->price)
                                                ${{ number_format($menu->price, 0) }} <!-- Without decimals -->
                                            @else
                                                ${{ number_format($menu->price, 2) }} <!-- With decimals -->
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="stars d-flex">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-regular fa-star"></i> <!-- Example rating -->
                                            </div>
                                            <div>(2)</div> <!-- Example rating count -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col">
                                <p>No menus available.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>


        </div>

    </div>
@endsection

@section('scripts')
@endsection
