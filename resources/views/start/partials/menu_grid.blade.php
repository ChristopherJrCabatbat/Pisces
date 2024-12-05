<ul class="grid-list grid-list-menus">
    @foreach ($menus as $menu)
        <li>
            <div class="restaurant-card">
                <div class="card-icon">
                    <img src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('images/logo.jpg') }}"
                        width="100" height="100" loading="lazy" alt="{{ $menu->name }}" class="w-100">
                </div>
                <h3 class="h5 card-title">{{ $menu->name }}</h3>
                <p class="card-text">₱{{ number_format($menu->price, 2) }}</p>
                <p class="card-text">{{ $menu->description }}</p>
            </div>
        </li>
    @endforeach
</ul>

