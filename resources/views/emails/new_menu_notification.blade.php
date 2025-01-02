<!DOCTYPE html>
<html>
<head>
    <title>New Menu Item</title>
</head>
<body>
    <h1>New Menu Alert!</h1>
    <p>We have added a new menu item just for you to try out!</p>

    <h2>{{ $name }}</h2>
    <p><strong>Price:</strong> ${{ number_format($price, 2) }}</p>
    <p>{{ $description }}</p>
    @if($image)
        <img src="{{ $image }}" alt="{{ $name }}" style="max-width: 100%; height: auto;">
    @endif

    <p>Come and give it a try! We hope to see you soon.</p>
</body>
</html>
