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
                            <label for="" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="" aria-describedby="">
                        </div>


                        <div class="d-flex gap-4 mb-3">
                            {{-- Email --}}
                            <div class="w-50">
                                <label for="exampleInputEmail1" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="exampleInputEmail1"
                                    aria-describedby="emailHelp">
                            </div>

                            {{-- Number --}}
                            <div class="w-50">
                                <label for="" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="" aria-describedby="">
                            </div>
                        </div>

                        {{-- Full Address --}}
                        <div class="mb-3">
                            <label for="" class="form-label">Full Address</label>
                            <input type="text" class="form-control" id="" aria-describedby="">
                        </div>

                        {{-- Shipping Information --}}
                        <div class="mb-3 h3">Shipping Information</div>
                        <div class="form-check form-control p-2 ps-5 mb-3">
                            <input class="form-check-input" type="radio" name="flexRadioDefault"
                                id="flexRadioDefault2" checked>
                            <label class="form-check-label" for="flexRadioDefault2">
                                Delivery - <strong>Free shipping</strong>
                            </label>
                        </div>

                        {{-- Payment Method --}}
                        <div class="mb-3 h3">Payment Method</div>
                        <div class="form-check form-control p-2 ps-5 mb-3">
                            <input class="form-check-input" type="radio" name="flexRadioDefault"
                                id="flexRadioDefault2" checked>
                            <label class="form-check-label" for="flexRadioDefault2">
                                Delivery - <strong>Free shipping</strong>
                            </label>
                        </div>

                        <div class="">
                            <label for="" class="form-label">Note</label>
                            <textarea class="form-control" placeholder="Leave a note here..." id="" style="height: 100px"></textarea>
                        </div>

<button type="submit">Order now</button>
                    </form>
                </div>

            </div>

            {{-- Right --}}
            <div class="right d-flex flex-column py-5 ps-5">

                <div class="products border-bottom pb-4 mb-4">
                    <div class="mb-3">Product(s):</div>
                    <div class="d-flex gap-3 justify-content-between">
                        <div class="picture border border-3">
                            <img src="{{ asset('images/logo.jpg') }}" class="image-fluid" width="70" height=""
                                alt="Picture">
                        </div>
                        <div class="menu-name d-flex flex-column">
                            <div class="name">Smart Home Speaker</div>
                            <div class="size">(Color: Black, Size: S)</div>
                        </div>
                        <div class="price">430</div>
                    </div>
                </div>

                <div class="cart-totals d-flex flex-column border-bottom pb-4 gap-3">
                    <div class="d-flex justify-content-between">
                        <div class="">Subtotal:</div>
                        <div class="fw-bold">862</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="">Subtotal:</div>
                        <div class="fw-bold">862</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="">Subtotal:</div>
                        <div class="fw-bold">862</div>
                    </div>
                    <div class="d-flex justify-content-between fw-bold">
                        <div class="">Total:</div>
                        <div class="fs-4">862</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- <div class="container">
        <h2>Shipping Information</h2>
        <div class="row">
            <!-- Left side: Shipping Information -->
            <div class="col-left">
                <form>
                    <div class="form-group">
                        <label for="fullName">Full Name</label>
                        <input type="text" id="fullName" placeholder="Full Name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" placeholder="Phone">
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <select id="country">
                            <option selected>Select country...</option>
                            <option>Country 1</option>
                            <option>Country 2</option>
                            <option>Country 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" id="state" placeholder="State">
                    </div>
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" placeholder="City">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" placeholder="Address">
                    </div>

                    <h4>Shipping Method</h4>
                    <div class="form-check">
                        <input type="radio" id="freeShipping" name="shippingMethod" checked>
                        <label for="freeShipping">Delivery - Free Shipping</label>
                    </div>

                    <h4>Payment Method</h4>
                    <div class="form-check">
                        <input type="radio" id="cod" name="paymentMethod" checked>
                        <label for="cod">Cash on Delivery (COD)</label>
                    </div>
                    <small>Please pay money directly to the postman if you choose Cash on Delivery.</small>

                    <div class="form-group">
                        <label for="note">Note</label>
                        <textarea id="note" rows="3" placeholder="Note..."></textarea>
                    </div>

                    <button type="submit" class="btn-primary">Checkout</button>
                    <a href="/cart" class="btn-link">Back to cart</a>
                </form>
            </div>

            <!-- Right side: Order Summary -->
            <div class="col-right">
                <h4>Order Summary</h4>
                <div class="order-summary">
                    <div>
                        <span>Product(s):</span>
                        <span>Smart Home Speaker (x2)</span>
                    </div>
                    <hr>
                    <div>
                        <span>Subtotal:</span>
                        <span>$862.00</span>
                    </div>
                    <div>
                        <span>Shipping fee:</span>
                        <span>$0.00</span>
                    </div>
                    <div>
                        <span>Tax:</span>
                        <span>$86.20</span>
                    </div>
                    <hr>
                    <div class="total">
                        <span>Total:</span>
                        <span>$948.20</span>
                    </div>
                </div>
                <a href="#" class="coupon-link">You have a coupon code?</a>
            </div>
        </div>
    </div> --}}

</body>

</html>
