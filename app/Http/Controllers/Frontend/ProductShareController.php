<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductShareController extends Controller
{
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        
        $imageUrl = $product->image 
            ? url('storage/' . $product->image) 
            : url('images/logo.png');
        
        $metaTags = [
            'title' => $product->name,
            'description' => strip_tags($product->description),
            'image' => $imageUrl,
            'url' => url()->current(),
        ];
        
        return view('frontEnd.share.product', compact('metaTags'));
    }

}
