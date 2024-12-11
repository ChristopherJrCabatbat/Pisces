@extends('user.layout')

@section('title', 'Menu')

@section('styles-links')
    <style>
        .main-content {
            margin-top: 13vh;
        }

        select {
            width: 30% !important;
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
    <div class="container main-content d-flex flex-column align-items-center mb-5">

        {{-- Top Container --}}
        <div class="top-container d-flex w-100 p-4 mb-5 justify-content-between align-items-center">
            <div class="fw-bold h1">
                {{ $selectedCategory }}
            </div>
            <div class="menu-chosen d-flex justify-content-center align-items-center gap-2 fs-5">
                <div>Menu <i class="fa-solid fa-caret-right mx-1"></i></div>
                <div class="low-opacity-white">{{ $selectedCategory }}</div>
            </div>
        </div>

        {{-- Content --}}
        <div class="d-flex container content user-content gap-5 p-0">

            {{-- Categories --}}
            <div class="categories d-flex flex-column">
                <div class="h3 mb-4">Categories</div>
                <div class="category-lists d-flex flex-column gap-2">
                    <!-- All Menus -->
                    <div>
                        <div>
                            <i class="fa-solid fa-caret-right me-2"></i>
                            <a href="{{ route('user.menu', ['category' => 'All Menus']) }}" class="white-underline">
                                <span class="{{ $selectedCategory == 'All Menus' ? 'active-category' : '' }}">All
                                    Menus</span>
                            </a>
                        </div>
                    </div>

                    <!-- Sorted Categories by Menu Count -->
                    @foreach ($categories as $category)
                        <div class="d-flex justify-content-between">
                            <div>
                                <i class="fa-solid fa-caret-right me-2"></i>
                                <a href="{{ route('user.menu', ['category' => $category->category]) }}"
                                    class="white-underline">
                                    <span class="{{ $selectedCategory == $category->category ? 'active-category' : '' }}">
                                        {{ $category->category }}
                                    </span>
                                </a>
                            </div>
                            <div>({{ $category->menu_count }})</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Menus --}}
            <div class="menus d-flex flex-column gap-4 mb-5 w-100">

                {{-- Select - Search --}}
                <div class="top-menus d-flex justify-content-between">
                    <select id="sort-select" class="form-select" aria-label="Sort by price">
                        <option selected disabled value="Default">Sort by price</option>
                        <option value="Expensive">Highest first</option>
                        <option value="Cheap">Lowest first</option>
                    </select>
                    <div class="position-relative custom-search" id="search-form">
                        <form action="">
                            <input type="text" id="search-input" class="form-control" placeholder="Search menus..." />
                            <i class="fas fa-search custom-search-icon"></i>
                        </form>
                    </div>
                </div>

                {{-- Menu --}}
                <div class="menu-list">
                    <div id="menu-container" class="row row-cols-1 row-cols-md-3 g-4">
                        @forelse($menus as $menu)
                            <div class="col menu-item" data-price="{{ $menu->price }}"
                                data-name="{{ strtolower($menu->name) }}">
                                <div class="card card-hover h-100 position-relative">
                                    {{-- Menu Content --}}
                                    <div class="img-container">
                                        @if ($menu->image)
                                            <img src="{{ asset('storage/' . $menu->image) }}" class="card-img-top darken"
                                                alt="{{ $menu->name }}">
                                        @else
                                            <img src="{{ asset('images/logo.jpg') }}" class="card-img-top darken"
                                                alt="No Image">
                                        @endif

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
                                            <button type="button" class="icon-buttons"
                                                onclick="copyMenuLink({{ $menu->id }})">
                                                <i class="fa-solid fa-share" title="Share Menu"></i>
                                            </button>

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
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $menu->name }}</h5>
                                            <p class="price fw-bold mb-0">₱{{ number_format($menu->price, 2) }}</p>
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
                                                        {{ $menu->ratingCount }}
                                                        review{{ $menu->ratingCount > 1 ? 's' : '' }}
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
                            <div class="col no-menus">
                                <p>No menus available.</p>
                            </div>
                        @endforelse
                    </div>
                </div>


            </div>

        </div>

    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sortSelect = document.getElementById('sort-select');
            const searchInput = document.getElementById('search-input');
            const menuContainer = document.getElementById('menu-container');
            const menuItems = [...document.querySelectorAll('.menu-item')]; // Convert NodeList to Array
    
            // Function to render menus
            function renderMenus(filteredMenus) {
                menuContainer.innerHTML = ''; // Clear existing menus
                if (filteredMenus.length === 0) {
                    menuContainer.innerHTML = `<div class="col no-menus"><p>No menus available.</p></div>`;
                } else {
                    filteredMenus.forEach(menu => menuContainer.appendChild(menu));
                }
            }
    
            // Combined function for sorting and searching
            function updateMenus() {
                const sortValue = sortSelect.value;
                const query = searchInput.value.toLowerCase();
    
                // Filter menus by search query
                let filteredMenus = menuItems.filter(menu => {
                    const menuName = menu.getAttribute('data-name');
                    return menuName.includes(query);
                });
    
                // Sort filtered menus
                filteredMenus = filteredMenus.sort((a, b) => {
                    const priceA = parseFloat(a.getAttribute('data-price'));
                    const priceB = parseFloat(b.getAttribute('data-price'));
                    return sortValue === 'Expensive' ? priceB - priceA : priceA - priceB;
                });
    
                // Render updated menus
                renderMenus(filteredMenus);
            }
    
            // Event listeners
            sortSelect.addEventListener('change', updateMenus);
            searchInput.addEventListener('input', updateMenus);
        });
    </script>
    
@endsection
