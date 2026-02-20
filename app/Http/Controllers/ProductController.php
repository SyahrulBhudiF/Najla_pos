<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function food()
    {
        return view('products.food');
    }

    public function beauty()
    {
        return view('products.beauty');
    }

    public function home()
    {
        return view('products.home');
    }

    public function baby()
    {
        return view('products.baby');
    }
}
