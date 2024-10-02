@extends('user.layout')

@section('title', 'User')

@section('styles-links')
    <style>
        .card-best {
            max-width: 50vw;
        }

        @media (max-width: 1244px) {
            .card-best {
                max-width: none;
            }
        }
    </style>
@endsection

@section('main-content')
    <div class="container main-content d-flex flex-column justify-content-center align-items-center">

        {{-- Top Categories --}}
        <div class="text-center mb-5">
            <div class="h2">
                Top Categories
            </div>
            <div class="w-50 text-center mx-auto">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Et vero fugit, repellat aperiam doloremque
                voluptatibus illum voluptate saepe eum nostrum!
            </div>
            <div class="container text-center mt-4">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-5">
                    <div class="col d-flex flex-column justify-content-center align-items-center">
                        <img src="{{ asset('images/logo.jpg') }}" width="130" height="" alt="Picture">
                        <div>Coffee</div>
                    </div>
                    <div class="col d-flex flex-column justify-content-center align-items-center">
                        <img src="{{ asset('images/logo.jpg') }}" width="130" height="" alt="Picture">
                        <div>Coffee</div>
                    </div>
                    <div class="col d-flex flex-column justify-content-center align-items-center">
                        <img src="{{ asset('images/logo.jpg') }}" width="130" height="" alt="Picture">
                        <div>Coffee</div>
                    </div>
                    <div class="col d-flex flex-column justify-content-center align-items-center">
                        <img src="{{ asset('images/logo.jpg') }}" width="130" height="" alt="Picture">
                        <div>Coffee</div>
                    </div>
                    <div class="col d-flex flex-column justify-content-center align-items-center">
                        <img src="{{ asset('images/logo.jpg') }}" width="130" height="" alt="Picture">
                        <div>Coffee</div>
                    </div>

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
                                <img src="{{ asset('images/logo.jpg') }}" class="img-fluid rounded-start darken" alt="...">
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
                                    <small class="text-body-secondary">Bold and intense, our dark coffee offers deep, rich flavors with a smooth finish. Perfect for those who enjoy a strong, full-bodied brew.</small>
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

        {{-- New Menus --}}
        <div class="w-100 mb-5">
            <div class="h2 border-baba pb-3 mb-4">
                New Menus
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


    </div>
@endsection

@section('scripts')
@endsection
