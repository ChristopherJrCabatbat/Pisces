@extends('user.layout')

@section('title', 'Favorites')

@section('styles-links')
    <style>
        .main-content {
            margin-top: 13vh;
        }

        select {
            width: 30% !important;
        }

        #heart-icon {
            color: #f81d0b;
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
        <a class="nav-link fw-bold" aria-current="page" href="{{ route('user.messages') }}">MESSAGES</a>
    </li>
@endsection

@section('main-content')
    <div class="container main-content d-flex flex-column align-items-center">

        {{-- Top Container --}}
        <div class="top-container d-flex w-100 p-4 mb-5 justify-content-between align-items-center">
            <div class="fw-bold h1">
                Favorites
            </div>
            <div class="menu-chosen d-flex justify-content-center align-items-center gap-2 fs-5">
                <div><a href="{{ route('user.menu') }}" class="white-underline">Menu</a> <i
                        class="fa-solid fa-caret-right mx-1"></i></div>
                <div class="low-opacity-white">Favorites</div>
            </div>
        </div>

        {{-- Content --}}
        <div class="d-flex container content user-content gap-5 p-0">

            {{-- Menus --}}
            <div class="menus d-flex flex-column gap-4 mb-5 w-100">

                {{-- Select --}}
                <div class="top-menus">
                    <select class="form-select" aria-label="Default select example">
                        <option selected value="Default">Default</option>
                        <option value="Expensive">Expensive</option>
                        <option value="Cheap">Cheap</option>
                    </select>
                </div>

                {{-- Menu --}}
                <div class="menu-list">
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        @forelse($menus as $menu)
                            <div class="col">
                                <div class="card h-100">

                                    {{-- Menu Image --}}
                                    <div class="img-container">

                                        @if ($menu->image)
                                            <img src="{{ asset('storage/' . $menu->image) }}" class="card-img-top darken"
                                                alt="{{ $menu->name }}">
                                        @else
                                            <img src="{{ asset('images/logo.jpg') }}" class="card-img-top darken"
                                                alt="No Image">
                                        @endif

                                        <div class="icon-overlay text-white">
                                            {{-- <i class="fa-solid fa-cart-plus" title="Add to Cart"></i> --}}
                                            <form action="{{ route('user.addToCart', $menu->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    style="background-color: transparent; border: none; padding: 0;"><i
                                                        class="fa-solid fa-cart-plus text-white"
                                                        title="Add to Cart"></i></button>
                                            </form>
                                            <i class="fa-solid fa-share" title="Share"></i>
                                            <i class="fa-solid fa-search" title="View"></i>
                                            <form action="{{ route('user.addToFavorites', $menu->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    style="background-color: transparent; border: none; padding: 0;">
                                                    <i class="fa-solid fa-heart"
                                                        style="color: {{ $user->favoriteItems->contains($menu->id) ? '#f81d0b' : 'white' }};"
                                                        title="{{ $user->favoriteItems->contains($menu->id) ? 'Remove from Favorites' : 'Add to Favorites' }}">
                                                    </i>
                                                </button>
                                            </form>

                                        </div>


                                    </div>

                                    {{-- Menu Body --}}
                                    <div class="card-body card-body-mt">
                                        <h5 class="card-title">{{ $menu->name }}</h5>
                                        <div class="price fw-bold mb-2">
                                            @if (floor($menu->price) == $menu->price)
                                                ₱{{ number_format($menu->price, 0) }} <!-- Without decimals -->
                                            @else
                                                ₱{{ number_format($menu->price, 2) }} <!-- With decimals -->
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
                                <p>No menus available in this category.</p>
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
