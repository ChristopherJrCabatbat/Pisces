<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-icon.png') }}">

    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/order-styles.css') }}">

    <script src="https://kit.fontawesome.com/f416851b63.js" crossorigin="anonymous"></script>

</head>

<body>

    <div class="container">
        <div class="d-flex">
            {{-- Combined Form --}}
            <form action="{{ route('user.orderStore') }}" method="POST" class="d-flex w-100">
                @csrf

                {{-- Left Section --}}
                <div class="left d-flex flex-column py-5 pe-5 border-end">
                    <div class="logo border-bottom pb-4 mb-4">
                        <img src="{{ asset('images/logo-name.png') }}" width="148" alt="Pisces logo">
                    </div>

                    <div class="form-container">
                        <div class="mb-3 h3">Shipping Information</div>

                        <!-- Full Name -->
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="fullName" name="fullName"
                                value="{{ old('fullName', $user->first_name . ' ' . $user->last_name) }}" required>
                        </div>

                        <div class="d-flex gap-4 mb-3">
                            <!-- Email -->
                            <div class="w-50">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>

                            <!-- Contact Number -->
                            <div class="w-50">
                                <label for="contactNumber" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="contactNumber" name="contactNumber"
                                    value="{{ old('contactNumber', $user->contact_number) }}" required>
                            </div>
                        </div>

                        <!-- Full Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Full Address</label>
                            <input type="text" class="form-control" id="address" name="address" autofocus
                                placeholder="#123 Barangay ABC SCCP" value="{{ old('address') }}" required>
                        </div>

                        <!-- Shipping Method -->
                        <label for="shippingMethod" class="form-label">Shipping Method</label>
                        <div class="form-check form-control p-2 ps-5 mb-3">
                            <input class="form-check-input" type="radio" name="shippingMethod" id="freeShipping"
                                value="Free Shipping"
                                {{ old('shippingMethod', 'Free Shipping') == 'Free Shipping' ? 'checked' : '' }}>
                            <label class="form-check-label" for="freeShipping">
                                Delivery - <strong>Free shipping</strong>
                            </label>
                        </div>

                        <!-- Payment Method -->
                        <label for="paymentMethod" class="form-label">Mode of Payment</label>
                        <div class="form-check form-control p-2 ps-5 mb-1">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="cod"
                                value="COD" {{ old('paymentMethod', 'COD') == 'COD' ? 'checked' : '' }}>
                            <label class="form-check-label" for="cod">Cash on Delivery (COD)</label>
                        </div>
                        <div class="form-check form-control p-2 ps-5 mb-3">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="gcash"
                                value="GCash" {{ old('paymentMethod') == 'GCash' ? 'checked' : '' }}>
                            <label class="form-check-label" for="gcash">GCash</label>
                        </div>

                        <!-- Note -->
                        <div class="mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea class="form-control" id="note" name="note" style="height: 100px" placeholder="Leave a note here...">{{ old('note') }}</textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('user.menu') }}" class="btn btn-outline-info back-cart">
                                <i class="fa-solid fa-arrow-left-long me-2"></i>Back To Menu
                            </a>
                            <button class="btn btn-danger order" type="submit">
                                Order now <i class="fa-solid fa-cart-shopping ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Right Section (Product Summary) --}}
                <div class="right d-flex flex-column py-5 ps-5">
                    <div class="products border-bottom pb-4 mb-4">
                        <div class="mb-3">Menu:</div>

                        @php
                            $quantity = old('quantity', request()->input('quantity', 1)); // Fetch quantity from request
                            $itemTotal = $menu->price * $quantity;
                        @endphp

                        <div class="d-flex gap-3 justify-content-between align-items-center">
                            <div class="picture border border-3">
                                <img src="{{ asset('storage/' . $menu->image) }}" class="img-fluid" width="70"
                                    alt="Picture">
                            </div>
                            <div class="menu-name d-flex flex-column align-items-center">
                                <div class="name">{{ $menu->name }}</div>
                                <div class="size">
                                    ({{ $quantity }})
                                </div>
                            </div>
                            <div class="price fw-bold">
                                ₱{{ number_format($itemTotal, 2) }}
                            </div>

                            <!-- Hidden Inputs for Order Data -->
                            <input type="hidden" name="menu_names[]" value="{{ $menu->name }}">
                            <input type="hidden" name="quantities[]" value="{{ $quantity }}">
                        </div>

                    </div>

                    <div class="cart-totals d-flex flex-column border-bottom pb-4 gap-3">
                        <div class="d-flex justify-content-between fw-bold align-items-center">
                            <div>Total:</div>
                            <div class="fs-4">₱{{ number_format($itemTotal, 2) }}</div>
                        </div>
                    </div>
                </div>
            </form>

        </div>


    </div>
    </div>

    <script src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.js') }}"></script>

</body>

</html>
