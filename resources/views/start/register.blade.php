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
    <title>Pisces Coffee Hub - sign up</title>
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

    <style>
        /* Form group */
        .form-group {
            margin-bottom: 15px;
        }

        /* Input fields */
        .input-field {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            outline: none;
        }

        .input-field:focus {
            border-color: #484045;
        }

        /* Labels */
        label {
            margin-bottom: 5px;
            display: block;
            font-weight: bold;
        }

        /* Error messages */
        .error-message {
            color: #d9534f;
            margin-top: 5px;
        }

        /* Checkbox group */
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .checkbox-group input[type="checkbox"] {
            margin-right: 10px;
        }

        /* Action buttons */
        .actions {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
            background-color: #484045;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #3c373b;
        }

        /* Register link */
        .register-link {
            margin-top: 15px;
            text-align: center;
        }

        .register-link span {
            color: #6c757d;
        }

        .register-link a {
            color: #484045;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        @media (min-width: 992px) {

            .hero .container {
                grid-template-columns: 1fr 1fr;
                align-items: center;
                gap: 80px;
            }
            
        }

    </style>

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

                {{-- <a href="{{ route('login') }}" class="btn btn-primary has-after">Order Now</a> --}}
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

                    <figure class="hero-banner" data-reveal>

                        <img src="./home-assets/images/hero-banner.png" width="680" height="720" alt="hero banner"
                            class="w-100">

                        <img src="./home-assets/images/hero-shape-1.svg" width="338" height="138" alt="shape"
                            class="shape shape-1">

                        <img src="./home-assets/images/hero-shape-2.svg" width="237" height="80" alt="shape"
                            class="shape shape-2">

                    </figure>

                    <div class="hero-content" data-reveal="right">
                        {{-- <h1 class="h1 hero-title">The Best Restaurants In Your Home</h1> --}}
                        <h1 class="h1 hero-title">Sign up</h1>

                        <p class="hero-text">
                            Register an account
                        </p>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <!-- First Name -->
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required
                                    autofocus autocomplete="username" placeholder="e.g. John"
                                    class="input-field">
                                @error('first_name')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required
                                    autofocus autocomplete="username" placeholder="e.g. Doe"
                                    class="input-field">
                                @error('last_name')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                    autocomplete="username" placeholder="e.g. my@email.com"
                                    class="input-field">
                                @error('email')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                           
                            <!-- Contact Number -->
                            <div class="form-group">
                                <label for="contact_number">Contact Number</label>
                                <input id="contact_number" type="tel" name="contact_number" value="{{ old('contact_number') }}" required pattern="[0-9+\-() ]*"
                                title="Only numbers and certain characters are allowed" placeholder="e.g. 0987654321"
                                    class="input-field">
                                @error('contact_number')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input id="password" type="password" name="password" required
                                    autocomplete="current-password" placeholder="Enter your password"
                                    class="input-field">
                                @error('password')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Confirm Password -->
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" required
                                    autocomplete="current-password" placeholder="Confirm your password"
                                    class="input-field">
                                @error('password')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="actions">
                                <button type="submit" class="btn btn-secondary has-after">Sign up</button>
                                {{-- <a href="{{ route('login') }}" class="btn btn-secondary has-after">Log in</a> --}}

                                <div class="register-link">
                                    <span>Already registered?</span>
                                    <a href="{{ route('login') }}">Log in now</a>
                                </div>
                            </div>
                        </form>


                        {{-- <a href="{{ route('login') }}" class="btn btn-secondary has-after">Log in</a> --}}
                    </div>

                </div>
            </section>

        </article>
    </main>


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
