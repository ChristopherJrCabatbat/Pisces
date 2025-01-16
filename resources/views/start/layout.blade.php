<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--
    - primary meta tags
  -->
    <title>@yield('title')</title>
    <meta name="title" content="Pisces - The Best Restaurants In Your Home">
    <meta name="description" content="This is a food html template made by codewithsadee">

    <!--
    - favicon
  -->
    <link rel="shortcut icon" href="{{ asset('images/logo-icon.png') }}" type="image/svg+xml">

    <script src="https://kit.fontawesome.com/f416851b63.js" crossorigin="anonymous"></script>


    <!--
    - custom css link
  -->

    {{-- <link rel="stylesheet" href="{{ asset('home-assets/css/style.css') }}"> --}}
    <link rel="stylesheet" href="./home-assets/css/style.css">
    <link rel="stylesheet" href="{{ asset('css/home-styles.css') }}">

    <!--
    - google font link
  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!--
    - preload images
  -->

    @yield('styles-links')

</head>

<body>

    <!--
    - #PRELOADER
  -->

    <div class="loading-container" data-loading-container>
        <div class="loading-circle"></div>
    </div>

    <!--
    - #HEADER
  -->

    <header class="header" data-header>
        <div class="container">

            <a href="#" class="logo">
                {{-- <img src="./home-assets/images/logo.svg" width="148" height="38" alt="Fasteat home"> --}}
                <img src="./images/logo-name.png" width="148" height="38" alt="Pisces logo">
            </a>

            <nav class="navbar" data-navbar>
                <button class="nav-close-btn" aria-label="close menu" data-nav-toggler>
                    <ion-icon name="close-outline" aria-hidden="true"></ion-icon>
                </button>

                <a href="#" class="logo">
                    {{-- <img src="./home-assets/images/logo.svg" width="148" height="38" alt="Fasteat home"> --}}
                    <img src="./images/logo-name.png" width="148" height="38" alt="Fasteat home">
                </a>

                <ul class="navbar-list">

                    <li class="navbar-item">
                        <a href="/" class="navbar-link" data-nav-link>Home</a>
                    </li>
                </ul>
            </nav>

            <div class="header-action">
                <button class="cart-btn" aria-label="cart">
                    <ion-icon name="bag" aria-hidden="true"></ion-icon>
                </button>
            </div>

            <button class="nav-open-btn" aria-label="open menu" data-nav-toggler>
                <ion-icon name="menu-outline" aria-hidden="true"></ion-icon>
            </button>

            <div class="overlay" data-overlay data-nav-toggler></div>

        </div>
    </header>





    <main>
        <article>

            <!-- #HERO -->
            @yield('main-content')

        </article>
    </main>


    <!--
    - custom js link
  -->
    <script src="./home-assets/js/script.js"></script>
    @yield('scripts')


    <!--
    - ionicon link
  -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>