<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
       $featured_products = Product::where('is_featured' , 'Yes')->where('status' , 1)->get();

       $latest_products = Product::orderBy('created_at' , 'DESC')->where('status' , 1)->take(8)->get();
        return view('frontend.home' , compact('featured_products' , 'latest_products'));
    }
}
