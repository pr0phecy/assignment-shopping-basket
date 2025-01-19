<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(): Factory|View
    {
        $products = Product::all();

        return view('welcome', compact('products'));
    }
}
