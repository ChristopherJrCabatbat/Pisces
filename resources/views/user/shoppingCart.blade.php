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
            color:  #007bff;
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
@endsection

@section('main-content')
    <div class="container main-content d-flex flex-column align-items-center">

        {{-- Top Container --}}
        <div class="top-container d-flex w-100 p-4 mb-5 justify-content-between align-items-center">
            <div class="fw-bold h1">
                Shopping Cart
            </div>
            <div class="menu-chosen d-flex justify-content-center align-items-center gap-2 fs-5">
                <div><a href="{{ route('user.menu') }}" class="white-underline">Menu</a> <i class="fa-solid fa-caret-right mx-1"></i></div>
                <div class="low-opacity-white">Shopping Cart</div>
            </div>
        </div>

        {{-- Content --}}
        <div class="d-flex container p-0">

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
                <tbody id="menu-table-body">
                    @forelse ($menus as $menu)
                        <tr class="menu-row">
                            <!-- Image Column -->
                            <td>
                                @if ($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}"
                                        class="img-fluid" width="50">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                            <!-- Name, Category, Description -->
                            <td>{{ $menu->name }}</td>
                            <td>{{ $menu->category }}</td>
                            <!-- Price (Remove trailing .00 if present) -->
                            <td>
                                @if (floor($menu->price) == $menu->price)
                                    {{ number_format($menu->price, 0) }} <!-- Show without decimals -->
                                @else
                                    {{ number_format($menu->price, 2) }} <!-- Show with decimals -->
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- <form action="{{ route('user.updateQuantity', $menu->cart_item_id) }}" method="POST"> --}}
                                <form action="" method="POST">
                                    {{-- @csrf
                                    @method('PUT') --}}
                                    <div class="d-flex align-items-center justify-content-center">
                                        <button type="button" class="btn qty-btn rounded-circle" onclick="decrementQuantity(this)"><i class="fa fa-minus"></i></button>
                                        <input type="text" name="quantity" value="{{ $menu->pivot->quantity ?? 1 }}" min="1" class="form-control text-center mx-2 quantity-input" style="width: 60px;">
                                        <button type="button" class="btn qty-btn rounded-circle" onclick="incrementQuantity(this)"><i class="fa fa-plus"></i></button>
                                    </div>
                                </form>
                            </td>
                            
                            <td>
                                <form action="{{ route('user.removeCart',  $menu->cart_item_id) }}" method="POST"
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

            <div></div>

        </div>

    </div>
@endsection

@section('scripts')
@endsection
