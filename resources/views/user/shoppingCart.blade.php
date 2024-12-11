@extends('user.layout')

@section('title', 'Shopping Cart')

@section('styles-links')
    <style>
        .main-content {
            margin-top: 13vh;
        }

        select {
            width: 30% !important;
        }

        #cart-icon {
            color: #007bff;
        }
    </style>
@endsection

@section('topbar')
    <li class="nav-item">
        <a class="nav-link fw-bold" aria-current="page" href="{{ route('user.dashboard') }}">HOME</a>
    </li>
    <li class="nav-item">
        <a class="nav-link fw-bold" aria-current="page" href="{{ route('user.menu') }}">MENU</a>
    </li>
     <li class="nav-item position-relative">
        <a class="nav-link fw-bold" aria-current="page" href="{{ route('user.orders') }}">
            ORDERS
            @if ($pendingOrdersCount > 0)
                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle-y-custom">
                    {{ $pendingOrdersCount }}
                </span>
            @endif
        </a>
    </li>
     <li class="nav-item position-relative">
        <a class="nav-link fw-bold" aria-current="page" href="{{ route('user.messages') }}">MESSAGES
            @if ($unreadCount > 0)
                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle-y-custom">
                    {{ $unreadCount }}
                </span>
            @endif
        </a>
    </li>
@endsection

@section('main-content')
    <div class="container main-content d-flex flex-column align-items-center">

        {{-- Top Container --}}
        <div class="top-container d-flex w-100 p-4 mb-5 justify-content-between align-items-center">
            <div class="fw-bold h1">
                Shopping Cart
            </div>
            <div class="menu-chosen d-flex justify-content-center align-items-center gap-2 fs-5">
                <div><a href="{{ route('user.menu') }}" class="white-underline">Menu</a> <i
                        class="fa-solid fa-caret-right mx-1"></i></div>
                <div class="low-opacity-white">Shopping Cart</div>
            </div>
        </div>

        {{-- Content --}}
        <div class="d-flex flex-column container p-0">

            {{-- Table --}}
            <table class="table text-center mb-5">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Image</th>
                        <th scope="col">Menu Name</th>
                        <th scope="col">Category</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>

                <!-- Update the table rows with data attributes for menu-id and price -->
                {{-- <tbody id="menu-table-body">
                    @forelse ($menus as $menu)
                        <tr class="menu-row" data-menu-id="{{ $menu->id }}" data-price="{{ $menu->price }}">
                            <!-- Image Column -->
                            <td>
                                @if ($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}"
                                        class="img-fluid" width="50">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                            <td>{{ $menu->name }}</td>
                            <td>{{ $menu->category }}</td>

                            <!-- Price Column with Item Price -->
                            <td class="menu-price">
                                ₱{{ number_format($menu->price, 2) }}
                            </td>

                            <!-- Quantity Column -->
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <button type="button" class="btn qty-btn rounded-circle"
                                        onclick="decrementQuantity(this)">
                                        <i class="fa fa-minus"></i>
                                    </button>

                                    <input type="text" readonly name="quantity" value="{{ $menu->quantity ?? 1 }}"
                                        min="1" class="form-control text-center mx-2 quantity-input"
                                        style="width: 60px;" data-menu-id="{{ $menu->id }}">



                                    <!-- Add data-menu-id here -->
                                    <button type="button" class="btn qty-btn rounded-circle"
                                        onclick="incrementQuantity(this)">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </td>


                            <!-- Delete Button -->
                            <td>
                                <form action="{{ route('user.removeCart', $menu->cart_item_id) }}" method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to remove this menu?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr id="no-menus-row">
                            <td colspan="6">There are no menus added to cart.</td>
                        </tr>
                    @endforelse
                </tbody> --}}

                <tbody id="menu-table-body">
                    @php
                        // Keep track of the count of each menu item by ID
                        $menuCounts = [];
                    @endphp

                    @forelse ($menus as $menu)
                        @php
                            // Increment the count for this menu ID
                            $menuCounts[$menu->id] = ($menuCounts[$menu->id] ?? 0) + 1;

                            // Check if this is a duplicate (second or more occurrence)
                            $isDuplicate = $menuCounts[$menu->id] > 1;
                        @endphp

                        <!-- Apply red background if it is a duplicate -->
                        <tr class="menu-row {{ $isDuplicate ? 'duplicate-bg' : '' }}" data-menu-id="{{ $menu->id }}"
                            data-price="{{ $menu->price }}">

                            <!-- Image Column with Enhanced Warning Icon for Duplicates -->
                            <td class="position-relative px-3">
                                @if ($isDuplicate)
                                    <i class="fa fa-exclamation-circle duplicate-warning-icon" title="Duplicate Menu"></i>
                                @endif

                                @if ($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}"
                                        class="img-fluid" width="50">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>


                            <td>{{ $menu->name }}</td>
                            <td>{{ $menu->category }}</td>

                            <!-- Price Column with Item Price -->
                            <td class="menu-price">
                                ₱{{ number_format($menu->price, 2) }}
                            </td>

                            <!-- Quantity Column -->
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <button type="button" class="btn qty-btn rounded-circle"
                                        onclick="decrementQuantity(this)">
                                        <i class="fa fa-minus"></i>
                                    </button>

                                    <input type="text" readonly name="quantity" value="{{ $menu->quantity ?? 1 }}"
                                        min="1" class="form-control text-center mx-2 quantity-input"
                                        style="width: 60px;" data-menu-id="{{ $menu->id }}">

                                    <button type="button" class="btn qty-btn rounded-circle"
                                        onclick="incrementQuantity(this)">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </td>

                            <!-- Delete Button -->
                            <td>
                                <form action="{{ route('user.removeCart', $menu->cart_item_id) }}" method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to remove this menu?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr id="no-menus-row">
                            <td colspan="6">There are no menus added to cart.</td>
                        </tr>
                    @endforelse
                </tbody>



            </table>

            {{-- Divider with Cart Icon --}}
            <div class="cart-divider text-center mb-5">
                <hr class="line-left">
                <i class="fa fa-shopping-cart mx-3" style="font-size: 1.5em; color: gray;"></i>
                <hr class="line-right">
            </div>

            {{-- Cart Totals --}}
            <div class="cart-totals-container mb-5 p-4 text-black" style="border: 1px solid #ddd; width: 400px;">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Cart Totals</h5>

                @php
                    $totalPrice = 0;
                @endphp

                @foreach ($menus as $menu)
                    @php
                        $quantity = $menu->pivot->quantity ?? 1;
                        $itemTotal = $menu->price * $quantity;
                        $totalPrice += $itemTotal;
                    @endphp
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom cart-item-{{ $menu->id }}">
                        <span>
                            {{ $menu->name }}
                            <span class="cart-item-quantity">
                                @if ($quantity > 1)
                                    ({{ $quantity }})
                                @endif
                            </span>
                        </span>
                        <span class="cart-item-total">
                            @if (floor($itemTotal) == $itemTotal)
                                ₱{{ number_format($itemTotal, 0) }}
                            @else
                                ₱{{ number_format($itemTotal, 2) }}
                            @endif
                        </span>
                    </div>
                @endforeach

                <div class="fw-bold d-flex justify-content-between font-weight-bold border-bottom pb-2">
                    <span>Total</span>
                    <span id="total-price">
                        @if (floor($totalPrice) == $totalPrice)
                            ₱{{ number_format($totalPrice, 0) }}
                        @else
                            ₱{{ number_format($totalPrice, 2) }}
                        @endif
                    </span>
                </div>

                {{-- Check if the menus collection is empty --}}
                <form action="{{ route('user.order') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger order rounded-1 checkout-btn mt-4 px-4"
                        style="font-size: 1em;" @if ($menus->isEmpty()) disabled @endif>
                        Check Out
                    </button>
                </form>


            </div>


            {{-- Divider --}}
            <hr class="mb-5">

        </div>


    </div>
@endsection

@section('scripts')
@endsection
