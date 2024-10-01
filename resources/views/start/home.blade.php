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

                <a href="{{ route('login') }}" class="btn btn-primary has-after">Order Now</a>
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

                        <img src="./home-assets/images/hero-shape-1.svg" width="338" height="138" alt="shape"
                            class="shape shape-1">

                        <img src="./home-assets/images/hero-shape-2.svg" width="237" height="80" alt="shape"
                            class="shape shape-2">

                    </figure>

                </div>
            </section>





            <!--
        - #INSTRUCTION
      -->

            <section class="section instruction" id="menu" aria-labelledby="">
                <div class="container">

                    {{-- <h2 class="h2 section-title" id="instruction-label" data-reveal>How It Works</h2> --}}
                    <h2 class="h2 section-title" id="instruction-label" data-reveal>What's New?</h2>

                    <p class="section-text" data-reveal>
                        Discover our latest dishes, made to satisfy every craving. Don’t miss out – try them today!
                    </p>

                    <ul class="grid-list">

                        <li data-reveal="left">
                            <div class="instruction-card">

                                <figure class="card-banner">
                                    <img src="./home-assets/images/instructuion-1.png" width="300" height="154"
                                        loading="lazy" alt="Select Restaurant" class="w-100">
                                </figure>

                                <div class="card-content">

                                    <h3 class="h5 card-title">
                                        <span class="span">01</span>
                                        Select Restaurant
                                    </h3>

                                    <p class="card-text">
                                        Non enim praesent elementum facilisis leo vel fringilla. Lectus proin nibh nisl
                                        condimentum id. Quis
                                        varius quam quisque id diam vel.
                                    </p>

                                </div>

                            </div>
                        </li>

                        <li data-reveal>
                            <div class="instruction-card">

                                <figure class="card-banner">
                                    <img src="./home-assets/images/instructuion-2.png" width="300" height="154"
                                        loading="lazy" alt="Select menu" class="w-100">
                                </figure>

                                <div class="card-content">

                                    <h3 class="h5 card-title">
                                        <span class="span">02</span>
                                        Select menu
                                    </h3>

                                    <p class="card-text">
                                        Eu mi bibendum neque egestas congue quisque. Nulla facilisi morbi tempus iaculis
                                        urna id volutpat
                                        lacus. Odio ut sem nulla pharetra diam sit amet.
                                    </p>

                                </div>

                            </div>
                        </li>

                        <li data-reveal="right">
                            <div class="instruction-card">

                                <figure class="card-banner">
                                    <img src="./home-assets/images/instructuion-3.png" width="300" height="154"
                                        loading="lazy" alt="Wait for delivery" class="w-100">
                                </figure>

                                <div class="card-content">

                                    <h3 class="h5 card-title">
                                        <span class="span">03</span>
                                        Wait for delivery
                                    </h3>

                                    <p class="card-text">
                                        Nunc lobortis mattis aliquam faucibus. Nibh ipsum consequat nisl vel pretium
                                        lectus quam id leo. A
                                        scelerisque purus semper eget. Tincidunt arcu non.
                                    </p>

                                </div>

                            </div>
                        </li>

                    </ul>

                </div>
            </section>





            <!--
        - #TOP RESTAURANT
      -->

            <section class="section top-restaurant" aria-labelledby="top-restaurent-label">
                <div class="container">

                    <ul class="grid-list">

                        <li data-reveal="left">
                            <h2 class="h2 section-title" id="top-restaurent-label">
                                Explore Our Menu
                            </h2>

                            <p class="section-text">
                                From handcrafted coffee to a variety of delicious dishes, our menu has something for
                                everyone. Whether you're craving a light snack, a hearty meal, or the perfect brew,
                                we’ve got you covered. Discover your next favorite bite!
                            </p>
                        </li>

                        <li data-reveal="right">
                            <div class="restaurant-card">

                                <div class="card-icon">
                                    <img src="./home-assets/images/rest-1.jpg" width="100" height="100"
                                        loading="lazy" alt="Kennington Lane Cafe" class="w-100">
                                </div>

                                <h3 class="h5 card-title">Kennington Lane Cafe</h3>

                                <div class="rating-wrapper">
                                    <ion-icon name="star" aria-hidden="true"></ion-icon>
                                    <ion-icon name="star" aria-hidden="true"></ion-icon>
                                    <ion-icon name="star" aria-hidden="true"></ion-icon>
                                    <ion-icon name="star" aria-hidden="true"></ion-icon>
                                    <ion-icon name="star-outline" aria-hidden="true"></ion-icon>
                                </div>

                                <div class="card-meta-wrapper">
                                    <a href="#" class="card-meta">american</a>
                                    <a href="#" class="card-meta">steakhouse</a>
                                </div>

                                <p class="card-text">
                                    Non enim praesent elementum facilisis leo vel fringilla. Lectus proin nibh nisl
                                    condimentum id. Quis
                                    varius quam quisque id diam vel.
                                </p>

                            </div>
                        </li>

                        <li data-reveal="left">
                            <div class="restaurant-card">

                                <div class="card-icon">
                                    <img src="./home-assets/images/rest-2.jpg" width="100" height="100"
                                        loading="lazy" alt="The Wilmington" class="w-100">
                                </div>

                                <h3 class="h5 card-title">The Wilmington</h3>

                                <div class="rating-wrapper">
                                    <ion-icon name="star" aria-hidden="true"></ion-icon>
                                    <ion-icon name="star" aria-hidden="true"></ion-icon>
                                    <ion-icon name="star" aria-hidden="true"></ion-icon>
                                    <ion-icon name="star" aria-hidden="true"></ion-icon>
                                    <ion-icon name="star-outline" aria-hidden="true"></ion-icon>
                                </div>

                                <div class="card-meta-wrapper">
                                    <a href="#" class="card-meta">american</a>
                                    <a href="#" class="card-meta">steakhouse</a>
                                </div>

                                <p class="card-text">
                                    Vulputate enim nulla aliquet porttitor lacus luctus. Suscipit adipiscing bibendum
                                    est ultricies
                                    integer. Sed adipiscing diam donec adipiscing tristique.
                                </p>

                            </div>
                        </li>

                        <li data-reveal="right">
                            <div class="restaurant-card">

                                <div class="card-icon">
                                    <img src="./home-assets/images/rest-3.jpg" width="100" height="100"
                                        loading="lazy" alt="Kings Arms" class="w-100">
                                </div>

                                <h3 class="h5 card-title">Kings Arms</h3>

                                <div class="rating-wrapper">
                                    <ion-icon name="star" aria-hidden="true"></ion-icon>
                                    <ion-icon name="star" aria-hidden="true"></ion-icon>
                                    <ion-icon name="star" aria-hidden="true"></ion-icon>
                                    <ion-icon name="star" aria-hidden="true"></ion-icon>
                                    <ion-icon name="star-outline" aria-hidden="true"></ion-icon>
                                </div>

                                <div class="card-meta-wrapper">
                                    <a href="#" class="card-meta">american</a>
                                    <a href="#" class="card-meta">healthy</a>
                                </div>

                                <p class="card-text">
                                    Tortor at risus viverra adipiscing at in tellus. Cras semper auctor neque vitae
                                    tempus. Dui accumsan
                                    sit amet nulla facilisi. Sed adipiscing diam donec adipiscing tristique.
                                </p>

                            </div>
                        </li>

                    </ul>

                    <a href="#" class="btn btn-secondary has-after">
                        <span class="span">See All</span>

                        <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                    </a>

                </div>
            </section>





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

            <section class="section testi" aria-labelledby="testi-label">
                <div class="container">

                    <div class="testi-content" data-reveal="left">

                        <h2 class="h2 section-title" id="testi-label">What customers say about us</h2>

                        <blockquote class="testi-text">
                            "I really love this place! The coffee is amazing, and the food is so good, especially the
                            pizza! The staff is super friendly, and it feels just like home. It's definitely my new
                            favorite tambayan!"
                        </blockquote>

                        <div class="wrapper">
                            <img src="./home-assets/images/testi-avatar.jpg" width="70" height="70"
                                loading="lazy" alt="Thomas Adamson" class="author-img">

                            <div>
                                <p class="author-title">Cardo Dalisay</p>

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
                        <img src="./images/logo-name.png" width="148" height="38" alt="Fasteat home">
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
                        <address class="address">
                            <ion-icon name="location" aria-hidden="true"></ion-icon>

                            {{-- <span class="span">1717 Harrison St, San Francisco, CA 94103, United States</span> --}}
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
                        <a href="tel:+12344567890" class="footer-link">
                            <ion-icon name="call" aria-hidden="true"></ion-icon>

                            <span class="span">0945 839 3794</span>
                        </a>
                    </li>

                    <li>
                        <ul class="social-list">

                            <li>
                                <a href="https://www.facebook.com/piscesCH" target="_blank" class="social-link">
                                    <ion-icon name="logo-facebook"></ion-icon>
                                </a>
                            </li>

                            <li>
                                <a href="#" class="social-link">
                                    <ion-icon name="logo-instagram"></ion-icon>
                                </a>
                            </li>

                            <li>
                                <a href="#" class="social-link">
                                    <ion-icon name="logo-twitter"></ion-icon>
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

</body>

</html>
