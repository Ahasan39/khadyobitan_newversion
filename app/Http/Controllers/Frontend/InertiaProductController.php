<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Models\ProductVariable;
use App\Models\ShippingCharge;
use App\Events\ProductViewed;

class InertiaProductController extends Controller
{
    /**
     * Display product details page
     */
    public function show($slug)
    {
        try {
            $product = Cache::remember("product_details_{$slug}", 600, function() use ($slug) {
                return Product::where(['slug' => $slug, 'status' => 1])
                    ->with([
                        'image',
                        'images',
                        'category',
                        'subcategory',
                        'childcategory',
                        'variables' => function($q) {
                            $q->where('stock', '>', 0);
                        },
                        'reviews'
                    ])
                    ->withCount(['variables as variable_count' => function($q) {
                        $q->where('stock', '>', 0);
                    }])
                    ->first();
            });

            // Get related products
            $relatedProducts = [];
            if ($product && $product->category_id) {
                $relatedProducts = Cache::remember("related_products_{$product->category_id}", 600, function() use ($product) {
                    return Product::where('category_id', $product->category_id)
                        ->where('status', 1)
                        ->where('id', '!=', $product->id)
                        ->with([
                            'image',
                            'variables' => function($q) {
                                $q->where('stock', '>', 0);
                            }
                        ])
                        ->withCount(['variables as variable_count' => function($q) {
                            $q->where('stock', '>', 0);
                        }])
                        ->select('id','name','slug','status','category_id','new_price','old_price','type')
                        ->take(12)
                        ->get();
                });
            }

            // Get shipping charges
            $shippingCharges = Cache::remember("shipping_charge_active", 600, fn() => 
                ShippingCharge::where('status', 1)->get()
            );

            $colors = [];
            $sizes = [];
            
            if ($product) {
                // Get product colors
                $colors = Cache::remember("product_colors_{$product->id}", 600, function() use ($product) {
                    return ProductVariable::where('product_id', $product->id)
                        ->where('stock', '>', 0)
                        ->whereNotNull('color')
                        ->select('color')
                        ->distinct()
                        ->get();
                });

                // Get product sizes
                $sizes = Cache::remember("product_sizes_{$product->id}", 600, function() use ($product) {
                    return ProductVariable::where('product_id', $product->id)
                        ->where('stock', '>', 0)
                        ->whereNotNull('size')
                        ->select('size')
                        ->distinct()
                        ->get();
                });

                // Track product view
                $sessionData = [
                    'cart_items' => session('cart', []),
                    'recently_viewed' => session('recently_viewed', []),
                    'wishlist' => session('wishlist', []),
                    'last_activity' => session('last_activity'),
                    'visit_count' => session('visit_count', 0) + 1
                ];
                session(['last_activity' => now(), 'visit_count' => $sessionData['visit_count']]);
                event(new ProductViewed($product, auth()->user(), $sessionData));
            }

            return Inertia::render('ProductDetail', [
                'product' => $product,
                'relatedProducts' => $relatedProducts,
                'shippingCharges' => $shippingCharges ?? [],
                'colors' => $colors,
                'sizes' => $sizes,
                'reviews' => $product ? $product->reviews : [],
                'currentPath' => "/product/{$slug}",
                'slug' => $slug,
            ]);
        } catch (\Exception $e) {
            // Return page with null product - frontend will use static data fallback
            return Inertia::render('ProductDetail', [
                'product' => null,
                'relatedProducts' => [],
                'shippingCharges' => [],
                'colors' => [],
                'sizes' => [],
                'reviews' => [],
                'currentPath' => "/product/{$slug}",
                'slug' => $slug,
            ]);
        }
    }

    /**
     * Check product stock
     */
    public function checkStock(Request $request)
    {
        $product = ProductVariable::where([
            'product_id' => $request->id,
            'color' => $request->color,
            'size' => $request->size
        ])->first();

        return response()->json([
            'status' => $product ? true : false,
            'product' => $product
        ]);
    }

    /**
     * Get product variables (colors and sizes)
     */
    public function getVariables(Request $request)
    {
        $product = Product::where(['id' => $request->id, 'status' => 1])
            ->with('image')
            ->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $colors = ProductVariable::where('product_id', $request->id)
            ->where('stock', '>', 0)
            ->whereNotNull('color')
            ->select('color')
            ->distinct()
            ->get();

        $sizes = ProductVariable::where('product_id', $request->id)
            ->where('stock', '>', 0)
            ->whereNotNull('size')
            ->select('size')
            ->distinct()
            ->get();

        return response()->json([
            'product' => $product,
            'colors' => $colors,
            'sizes' => $sizes,
        ]);
    }
}
