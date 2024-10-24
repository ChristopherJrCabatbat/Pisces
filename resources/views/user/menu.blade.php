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
        <div class="top-container d-flex w-100 p-4 mb-5 justify-content-between align-items-center">
            <div class="fw-bold h1">
                {{ $selectedCategory }}
            </div>
            <div class="menu-chosen d-flex justify-content-center align-items-center gap-2 fs-5">
                <div>Menu <i class="fa-solid fa-caret-right mx-1"></i></div>
                <div class="low-opacity-white">{{ $selectedCategory }}</div>
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
                        <div>
                            <i class="fa-solid fa-caret-right me-2"></i>
                            <a href="{{ route('user.menu', ['category' => 'All Menus']) }}" class="category-links">
                                <span class="{{ $selectedCategory == 'All Menus' ? 'active-category' : '' }}">All
                                    Menus</span>
                            </a>
                        </div>
                    </div>

                    <!-- Dynamically generated categories with counts -->
                    @foreach ($categories as $category)
                        <div class="d-flex justify-content-between">
                            <div>
                                <i class="fa-solid fa-caret-right me-2"></i>
                                <a href="{{ route('user.menu', ['category' => $category->category]) }}"
                                    class="category-links">
                                    <span
                                        class="{{ $selectedCategory == $category->category ? 'active-category' : '' }}">{{ $category->category }}</span>
                                </a>
                            </div>
                            <div>({{ $category->menu_count }})</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Menus --}}
            <div class="menus d-flex flex-column gap-4 mb-5">

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

                                    <div class="img-container">

                                        @if ($menu->image)
                                            <img src="{{ asset('storage/' . $menu->image) }}" class="card-img-top darken"
                                                alt="{{ $menu->name }}">
                                        @else
                                            <img src="{{ asset('images/logo.jpg') }}" class="card-img-top darken"
                                                alt="No Image">
                                        @endif

                                        <div class="icon-overlay">
                                            {{-- <i class="fa-solid fa-cart-plus" title="Add to Cart"></i> --}}
                                            <form action="{{ route('user.addToCart', $menu->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    style="background-color: transparent; border: none; padding: 0;"><i
                                                        class="fa-solid fa-cart-plus" title="Add to Cart"></i></button>
                                            </form>
                                            <i class="fa-solid fa-share" title="Share"></i>
                                            <i class="fa-solid fa-search" title="View"></i>
                                            <i class="fa-solid fa-heart" title="Add to Favorites"></i>
                                        </div>

                                        {{-- <div class="icon-overlay">
                                            <!-- Add to Cart -->
                                            <a href="{{ route('user.addToCart', ['menuId' => $menu->id]) }}"
                                                class="nav-icon add-to-cart" data-menu-id="{{ $menu->id }}"
                                                title="Add to Cart">
                                                <i class="fa-solid fa-cart-plus"></i>
                                            </a>

                                            <!-- Share (You can add a share link here, maybe a modal or link to a share page) -->
                                            <a href="#" class="nav-icon" title="Share">
                                                <i class="fa-solid fa-share"></i>
                                            </a>

                                            <!-- View Details (Link to the menu's details) -->
                                            <a href="{{ route('user.menuDetail', ['menuId' => $menu->id]) }}">
                                                <i class="fa-solid fa-search"></i>
                                            </a>

                                            <!-- Add to Favorites -->
                                            <a href="{{ route('user.addToFavorites', ['menuId' => $menu->id]) }}"
                                                class="nav-icon" title="Add to Favorites">
                                                <i class="fa-solid fa-heart"></i>
                                            </a>

                                        </div> --}}

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
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get the CSRF token from the page
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Handle add to cart click
            document.querySelectorAll('.add-to-cart').forEach(function(cartButton) {
                cartButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    const menuId = this.getAttribute('data-menu-id');
                    addToCart(menuId);
                });
            });

            // Handle add to favorites click
            document.querySelectorAll('.add-to-favorites').forEach(function(favoriteButton) {
                favoriteButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    const menuId = this.getAttribute('data-menu-id');
                    addToFavorites(menuId);
                });
            });

            // Function to update cart
            function addToCart(menuId) {
                fetch(`/user/add-to-cart/${menuId}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken // Include CSRF token
                        }
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(data.message);
                            let badge = document.getElementById("cart-badge");
                            let currentCount = parseInt(badge.textContent);
                            badge.textContent = currentCount + 1;
                        }
                    })
                    .catch((error) => console.error("Error:", error));
            }

            // Function to update favorites
            function addToFavorites(menuId) {
                fetch(`/user/add-to-favorites/${menuId}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken // Include CSRF token
                        }
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(data.message);
                            let badge = document.getElementById("heart-badge");
                            let currentCount = parseInt(badge.textContent);
                            badge.textContent = currentCount + 1;
                        }
                    })
                    .catch((error) => console.error("Error:", error));
            }
        });
    </script> --}}
@endsection
