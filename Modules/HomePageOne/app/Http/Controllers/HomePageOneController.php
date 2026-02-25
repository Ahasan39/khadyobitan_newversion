<?php

namespace Modules\HomePageOne\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Banner;
use App\Models\Category;

class HomePageOneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    
    {
        $sliders = Banner::where(['status' => 1, 'category_id' => 1])
            ->select('id', 'image', 'link')
            ->get();
             $top_selling = Product::where(['status' => 1, 'top_selling' => 1])
            ->orderBy('id', 'DESC')
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'category_id')
            ->withCount('variable')
            ->limit(12)
            ->get();


         $new_arrival = Product::where(['status' => 1, 'new_arrival' => 1])
            ->orderBy('id', 'DESC')
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'category_id')
            ->withCount('variable')
            ->limit(12)
            ->get();
            
        $homecategory = Category::where(['front_view' => 1, 'status' => 1])
            ->select('id', 'name', 'slug', 'front_view', 'banner','status')
            ->orderBy('id', 'ASC')
            ->get();
            
        return view('homepageone::index',compact('new_arrival','sliders','top_selling','homecategory'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('homepageone::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('homepageone::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('homepageone::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}

    public function productDetails()
    {
        return view('homepageone::frontend.products.details');
    }
    public function checkout()
    {
        return view('homepageone::frontend.checkout');
    }
}
