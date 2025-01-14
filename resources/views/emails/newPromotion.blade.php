<!DOCTYPE html>
<html>

<head>
    <title>New Promotion: {{ $promotion->name }}</title>
</head>

<body>
    <h1>Exciting News, {{ $user->first_name }}!</h1>
    <p>We have a new promotion just for you:</p>
    <h3>{{ $promotion->name }}</h3>.
    <p>Don't miss out! Visit our website now to learn more:
        <a href="{{ url('/') }}">Pisces Coffee Hub</a>
    </p>
    <p>Thank you for being a valued part of our community!</p>
</body>

</html>
