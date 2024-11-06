@foreach ($menus as $menu)
<div class="menu-card">
    <div class="img-container">
        <img src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('images/logo.jpg') }}" alt="{{ $menu->name }}">
        
        <!-- Darken overlay div -->
        <div class="darken"></div>
        
        <!-- Icon overlay with centered icons -->
        <div class="icon-overlay">
            <button onclick="showLoginAlert()" title="Add to Cart"><i class="fa-solid fa-cart-plus"></i></button>
            <button onclick="showLoginAlert()" title="Favorites"><i class="fa-solid fa-heart"></i></button>
            <button onclick="showLoginAlert()" title="Share"><i class="fa-solid fa-share"></i></button>
            <button onclick="showLoginAlert()" title="View"><i class="fa-solid fa-search"></i></button>
        </div>
    </div>
    <div class="card-body">
        <h5 class="card-title">{{ $menu->name }}</h5>
        <p class="card-text">₱{{ number_format($menu->price, 2) }}</p>
    </div>
</div>
@endforeach