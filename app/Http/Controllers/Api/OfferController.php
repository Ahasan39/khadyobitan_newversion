<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    // সব অফার লিস্ট (প্রোডাক্ট সম্পর্ক সহ)
    public function offersApi()
    {
        $offers = Offer::with(['products' => function($q){
    $q->with(['category','subcategory','childcategory','brand','image','images','reviews','variables','policies'])
      ->select('products.id','products.slug','products.name','products.description','products.old_price','products.new_price','products.stock','products.pro_video','products.pro_barcode','products.category_id','products.subcategory_id','products.childcategory_id','products.brand_id');
}])

->get(['offers.id','offers.title','offers.description']);


        // Prepare size-color mapping for each product
        $offers->each(function($offer){
            $offer->products->transform(function($product){
                $product['color_size'] = $product->variables
                    ->groupBy('size')
                    ->map(function ($items, $size) {
                        return [
                            'size' => $size,
                            'colors' => $items->pluck('color')->unique()->values(),
                        ];
                    })
                    ->values();
                return $product;
            });
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Offers fetched successfully',
            'data' => $offers
        ]);
    }

    // Single Offer products (Full details)
    public function offerProductsApi($id)
    {
        $offer = Offer::with(['products' => function($q){
    $q->with(['category','subcategory','childcategory','brand','image','images','reviews','variables','policies'])
      ->select('products.id','products.slug','products.name','products.description','products.old_price','products.new_price','products.stock','products.pro_video','products.pro_barcode','products.category_id','products.subcategory_id','products.childcategory_id','products.brand_id');
}])->find($id, ['offers.id','offers.title','offers.description']);


        if(!$offer){
            return response()->json([
                'status' => 'error',
                'message' => 'Offer not found',
            ],404);
        }

        // Prepare size-color mapping
        $offer->products->transform(function($product){
            $product['color_size'] = $product->variables
                ->groupBy('size')
                ->map(function ($items, $size) {
                    return [
                        'size' => $size,
                        'colors' => $items->pluck('color')->unique()->values(),
                    ];
                })
                ->values();
            return $product;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Offer products fetched successfully',
            'data' => $offer->products
        ]);
    }
}