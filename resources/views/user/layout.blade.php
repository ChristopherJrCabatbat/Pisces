<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <script src="https://kit.fontawesome.com/f416851b63.js" crossorigin="anonymous"></script>

    @yield('styles-links')

</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg fixed-top" style="background-color: #e3f2fd;">
            <div class="container">
                <a class="navbar-brand" href="{{ route('user.dashboard') }}">
                    <img src="{{ asset('images/logo-name.png') }}" width="148" height="" alt="Pisces logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
                    aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarScroll">
                    <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                        @yield('topbar')
                    </ul>

                    <div class="d-flex align-items-center">

                        <!-- Heart Icon -->
                        <div class="icon-wrapper">
                            <a href="{{ route('user.favorites') }}" class="nav-icon">
                                <i class="fa fa-heart" id="heart-icon" title="View Favorites"></i>
                                <span class="notification-badge" id="heart-badge">{{ $userFavorites ?? 0 }}</span>
                            </a>
                        </div>

                        <!-- Cart Icon -->
                        <div class="icon-wrapper ms-3">
                            <a href="{{ route('user.shoppingCart') }}" class="nav-icon">
                                <i class="fa-solid fa-cart-shopping" id="cart-icon" title="View Cart"></i>
                                <span class="notification-badge" id="cart-badge">{{ $userCart ?? 0 }}</span>
                            </a>
                        </div>


                        <!-- User Dropdown -->
                        <ul class="navbar-nav ms-4 my-2 my-lg-0 navbar-nav-scroll">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->first_name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user me-2"></i>Profile</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button class="dropdown-item" type="submit"><i class="fa-solid fa-right-from-bracket me-2"></i>Log out</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>
        @yield('main-content')
    </main>

    <footer class="footer text-black">
        <div>
            <div class="container pt-5">
                <div class="row footer-content border-bottoms pb-4 gap-4 footer-fs">
                    <div class="col d-flex flex-column">
                        <div>
                            <img src="{{ asset('images/logo-name.png') }}" width="148" height=""
                                alt="Pisces Coffee Hub">
                        </div>
                        <div class="h1 footer-title my-3 fw-bold">Pisces Coffee Hub</div>
                        <div>
                            Coffee makes everything possible - and our variety of meals, from appetizers to hearty
                            dishes, make every visit unforgettable.
                        </div>
                    </div>
                    <div class="col d-flex flex-column gap-3">
                        <div class="h3 footer-title mb-3 fw-bold">Contact Info</div>
                        <div class="d-flex align-items-center">
                            <span class="border-bottoms pb-2"><i class="fa-solid fa-location-dot me-2"></i> Barangay
                                Ilang, San Carlos City, Pangasinan</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-envelope me-2"></i> piscescoffeehub@gmail.com
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-phone me-2"></i> 0945 839 3794
                        </div>
                        <div class="icons d-flex align-items-center gap-4 mt-4">
                            <a href="https://www.facebook.com/@piscesCH" title="Go to Pisces Facebook Page" target="_blank" class="social-link">
                                <div class="rounded-circle">
                                    <i class="fa-brands fa-facebook"></i>
                                </div>
                            </a>
                            <div class="rounded-circle">
                                <i class="fa-brands fa-instagram"></i>
                            </div>
                            <div class="rounded-circle">
                                <i class="fa-brands fa-twitter"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="copyright text-center py-4">
                    <div>Copyright 2024 Pisces. All rights reserved.</div>
                </div>
            </div>
        </div>
    </footer>


    @yield('scripts')

    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.js') }}"></script>

</body>

</html>
