<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class InertiaWishlistController extends Controller
{
    /**
     * Display wishlist page
     */
    public function index()
    {
        $wishlistIds = session('wishlist', []);
        $wishlistProducts = [];

        if (!empty($wishlistIds)) {
            $wishlistProducts = Product::whereIn('id', $wishlistIds)
                ->where('status', 1)
                ->with('image')
                ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type')
                ->get();
        }

        return Inertia::render('Wishlist', [
            'wishlistProducts' => $wishlistProducts,
            'currentPath' => '/wishlist',
        ]);
    }

    /**
     * Add product to wishlist
     */
    public function add(Request $request)
    {
        try {
            $productId = $request->product_id;
            $wishlist = session('wishlist', []);

            if (!in_array($productId, $wishlist)) {
                $wishlist[] = $productId;
                session(['wishlist' => $wishlist]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product added to wishlist',
                'wishlistCount' => count($wishlist),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove product from wishlist
     */
    public function remove(Request $request)
    {
        try {
            $productId = $request->product_id;
            $wishlist = session('wishlist', []);

            $wishlist = array_filter($wishlist, function($id) use ($productId) {
                return $id != $productId;
            });

            session(['wishlist' => array_values($wishlist)]);

            return response()->json([
                'success' => true,
                'message' => 'Product removed from wishlist',
                'wishlistCount' => count($wishlist),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check if product is in wishlist
     */
    public function check(Request $request)
    {
        $wishlist = session('wishlist', []);
        $isInWishlist = in_array($request->product_id, $wishlist);

        return response()->json([
            'success' => true,
            'isInWishlist' => $isInWishlist,
        ]);
    }
}
