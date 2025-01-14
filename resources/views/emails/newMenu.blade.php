<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Menu Item</title>
</head>
<body>
    <h1>Hello {{ $user->first_name }}!</h1>
    <p>We have added a new menu to our restaurant that you may want to check out: <strong>{{ $menu->name }}</strong>.</p>
    <p>Here are the details:</p>
    <ul>
        <li><strong>Price:</strong> ₱{{ number_format($menu->price, 2) }}</li>
        <li><strong>Category:</strong> {{ $menu->category }}</li>
        <li><strong>Description:</strong> {{ $menu->description }}</li>
    </ul>
    <p><a href="{{ route('user.menuDetails', $menu->id) }}">Click here</a> to view the menu item.</p>
    <p>Thank you for subscribing to our newsletter!</p>
    <p>- Pisces Coffee Hub</p>
</body>
</html>