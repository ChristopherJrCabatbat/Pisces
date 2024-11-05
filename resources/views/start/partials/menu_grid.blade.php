<!-- resources/views/start/partials/menu_grid.blade.php -->
@foreach ($menus as $menu)
    <div class="menu-card">
        <img src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('images/logo.jpg') }}" alt="{{ $menu->name }}">
        <div class="card-body">
            <h5 class="card-title">{{ $menu->name }}</h5>
            <p class="card-text">₱{{ number_format($menu->price, 2) }}</p>
            <div class="button-group">
                <button class="custom-button" onclick="showLoginAlert()">Add to Cart</button>
                <button class="custom-button" onclick="showLoginAlert()">Favorites</button>
                <button class="custom-button" onclick="showLoginAlert()">Share</button>
                <button class="custom-button" onclick="showLoginAlert()">View</button>
            </div>
        </div>
    </div>
@endforeach
