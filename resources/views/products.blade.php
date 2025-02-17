<!DOCTYPE html>
<html lang="en">
<head>
    <title>Products</title>
</head>
<body>
    <h1>Product Category: {{ $category }}</h1>
    <ul>
        <li><a href="{{ url('/category/food-beverage') }}">Food & Beverage</a></li>
        <li><a href="{{ url('/category/beauty-health') }}">Beauty & Health</a></li>
        <li><a href="{{ url('/category/home-care') }}">Home Care</a></li>
        <li><a href="{{ url('/category/baby-kid') }}">Baby & Kid</a></li>
    </ul>
    <a href="{{ url('/') }}">Back to Home</a>
</body>
</html>

