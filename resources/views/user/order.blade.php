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

            {{-- Left --}}
            <div class="left d-flex flex-column py-5 pe-5 border-end">
                <div class="logo border-bottom pb-4 mb-4">
                    <img src="{{ asset('images/logo-name.png') }}" width="148" height="" alt="Pisces logo">
                </div>
                <div class="form-container">
                    <form action="">
                        <div class="mb-3 h3">Shipping Information</div>

                        {{-- Full Name --}}
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="fullName" name="fullName"
                                value="{{ $user->first_name . ' ' . $user->last_name }}" required>
                        </div>

                        <div class="d-flex gap-4 mb-3">
                            {{-- Email --}}
                            <div class="w-50">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ $user->email }}" required>
                            </div>

                            {{-- Contact Number --}}
                            <div class="w-50">
                                <label for="contactNumber" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="contactNumber" name="contactNumber"
                                    value="{{ $user->contact_number }}" required>
                            </div>
                        </div>

                        {{-- Full Address --}}
                        <div class="mb-3">
                            <label for="address" class="form-label">Full Address</label>
                            <input type="text" class="form-control" id="address" name="address" required autofocus>
                        </div>

                        {{-- Shipping Method --}}
                        <label for="shippingMethod" class="form-label">Shipping Method</label>
                        <div class="form-check form-control p-2 ps-5 mb-3">
                            <input class="form-check-input" type="radio" name="shippingMethod" id="freeShipping"
                                checked>
                            <label class="form-check-label" for="freeShipping">
                                Delivery - <strong>Free shipping</strong>
                            </label>
                        </div>

                        {{-- Payment Method --}}
                        <label for="paymentMethod" class="form-label">Mode of Payment</label>
                        <div class="form-check form-control p-2 ps-5 mb-1">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="cod" checked>
                            <label class="form-check-label" for="cod">
                                Cash on Delivery (COD)
                            </label>
                        </div>
                        <div class="form-check form-control p-2 ps-5 mb-3">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="gcash">
                            <label class="form-check-label" for="gcash">
                                GCash
                            </label>
                        </div>

                        {{-- Note --}}
                        <div class="mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea class="form-control" placeholder="Leave a note here..." id="note" name="note" style="height: 100px"></textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div><a href="{{ route('user.shoppingCart') }}" class="btn btn-outline-info back-cart">
                                    <i class="fa-solid fa-arrow-left-long me-2"></i>Back To Cart</a></div>
                            <div><button class="btn btn-danger order" type="submit">
                                    Order now <i class="fa-solid fa-cart-shopping ms-1"></i></button></div>
                        </div>

                    </form>
                </div>


            </div>

            {{-- Right --}}
            <div class="right d-flex flex-column py-5 ps-5">

                {{-- Products --}}
                <div class="products border-bottom pb-4 mb-4">
                    <div class="mb-3">Menu(s):</div>

                    @php
                        $totalPrice = 0;
                    @endphp

                    @foreach ($menus as $menu)
                        @php
                            $quantity = $menu->pivot->quantity ?? 1; // Get quantity from pivot
                            $itemTotal = $menu->price * $quantity; // Calculate item total
                            $totalPrice += $itemTotal; // Add to total
                        @endphp

                        <div class="d-flex gap-3 justify-content-between align-items-center">
                            <div class="picture border border-3">
                                <img src="{{ asset('storage/' . $menu->image) }}" class="img-fluid" width="70"
                                    alt="Picture">
                            </div>
                            <div class="menu-name d-flex flex-column align-items-center">
                                <div class="name">{{ $menu->name }}</div>
                                <div class="size">
                                    @if ($quantity > 1)
                                        ({{ $quantity }})
                                        {{-- Show quantity if more than 1 --}}
                                    @endif
                                </div>
                            </div>
                            <div class="price fw-bold">
                                @if (floor($itemTotal) == $itemTotal)
                                    ₱{{ number_format($itemTotal, 0) }}
                                @else
                                    ₱{{ number_format($itemTotal, 2) }}
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Cart Totals --}}
                <div class="cart-totals d-flex flex-column border-bottom pb-4 gap-3">
                    <div class="d-flex justify-content-between fw-bold align-items-center">
                        <div>Total:</div>
                        <div class="fs-4">
                            @if (floor($totalPrice) == $totalPrice)
                                ₱{{ number_format($totalPrice, 0) }}
                            @else
                                ₱{{ number_format($totalPrice, 2) }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <script src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.js') }}"></script>

</body>

</html>
