@extends('user.layout')

@section('title', 'User')

@section('styles-links')
    {{-- <link rel="stylesheet" href="{{ asset('home-assets/css/style.css') }}"> --}}

    <style>
        .card-best {
            max-width: 50vw;
        }

        @media (max-width: 1244px) {
            .card-best {
                max-width: none;
            }
        }

        /* Margin adjustment for better spacing */
        @media (max-width: 576px) {
            .col-6 {
                margin-bottom: 1rem;
            }
        }
        @media (min-width: 990px) {
            .gap-full-screen {
                gap: 1rem;
            }
        }

        /* Adjust image hover effect */
        .rounded-circle {
            transition: transform 0.2s ease, border-color 0.2s ease;
        }

        .rounded-circle:hover {
            transform: scale(1.1);
            border-color: #e74c3c;
        }
    </style>
@endsection

@section('topbar')
    <li class="nav-item">
        <a class="nav-link fw-bold active" aria-current="page" href="{{ route('user.dashboard') }}">HOME</a>
    </li>
    <li class="nav-item">
        <a class="nav-link fw-bold" aria-current="page" href="{{ route('user.menu') }}">MENU</a>
    </li>
@endsection

@section('main-content')
    <div class="container main-content d-flex flex-column justify-content-center align-items-center">

        <!-- Top Categories -->
        <div class="text-center mb-5">
            <h2 class="text-white">Top Categories</h2>
            <p class="w-75 mx-auto text-white">
                Explore the top categories our customers love, featuring a variety of dishes that keep them coming back for
                more.
            </p>

            <div class="container text-center mt-4">
                <div class="row gap-full-screen justify-content-center g-4">
                    @foreach ($topCategories as $category)
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2 d-flex flex-column align-items-center">
                            <div class="position-relative" style="width: 150px; height: 150px;"> <!-- Increased size -->
                                <img src="{{ asset('storage/' . $category->image) }}" class="rounded-circle shadow mb-3"
                                    style="width: 100%; height: 100%; object-fit: cover; border: 3px solid #f81d0b;"
                                    alt="{{ $category->category }}">
                            </div>
                            <div class="fw-bold fs-5 text-white text-center mt-1">{{ $category->category }}</div>
                            <small class="text-white text-center" style="font-size: 0.9rem;">{{ $category->menu_count }}
                                Menus</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>




        {{-- Best Deals For You --}}
        <div class="w-100 mb-5">
            <div class="h2 border-baba pb-3 mb-4">
                Best Deals For You
            </div>
            <div>
                <div class="card card-best mb-3">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <!-- Image with overlay -->
                            <div class="img-container">
                                <img src="{{ asset('images/logo.jpg') }}" class="img-fluid rounded-start darken"
                                    alt="...">
                                <div class="icon-overlay">
                                    <i class="fa-solid fa-cart-plus"></i>
                                    <i class="fa-solid fa-share"></i>
                                    <i class="fa-solid fa-search"></i>
                                    <i class="fa-solid fa-heart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Dark Coffee</h5>
                                <div class="price-container d-flex align-items-center gap-3 mb-2">
                                    <div class="price fw-bold fs-5">$10.00</div>
                                    <div class="price-line">$12.00</div>
                                    <div class="off text-success">-10% Off</div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="stars d-flex">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                    </div>
                                    <div>(2)</div>
                                </div>
                                <p class="card-text mt-2">
                                    <small class="text-body-secondary">Bold and intense, our dark coffee offers deep, rich
                                        flavors with a smooth finish. Perfect for those who enjoy a strong, full-bodied
                                        brew.</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        {{-- Popular Menus --}}
        <div class="w-100 mb-5">
            <div class="h2 border-baba pb-3 mb-4">
                Popular Menus
            </div>
            <div>
                <div class="row row-cols-1 row-cols-md-4 g-4">
                    <div class="col">
                        <div class="card h-100">
                            <div class="img-container">
                                <img src="{{ asset('images/logo.jpg') }}" class="card-img-top darken" alt="...">
                                <div class="icon-overlay">
                                    <i class="fa-solid fa-cart-plus"></i>
                                    <i class="fa-solid fa-share"></i>
                                    <i class="fa-solid fa-search"></i>
                                    <i class="fa-solid fa-heart"></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Special Pisces Pizza</h5>
                                <div class="price fw-bold mb-2">$10.00</div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="stars d-flex">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                    </div>
                                    <div>(2)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <div class="img-container">
                                <img src="{{ asset('images/logo.jpg') }}" class="card-img-top darken" alt="...">
                                <div class="icon-overlay">
                                    <i class="fa-solid fa-cart-plus"></i>
                                    <i class="fa-solid fa-share"></i>
                                    <i class="fa-solid fa-search"></i>
                                    <i class="fa-solid fa-heart"></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Special Pisces Pizza</h5>
                                <div class="price fw-bold mb-2">$10.00</div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="stars d-flex">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                    </div>
                                    <div>(2)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <div class="img-container">
                                <img src="{{ asset('images/logo.jpg') }}" class="card-img-top darken" alt="...">
                                <div class="icon-overlay">
                                    <i class="fa-solid fa-cart-plus"></i>
                                    <i class="fa-solid fa-share"></i>
                                    <i class="fa-solid fa-search"></i>
                                    <i class="fa-solid fa-heart"></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Special Pisces Pizza</h5>
                                <div class="price fw-bold mb-2">$10.00</div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="stars d-flex">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                    </div>
                                    <div>(2)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <div class="img-container">
                                <img src="{{ asset('images/logo.jpg') }}" class="card-img-top darken" alt="...">
                                <div class="icon-overlay">
                                    <i class="fa-solid fa-cart-plus"></i>
                                    <i class="fa-solid fa-share"></i>
                                    <i class="fa-solid fa-search"></i>
                                    <i class="fa-solid fa-heart"></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Special Pisces Pizza</h5>
                                <div class="price fw-bold mb-2">$10.00</div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="stars d-flex">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                    </div>
                                    <div>(2)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

       <!-- New Menus -->
<div class="w-100 mb-5">
    <div class="h2 border-baba pb-3 mb-4">
        New Menus
    </div>
    <div>
        <div class="row row-cols-1 row-cols-md-4 g-4">
            @foreach ($latestMenus as $menu)
                <div class="col">
                    <div class="card h-100">
                        <div class="img-container">
                            <img src="{{ asset('storage/' . $menu->image) }}" class="card-img-top darken" alt="{{ $menu->name }}">
                            <div class="icon-overlay">
                                <i class="fa-solid fa-cart-plus"></i>
                                <i class="fa-solid fa-share"></i>
                                <i class="fa-solid fa-search"></i>
                                <i class="fa-solid fa-heart"></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $menu->name }}</h5>
                            <div class="price fw-bold mb-2">${{ number_format($menu->price, 2) }}</div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="stars d-flex">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-regular fa-star"></i>
                                </div>
                                <div>({{ $menu->rating_count ?? 0 }})</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>



    </div>

@endsection

@section('scripts')
@endsection
