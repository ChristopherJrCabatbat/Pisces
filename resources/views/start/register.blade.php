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

        .flex-gap {
            display: flex;
            gap: 1rem;
        }

        .two-columns-container {
            width: 100%;
        }

        select.input-field {
            background-color: transparent;
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

                    <div class="flex-gap">
                        <!-- First Name -->
                        <div class="form-group two-columns-container">
                            <label for="first_name">First Name</label>
                            <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required
                                autofocus autocomplete="username" placeholder="e.g. John" class="input-field">
                            @error('first_name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="form-group two-columns-container">
                            <label for="last_name">Last Name</label>
                            <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required
                                placeholder="e.g. Doe" class="input-field">
                            @error('last_name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
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

                    {{-- Address --}}
                    <div class="flex-gap">
                        <!-- House Number -->
                        <div class="form-group two-columns-container">
                            <label for="house_num">House Number (optional)</label>
                            <input id="house_num" type="number" name="house_num" value="{{ old('house_num') }}"
                                placeholder="e.g. 123" class="input-field">
                            @error('house_num')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Purok -->
                        <div class="form-group two-columns-container">
                            <label for="purok">Purok (optional)</label>
                            <input id="purok" type="number" name="purok" value="{{ old('purok') }}"
                                placeholder="e.g. 5" class="input-field" min="0" max="20">
                            @error('purok')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Barangay Dropdown -->
                    <div class="form-group">
                        <label for="barangay">Barangay</label>
                        <select id="barangay" name="barangay" class="input-field" required>
                            <option value="">Select Barangay</option>
                            <!-- Options will be dynamically populated -->
                        </select>
                    </div>

                    <!-- Hidden Input for Shipping Fee -->
                    <input type="hidden" id="hiddenShippingFee" name="shipping_fee" value="0">

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
                        <input type="checkbox" id="newsletter_subscription" name="newsletter_subscription"
                            value="1">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const barangayDropdown = document.getElementById('barangay');
            const hiddenShippingInput = document.getElementById(
                'hiddenShippingFee'); // Hidden input for form submission

            // Barangay with corresponding shipping fees
            const barangayRates = {
                "Abanon": 110,
                "Agdao": 80,
                "Ano": 80,
                "Anando": 120,
                "Antipangol": 140,
                "Aponit": 100,
                "Bacnar": 80,
                "Bacnar UP": 90,
                "Balaya": 100,
                "Balayong": 70,
                "Baldog": 80,
                "Balite Sur": 90,
                "Balococ": 100,
                "Bani": 160,
                "Bega": 70,
                "Bogaoan": 170,
                "Bocboc East": 150,
                "Bocboc West": 170,
                "Bolingit": 70,
                "Bolosan": 70,
                "Bonifacio": 40,
                "Bugallon": 40,
                "Buenglat": 90,
                "Burgos-Padlan": 40,
                "Cacaritan": 60,
                "Caingal": 60,
                "Calobaoan": 120,
                "Calomboyan": 100,
                "Caoayan Kiling": 130,
                "Capataan": 90,
                "Cobol": 100,
                "Coliling": 70,
                "Coliling Anlabo": 90,
                "Cruz": 80,
                "Doyong": 80,
                "Gamata": 90,
                "Guelew": 140,
                "Ilang": 40,
                "Inerangan": 90,
                "Isla": 100,
                "Libas": 120,
                "Lilimasan": 70,
                "Longos": 60,
                "Lucban": 40,
                "M. Soriano st.": 40,
                "Mabalbalino": 150,
                "Mabini": 40,
                "Magtaking": 60,
                "Malacañang": 90,
                "Maliwa": 90,
                "Mamarlao Court": 40,
                "Manzon": 60,
                "Matagdem": 70,
                "Mc Arthur": 40,
                "Meztizo Norte": 70,
                "Naguilayan": 80,
                "Nilentap": 90,
                "Padilla": 40,
                "Pagal": 70,
                "Palaming": 70,
                "Palaris": 40,
                "Palospos": 120,
                "Paitan": 80,
                "Pangoloan": 80,
                "Pangalangan": 80,
                "Pangpang": 90,
                "Parayao": 100,
                "Payapa": 90,
                "Payar": 100,
                "Perez": 40,
                "PNR": 40,
                "Posadas Street": 40,
                "Polo": 90,
                "Quezon": 40,
                "Quintong": 90,
                "Rizal": 40,
                "Roxas": 40,
                "Salinap": 120,
                "San Juan": 60,
                "San Pedro": 40,
                "Taloy": 60,
                "Sapinit": 70,
                "Supo": 150,
                "Talang": 90,
                "Taloy (Until VMUF)": 40,
                "Tamayo": 130,
                "Tandoc": 80,
                "Tandang Sora": 40,
                "Tarece": 60,
                "Tarectec": 90,
                "Tayambani": 90,
                "Tebag": 80,
                "Turac": 80
            };

            // Dynamically populate the Barangay dropdown
            Object.keys(barangayRates).forEach(barangay => {
                const option = document.createElement('option');
                option.value = barangay;
                option.textContent = barangay;
                barangayDropdown.appendChild(option);
            });

            // Handle dropdown change
            barangayDropdown.addEventListener('change', function() {
                const selectedBarangay = barangayDropdown.value;
                const shippingFee = barangayRates[selectedBarangay] || 0; // Default to 0

                // Update hidden shipping input
                hiddenShippingInput.value = shippingFee;
            });
        });
    </script>


@endsection
