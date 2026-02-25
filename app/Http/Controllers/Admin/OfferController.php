<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    // List all offers
    public function index()
    {
        $offers = Offer::with('products')->latest()->get();
        return view('backEnd.offer.index', compact('offers'));
    }

    // Create offer form
    public function create()
    {
        $products = Product::select('id','name')->where('status',1)->get();
        return view('backEnd.offer.create', compact('products'));
    }

    // Store offer
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'products'    => 'required|array',
        ]);

        $offer = Offer::create([
            'title'       => $request->title,
            'description' => $request->description,
        ]);

        // Attach products
        $offer->products()->attach($request->products);

        return redirect()->route('offers.index')->with('success', 'Offer Created Successfully!');
    }
    
    
    public function edit($id)
{
    $offer = Offer::with('products')->findOrFail($id);
    $products = Product::select('id', 'name')->get();

  
    $selectedProducts = $offer->products->pluck('id')->toArray();

    return view('backEnd.offer.edit', compact('offer', 'products', 'selectedProducts'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string',
        'description' => 'nullable|string',
        'products' => 'required|array',
    ]);

    $offer = Offer::findOrFail($id);
    $offer->update([
        'title' => $request->title,
        'description' => $request->description,
    ]);

    // sync updated products
    $offer->products()->sync($request->products);

    return redirect()->route('offers.index')->with('success', 'Offer Updated Successfully');
}

public function destroy($id)
{
    $offer = Offer::findOrFail($id);
    $offer->delete();

    return redirect()->route('offers.index')->with('success', 'Offer Deleted Successfully');
}
   public function productSearch(Request $request)
{
    $search = $request->q;

    $data = Product::where('name', 'LIKE', "%$search%")
        ->select('id', 'name as text')
        ->limit(20)
        ->get();

    return response()->json($data);
}


}
