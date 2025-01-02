@extends('start.layout')

@section('title', 'Pisces Coffee Hub - sign up')

@section('styles-links')
    <style>
        @media (min-width: 992px) {

            .hero .container {
                grid-template-columns: 1fr 1fr;
                align-items: center;
                gap: 80px;
            }

        }

        .newsletter-subscription {
            display: flex;
            align-items: flex-start;
            gap: 0.8rem;
            margin-bottom: 1rem;
        }

        .newsletter-subscription input[type="checkbox"] {
            width: 1.2rem;
            height: 1.2rem;
            margin-top: 0.4rem;
            accent-color: #ff5e57;
            cursor: pointer;
        }

        .newsletter-subscription label {
            font-size: 1.4rem;
            font-weight: normal;
            margin: 0;
            line-height: 1.5;
            color: #333;
            cursor: pointer;
        }
    </style>
@endsection

@section('main-content')

    <section class="section hero has-bg-image" id="home" aria-label="home"
        style="background-image: url('./home-assets/images/hero-bg.png')">
        <div class="container">

            <figure class="hero-banner" data-reveal>

                <img src="./home-assets/images/hero-banner.png" width="680" height="720" alt="hero banner" class="w-100">

                <img src="{{ asset('home-assets/images/pisces-white.jpg') }}" width="280" height="120" alt="shape"
                    class="shape shape-1">

                <img src="{{ asset('home-assets/images/ilang-white.jpg') }}" width="217" height="80" alt="shape"
                    class="shape shape-2">

            </figure>

            <div class="hero-content" data-reveal="right">
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
                            autofocus autocomplete="username" placeholder="e.g. John" class="input-field">
                        @error('first_name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required
                            placeholder="e.g. Doe" class="input-field">
                        @error('last_name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            placeholder="e.g. my@email.com" class="input-field">
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Contact Number -->
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input id="contact_number" type="tel" name="contact_number" value="{{ old('contact_number') }}"
                            required pattern="[0-9+\-() ]*" title="Only numbers and certain characters are allowed"
                            placeholder="e.g. 0987654321" class="input-field">
                        @error('contact_number')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" required placeholder="Enter your password"
                            class="input-field">
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            placeholder="Confirm your password" class="input-field">
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Newsletter Subscription -->
                    <div class="form-group newsletter-subscription">
                        <input type="checkbox" id="newsletter_subscription" name="newsletter_subscription" value="1">
                        <label for="newsletter_subscription">
                            I want to receive updates about new menu items, discounts, and events.
                        </label>
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

            </div>

        </div>
    </section>

@endsection

@section('scripts')
@endsection
