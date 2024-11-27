<div class="order-container border rounded mb-4 p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <p class="text-muted mb-1">Order Date</p>
            <p class="fw-bold mb-0">{{ $order->created_at->format('M d') }}</p>
        </div>
        <div>
            <span class="badge {{ $statusClass }}">{{ $order->status }}</span>
        </div>
    </div>
    <div class="d-flex align-items-center">
        {{-- Menu Image --}}
        <img src="{{ asset('images/logo.jpg') }}" alt="Menu Image" class="rounded me-3"
            style="width: 80px; height: 80px; object-fit: cover;">
        <div>
            <p class="fw-bold mb-1">{{ $order->order }}</p>
            <p class="text-muted mb-0">₱{{ number_format($order->total_price, 2) }}</p>
        </div>
    </div>
</div>
