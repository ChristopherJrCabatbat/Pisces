<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-icon.png') }}">

    <link rel="stylesheet" href="{{ asset('css/order-styles.css') }}">

</head>

<body>
    <div class="container">
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
    </div>
</body>

</html>
