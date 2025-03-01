<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pisces Coffee Hub</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-home.png') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="https://kit.fontawesome.com/f416851b63.js" crossorigin="anonymous"></script>

    @yield('styles-links')

</head>

<body>

    @yield('modals')

    @php
        $promotions = session('availablePromotions', []);
    @endphp

    @if ($promotions)
        <div class="promotion-modal-container">
            @foreach ($promotions as $promotion)
                <div class="promotion-modal" id="promotionModal-{{ $promotion->id }}">
                    <div class="promotion-modal-content">
                        <div class="promotion-modal-header">
                            <h5>{{ $promotion->name }}</h5>
                            <button class="promotion-modal-close"
                                data-modal-id="promotionModal-{{ $promotion->id }}">✖</button>
                        </div>
                        <div class="promotion-modal-body text-center">
                            <img src="{{ asset('storage/' . $promotion->image) }}" alt="{{ $promotion->name }}"
                                class="promotion-modal-img">
                            <p class="promotion-notice">This promotion is available for dine-in customers only.</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modals = Array.from(document.querySelectorAll('.promotion-modal'));
            let currentModalIndex = 0;

            const showModal = (index) => {
                if (index >= 0 && index < modals.length) {
                    modals[index].style.display = 'flex';
                }
            };

            const hideModal = (index) => {
                if (index >= 0 && index < modals.length) {
                    modals[index].style.display = 'none';
                }
            };

            modals.forEach((modal, index) => {
                const closeBtn = modal.querySelector('.promotion-modal-close');

                closeBtn.addEventListener('click', () => {
                    hideModal(index);

                    // Show the next modal only if it's not the last
                    if (index + 1 < modals.length) {
                        showModal(index + 1);
                    } else {
                        // Clear promotions from session after showing all modals
                        fetch('/clear-promotions-session', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                    }
                });
            });

            // Show the first modal on page load
            showModal(currentModalIndex);
        });
    </script>


    @if (session()->pull('showExperienceModal'))
        <div id="experienceModal" class="experience-modal-container">
            <div class="experience-modal-content">
                <!-- Close Button -->
                <button type="button" class="experience-modal-close" onclick="closeExperienceModal()">×</button>

                <!-- Modal Header and Text -->
                <h4 class="experience-modal-header">We value your feedback!</h4>
                <p class="experience-modal-text">Thank you for your continued support! We'd love to hear from you.
                    Please rate your experience in using our service:</p>

                <!-- Feedback Form -->
                <form action="{{ route('user.submitExperience') }}" method="POST">
                    @csrf
                    <div class="experience-modal-form-group">
                        <label for="rating" class="experience-modal-label">Rate Us:</label>
                        <select name="rating" id="rating" class="experience-modal-select" required>
                            <option value="" disabled selected>Choose your rating</option>
                            <option value="1">1 - Poor</option>
                            <option value="2">2 - Fair</option>
                            <option value="3">3 - Good</option>
                            <option value="4">4 - Very Good</option>
                            <option value="5">5 - Excellent</option>
                        </select>
                    </div>
                    <div class="experience-modal-form-group">
                        <label for="feedback" class="experience-modal-label">Your Feedback:</label>
                        <textarea name="feedback" id="feedback" class="experience-modal-textarea" rows="4"
                            placeholder="Share your thoughts with us..."></textarea>
                    </div>
                    <button type="submit" class="experience-modal-button">Submit Feedback</button>
                </form>
            </div>
        </div>
        <script>
            // Open the modal on page load
            window.onload = function() {
                document.getElementById('experienceModal').style.display = 'flex';
            };

            // Close modal function
            function closeExperienceModal() {
                document.getElementById('experienceModal').style.display = 'none';
            }
        </script>
    @endif

    <!-- Menu Details Modal -->
    <div class="modal fade" id="menuDetailsModal" tabindex="-1" aria-labelledby="menuDetailsModalLabel"
        style="overflow: hidden;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content text-black">
                <div class="modal-header">
                    <h5 class="modal-title" id="menuDetailsModalLabel">Menu Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="product-page">
                        <!-- Product Images -->
                        <div class="product-images">
                            <img src="" alt="Product Image" id="menuImage" class="main-image img-fluid">
                        </div>

                        <!-- Product Details -->
                        <div class="product-details">
                            <h1 id="menuName" class="h2"></h1>
                            <div class="ratings mb-2">
                                <span id="menuRating">⭐ 4.2</span>
                            </div>

                            <!-- Pricing Section -->
                            <div class="pricing mb-2">
                                <span id="discountedPrice" class="discounted-price"></span>
                                <span id="originalPrice" class="original-price"></span>
                                <span id="discountPercentage" class="discount"></span>
                            </div>

                            <!-- Category and Description -->
                            <p><strong>Category:</strong> <span id="menuCategory"></span></p>
                            <p><strong>Description:</strong> <span id="menuDescription"></span></p>

                            <!-- Quantity Selector -->
                            <div class="quantity-selector mb-3">
                                <button type="button" class="btn qty-btn rounded-circle"
                                    onclick="modalDecrementQuantity(this)">
                                    <i class="fa fa-minus"></i>
                                </button>

                                <input type="text" readonly name="display_quantity" value="1" min="1"
                                    class="form-control text-center mx-2 quantity-input" style="width: 60px;"
                                    id="modalQuantityInput">

                                <!-- Hidden input to pass the quantity to the backend -->
                                <input type="hidden" name="quantity" id="modalHiddenQuantity" value="1">

                                <button type="button" class="btn qty-btn rounded-circle"
                                    onclick="modalIncrementQuantity(this)">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>

                            <!-- Action Buttons -->
                            <div class="action-buttons">
                                <button type="button" class="btn btn-danger modal-button add-to-cart">Add To
                                    Cart</button>
                                <button class="btn btn-danger modal-button order-now">Order Now</button>
                            </div>

                            <!-- Additional Info -->
                            <div class="extra-info mt-3">
                                <span>❤️ 0 Favorites</span>
                                {{-- <span><i class="fa-solid fa-heart me-1" style="color: red;"></i> {{ $favoritesCount }} Favorites</span> --}}
                                {{-- <span>Shopee Guarantee</span> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="custom-modal-overlay" id="editProfileModal">
        <div class="custom-modal">
            <div class="custom-modal-header text-black">
                <h5 class="custom-modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" onclick="closeModal('editProfileModal')"
                    aria-label="Close"></button>
            </div>
            <div class="custom-modal-body">
                <form id="editProfileForm" method="POST" action="{{ route('user.userUpdate') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3 d-flex gap-2">

                        <!-- First Name -->
                        <div class="w-100">
                            <label for="firstName" class="form-label text-black">First Name:</label>
                            <input type="text" class="form-control" id="firstName" name="first_name"
                                value="{{ old('first_name', Auth::user()->first_name) }}" required
                                placeholder="e.g. John">
                            @error('first_name')
                                <div><small class="text-danger">{{ $message }}</small></div>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="w-100">
                            <label for="lastName" class="form-label text-black">Last Name:</label>
                            <input type="text" class="form-control" id="lastName" name="last_name"
                                value="{{ old('last_name', Auth::user()->last_name) }}" required
                                placeholder="e.g. Doe">
                            @error('last_name')
                                <div><small class="text-danger">{{ $message }}</small></div>
                            @enderror
                        </div>

                    </div>

                    <div class="mb-3 d-flex gap-2">

                        <!-- Email -->
                        <div class="w-100">
                            <label for="email" class="form-label text-black">Email:</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email', Auth::user()->email) }}" required
                                placeholder="e.g. my@email.com">
                            @error('email')
                                <div><small class="text-danger">{{ $message }}</small></div>
                            @enderror
                        </div>

                        <!-- Contact Number -->
                        <div class="" style="width: 60%;">
                            <label for="contactNumber" class="form-label text-black">Contact Number:</label>
                            <input type="text" class="form-control" id="contactNumber" name="contact_number"
                                value="{{ old('contact_number', Auth::user()->contact_number) }}" required
                                placeholder="09876543210">
                            @error('contact_number')
                                <div><small class="text-danger">{{ $message }}</small></div>
                            @enderror
                        </div>

                    </div>

                    <div class="mb-3 d-flex gap-2">

                        <!-- House Number -->
                        <div class="" style="width: 30%;">
                            <label for="house_num" class="form-label text-black">House Num:</label>
                            <input type="number" class="form-control" id="house_num" name="house_num"
                                value="{{ old('house_num', Auth::user()->house_num) }}" placeholder="e.g. 123">
                        </div>

                        <!-- Purok -->
                        <div class="" style="width: 30%;">
                            <label for="purok" class="form-label text-black">Purok:</label>
                            <input type="number" class="form-control" id="purok" name="purok"
                                value="{{ old('purok', Auth::user()->purok) }}" placeholder="e.g. 5">
                        </div>

                        <!-- Barangay -->
                        <div class="w-100">
                            <label for="barangay" class="form-label text-black">Barangay:</label>
                            <select id="barangay" name="barangay" class="form-select" style="width: 100% !important;" required>
                                <option value="">Select Barangay</option>
                            </select>

                        </div>

                    </div>

                    <!-- Hidden Input for Shipping Fee -->
                    <input type="hidden" id="hiddenShippingFee" name="shipping_fee" value="{{ old('shipping_fee', Auth::user()->shipping_fee) }}">


                    <!-- Change Password -->
                    <div class="mb-3">
                        <label for="newPassword" class="form-label text-black">New Password:</label>
                        <input type="password" class="form-control" id="newPassword" name="password">
                        <small class="text-secondary">*Leave this blank if you don't want to change your
                            password.</small>
                        @error('password')
                            <div><small class="text-danger">{{ $message }}</small></div>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label text-black">Confirm New Password:</label>
                        <input type="password" class="form-control" id="confirmPassword"
                            name="password_confirmation">
                        @error('password_confirmation')
                            <div><small class="text-danger">{{ $message }}</small></div>
                        @enderror
                    </div>

                    <!-- Newsletter Subscription -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="newsletterSubscription"
                            name="newsletter_subscription" value="1"
                            {{ Auth::user()->newsletter_subscription ? 'checked' : '' }}>
                        <label class="form-check-label text-black" for="newsletterSubscription">
                            I want to receive updates about new menu items, discounts, and events.
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <header>
        <nav class="navbar navss navbar-expand-lg fixed-top" style="background-color: #fff;">
            <div class="container">
                <a class="navbar-brand" href="{{ route('user.dashboard') }}">
                    <img src="{{ asset('images/logo-name.png') }}" width="148" height="" alt="Pisces logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarScroll">
                    <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll">
                        @yield('topbar')
                    </ul>

                    <div class="d-flex align-items-center" style="max-height: 57px">

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
                            <li class="nav-item dropdown dropdown-binago">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->first_name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="#"
                                            onclick="openModal(event, 'editProfileModal')">
                                            <i class="fa-solid fa-user me-2"></i>Profile
                                        </a>
                                    </li>
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
        @yield('main-content')
    </main>

    <footer class="footer text-black">
        <div>
            <div class="container pt-5">
                <div class="row footer-content border-bottoms pb-4 gap-4 footer-fs">
                    <div class="col d-flex flex-column">
                        <div>
                            <img src="{{ asset('images/logo-name.png') }}" width="148" height=""
                                alt="Pisces Coffee Hub">
                        </div>
                        <div class="h1 footer-title my-3 fw-bold">Pisces Coffee Hub</div>
                        <div>
                            Coffee makes everything possible - and our variety of meals, from appetizers to hearty
                            dishes, make every visit unforgettable.
                        </div>
                    </div>
                    <div class="col d-flex flex-column gap-3">
                        <div class="h3 footer-title mb-3 fw-bold">Contact Info</div>
                        <div class="d-flex align-items-center">
                            <span class="border-bottoms pb-2 red-hover"><i class="fa-solid fa-location-dot me-2"></i>
                                Barangay
                                Ilang, San Carlos City, Pangasinan</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <a href="mailto:piscescofeehub@gmail.com" class="red-hover"><i
                                    class="fa-solid fa-envelope me-2"></i> piscescofeehub@gmail.com</a>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="red-hover"><i class="fa-solid fa-phone me-2"></i> 0945 839 3794</div>
                        </div>
                        <div class="icons d-flex align-items-center gap-3 mt-4">
                            <a href="https://www.facebook.com/@piscesCH" title="Go to Pisces Facebook Page"
                                target="_blank" class="social-link">
                                <div class="rounded-circle">
                                    <i class="fa-brands fa-facebook"></i>
                                </div>
                            </a>
                            <a href="https://www.instagram.com/piscescoffeehub/" class="social-link"
                                title="Go to Pisces Instagram Account" target="_blank">
                                <div class="rounded-circle">
                                    <i class="fa-brands fa-instagram"></i>
                                </div>
                            </a>
                            <a href="https://www.tiktok.com/@piscescoffeehub2017" title="Go to Pisces TikTok"
                                target="_blank" class="social-link">
                                <div class="rounded-circle">
                                    <i class="fa-brands fa-tiktok"></i>
                                </div>
                            </a>
                            <a href="https://maps.app.goo.gl/a7SCsvrNhQdoydqg7" title="See Pisces in Maps"
                                target="_blank" class="social-link">
                                <div class="rounded-circle">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="copyright text-center py-4">
                    <div>Copyright 2025 Pisces. All rights reserved.</div>
                </div>
            </div>
        </div>
    </footer>

    {{-- Profile Modal Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const editProfileForm = document.getElementById('editProfileForm');
            const modalOverlay = document.getElementById('editProfileModal');

            // Reopen the modal if there are server-side validation errors
            if ({{ $errors->any() ? 'true' : 'false' }}) {
                modalOverlay.classList.add('active');
                modalOverlay.querySelector('.custom-modal').classList.add('active');
            }

            // Add client-side password validation
            editProfileForm.addEventListener('submit', (event) => {
                const password = document.getElementById('newPassword').value;
                const confirmPassword = document.getElementById('confirmPassword').value;

                // Clear previous error messages
                document.querySelectorAll('.validation-error').forEach(el => el.remove());

                if (password !== confirmPassword) {
                    event.preventDefault(); // Prevent form submission

                    // Display error message
                    const errorMsg = document.createElement('small');
                    errorMsg.className = 'text-danger validation-error';
                    errorMsg.textContent = 'Passwords do not match.';
                    document.getElementById('confirmPassword').after(errorMsg);
                }
            });
        });
    </script>

    @yield('scripts')

    @if (session('discount'))
        <script>
            alert("{{ session('discount') }}");
        </script>
    @else
        <script>
            console.log("Session flash not found.");
        </script>
    @endif


    {{-- Icon Actions Toast Message --}}
    <div id="customToastBox"></div>
    <script>
        let customToastBox = document.getElementById('customToastBox');

        function showToast(msg, type) {
            let customToast = document.createElement('div');
            customToast.classList.add('custom-toast');

            // Set the icon based on the type
            let icon = type === 'error' ?
                '<i class="fa fa-circle-xmark"></i>' :
                '<i class="fa fa-circle-check"></i>';

            customToast.innerHTML = `${icon} ${msg}`;
            customToastBox.appendChild(customToast);

            // Add class for error or success styles
            if (type === 'error') {
                customToast.classList.add('error');
            }

            setTimeout(() => {
                customToast.remove();
            }, 5500);
        }

        // Check if a toast message exists in the session
        @if (session('toast'))
            const toastData = @json(session('toast'));
            showToast(toastData.message, toastData.type);
        @endif
    </script>


    {{-- Modal Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewButtons = document.querySelectorAll('.view-menu-btn');

            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const menuId = this.getAttribute('data-id');

                    // Fetch menu details
                    fetch(`/user/menuView/${menuId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Populate modal with menu details
                            document.getElementById('menuImage').src = data.image ?
                                `/storage/${data.image}` :
                                '/images/logo.jpg';
                            document.getElementById('menuName').textContent = data.name;
                            document.getElementById('menuCategory').textContent = data.category;
                            document.getElementById('menuDescription').textContent = data
                                .description;

                            // Populate pricing
                            const discountedPriceElement = document.getElementById(
                                'discountedPrice');
                            const originalPriceElement = document.getElementById(
                                'originalPrice');
                            const discountPercentageElement = document.getElementById(
                                'discountPercentage');

                            if (data.discount > 0) {
                                discountedPriceElement.textContent =
                                    `₱${data.discountedPrice.toLocaleString()}`;
                                discountPercentageElement.textContent =
                                    `(-${data.discount}% OFF)`;
                                discountPercentageElement.classList.add('text-success');

                                originalPriceElement.textContent =
                                    `₱${data.price.toLocaleString()}`;
                                originalPriceElement.classList.add('text-muted',
                                    'text-decoration-line-through');
                            } else {
                                discountedPriceElement.textContent =
                                    `₱${data.price.toLocaleString()}`;
                                discountPercentageElement.textContent = '';
                                originalPriceElement.textContent = '';
                            }

                            // Ratings and favorites
                            const starContainer = document.getElementById('menuRating');
                            starContainer.innerHTML = ''; // Clear stars
                            const rating = parseFloat(data.rating || 0);
                            const fullStars = Math.floor(rating);
                            const halfStar = rating % 1 >= 0.5 ? 1 : 0;
                            const emptyStars = 5 - (fullStars + halfStar);

                            for (let i = 0; i < fullStars; i++) {
                                const star = document.createElement('i');
                                star.className = 'fa-solid fa-star';
                                starContainer.appendChild(star);
                            }

                            if (halfStar) {
                                const halfStarIcon = document.createElement('i');
                                halfStarIcon.className = 'fa-solid fa-star-half-stroke';
                                starContainer.appendChild(halfStarIcon);
                            }

                            for (let i = 0; i < emptyStars; i++) {
                                const emptyStarIcon = document.createElement('i');
                                emptyStarIcon.className = 'fa-regular fa-star';
                                starContainer.appendChild(emptyStarIcon);
                            }

                            // Add rating text and favorites
                            const ratingText = document.createElement('span');
                            ratingText.textContent = data.ratingCount > 0 ?
                                ` (${rating.toFixed(1)}) ${data.ratingCount} review${data.ratingCount > 1 ? 's' : ''}` :
                                ' No reviews yet';
                            starContainer.appendChild(ratingText);

                            const favoriteInfo = document.querySelector('.extra-info span');
                            favoriteInfo.textContent = `❤️ ${data.favoriteCount} Favorites`;

                            // Handle quantity inputs
                            document.getElementById('modalQuantityInput').value = 1;
                            document.getElementById('modalHiddenQuantity').value = 1;

                            document.querySelector('.modal-button.order-now').onclick =
                                function() {
                                    const quantity = document.getElementById(
                                        'modalHiddenQuantity').value;
                                    window.location.href =
                                        `/user/orderView/${menuId}?quantity=${quantity}`;
                                };

                            document.querySelector('.modal-button.add-to-cart').onclick =
                                function() {
                                    fetch(`/user/addToCart/${menuId}`, {
                                        method: 'PUT',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-Token': '{{ csrf_token() }}',
                                        },
                                    });
                                    window.location.reload();
                                };


                            // Show modal
                            const menuDetailsModal = new bootstrap.Modal(
                                document.getElementById('menuDetailsModal')
                            );
                            menuDetailsModal.show();
                        })
                        .catch(error => {
                            console.error('Error fetching menu details:', error);
                            alert('Failed to fetch menu details. Please try again.');
                        });
                });


            });
        });

        // Modal-specific quantity increment and decrement
        function modalIncrementQuantity(button) {
            let input = document.getElementById('modalQuantityInput');
            input.value = parseInt(input.value) + 1;
            document.getElementById('modalHiddenQuantity').value = input.value;
        }

        function modalDecrementQuantity(button) {
            let input = document.getElementById('modalQuantityInput');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                document.getElementById('modalHiddenQuantity').value = input.value;
            }
        }
    </script>

    {{-- Share Link Script --}}
    <script>
        function copyMenuLink(menuId) {
            // Construct the menu link URL
            const menuLink = `${window.location.origin}/user/menuDetails/${menuId}`;

            // Copy to clipboard
            navigator.clipboard.writeText(menuLink)
                .then(() => {
                    // Show success toast
                    showToast('Menu link copied successfully!', 'success');
                })
                .catch(err => {
                    // Show error toast
                    showToast('Failed to copy the menu link!', 'error');
                    console.error('Failed to copy the text: ', err);
                });
        }
    </script>

    {{-- no need reload unread  --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messageLinks = document.querySelectorAll('.message-a');

            messageLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const userId = this.dataset.userId; // Attach userId to <a>

                    // Mark messages as read via AJAX
                    fetch(`/user/markAsRead/${userId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                    }).then(response => {
                        if (response.ok) {
                            // Update styling dynamically
                            this.querySelector('.message-name').classList.remove('fw-bold');
                            this.querySelector('.message-text').classList.remove('fw-bold');
                        }
                        window.location.href = this.href; // Redirect after marking as read
                    });
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const barangayDropdown = document.getElementById('barangay');
            const hiddenShippingInput = document.getElementById('hiddenShippingFee');
    
            const oldBarangay = "{{ old('barangay', Auth::user()->barangay) }}"; // Old or default value
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
    
            // Populate the dropdown
            Object.entries(barangayRates).forEach(([barangay, rate]) => {
                const option = document.createElement('option');
                option.value = barangay;
                option.textContent = barangay;
    
                // Set the selected option
                if (barangay === oldBarangay) {
                    option.selected = true;
                    hiddenShippingInput.value = rate; // Set the initial shipping fee
                }
    
                barangayDropdown.appendChild(option);
            });
    
            // Handle dropdown change to update the shipping fee
            barangayDropdown.addEventListener('change', function () {
                const selectedBarangay = barangayDropdown.value;
                const shippingFee = barangayRates[selectedBarangay] || 0; // Default to 0 if not found
                hiddenShippingInput.value = shippingFee; // Update the hidden input
            });
        });
    </script>

    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.js') }}"></script>

</body>

</html>
