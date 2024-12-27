<div class="a-container order-container border rounded mb-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <p class="text-muted mb-1">Order Date</p>
            <p class="fw-bold mb-0">{{ $order->created_at->format('M d, Y') }}</p>
        </div>
        <div>
            {{-- Dynamic Badge Color Based on Order Status --}}
            @if ($order->status == 'Returned')
                <span class="badge bg-danger">{{ $order->status }}</span>
            @elseif ($order->status == 'Pending' || $order->status == 'Pending GCash Transaction')
                <span class="badge bg-secondary">{{ $order->status }}</span>
            @elseif ($order->status == 'Preparing')
                <span class="badge bg-warning text-dark">{{ $order->status }}</span>
            @elseif ($order->status == 'Out for Delivery')
                <span class="badge bg-info">{{ $order->status }}</span>
            @elseif ($order->status == 'Delivered')
                <span class="badge bg-success">{{ $order->status }}</span>
            @else
                <span class="badge bg-secondary">{{ $order->status }}</span> {{-- Default fallback --}}
            @endif
        </div>
    </div>

    @foreach ($order->menuDetails as $menu)
        <div class="@if ($order->status == 'Delivered') d-flex justify-content-between align-items-center @endif">
            <div class="d-flex align-items-center mb-2">
                {{-- Menu Image --}}
                <img src="{{ url($menu->image) }}" alt="{{ $menu->name }}" class="rounded me-3"
                    style="width: 80px; height: 80px; object-fit: cover;">
                <div>
                    <p class="fw-bold mb-1">{{ $menu->name }} (x{{ $menu->quantity }})</p>
                    <p class="text-muted mb-0">₱{{ number_format($menu->price * $menu->quantity, 2) }}</p>
                    {{-- <p class="text-muted mb-0">₱{{ number_format($menu->price * $menu->pivot->quantity, 2) }}</p> --}}
                </div>
            </div>
            @if ($order->status == 'Delivered')
                <button type="button" class="btn btn-warning to-review h-25" data-bs-toggle="modal"
                    data-bs-target="#feedbackModal" data-menu-name="{{ $menu->name }}"
                    data-menu-image="{{ url($menu->image) }}">
                    To Review
                </button>
            @endif
        </div>
    @endforeach
</div>
