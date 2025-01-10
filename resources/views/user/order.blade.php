<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-home.png') }}">

    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/order-styles.css') }}">

    <script src="https://kit.fontawesome.com/f416851b63.js" crossorigin="anonymous"></script>

</head>

<body>

    <div class="container">
        <div class="d-flex">

            {{-- Combined Form --}}
            <form action="{{ route('user.delivery.store') }}" method="POST" class="d-flex w-100">
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
                            <label for="fullName" class="form-label">Full Name:</label>
                            <input type="text" class="form-control" id="fullName" name="fullName"
                                value="{{ $user->first_name . ' ' . $user->last_name }}" required>
                        </div>

                        <div class="d-flex gap-4 mb-3">
                            <!-- Email -->
                            <div class="w-50">
                                <label for="email" class="form-label">Email Address:</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ $user->email }}" required>
                            </div>

                            <!-- Contact Number -->
                            <div class="w-50">
                                <label for="contactNumber" class="form-label">Contact Number:</label>
                                <input type="text" class="form-control" id="contactNumber" name="contactNumber"
                                    value="{{ $user->contact_number }}" required>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="d-flex gap-4 mb-3">
                            <!-- House Number -->
                            <div class="w-50">
                                <label for="house_number" class="form-label">House Num. (optional):</label>
                                <input type="text" class="form-control" id="house_number" name="house_number"
                                    placeholder="123">
                            </div>

                            <!-- Purok -->
                            <div class="w-50">
                                <label for="purok" class="form-label">Purok (optional):</label>
                                <input type="number" class="form-control" id="purok" name="purok" min="0"
                                    max="20" placeholder="1">
                            </div>
                        </div>

                        <!-- Barangay -->
                        <div class="w-100 mb-3">
                            <label for="barangay" class="form-label">Barangay:</label>
                            <select class="form-control" id="barangay" name="barangay" required>
                                <option value="">Select Barangay</option>
                            </select>
                        </div>

                        <!-- Shipping Fee -->
                        <div class="mb-3">
                            <label for="shippingFee" class="form-label">Shipping Fee:</label>
                            <input type="text" class="form-control" id="shippingMethod" name="shippingFee"
                                value="0" readonly required>
                        </div>

                        <!-- Payment Method -->
                        <label for="paymentMethod" class="form-label">Mode of Payment:</label>
                        <div class="form-check form-control p-2 ps-5 mb-1">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="cod"
                                value="COD" checked>
                            <label class="form-check-label" for="cod">Cash on Delivery (COD)</label>
                        </div>
                        <div class="form-check form-control p-2 ps-5 mb-3">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="gcash"
                                value="GCash">
                            <label class="form-check-label" for="gcash">GCash</label>
                        </div>

                        <!-- Note -->
                        <div class="mb-3">
                            <label for="note" class="form-label">Note:</label>
                            <textarea class="form-control" id="note" name="note" style="height: 100px"
                            placeholder="Your area's landmark or note about your order..." required></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('user.shoppingCart') }}" class="btn btn-outline-info back-cart">
                                <i class="fa-solid fa-arrow-left-long me-2"></i>Back To Cart
                            </a>
                            <button class="btn btn-danger order" type="submit">
                                Order now <i class="fa-solid fa-cart-shopping ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Right Section (Products Summary) --}}
                <div class="right d-flex flex-column py-5 ps-5">
                    <div class="products border-bottom pb-4 mb-4">
                        <div class="mb-3">Menu(s):</div>

                        @php
                            $totalPrice = 0;
                        @endphp

                        <div class="d-flex flex-column gap-3">
                            @foreach ($menus as $menu)
                                @php
                                    $quantity = $menu->pivot->quantity ?? 1; // Fetch quantity from pivot
                                    $itemTotal = $menu->discounted_price * $quantity; // Use discounted price
                                    $totalPrice += $itemTotal; // Update total price
                                @endphp

                                <div class="d-flex gap-3 justify-content-between align-items-center">
                                    <div class="picture border border-1">
                                        <img src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('images/default.jpg') }}"
                                            class="img-fluid" width="70" alt="Picture">
                                    </div>
                                    <div class="menu-name d-flex flex-column align-items-center">
                                        @if ($quantity > 1)
                                            <div class="name">{{ $menu->name }} (₱{{ $menu->discounted_price }})
                                            </div>
                                            <div class="size">({{ $quantity }})</div>
                                        @else
                                            <div class="name">{{ $menu->name }}</div>
                                            <div class="size">({{ $quantity }})</div>
                                        @endif
                                    </div>
                                    <div class="price fw-bold">
                                        ₱{{ number_format($itemTotal, 2) }}
                                    </div>

                                    <!-- Hidden Inputs for Order Data -->
                                    <input type="hidden" name="menu_names[]" value="{{ $menu->name }}">
                                    <input type="hidden" name="quantities[]" value="{{ $quantity }}">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Total Price Section --}}
                    @php
                        $hasDiscount = $user->has_discount; // Check user eligibility for discount
                        $discountAmount = $hasDiscount ? $totalPrice * 0.05 : 0; // Calculate user discount
                        $finalTotal = $totalPrice - $discountAmount; // Calculate final total
                    @endphp

                    <div class="cart-totals d-flex flex-column border-bottom pb-4 gap-3">
                        @if ($hasDiscount)
                            <!-- Original Total -->
                            <div class="d-flex justify-content-between fw-bold align-items-center">
                                <div>Original Total:</div>
                                <div class="fs-4">₱{{ number_format($totalPrice, 2) }}</div>
                            </div>

                            <!-- Discount -->
                            <div class="d-flex justify-content-between fw-bold align-items-center text-success">
                                <div>Discount (5%):</div>
                                <div class="fs-4">₱{{ number_format($discountAmount, 2) }}</div>
                            </div>
                        @endif

                        <!-- Final Total -->
                        <div class="d-flex justify-content-between fw-bold align-items-center">
                            <div>Total:</div>
                            <div class="fs-4">₱{{ round($finalTotal) }}</div>
                        </div>
                        <input type="hidden" name="total_price" value="{{ round($finalTotal) }}">
                    </div>
                </div>

            </form>

        </div>


    </div>
    </div>

    <script src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.js') }}"></script>

    {{-- Barangay auto shipping fee --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const barangayDropdown = document.getElementById('barangay');
            const shippingInput = document.getElementById('shippingMethod');

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

            // Sort barangays alphabetically
            const sortedBarangays = Object.keys(barangayRates).sort();

            // Populate barangays in dropdown
            sortedBarangays.forEach(barangay => {
                const option = document.createElement('option');
                option.value = barangay;
                option.text = barangay;
                barangayDropdown.appendChild(option);
            });

            // Update shipping fee when a barangay is selected
            barangayDropdown.addEventListener('change', function() {
                const selectedBarangay = barangayDropdown.value;
                if (barangayRates[selectedBarangay]) {
                    shippingInput.value = `${barangayRates[selectedBarangay]}`;
                } else {
                    shippingInput.value = "0";
                }
            });
        });
    </script>

</body>

</html>
