<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
                <a class="navbar-brand" href="#">
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
                    <div>
                        <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll">
                            {{-- style="--bs-scroll-height: 100px;" --}}
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->first_name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Profile</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button class="dropdown-item" type="submit">Log out</button>
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
        <div class="">
            <div class="container">
                <div class="row">
                    <div class="col d-flex flex-column">
                        <div>
                            <img src="{{ asset('images/logo-name.png') }}" width="148" height="38" alt="Fasteat home">
                        </div>
                        <div class="h1">Pisces Coffee Hub</div>
                        <div>Coffee makes everything possible - and our variety of meals, from appetizers to hearty dishes, make every visit unforgettable.</div>

                    </div>
                    <div class="col d-flex flex-column">
                        <div class="h3">Contact Info</div>
                        <div class="d-flex align-items-center gap-3 border-bottom"><i class="fa-solid fa-location-dot"></i> Barangay Ilang, San Carlos City, Pangasinan</div>
                        <div class="d-flex align-items-center gap-3"><i class="fa-solid fa-envelope"></i> piscescoffeehub@gmail.com</div>
                        <div class="d-flex align-items-center gap-3"><i class="fa-solid fa-phone"></i> 0945 839 3794</div>

                        <div class="icons d-flex align-items-center gap-4">
                            <div class="rounded-circle"><i class="fa-brands fa-facebook"></i></div>
                            <div class="rounded-circle"><i class="fa-brands fa-instagram"></i></div>
                            <div class="rounded-circle"><i class="fa-brands fa-twitter"></i></div>
                        </div>
                    </div>
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
