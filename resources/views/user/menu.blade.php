@extends('user.layout')

@section('title', 'Menu')

@section('styles-links')
    <style>
        .main-content {
            margin-top: 13vh;
        }

        select {
            width: 30% !important;
        }

        /* Modal styles */
        .product-page {
            display: flex;
            gap: 20px;
            padding: 20px;
        }

        .product-images {
            flex: 1;
        }

        .main-image {
            /* width: 100%; */
            max-width: 350px;
        }

        .thumbnails img {
            width: 50px;
            margin-right: 5px;
        }

        .product-details {
            flex: 2;
        }

        h1 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .ratings {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            color: #f39c12;
        }

        .pricing {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.2rem;
        }

        .discounted-price {
            color: red;
            font-weight: bold;
        }

        .original-price {
            text-decoration: line-through;
            color: #999;
        }

        .discount {
            color: green;
        }

        .return-shipping {
            margin: 10px 0;
        }

        .brand-options button {
            margin: 5px;
            padding: 5px 10px;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 5px;
            margin: 10px 0;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .extra-info {
            margin-top: 10px;
            display: flex;
            gap: 20px;
        }
    </style>
@endsection

@section('modals')
    <!-- Product Details Modal -->
    <div class="modal fade" id="menuDetailsModal" tabindex="-1" aria-labelledby="menuDetailsModalLabel" aria-hidden="true">
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
                                {{-- <form action="{{ route('user.addToCart', $menu->id) }}" method="POST" --}}
                                {{-- <form action="{{ route('user.addToCartModal', $menuId ?? '') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="quantity" id="modalHiddenQuantity" value="1">
                                    <button type="submit" data-id="{{ $menuId ?? '' }}"
                                        class="btn btn-danger modal-button">Add To Cart</button>
                                </form> --}}

                                <button type="button" class="btn btn-danger modal-button add-to-cart">Add To Cart</button>
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
@endsection

@section('topbar')
    <li class="nav-item">
        <a class="nav-link fw-bold" aria-current="page" href="{{ route('user.dashboard') }}">HOME</a>
    </li>
    <li class="nav-item">
        <a class="nav-link fw-bold active" aria-current="page" href="{{ route('user.menu') }}">MENU</a>
    </li>
    <li class="nav-item">
        <a class="nav-link fw-bold" aria-current="page" href="{{ route('user.orders') }}">ORDERS</a>
    </li>
    <li class="nav-item">
        <a class="nav-link fw-bold" aria-current="page" href="{{ route('user.messages') }}">MESSAGES</a>
    </li>
@endsection

@section('main-content')
    <div class="container main-content d-flex flex-column align-items-center mb-5">

        {{-- Top Container --}}
        <div class="top-container d-flex w-100 p-4 mb-5 justify-content-between align-items-center">
            <div class="fw-bold h1">
                {{ $selectedCategory }}
            </div>
            <div class="menu-chosen d-flex justify-content-center align-items-center gap-2 fs-5">
                <div>Menu <i class="fa-solid fa-caret-right mx-1"></i></div>
                <div class="low-opacity-white">{{ $selectedCategory }}</div>
            </div>
        </div>

        {{-- Content --}}
        <div class="d-flex container content user-content gap-5 p-0">

            {{-- Categories --}}
            <div class="categories d-flex flex-column">
                <div class="h3 mb-4">Categories</div>
                <div class="category-lists d-flex flex-column gap-2">
                    <!-- All Menus -->
                    <div>
                        <div>
                            <i class="fa-solid fa-caret-right me-2"></i>
                            <a href="{{ route('user.menu', ['category' => 'All Menus']) }}" class="white-underline">
                                <span class="{{ $selectedCategory == 'All Menus' ? 'active-category' : '' }}">All
                                    Menus</span>
                            </a>
                        </div>
                    </div>

                    <!-- Sorted Categories by Menu Count -->
                    @foreach ($categories as $category)
                        <div class="d-flex justify-content-between">
                            <div>
                                <i class="fa-solid fa-caret-right me-2"></i>
                                <a href="{{ route('user.menu', ['category' => $category->category]) }}"
                                    class="white-underline">
                                    <span
                                        class="{{ $selectedCategory == $category->category ? 'active-category' : '' }}">{{ $category->category }}</span>
                                </a>
                            </div>
                            <div>({{ $category->menu_count }})</div>
                        </div>
                    @endforeach
                </div>
            </div>


            {{-- Menus --}}
            <div class="menus d-flex flex-column gap-4 mb-5 w-100">

                {{-- Select --}}
                <div class="top-menus">
                    <select class="form-select" aria-label="Default select example">
                        <option selected value="Default">Default</option>
                        <option value="Expensive">Expensive</option>
                        <option value="Cheap">Cheap</option>
                    </select>
                </div>

                {{-- Menu --}}
                <div class="menu-list">
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        @forelse($menus as $menu)
                            <div class="col">
                                <div class="card card-hover h-100 position-relative">
                                    <!-- Unique Placeholder for success message, positioned within each card -->
                                    <div id="copyMessage-{{ $menu->id }}" class="copy-message"></div>

                                    {{-- Menu Image --}}
                                    <div class="img-container">
                                        @if ($menu->image)
                                            <img src="{{ asset('storage/' . $menu->image) }}" class="card-img-top darken"
                                                alt="{{ $menu->name }}">
                                        @else
                                            <img src="{{ asset('images/logo.jpg') }}" class="card-img-top darken"
                                                alt="No Image">
                                        @endif

                                        <div class="icon-overlay text-white">
                                            {{-- Add to Cart --}}
                                            <form action="{{ route('user.addToCart', $menu->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="icon-buttons"><i
                                                        class="fa-solid fa-cart-plus text-white"
                                                        title="Add to Cart"></i></button>
                                            </form>

                                            {{-- Share Menu --}}
                                            <form action="" method="GET">
                                                @csrf
                                                <button type="button" class="icon-buttons">
                                                    <!-- Share Button -->
                                                    <i class="fa-solid fa-share" title="Share Menu"
                                                        onclick="copyMenuLink({{ $menu->id }})"></i>
                                                </button>
                                            </form>

                                            {{-- View Menu --}}
                                            <form action="" method="GET">
                                                @csrf
                                                <button type="button" class="icon-buttons"><i
                                                        class="fa-solid fa-search view-menu-btn" title="View Menu"
                                                        data-id="{{ $menu->id }}"></i></button>
                                            </form>

                                            {{-- Add to Favorites --}}
                                            <form action="{{ route('user.addToFavorites', $menu->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="icon-buttons">
                                                    <i class="fa-solid fa-heart"
                                                        style="color: {{ $user->favoriteItems->contains($menu->id) ? '#f81d0b' : 'white' }};"
                                                        title="{{ $user->favoriteItems->contains($menu->id) ? 'Remove from Favorites' : 'Add to Favorites' }}">
                                                    </i>
                                                </button>
                                            </form>

                                        </div>
                                    </div>

                                    {{-- Menu Body --}}
                                    <a href="{{ route('user.menuDetails', $menu->id) }}" data-id="{{ $menu->id }}"
                                        class="menu-body">
                                        <div class="card-body card-body-mt">
                                            <h5 class="card-title">{{ $menu->name }}</h5>
                                            <div class="price fw-bold mb-2">
                                                @if (floor($menu->price) == $menu->price)
                                                    ₱{{ number_format($menu->price, 0) }}
                                                @else
                                                    ₱{{ number_format($menu->price, 2) }}
                                                @endif
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="stars d-flex">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                </div>
                                                <div>(2)</div>
                                            </div>
                                        </div>
                                    </a>

                                </div>
                            </div>
                        @empty
                            <div class="col">
                                <p>No menus available.</p>
                            </div>
                        @endforelse
                    </div>
                </div>


            </div>

        </div>

    </div>
@endsection

@section('scripts')
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

                            // // Set button destination for "Add to Cart"
                            // document.querySelector('.modal-button.add-to-cart').onclick =
                            //     function() {
                            //         window.location.href =
                            //             `/user/addToCart/${menuId}`;
                            //     };

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
                                            alert('Error adding item to cart.');
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
                    // Check if the correct element is targeted
                    console.log("Copy successful, targeting message element for menu ID:", menuId);
                    const messageElement = document.getElementById(`copyMessage-${menuId}`);
                    if (messageElement) {
                        messageElement.textContent = "Menu link copied successfully";
                        messageElement.style.display = 'block'; // Make it visible
                        console.log("Message displayed for menu ID:", menuId);

                        // Hide the message after 3 seconds
                        setTimeout(() => {
                            messageElement.style.display = 'none';
                            console.log("Message hidden for menu ID:", menuId);
                        }, 2000);
                    } else {
                        console.error("Message element not found for menu ID:", menuId);
                    }
                })
                .catch(err => {
                    console.error('Failed to copy the text: ', err);
                });
        }
    </script>
@endsection
