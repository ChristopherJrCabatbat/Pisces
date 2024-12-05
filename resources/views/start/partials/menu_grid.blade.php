<ul class="grid-list grid-list-menus">
    @foreach ($menus as $menu)
        <li>
            <div class="restaurant-card_grid">
                <div class="card-icon">
                    <img src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('images/logo.jpg') }}"
                        width="100" height="100" loading="lazy" alt="{{ $menu->name }}" class="w-100">
                </div>
                <h3 class="h5 card-title">{{ $menu->name }}</h3>
                <!-- Updated Star Rating Implementation -->
                <div class="rating-wrapper d-flex align-items-center gap-3">
                    <div class="stars d-flex">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <div class="star-label">
                        @if ($menu->ratingCount > 0)
                            {{ number_format($menu->rating, 1) }} ({{ $menu->ratingCount }})
                            {{-- {{ $menu->ratingCount > 1 ? 's' : '' }} --}}
                        @else
                            No Rating
                        @endif
                    </div>
                </div>
                <p class="card-text">₱{{ number_format($menu->price, 2) }}</p>
                <p class="card-text">{{ $menu->category }}</p>
            </div>
        </li>
    @endforeach
</ul>
