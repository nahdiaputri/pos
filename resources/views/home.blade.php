<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
</head>
<body>
    <h1>Welcome to Our Website</h1>
    <ul>
        <li><a href="{{ url('/category/food-beverage') }}">Food & Beverage</a></li>
        <li><a href="{{ url('/category/beauty-health') }}">Beauty & Health</a></li>
        <li><a href="{{ url('/category/home-care') }}">Home Care</a></li>
        <li><a href="{{ url('/category/baby-kid') }}">Baby & Kid</a></li>
        <li><a href="{{ url('/sales') }}">Sales (POS)</a></li>
    </ul>
</body>
</html>

