<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\ShippingCharge;

class InertiaCartController extends Controller
{
    /**
     * Display shopping cart
     */
    public function index()
    {
        $cartItems = Cart::instance('shopping')->content();
        $shippingCharges = ShippingCharge::where('status', 1)->get();
        $shippingCost = session('shipping', 0);

        return Inertia::render('Cart', [
            'cartItems' => $cartItems,
            'shippingCharges' => $shippingCharges,
            'shippingCost' => $shippingCost,
            'subtotal' => Cart::instance('shopping')->subtotal(),
            'total' => Cart::instance('shopping')->total(),
            'currentPath' => '/cart',
        ]);
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        try {
            $product = $request->product;
            
            Cart::instance('shopping')->add([
                'id' => $product['id'],
                'name' => $product['name'],
                'qty' => $request->quantity ?? 1,
                'price' => $product['new_price'],
                'weight' => 1,
                'options' => [
                    'slug' => $product['slug'],
                    'image' => $product['image'],
                    'old_price' => $product['old_price'],
                    'color' => $request->color,
                    'size' => $request->size,
                    'type' => $product['type'],
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart',
                'cartCount' => Cart::instance('shopping')->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        try {
            Cart::instance('shopping')->remove($request->rowId);

            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart',
                'cartCount' => Cart::instance('shopping')->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request)
    {
        try {
            Cart::instance('shopping')->update($request->rowId, $request->quantity);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated',
                'subtotal' => Cart::instance('shopping')->subtotal(),
                'total' => Cart::instance('shopping')->total(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update shipping charge
     */
    public function updateShipping(Request $request)
    {
        try {
            if ($request->shippingId) {
                $shipping = ShippingCharge::find($request->shippingId);
                session(['shipping' => $shipping->amount]);
            } else {
                session(['shipping' => 0]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Shipping updated',
                'total' => Cart::instance('shopping')->total() + session('shipping', 0),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        Cart::instance('shopping')->destroy();
        session(['shipping' => 0]);

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared',
        ]);
    }

    /**
     * Get cart content via AJAX
     */
    public function getContent()
    {
        $cartItems = Cart::instance('shopping')->content();

        return response()->json([
            'success' => true,
            'items' => $cartItems,
            'count' => Cart::instance('shopping')->count(),
            'subtotal' => Cart::instance('shopping')->subtotal(),
            'total' => Cart::instance('shopping')->total(),
        ]);
    }
}
