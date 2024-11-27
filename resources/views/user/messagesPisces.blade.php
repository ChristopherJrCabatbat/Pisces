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
            overflow-x: hidden !important;
        }

        html {
            overflow: hidden;
        }

        .message {
            line-height: 1.5;
        }

        input.form-control {}

        .back-button {
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: none;
        }

        .back-button i {
            font-size: 18px;
            color: #000;
        }

        .back-button:hover {
            background-color: #dedede;
        }

        .position-relative .back-button {
            z-index: 10;
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
                            <a class="nav-link fw-bold active" aria-current="page"
                                href="{{ route('user.messages') }}">MESSAGES</a>
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
                    <!-- Header with Back Icon -->
                    <div
                        class="d-flex align-items-center justify-content-center position-relative py-3 px-3 border-bottom">
                        <a href="{{ route('user.messages') }}"
                            class="btn btn-light rounded-circle back-button position-absolute start-0 ms-3">
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('images/logo.jpg') }}" class="rounded-circle border me-2"
                                alt="Shop icon" style="width: 50px; height: 50px; object-fit: cover;">
                            <h3 class="ms-2 h3 fw-bold mb-0">PISCES COFFEE HUB</h3>
                        </div>
                    </div>

                    <!-- Chat Body -->
                    <div class="shop-messages overflow-auto px-3 py-3">
                        @foreach ($messages as $message)
                            @if ($message->user_id === $user->id)
                                <!-- Message from User -->
                                <div class="d-flex align-items-start justify-content-end mb-4">
                                    <span class="text-muted align-self-center small me-3">{{ $message->created_at->diffForHumans() }}</span>
                                    <div class="message bg-primary text-white px-3 py-2 rounded shadow-sm"
                                        style="max-width: 70%;">
                                        <p class="m-0">{{ $message->message_text }}</p>
                                    </div>
                                    <!-- Updated User Icon -->
                                    <div class="message-avatar bg-primary text-white">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                </div>
                            @else
                                <!-- Message from Shop -->
                                <div class="d-flex align-items-start mb-4">
                                    <img src="{{ asset('images/logo.jpg') }}" class="rounded-circle border me-3"
                                        alt="Shop icon" style="width: 40px; height: 40px; object-fit: cover;">
                                    <div class="message bg-white border px-3 py-2 rounded shadow-sm"
                                        style="max-width: 70%;">
                                        <p class="m-0">{{ $message->message_text }}</p>
                                    </div>
                                    <span class="text-muted align-self-center small ms-3">{{ $message->created_at->diffForHumans() }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Input Section -->
                    <div class="d-flex border-top p-3 align-items-center">
                        <form action="{{ route('user.sendMessage', ['userId' => 1]) }}" method="POST"
                            class="d-flex w-100">
                            @csrf
                            <input type="text" name="message_text" class="form-control me-2 rounded-pill"
                                placeholder="Type your message here..." required autofocus />
                            <button class="btn btn-primary rounded-pill px-4">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </form>
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
