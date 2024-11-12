@extends('user.layout')

@section('title', 'Menu')

@section('styles-links')
    <style>
        .main-content {
            margin-top: 9vh;
            /* padding: 20px; */
        }

        /* Product Page Styling */
        .product-page {
            display: flex;
            gap: 30px;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            /* max-width: 900px; */
            width: 100%;
            margin: auto;
            color: black;
        }

        .product-images {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .main-image {
            max-width: 100%;
            /* width: 350px; */
            border-radius: 8px;
            object-fit: cover;
        }

        .thumbnails {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        .thumbnails img {
            width: 50px;
            cursor: pointer;
            border-radius: 4px;
            transition: transform 0.2s;
        }

        .thumbnails img:hover {
            transform: scale(1.1);
        }

        .product-details {
            flex: 1;
            padding: 0 20px;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #333;
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
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .discounted-price {
            color: #e74c3c;
            font-weight: bold;
        }

        .original-price {
            text-decoration: line-through;
            color: #999;
        }

        .discount {
            color: #27ae60;
            font-size: 1rem;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 15px 0;
        }

        .quantity-input {
            width: 60px;
        }

        .action-buttons {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .btn-danger {
            padding: 10px 20px;
            font-size: 1em;
            font-weight: bold;
            border-radius: 5px;
        }

        .extra-info {
            margin-top: 20px;
            display: flex;
            gap: 20px;
            color: #666;
            font-size: 0.9rem;
        }
    </style>
@endsection

@section('modals')
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

        <div class="container main-content d-flex flex-column align-items-center">
            <!-- Product Details Section -->
            <div class="product-page">
                <!-- Product Images -->
                <div class="product-images">
                    <img src="{{ asset('storage/' . $menus->image) }}" id="menuImage" class="main-image" alt="Picture">
                    {{-- <div class="thumbnails">
                    <!-- Additional thumbnail images -->
                    <img src="{{ asset('storage/' . $menus->image) }}" id="menuImage" class="main-image"
                        alt="Picture">
                    <img src="{{ asset('storage/' . $menus->image) }}" id="menuImage" class="main-image"
                        alt="Picture">
                    <img src="{{ asset('storage/' . $menus->image) }}" id="menuImage" class="main-image"
                        alt="Picture">
                </div> --}}
                </div>


                <!-- Product Details -->
                <div class="product-details">
                    <h1 id="menuName">{{ $menus->name }}</h1>
                    <div class="ratings">
                        <span id="menuRating">⭐ 4.2</span>
                        <span id="ratingCount">(4K Ratings)</span>
                    </div>

                    <!-- Pricing Section -->
                    <div class="pricing">
                        <span id="discountedPrice" class="discounted-price">₱{{ $menus->price }}</span>
                        {{-- <span id="originalPrice" class="original-price">₱1000.00</span>
                        <span id="discountPercentage" class="discount">20% OFF</span> --}}
                    </div>

                    <!-- Category and Description -->
                    <p><strong>Category:</strong> <span id="menuCategory">{{ $menus->category }}</span></p>
                    <p><strong>Description:</strong> <span id="menuDescription">{{ $menus->description }}</span>
                    </p>

                    <!-- Quantity Selector -->
                    <div class="quantity-selector">
                        <button type="button" class="btn qty-btn rounded-circle" onclick="modalDecrementQuantity(this)">
                            <i class="fa fa-minus"></i>
                        </button>

                        <input type="text" readonly name="display_quantity" value="1" min="1"
                            class="form-control text-center mx-2 quantity-input" id="modalQuantityInput">
                        <input type="hidden" name="quantity" id="modalHiddenQuantity" value="1">

                        <button type="button" class="btn qty-btn rounded-circle" onclick="modalIncrementQuantity(this)">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button class="btn btn-danger add-to-cart">Add To Cart</button>
                        <form action="{{ route('user.menuDetailsOrder', $menus->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-danger order-now">Order Now</button>
                        </form>
                    </div>

                    <!-- Additional Info -->
                    <div class="extra-info">
                        <span>❤️ 1K Favorites</span>
                        <span>✔️ Free Shipping</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
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


@endsection
