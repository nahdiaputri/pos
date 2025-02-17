<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function foodBeverage()
    {
        return view('products', ['category' => 'Food & Beverage']);
    }

    public function beautyHealth()
    {
        return view('products', ['category' => 'Beauty & Health']);
    }

    public function homeCare()
    {
        return view('products', ['category' => 'Home Care']);
    }

    public function babyKid()
    {
        return view('products', ['category' => 'Baby & Kid']);
    }
}
