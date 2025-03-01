@extends('user.layout')

@section('title', 'User')

@section('styles-links')
    {{-- <link rel="stylesheet" href="{{ asset('home-assets/css/style.css') }}"> --}}

    <style>
        .card-best {
            max-width: 50vw;
        }

        .card-body {
            border-top: 1px solid #484045;
        }

        @media (max-width: 1244px) {
            .card-best {
                max-width: none;
            }
        }

        /* Margin adjustment for better spacing */
        @media (max-width: 576px) {
            .col-6 {
                margin-bottom: 1rem;
            }
        }

        @media (min-width: 990px) {
            .gap-full-screen {
                gap: 2rem;
            }
        }

        /* Adjust image hover effect */
        .rounded-circle {
            transition: transform 0.2s ease, border-color 0.2s ease;
        }

        .rounded-circle:hover {
            transform: scale(1.1);
            border-color: #e74c3c;
        }
    </style>
@endsection

@section('topbar')
    <li class="nav-item">
        <a class="nav-link fw-bold active" aria-current="page" href="{{ route('user.dashboard') }}">HOME</a>
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
    <div class="container main-content d-flex flex-column justify-content-center align-items-center">

        <!-- Top Categories -->
        <div class="text-center mb-5">
            <h2 class="">Top Categories</h2>
            <p class="w-75 mx-auto">
                Explore the top categories our customers love, featuring a variety of dishes that keep them coming back for
                more.
            </p>

            <div class="container text-center mt-4">
                <div class="row gap-full-screen justify-content-center g-4">
                    @forelse ($topCategories as $category)
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2 d-flex flex-column align-items-center">
                            <a href="{{ route('user.menu', ['category' => $category->category]) }}"
                                class="text-decoration-none">
                                <div class="position-relative" style="width: 150px; height: 150px;">
                                    <img src="{{ asset('storage/' . $category->image) }}" class="rounded-circle shadow mb-3"
                                        style="width: 100%; height: 100%; object-fit: cover; border: 3px solid #f81d0b;"
                                        alt="{{ $category->category }}">
                                </div>
                                <div class="fw-bold fs-5 text-black text-center mt-1">{{ $category->category }}</div>
                                <small class="text-center text-black" style="font-size: 0.9rem;">
                                    {{ $category->menu_count }} Orders
                                </small>
                            </a>
                        </div>
                    @empty
                        <div class="col">
                            <p>No category available.</p>
                        </div>
                    @endforelse

                </div>
            </div>

        </div>

        {{-- Best Deals For You --}}
        <div class="w-100 mb-5">
            <div class="h2 border-baba pb-3 mb-4">
                Best Deals For You
            </div>
            <div>
                <div class="row row-cols-1 row-cols-md-4 g-4">
                    @forelse ($bestDeals as $menu)
                        <div class="col">
                            <div class="card h-100 card-shadow">
                                <div class="img-container">
                                    <img src="{{ asset('storage/' . $menu->image) }}" class="card-img-top darken"
                                        alt="{{ $menu->name }}">
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
                                <a href="{{ route('user.menuDetails', $menu->id) }}" data-id="{{ $menu->id }}"
                                    class="menu-body">
                                    <div class="card-body card-body-mt">
                                        <h5 class="card-title">{{ $menu->name }}</h5>

                                        <div class="price-container d-flex align-items-center gap-3 mb-2">
                                            {{-- Display the discounted price --}}
                                            <div class="price fw-bold">
                                                ₱{{ number_format($menu->price * (1 - $menu->discount / 100), 2) }}
                                            </div>
                                            {{-- Display the original price with a strike-through line --}}
                                            <div class="price-line text-muted text-decoration-line-through">
                                                @if (floor($menu->price) == $menu->price)
                                                    ₱{{ number_format($menu->price, 0) }}
                                                @else
                                                    ₱{{ number_format($menu->price, 2) }}
                                                @endif
                                            </div>
                                            {{-- Display the discount percentage --}}
                                            <div class="off fw-bold text-success">(-{{ $menu->discount }}% OFF)</div>
                                        </div>

                                        <div class="d-flex align-items-center gap-2">
                                            <div class="stars d-flex">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($menu->rating))
                                                        <i class="fa-solid fa-star"></i>
                                                    @elseif ($i - $menu->rating < 1)
                                                        <i class="fa-solid fa-star-half-stroke"></i>
                                                    @else
                                                        <i class="fa-regular fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <div class="star-label">
                                                @if ($menu->rating > 0 && $menu->ratingCount > 0)
                                                    ({{ number_format($menu->rating, 1) }})
                                                    {{ $menu->ratingCount }} review{{ $menu->ratingCount > 1 ? 's' : '' }}
                                                @else
                                                    No Rating
                                                @endif
                                            </div>
                                        </div>
                                        

                                    </div>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col">
                            <p>No best deals available.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>


        {{-- Popular Menus --}}
        <div class="w-100 mb-5">
            <div class="h2 border-baba pb-3 mb-4">
                Popular Menus
            </div>
            <div>
                <div class="row row-cols-1 row-cols-md-4 g-4">
                    {{-- @foreach ($popularMenus as $menu) --}}
                    @forelse ($popularMenus as $menu)
                        <div class="col">
                            <div class="card h-100 card-shadow">

                                <div class="img-container">
                                    <img src="{{ asset('storage/' . $menu->image) }}" class="card-img-top darken"
                                        alt="{{ $menu->name }}">

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
                                            @if ($menu->discount > 0)
                                                {{-- Display discounted price with discount percentage --}}
                                                ₱{{ number_format($menu->price * (1 - $menu->discount / 100), 2) }}
                                                <span class="text-success">(-{{ $menu->discount }}% OFF)</span>
                                            @else
                                                {{-- Display original price --}}
                                                @if (floor($menu->price) == $menu->price)
                                                    ₱{{ number_format($menu->price, 0) }}
                                                @else
                                                    ₱{{ number_format($menu->price, 2) }}
                                                @endif
                                            @endif
                                        </div>


                                        <div class="d-flex align-items-center gap-2">
                                            <div class="stars d-flex">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($menu->rating))
                                                        <i class="fa-solid fa-star"></i>
                                                    @elseif ($i - $menu->rating < 1)
                                                        <i class="fa-solid fa-star-half-stroke"></i>
                                                    @else
                                                        <i class="fa-regular fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <div class="star-label">
                                                @if ($menu->ratingCount > 0)
                                                    ({{ number_format($menu->rating, 1) }})
                                                    {{ $menu->ratingCount }} review{{ $menu->ratingCount > 1 ? 's' : '' }}
                                                @else
                                                    No Rating
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </a>

                            </div>
                        </div>
                    @empty
                        <div class="col">
                            <p>No popular menu available.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- New Menus -->
        <div class="w-100 mb-5">
            <div class="h2 border-baba pb-3 mb-4">
                New Menus
            </div>
            <div>
                <div class="row row-cols-1 row-cols-md-4 g-4">
                    @forelse ($latestMenus as $menu)
                        <div class="col">
                            <div class="card h-100 card-shadow">

                                <!-- Unique Placeholder for success message, positioned within each card -->
                                <div id="copyMessage-new-{{ $menu->id }}" class="copy-message"></div>

                                <div class="img-container">
                                    <img src="{{ asset('storage/' . $menu->image) }}" class="card-img-top darken"
                                        alt="{{ $menu->name }}">

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
                                            @if ($menu->discount > 0)
                                                {{-- Display discounted price with discount percentage --}}
                                                ₱{{ number_format($menu->price * (1 - $menu->discount / 100), 2) }}
                                                <span class="text-success">(-{{ $menu->discount }}% OFF)</span>
                                            @else
                                                {{-- Display original price --}}
                                                @if (floor($menu->price) == $menu->price)
                                                    ₱{{ number_format($menu->price, 0) }}
                                                @else
                                                    ₱{{ number_format($menu->price, 2) }}
                                                @endif
                                            @endif
                                        </div>

                                        <div class="d-flex align-items-center gap-2">
                                            <div class="stars d-flex">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($menu->rating))
                                                        <i class="fa-solid fa-star"></i>
                                                    @elseif ($i - $menu->rating < 1)
                                                        <i class="fa-solid fa-star-half-stroke"></i>
                                                    @else
                                                        <i class="fa-regular fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <div class="star-label">
                                                @if ($menu->ratingCount > 0)
                                                    ({{ number_format($menu->rating, 1) }})
                                                    {{ $menu->ratingCount }} review{{ $menu->ratingCount > 1 ? 's' : '' }}
                                                @else
                                                    No Rating
                                                @endif
                                            </div>
                                        </div>


                                    </div>
                                </a>

                            </div>
                        </div>
                    @empty
                        <div class="col">
                            <p>No new menu available.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Highest Rated Menus --}}
        <div class="w-100 mb-5">
            <div class="h2 border-baba pb-3 mb-4">
                Highest Rated Menus
            </div>
            <div>
                <div class="row row-cols-1 row-cols-md-4 g-4">
                    @forelse ($highestRatedMenus as $menu)
                        <div class="col">
                            <div class="card h-100 card-shadow">

                                <div class="img-container">
                                    <img src="{{ asset('storage/' . $menu->image) }}" class="card-img-top darken"
                                        alt="{{ $menu->name }}">

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
                                            @if ($menu->discount > 0)
                                                {{-- Display discounted price with discount percentage --}}
                                                ₱{{ number_format($menu->price * (1 - $menu->discount / 100), 2) }}
                                                <span class="text-success">(-{{ $menu->discount }}% OFF)</span>
                                            @else
                                                {{-- Display original price --}}
                                                @if (floor($menu->price) == $menu->price)
                                                    ₱{{ number_format($menu->price, 0) }}
                                                @else
                                                    ₱{{ number_format($menu->price, 2) }}
                                                @endif
                                            @endif
                                        </div>

                                        <div class="d-flex align-items-center gap-2">
                                            <div class="stars d-flex">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($menu->rating))
                                                        <i class="fa-solid fa-star"></i>
                                                    @elseif ($i - $menu->rating < 1)
                                                        <i class="fa-solid fa-star-half-stroke"></i>
                                                    @else
                                                        <i class="fa-regular fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <div class="star-label">
                                                @if ($menu->ratingCount > 0)
                                                    ({{ number_format($menu->rating, 1) }})
                                                    {{ $menu->ratingCount }} review{{ $menu->ratingCount > 1 ? 's' : '' }}
                                                @else
                                                    No Rating
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </a>

                            </div>
                        </div>
                    @empty
                        <div class="col">
                            <p>No highest-rated menu available.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
    {{-- New Menus Share Link Script --}}
    <script>
        function copyMenuLinkNew(menuIdentifier) {
            // Extract the actual menu ID by splitting the identifier (e.g., "new-6" becomes "6")
            const menuId = menuIdentifier.split('-')[1];
            const menuLink = `${window.location.origin}/user/menuDetails/${menuId}`;

            // Copy the link to the clipboard
            navigator.clipboard.writeText(menuLink)
                .then(() => {
                    // Get the correct message element
                    const messageElement = document.getElementById(`copyMessage-${menuIdentifier}`);

                    // Display the success message
                    messageElement.textContent = "Menu link copied successfully";
                    messageElement.style.display = 'block';

                    // Hide the message after 2 seconds
                    setTimeout(() => {
                        messageElement.style.display = 'none';
                    }, 2000);
                })
                .catch(err => {
                    console.error('Failed to copy the text:', err);
                });
        }
    </script>
@endsection
