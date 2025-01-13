<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--
    - primary meta tags
  -->
    {{-- <title>Pisces - The Best Restaurants In Your Home</title> --}}
    <title>Pisces Coffee Hub</title>
    <meta name="title" content="Pisces - The Best Restaurants In Your Home">
    <meta name="description" content="This is a food html template made by codewithsadee">

    <!--
    - favicon
  -->
    {{-- <link rel="shortcut icon" href="./images/logo.jpg" type="image/svg+xml"> --}}
    <link rel="shortcut icon" href="{{ asset('images/logo-icon.png') }}" type="image/svg+xml">

    {{-- Bootstrap link --}}
    {{-- <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}"> --}}
    <script src="https://kit.fontawesome.com/f416851b63.js" crossorigin="anonymous"></script>


    <!--
    - custom css link
  -->

    {{-- <link rel="stylesheet" href="{{ asset('home-assets/css/style.css') }}"> --}}
    <link rel="stylesheet" href="./home-assets/css/style.css">

    <style>
        .text-center {
            text-align: center !important;
        }

        /* Modal Overlay */
        .custom-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0.3s, opacity 0.3s ease-in-out;
        }

        .custom-modal.visible {
            visibility: visible;
            opacity: 1;
        }

        /* Modal Content */
        .custom-modal .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        /* Close Button */
        .custom-modal .close-modal {
            position: absolute;
            top: 7vh;
            right: 22vw;
            font-size: 34px;
            cursor: pointer;
            color: #333;
            transition: color 0.2s ease;
        }

        .custom-modal .close-modal:hover {
            color: #d9534f;
        }

        /* Title */
        .custom-modal .modal-title {
            font-size: 28px;
            margin-bottom: 15px;
            color: #333;
            font-weight: bold;
            text-align: center;
        }

        /* Grid Container */
        .custom-modal #menu-grid {
            display: grid;
            /* grid-template-columns: repeat(auto-fill, minmax(200px, 3fr)); */
            /* grid-template-columns: 1fr 1fr; */
            gap: 30px;
            /* Space between grid items */
            justify-content: center;
            align-items: center;
            overflow-y: auto;
            flex-grow: 1;
            padding: 10px;
        }

        .custom-modal #menu-grid .grid-list {
            grid-template-columns: 1fr 1fr 1fr;
        }

        @media (max-width: 820px) {
            .custom-modal #menu-grid .grid-list {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 550px) {
            .custom-modal #menu-grid .grid-list {
                grid-template-columns: 1fr;
            }
        }

        /* Menu Card */
        .custom-modal .menu-card {
            background: #f9f9f9;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 200px;
            /* Ensure proper width for each card */
            padding: 15px;
        }

        /* .restaurant-card {
            display: flex;
            justify-content: center;
            align-items: center;
        } */

        .custom-modal .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .custom-modal .menu-card img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .custom-modal .menu-card h3 {
            font-size: 18px;
            margin-bottom: 8px;
            color: #333;
        }

        .custom-modal .menu-card p {
            font-size: 14px;
            color: #555;
        }

        .fa-star,
        .fa-star-half-stroke {
            color: #F81D0B;
        }


        /* Image container styling */
        .img-container {
            position: relative;
            width: 100%;
            height: 300px;
            /* Set height to keep images consistent */
            overflow: hidden;
        }

        /* Image within the container */
        .img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Ensures the image covers the container */
        }

        /* Darken overlay on hover */
        .img-container:hover .darken {
            filter: brightness(30%);
        }

        /* Optional styling for icons */
        .rounded-circle {
            width: 40px;
            height: 40px;
            background-color: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid hsl(0, 0%, 47%);
        }
    </style>


    <!--
    - google font link
  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!--
    - preload images
  -->

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
                <img src="./images/logo-name.png" width="148" height="38" alt="Fasteat home">
            </a>

            <nav class="navbar" data-navbar>
                <button class="nav-close-btn" aria-label="close menu" data-nav-toggler>
                    <ion-icon name="close-outline" aria-hidden="true"></ion-icon>
                </button>

                <a href="#" class="logo">
                    <img src="./home-assets/images/logo.svg" width="148" height="38" alt="Fasteat home">
                    {{-- <img src="./images/logo-name.png" width="148" height="38" alt="Fasteat home"> --}}
                </a>

                <ul class="navbar-list">

                    <li class="navbar-item">
                        <a href="#home" class="navbar-link" data-nav-link>Home</a>
                    </li>

                    {{-- <li class="navbar-item">
                        <a href="#" class="navbar-link" data-nav-link>About Us</a>
                    </li> --}}

                    <li class="navbar-item">
                        <a href="#menu" class="navbar-link" data-nav-link>Menu</a>
                    </li>

                    <li class="navbar-item">
                        <a href="#contacts" class="navbar-link" data-nav-link>Contacts</a>
                    </li>

                </ul>
            </nav>

            <div class="header-action">
                <button class="cart-btn" aria-label="cart">
                    <ion-icon name="bag" aria-hidden="true"></ion-icon>
                </button>

                <a href="{{ route('login') }}" class="btn btn-primary has-after">LOG IN</a>
            </div>

            <button class="nav-open-btn" aria-label="open menu" data-nav-toggler>
                <ion-icon name="menu-outline" aria-hidden="true"></ion-icon>
            </button>

            <div class="overlay" data-overlay data-nav-toggler></div>

        </div>
    </header>





    <main>
        <article>

            <!--
        - #HERO
      -->

            <section class="section hero has-bg-image" id="home" aria-label="home"
                style="background-image: url('./home-assets/images/hero-bg.png')">
                <div class="container">

                    <div class="hero-content" data-reveal="left">
                        {{-- <h1 class="h1 hero-title">The Best Restaurants In Your Home</h1> --}}
                        <h1 class="h1 hero-title">Pisces Coffee Hub</h1>

                        <p class="hero-text">
                            Coffee makes everything possible - and our variety of meals, from appetizers to hearty
                            dishes, make every visit unforgettable.
                        </p>

                        <a href="{{ route('login') }}" class="btn btn-secondary has-after">Order Now</a>
                    </div>

                    <figure class="hero-banner" data-reveal>

                        <img src="./home-assets/images/hero-banner.png" width="680" height="720" alt="hero banner"
                            class="w-100">

                        <img src="{{ asset('home-assets/images/pisces-white.jpg') }}" width="280" height="120"
                            alt="shape" class="shape shape-1">

                        <img src="{{ asset('home-assets/images/ilang-white.jpg') }}" width="217" height="80"
                            alt="shape" class="shape shape-2">

                    </figure>

                </div>
            </section>





            <!--
        - #INSTRUCTION
      -->

            <section class="section instruction whats-new-section" id="menu" aria-labelledby="">
                <div class="container">
                    <h2 class="h2 section-title" id="instruction-label" data-reveal>What's New?</h2>

                    <p class="section-text" data-reveal>
                        Discover our latest dishes, made to satisfy every craving. Don’t miss out – try them today!
                    </p>

                    {{-- <ul class="grid-list">
                        @foreach ($latestMenus as $index => $menu)
                            <li data-reveal="{{ $index % 2 == 0 ? 'left' : 'right' }}">
                                <div class="instruction-card">
                                    <figure class="card-banner">
                                        <img src="{{ asset('storage/' . $menu->image) }}" width="300"
                                            height="154" loading="lazy" alt="{{ $menu->name }}" class="w-100">
                                    </figure>

                                    <div class="card-content">
                                        <h3 class="h5 card-title">
                                            <span class="span">{{ sprintf('%02d', $index + 1) }}</span>
                                            {{ $menu->name }}
                                        </h3>
                                        <p class="card-text">
                                            {{ $menu->description }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul> --}}

                    <ul class="grid-list">
                        @foreach ($latestMenus as $index => $menu)
                            <li data-reveal="{{ $index % 2 == 0 ? 'left' : 'right' }}">
                                <div class="instruction-card">
                                    <figure class="img-container">
                                        <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}">
                                    </figure>
                                    <div class="card-content">
                                        <h3 class="h5 card-title">
                                            <span class="span">{{ sprintf('%02d', $index + 1) }}</span>
                                            {{ $menu->name }}
                                        </h3>
                                        <p class="card-text">
                                            {{ $menu->description }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>


                </div>
            </section>





            <!--
        - #TOP RESTAURANT
      -->
            <section class="section top-restaurant explore-menu-section" aria-labelledby="top-restaurent-label">
                <div class="container">
                    <ul class="grid-list grid-list-menus">
                        <li data-reveal="left">
                            <h2 class="h2 section-title" id="top-restaurent-label">
                                Explore Our Menu
                            </h2>
                            <p class="section-text">
                                From handcrafted coffee to a variety of delicious dishes, our menu has something for
                                everyone.
                                Whether you're craving a light snack, a hearty meal, or the perfect brew, we’ve got you
                                covered.
                                Discover your next favorite bite!
                            </p>
                        </li>
                        @foreach ($popularMenus as $menu)
                            <li data-reveal="right">
                                <div class="restaurant-card">
                                    <div class="card-icon">
                                        <img src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('images/logo.jpg') }}"
                                            width="100" height="100" loading="lazy" alt="{{ $menu->name }}"
                                            class="w-100">

                                    </div>
                                    <h3 class="h5 card-title">{{ $menu->name }}</h3>

                                    <div class="rating-wrapper d-flex align-items-center gap-2">
                                        <div class="stars d-flex">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($menu->rating))
                                                    <i class="fa-solid fa-star"></i>
                                                @elseif ($i - $menu->rating < 1)
                                                    <i class="fa-solid fa-star-half-stroke"></i>
                                                @else
                                                    <i class="fa-regular fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <div class="star-label">
                                            @if ($menu->ratingCount > 0)
                                                ({{ number_format($menu->rating, 1) }}) {{ $menu->ratingCount }}
                                                review{{ $menu->ratingCount > 1 ? 's' : '' }}
                                            @else
                                                No Rating
                                            @endif
                                        </div>
                                    </div>

                                    <div class="card-meta-wrapper">
                                        <a href="#" class="card-meta">Popular</a>
                                        <a href="#" class="card-meta">{{ $menu->category }}</a>
                                    </div>
                                    <p class="card-text">₱{{ number_format($menu->price, 2) }}</p>
                                    <p class="card-text">{{ $menu->description }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn btn-secondary has-after" id="openMenuModal">See All</button>
                </div>
            </section>

            <!-- Modal for Menus -->
            <div id="menuModal" class="custom-modal hidden">
                <div class="modal-content">
                    <span class="close-modal" id="closeMenuModal">&times;</span>
                    <h3 class="modal-title">All Menus</h3>
                    <div id="menu-grid" class="">
                        <!-- Menu cards dynamically loaded here -->
                        <div class="menu-card">
                            <img src="path-to-image.jpg" alt="Menu Item">
                            <h3>Iced Taro</h3>
                            <p>No Rating<br>₱89.00<br>Drinks</p>
                        </div>
                        <div class="menu-card">
                            <img src="path-to-image.jpg" alt="Menu Item">
                            <h3>Pork Shanghai</h3>
                            <p>4.0 (1)<br>₱599.00<br>Bilao</p>
                        </div>
                        <!-- Add more menu cards -->
                    </div>
                </div>
            </div>






            <!--
        - #CTA
      -->

            <section class="section cta has-bg-image" aria-labelledby="cta-label"
                style="background-image: url('./home-assets/images/hero-bg.png')">
                <div class="container">

                    <figure class="cta-banner" data-reveal="left">
                        <img src="./home-assets/images/cta-banner.png" width="680" height="704" loading="lazy"
                            alt="cta banner" class="w-100">
                    </figure>

                    <div class="cta-content" data-reveal="right">

                        <h2 class="h3 section-title" id="cta-label">
                            Hungry? We've Got You Covered!
                        </h2>

                        <p class="section-text">
                            From coffee to pizza and more, we have what you're craving. Order now for a quick and
                            delicious treat!
                        </p>

                        <a href="{{ route('login') }}" class="btn btn-primary has-after">Order Now</a>

                    </div>

                </div>
            </section>


            <!--
        - #STATS
      -->

            <section class="stats" aria-label="statistics" data-reveal>
                <div class="container">

                    <ul class="grid-list">

                        <li>
                            <h2 class="h3 section-title">Service shows good taste.</h2>
                        </li>

                        <li class="stats-item">
                            <span class="span">976</span>

                            <p class="stats-text">
                                Satisfied <br>
                                Customer
                            </p>
                        </li>

                        <li class="stats-item">
                            <span class="span">12</span>

                            <p class="stats-text">
                                Best <br>
                                Restaurants
                            </p>
                        </li>

                        <li class="stats-item">
                            <span class="span">1K+</span>

                            <p class="stats-text">
                                Food <br>
                                Delivered
                            </p>
                        </li>

                    </ul>

                </div>
            </section>



            <!--
        - #TESTIMONIALS
      -->

            {{-- <section class="section testi" aria-labelledby="testi-label">
                <div class="container">

                    <div class="testi-content" data-reveal="left">

                        <h2 class="h2 section-title" id="testi-label">What customers say about us</h2>

                        <blockquote class="testi-text">
                            "I really love this place! The coffee is amazing, and the food is so good, especially the
                            pizza! The staff is super friendly, and it feels just like home. It's definitely my new
                            favorite tambayan!"
                        </blockquote>

                        <div class="wrapper">
                            <img src="./home-assets/images/testi-avatar.png" width="70" height="70"
                                loading="lazy" alt="Thomas Adamson" class="author-img">

                            <div>
                                <p class="author-title">Arron Paul Macaraeg</p>

                                <div class="rating-wrapper">
                                    <ion-icon name="star" aria-hidden="true"></ion-icon>
                                    <ion-icon name="star" aria-hidden="true"></ion-icon>
                                    <ion-icon name="star" aria-hidden="true"></ion-icon>
                                    <ion-icon name="star" aria-hidden="true"></ion-icon>
                                    <ion-icon name="star" aria-hidden="true"></ion-icon>
                                </div>
                            </div>
                        </div>

                    </div>

                    <figure class="testi-banner" data-reveal="right">
                        <img src="./home-assets/images/testimonial-banner.png" width="680" height="588"
                            alt="testimonial banner" class="w-100">
                    </figure>

                </div>
            </section> --}}

            <section class="section testi" aria-labelledby="testi-label">
                <div class="container">
            
                    <div class="testi-content" data-reveal="left">
            
                        <h2 class="h2 section-title" id="testi-label">What customers say about us</h2>
            
                        <blockquote class="testi-text">
                            "{{ $feedback->feedback ?? 'No feedback available yet.' }}"
                        </blockquote>
            
                        <div class="wrapper">
                            <img src="./home-assets/images/testi-avatar.png" width="70" height="70"
                                loading="lazy" alt="Author Image" class="author-img">
            
                            <div>
                                <p class="author-title">
                                    {{ $feedback ? $feedback->first_name . ' ' . $feedback->last_name : 'Anonymous' }}
                                </p>
            
                                <div class="rating-wrapper">
                                    @if ($feedback && $feedback->rating)
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $feedback->rating)
                                                <ion-icon name="star" aria-hidden="true"></ion-icon>
                                            @else
                                                <ion-icon name="star-outline" aria-hidden="true"></ion-icon>
                                            @endif
                                        @endfor
                                    @else
                                        <p>No rating available</p>
                                    @endif
                                </div>
                            </div>
                        </div>
            
                    </div>
            
                    <figure class="testi-banner" data-reveal="right">
                        <img src="./home-assets/images/testimonial-banner.png" width="680" height="588"
                            alt="testimonial banner" class="w-100">
                    </figure>
            
                </div>
            </section>



            <!--
        - #PARTNERSHIP
      -->

            {{-- <section class="section partnership" aria-label="partnership">
                <div class="container">

                    <h2 class="h2 section-title" data-reveal>Want to Join Partnership?</h2>

                    <ul class="grid-list">

                        <li data-reveal="left">
                            <div class="partnership-card">

                                <figure class="card-banner img-holder" style="--width: 640; --height: 402;">
                                    <img src="./home-assets/images/partner-1.jpg" width="640" height="402"
                                        loading="lazy" alt="Join Courier" class="img-cover">
                                </figure>

                                <div class="card-content">
                                    <h3 class="h5 card-title">Join Courier</h3>

                                    <a href="#" class="btn btn-primary has-after">
                                        <span class="span">Learn More</span>

                                        <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                                    </a>
                                </div>

                            </div>
                        </li>

                        <li data-reveal="right">
                            <div class="partnership-card">

                                <figure class="card-banner img-holder" style="--width: 640; --height: 402;">
                                    <img src="./home-assets/images/partner-2.jpg" width="640" height="402"
                                        loading="lazy" alt="Join Merchant" class="img-cover">
                                </figure>

                                <div class="card-content">
                                    <h3 class="h5 card-title">Join Merchant</h3>

                                    <a href="#" class="btn btn-primary has-after">
                                        <span class="span">Learn More</span>

                                        <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                                    </a>
                                </div>

                            </div>
                        </li>

                    </ul>

                </div>
            </section> --}}





            <!--
        - #NEWSLETTER
      -->

            {{-- <section class="section newsletter" aria-label="newsletter">
                <div class="container">

                    <figure class="newsletter-banner" data-reveal="left">
                        <img src="./home-assets/images/newsletter-banenr.png" width="680" height="405"
                            loading="lazy" alt="Illustration" class="w-100">
                    </figure>

                    <div class="newsletter-content" data-reveal="right">
                        <h2 class="h4 section-title">
                            Get the menu of your favorite restaurants every day
                        </h2>

                        <div class="input-wrapper">
                            <input type="email" name="email_address" placeholder="Enter email address" required
                                class="input-field">

                            <button type="submit" class="btn btn-primary has-after">
                                <ion-icon name="notifications-outline" aria-hidden="true"></ion-icon>

                                <span class="span">Subscribe</span>
                            </button>
                        </div>

                    </div>

                </div>
            </section> --}}

        </article>
    </main>





    <!--
    - #FOOTER
  -->

    <footer class="footer" id="footer">
        <div class="container" id="contacts">

            <div class="section footer-top grid-list">

                <div class="footer-brand">

                    <a href="#" class="logo">
                        {{-- <img src="./home-assets/images/logo-footer.svg" width="148" height="38" alt="fasteat home"> --}}
                        <img src="{{ asset('images/logo-name.png') }}" width="148" height="38"
                            alt="Fasteat home">
                    </a>

                    <h2 class="h2 section-title">Pisces Coffee Hub</h2>

                    <p class="section-text">
                        Coffee makes everything possible - and our variety of meals, from appetizers to hearty dishes,
                        make every visit unforgettable.
                    </p>

                </div>

                <ul class="footer-list">

                    <li>
                        <p class="footer-list-title h5">Navigation</p>
                    </li>

                    <li>
                        <a href="#home" class="footer-link">
                            <span class="span">Home</span>

                            <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                        </a>
                    </li>

                    {{-- <li>
                        <a href="#" class="footer-link">
                            <span class="span">About Us</span>

                            <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                        </a>
                    </li> --}}

                    <li>
                        <a href="#menu" class="footer-link">
                            <span class="span">Menu</span>

                            <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                        </a>
                    </li>

                    <li>
                        <a href="#contacts" class="footer-link">
                            <span class="span">Contacts</span>

                            <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                        </a>
                    </li>

                </ul>

                <ul class="footer-list">

                    <li>
                        <p class="footer-list-title h5">Contacts</p>
                    </li>

                    <li>
                        <address class="address footer-link">
                            <ion-icon name="location" aria-hidden="true"></ion-icon>

                            <span class="span">Barangay Ilang, San Carlos City, Pangasinan</span>
                        </address>
                    </li>

                    <li>
                        <a href="mailto:quickeat@mail.net" class="footer-link">
                            <ion-icon name="mail" aria-hidden="true"></ion-icon>

                            <span class="span">piscescoffeehub@gmail.com</span>
                        </a>
                    </li>

                    <li>
                        {{-- <a href="tel:+12344567890" class="footer-link"> --}}
                        <div class="footer-link">
                            <ion-icon name="call" aria-hidden="true"></ion-icon>
                            <span class="span">0945 839 3794</span>
                        </div>
                        {{-- </a> --}}
                    </li>

                    <li>
                        <ul class="social-list">

                            <li>
                                <a href="https://www.facebook.com/piscesCH" target="_blank"
                                    title="Go to Pisces Facebook Page" class="social-link">
                                    <ion-icon name="logo-facebook"></ion-icon>
                                </a>
                            </li>

                            <li>
                                <a href="https://www.instagram.com/piscescoffeehub/" target="_blank"
                                    title="Go to Pisces Instagram Account" class="social-link">
                                    <ion-icon name="logo-instagram"></ion-icon>
                                </a>
                            </li>

                            <li>
                                <a href="https://maps.app.goo.gl/a7SCsvrNhQdoydqg7" class="social-link"
                                    title="See Pisces in Maps" target="_blank">
                                    <ion-icon name="location-outline"></ion-icon>
                                </a>
                            </li>

                        </ul>
                    </li>

                </ul>

            </div>

            <div class="footer-bottom">

                <p class="copyright">
                    Copyright 2024 Pisces. All rights reserved.
                </p>

            </div>

        </div>
    </footer>





    <!--
    - custom js link
  -->
    <script src="./home-assets/js/script.js"></script>

    <!--
    - ionicon link
  -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    {{-- Modal --}}
    <script>
        const modal = document.getElementById('menuModal');
        const openModalBtn = document.getElementById('openMenuModal');
        const closeModalBtn = document.getElementById('closeMenuModal');
        const menuGrid = document.getElementById('menu-grid');

        // Open Modal and Load Content
        openModalBtn.addEventListener('click', () => {
            fetch('/menus/all')
                .then(response => response.text())
                .then(html => {
                    menuGrid.innerHTML = html;
                    modal.classList.remove('hidden');
                    modal.classList.add('visible');
                })
                .catch(error => console.error('Error loading menus:', error));
        });

        // Close Modal
        closeModalBtn.addEventListener('click', () => {
            modal.classList.remove('visible');
            modal.classList.add('hidden');
        });

        // Close Modal on Click Outside
        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.remove('visible');
                modal.classList.add('hidden');
            }
        });
    </script>



</body>

</html>
