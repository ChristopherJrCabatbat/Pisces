<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <script src="https://kit.fontawesome.com/f416851b63.js" crossorigin="anonymous"></script>

    @yield('styles-links')

</head>

<body>

    @yield('modals')
    <!-- Product Details Modal -->
    <div class="modal fade" id="menuDetailsModal" tabindex="-1" aria-labelledby="menuDetailsModalLabel"
        aria-hidden="true">
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
                                <span id="ratingCount">(4K Ratings)</span>
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
                                <span>❤️ 1K Favorites</span>
                                {{-- <span>Shopee Guarantee</span> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        @yield('topbar')
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
                            <span class="border-bottoms pb-2"><i class="fa-solid fa-location-dot me-2"></i> Barangay
                                Ilang, San Carlos City, Pangasinan</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-envelope me-2"></i> piscescoffeehub@gmail.com
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-phone me-2"></i> 0945 839 3794
                        </div>
                        <div class="icons d-flex align-items-center gap-4 mt-4">
                            <a href="https://www.facebook.com/@piscesCH" title="Go to Pisces Facebook Page"
                                target="_blank" class="social-link">
                                <div class="rounded-circle">
                                    <i class="fa-brands fa-facebook"></i>
                                </div>
                            </a>
                            <div class="rounded-circle">
                                <i class="fa-brands fa-instagram"></i>
                            </div>
                            <div class="rounded-circle">
                                <i class="fa-brands fa-twitter"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="copyright text-center py-4">
                    <div>Copyright 2024 Pisces. All rights reserved.</div>
                </div>
            </div>
        </div>
    </footer>


    @yield('scripts')

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
            }, 5000);
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

                    // Fetch menu details via AJAX
                    fetch(`/user/menuView/${menuId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Populate the modal with menu details
                            document.getElementById('menuImage').src = data.image ?
                                `/storage/${data.image}` : '/images/logo.jpg';
                            document.getElementById('menuName').textContent = data.name;
                            document.getElementById('menuCategory').textContent = data.category;
                            document.getElementById('menuDescription').textContent = data
                                .description;
                            document.getElementById('discountedPrice').textContent =
                                `₱${parseFloat(data.price).toLocaleString()}`;
                            document.getElementById('menuRating').textContent =
                                `⭐ ${data.rating}`;
                            document.getElementById('ratingCount').textContent =
                                `(${data.ratingCount} Ratings)`;

                            // Reset the quantity input for each new modal view
                            document.getElementById('modalQuantityInput').value = 1;
                            document.getElementById('modalHiddenQuantity').value = 1;

                            // Set button destination for "Order Now"
                            document.querySelector('.modal-button.order-now').onclick =
                                function() {
                                    const quantity = document.getElementById(
                                        'modalHiddenQuantity').value;
                                    window.location.href =
                                        `/user/orderView/${menuId}?quantity=${quantity}`;
                                };

                            // Add To Cart
                            document.querySelector('.modal-button.add-to-cart').onclick =
                                function() {
                                    const quantity = document.getElementById(
                                        'modalHiddenQuantity').value;

                                    fetch(`/user/addToCart/${menuId}`, {
                                            method: 'PUT',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-Token': '{{ csrf_token() }}',
                                            },
                                            body: JSON.stringify({
                                                quantity
                                            }),
                                        })
                                        .then(response => {
                                            if (!response.ok) {
                                                throw new Error(
                                                    'Failed to add item to cart');
                                            }
                                            return response.json();
                                        })
                                        .then(data => {
                                            alert('Item added to cart successfully!');
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            alert('Item added to cart successfully!');
                                            window.location
                                                .reload(); // Reload the page after the alert in the catch block

                                        });
                                };


                            // Show the modal
                            const menuDetailsModal = new bootstrap.Modal(document
                                .getElementById('menuDetailsModal'));
                            menuDetailsModal.show();
                        })
                        .catch(error => {
                            console.error('Error fetching menu details:', error);
                            alert('Failed to fetch menu details. Please try again.');
                        });
                });
            });
        });

        // Modal-specific increment function
        function modalIncrementQuantity(button) {
            let input = document.getElementById('modalQuantityInput');
            input.value = parseInt(input.value) + 1;

            // Update the hidden input field for quantity
            document.getElementById('modalHiddenQuantity').value = input.value;
        }

        // Modal-specific decrement function
        function modalDecrementQuantity(button) {
            let input = document.getElementById('modalQuantityInput');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;

                // Update the hidden input field for quantity
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


    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.js') }}"></script>

</body>

</html>
