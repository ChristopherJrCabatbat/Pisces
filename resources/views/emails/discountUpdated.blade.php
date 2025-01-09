<!DOCTYPE html>
<html>

<head>
    <title>Discount Update Notification</title>
</head>

<body>
    <h1>Hello {{ $user->first_name }},</h1>

    @if ($newDiscount > $oldDiscount)
        <p>Great news! The discount on <strong>{{ $menu->name }}</strong> has been <strong>increased</strong>.</p>
    @else
        <p>Heads up! The discount on <strong>{{ $menu->name }}</strong> has been <strong>reduced</strong>.</p>
    @endif

    <p>Here are the details:</p>
    <ul>
        <li><strong>Original Price:</strong> ${{ number_format($menu->price, 2) }}</li>
        <li><strong>Previous Discount:</strong> {{ $oldDiscount }}%</li>
        <li><strong>New Discount:</strong> {{ $newDiscount }}% (New Price:
            ${{ number_format($menu->price * (1 - $newDiscount / 100), 2) }})</li>
    </ul>

    @if ($newDiscount > $oldDiscount)
        <p>Visit our website to place your order and enjoy these savings!</p>
    @else
        <p>Visit our website to place your order now!</p>
    @endif
    <a href="{{ url('/') }}">Visit Pisces Coffee Hub</a>
</body>

</html>
