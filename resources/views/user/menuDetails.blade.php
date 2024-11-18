@extends('user.layout')

@section('title', 'Menu')

@section('styles-links')
    <style>
        .main-content {
            margin-top: 15vh;
            padding: 20px;
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
        <a class="nav-link fw-bold" aria-current="page" href="{{ route('user.menu') }}">MENU</a>
    </li>
    <li class="nav-item">
        <a class="nav-link fw-bold" aria-current="page" href="{{ route('user.orders') }}">ORDERS</a>
    </li>
    <li class="nav-item">
        <a class="nav-link fw-bold" aria-current="page" href="{{ route('user.messages') }}">MESSAGES</a>
    </li>
@endsection

@section('main-content')
    <div class="container main-content d-flex flex-column align-items-start mb-5">

        <div class="current-file mb-4 d-flex">
            <div class="fw-bold"><i class="fa-solid fa-house me-2"></i><a href="{{ route('user.dashboard') }}"
                    class="navigation">Home</a> / <a href="{{ route('user.menu') }}" class="navigation">Menu</a> /</div>
            <span class="faded-white ms-1">{{ $menu->name }}</span>
        </div>


        <!-- Product Details Section -->
        <div class="product-page">
            <!-- Product Images -->
            <div class="product-images">
                <img src="{{ asset('storage/' . $menu->image) }}" id="menuImage" class="main-image" alt="Picture">
                {{-- <div class="thumbnails">
                    <!-- Additional thumbnail images -->
                    <img src="{{ asset('storage/' . $menu->image) }}" id="menuImage" class="main-image"
                        alt="Picture">
                    <img src="{{ asset('storage/' . $menu->image) }}" id="menuImage" class="main-image"
                        alt="Picture">
                    <img src="{{ asset('storage/' . $menu->image) }}" id="menuImage" class="main-image"
                        alt="Picture">
                </div> --}}
            </div>


            <!-- Product Details -->
            <div class="product-details">
                <h1 id="menuName">{{ $menu->name }}</h1>
                <div class="ratings">
                    <span id="menuRating">⭐ 4.2</span>
                    <span id="ratingCount">(4K Ratings)</span>
                </div>

                <!-- Pricing Section -->
                <div class="pricing">
                    <span id="discountedPrice" class="discounted-price">₱{{ $menu->price }}</span>
                    {{-- <span id="originalPrice" class="original-price">₱1000.00</span>
                        <span id="discountPercentage" class="discount">20% OFF</span> --}}
                </div>

                <!-- Category and Description -->
                <p><strong>Category:</strong> <span id="menuCategory">{{ $menu->category }}</span></p>
                <p><strong>Description:</strong> <span id="menuDescription">{{ $menu->description }}</span>
                </p>

                <!-- Quantity Selector -->
                <div class="quantity-selector mb-3">
                    <button type="button" class="btn qty-btn rounded-circle" onclick="detailDecrementQuantity(this)">
                        <i class="fa fa-minus"></i>
                    </button>

                    <input type="text" readonly name="display_quantity" value="1" min="1"
                        class="form-control text-center mx-2 quantity-input" style="width: 60px;" id="detailQuantityInput">

                    <!-- Hidden input to track the quantity -->
                    <input type="hidden" name="quantity" id="detailHiddenQuantity" value="1">

                    <button type="button" class="btn qty-btn rounded-circle" onclick="detailIncrementQuantity(this)">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <form action="{{ route('user.addToCart', $menu->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger modal-button add-to-cart">Add To Cart</button>
                    </form>
                    <button class="btn btn-danger modal-button order-now" onclick="redirectToOrderNow()">Order
                        Now</button>
                </div>

                <!-- Additional Info -->
                <div class="extra-info">
                    <span>❤️ 1K Favorites</span>
                    <span>✔️ Free Shipping</span>
                </div>
            </div>
        </div>
    </div>

    {{-- </div> --}}
@endsection

@section('scripts')
    <script>
        // Increment function
        function detailIncrementQuantity(button) {
            let input = document.getElementById('detailQuantityInput');
            input.value = parseInt(input.value) + 1;

            // Update the hidden input field for quantity
            document.getElementById('detailHiddenQuantity').value = input.value;
        }

        // Decrement function
        function detailDecrementQuantity(button) {
            let input = document.getElementById('detailQuantityInput');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;

                // Update the hidden input field for quantity
                document.getElementById('detailHiddenQuantity').value = input.value;
            }
        }

        // Redirect to menuDetailsOrder with quantity as a query parameter
        function redirectToOrderNow() {
            const quantity = document.getElementById('detailHiddenQuantity').value;
            const menuId = {{ $menu->id }}; // Ensure the menu ID is available here
            window.location.href = `/user/menuDetailsOrder/${menuId}?quantity=${quantity}`;
        }
    </script>
@endsection
