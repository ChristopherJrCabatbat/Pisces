<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pisces Coffee Hub</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <script src="https://kit.fontawesome.com/f416851b63.js" crossorigin="anonymous"></script>

    <style>
        .main-content {
            margin-top: 18vh;
        }

        select {
            width: 30% !important;
        }

        .shop-messages {
            height: 56vh;
        }

        html {
            overflow: hidden;
        }

        .message {
            line-height: 1.5;
        }

        input.form-control {
        }

        button.btn i {
            margin-right: 0;
        }
    </style>

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
                        <li class="nav-item">
                            <a class="nav-link fw-bold" aria-current="page"
                                href="{{ route('user.dashboard') }}">HOME</a>
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
                                    <li><a class="dropdown-item" href="#"><i
                                                class="fa-solid fa-user me-2"></i>Profile</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button class="dropdown-item" type="submit"><i
                                                    class="fa-solid fa-right-from-bracket me-2"></i>Log out</button>
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
        <div class="container main-content d-flex flex-column align-items-center mb-5">

            {{-- Content --}}
            <div class="d-flex flex-column content user-content p-0 w-100">
                <!-- Messages Section -->
                <div class="d-flex flex-column flex-grow-1 bg-light text-black rounded shadow-sm">
                    <!-- Header -->
                    <div class="py-3 d-flex justify-content-center align-items-center border-bottom">
                        <img src="{{ asset('images/logo.jpg') }}" class="rounded-circle border" alt="Shop icon"
                            style="width: 50px; height: 50px; object-fit: cover;">
                        <h3 class="ms-2 h3 fw-bold mb-0">PISCES COFFEE HUB</h3>
                    </div>

                    <!-- Chat Body -->
                    <div class="shop-messages overflow-auto px-3 py-3">
                        <!-- Message from Shop -->
                        <div class="d-flex align-items-start mb-4">
                            <img src="{{ asset('images/logo.jpg') }}" class="rounded-circle border me-3"
                                alt="Shop icon" style="width: 40px; height: 40px; object-fit: cover;">
                            <div class="message bg-white border p-3 rounded shadow-sm" style="max-width: 70%;">
                                <p class="m-0 fw-bold">Pisces Coffee Hub</p>
                                <p class="m-0">Hello! Welcome to Pisces Coffee Hub. Let us know how we can assist
                                    you today!</p>
                            </div>
                        </div>

                        <!-- Message from User -->
                        <div class="d-flex align-items-start justify-content-end mb-4">
                            <div class="message bg-primary text-white p-3 rounded shadow-sm" style="max-width: 70%;">
                                <p class="m-0">Thank you so much! 😊</p>
                            </div>
                            <img src="{{ asset('images/logo.jpg') }}" class="rounded-circle border ms-3"
                                alt="User icon" style="width: 40px; height: 40px; object-fit: cover;">
                        </div>
                    </div>

                    <!-- Input Section -->
                    <div class="d-flex border-top p-3 align-items-center">
                        <input type="text" class="form-control me-2 rounded-pill"
                            placeholder="Type your message here..." />
                        <button class="btn btn-primary rounded-pill px-4">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </main>



    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.js') }}"></script>

</body>

</html>
