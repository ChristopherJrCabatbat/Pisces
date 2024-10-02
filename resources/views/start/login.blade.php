@extends('start.layout')

@section('title', 'Pisces Coffee Hub - log in')

@section('styles-links')
@endsection

@section('main-content')
    <section class="section no-padding hero has-bg-image" id="home" aria-label="home"
        style="background-image: url('./home-assets/images/hero-bg.png')">
        <div class="container">
            <div class="hero-content" data-reveal="left">
                {{-- <h1 class="h1 hero-title">The Best Restaurants In Your Home</h1> --}}
                <h1 class="h1 hero-title">Log in</h1>

                <p class="hero-text">
                    Log in first to order
                </p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            autocomplete="username" placeholder="e.g. my@email.com" class="input-field">
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            placeholder="Enter your password" class="input-field">
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="actions">
                        <button type="submit" class="btn btn-secondary has-after">Log in</button>
                        {{-- <a href="{{ route('login') }}" class="btn btn-secondary has-after">Log in</a> --}}

                        <div class="register-link">
                            <span>Not a member yet?</span>
                            <a href="{{ route('register') }}">Sign up now</a>
                        </div>
                    </div>
                </form>

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
@endsection

@section('scripts')
@endsection
