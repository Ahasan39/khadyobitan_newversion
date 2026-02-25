<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\ProductVariable;
use App\Models\Product;
// ADD THESE IMPORTS FOR EVENTS
use App\Events\AddToCart;
use App\Events\OrderNow;

class ShoppingController extends Controller
{

    public function addTocartGet($id, Request $request)
    {
        $qty = 1;
        $productInfo = DB::table('products')->where('id', $id)->first();
        $productImage = DB::table('productimages')->where('product_id', $id)->first();
        $cartinfo = Cart::instance('shopping')->add([
            'id' => $productInfo->id,
            'name' => $productInfo->name,
            'qty' => $qty,
            'price' => $productInfo->new_price,
            'weight' => 1,
            'options' => [
                'image' => $productImage->image,
                'old_price' => $productInfo->old_price,
                'slug' => $productInfo->slug,
                'purchase_price' => $productInfo->purchase_price,
                'category_id' => $productInfo->category_id,
               
            ]
        ]);

        // return redirect()->back();
        return response()->json($cartinfo);
    }

    public function cart_store(Request $request)
    {
        // Get product with full information for events
        $product = Product::with('image', 'category')->select('id', 'name', 'slug', 'new_price', 'old_price', 'purchase_price', 'type', 'stock','category_id','shipping_title_dhaka','shipping_amount_dhaka','shipping_title_outside_dhaka','shipping_amount_outside_dhaka')->where(['id' => $request->id])->first();
        
        $var_product = ProductVariable::where(['product_id' => $request->id, 'color' => $request->product_color, 'size' => $request->product_size])->first();
        
        if ($product->type == 0) {
            $purchase_price = $var_product ? $var_product->purchase_price : 0;
            $old_price = $var_product ? $var_product->old_price : 0;
            $new_price = $var_product ? $var_product->new_price : 0;
            $stock = $var_product ? $var_product->stock : 0;
        } else {
            $purchase_price = $product->purchase_price;
            $old_price = $product->old_price;
            $new_price = $product->new_price;
            $stock = $product->stock;
        }
        
        $cartitem = Cart::instance('shopping')->content()->where('id', $product->id)->first();
        if ($cartitem) {
            $cart_qty = $cartitem->qty + $request->qty??1;
        } else {
            $cart_qty = $request->qty??1;
        }
        
        if ($stock < $cart_qty) {
            Toastr::error('Product stock limit over', 'Failed!');
            return back();
        }
        
        // Add to cart
        Cart::instance('shopping')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $request->qty??1,
            'price' => $new_price,
            'weight' => 1,
             'shipping_title_dhaka' => $product->shipping_title_dhaka,
                'shipping_amount_dhaka' => $product->shipping_amount_dhaka,
                'shipping_title_outside_dhaka' => $product->shipping_title_outside_dhaka,
                'shipping_amount_outside_dhaka' => $product->shipping_amount_outside_dhaka,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image->image,
                'old_price' => $new_price,
                'purchase_price' => $purchase_price,
                'product_size' => $request->product_size,
                'product_color' => $request->product_color,
                'type' => $product->type,
                'category_id' => $product->category_id,
                'free_shipping' =>  0,
                'shipping_title_dhaka' => $product->shipping_title_dhaka,
                'shipping_amount_dhaka' => $product->shipping_amount_dhaka,
                'shipping_title_outside_dhaka' => $product->shipping_title_outside_dhaka,
                'shipping_amount_outside_dhaka' => $product->shipping_amount_outside_dhaka,
            ],
        ]);

        // FIRE WEBHOOK EVENTS AFTER SUCCESSFUL CART ADDITION
        // Prepare session data
        $sessionData = [
            'cart_items' => session('cart', []),
            'recently_viewed' => session('recently_viewed', []),
            'wishlist' => session('wishlist', []),
            'last_activity' => session('last_activity'),
            'visit_count' => session('visit_count', 0)
        ];

        // Prepare action data
        $actionData = [
            'qty' => $request->qty ?? 1,
            'product_color' => $request->product_color,
            'product_size' => $request->product_size,
            'category_id' => $request->category_id,
            'price' => $new_price,
            'total_price' => $new_price * ($request->qty ?? 1),
            'old_price' => $old_price,
            'purchase_price' => $purchase_price
        ];

        Toastr::success('Product successfully add to cart', 'Success!');
        
        // Check action type and fire appropriate event
        if ($request->add_cart) {
            // Add to Cart button clicked
            event(new AddToCart($product, $actionData, auth()->user(), $sessionData));
            return back();
        } else {
            // Order Now button clicked (redirect to checkout)
            event(new OrderNow($product, $actionData, auth()->user(), $sessionData));
            return redirect()->route('customer.checkout');
        }
    }

    public function ajax_cart_store(Request $request)
    {
        $productInfo = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'purchase_price', 'type', 'stock', 'category_id','shipping_title_dhaka','shipping_amount_dhaka','shipping_title_outside_dhaka','shipping_amount_outside_dhaka')->where(['id' => $request->id])->first();
        $var_product = ProductVariable::where(['product_id' => $request->id, 'color' => $request->color, 'size' => $request->size])->first();
        if ($productInfo->type == 0) {
            $purchase_price = $var_product ? $var_product->purchase_price : 0;
            $old_price = $var_product ? $var_product->old_price : 0;
            $new_price = $var_product ? $var_product->new_price : 0;
            $stock = $var_product ? $var_product->stock : 0;
        } else {
            $purchase_price = $productInfo->purchase_price;
            $old_price = $productInfo->old_price;
            $new_price = $productInfo->new_price;
            $stock = $productInfo->stock ?? 0;
        }
        $cartitem = Cart::instance('shopping')->content()->where('id', $productInfo->id)->first();
        if ($cartitem) {
            $cart_qty = $cartitem->qty + $request->qty ?? 1;
        } else {
            $cart_qty = $request->qty ?? 1;
        }
        if ($stock < $cart_qty) {
            return response()->json(['success' => false, 'message' => 'Product stock limit over']);
        }

        Cart::instance('shopping')->add([
         
            'id' => $productInfo->id,
            'name' => $productInfo->name,
            'qty' => $request->qty ?? 1,
            'price' => $new_price,
             'shipping_charge_dhaka' =>$request->shipping_charge_dhaka,
            'shipping_charge_outside_dhaka	' =>$request->shipping_charge_outside_dhaka,
            'weight' => 1,
            'options' => [
                'slug' => $productInfo->slug,
                'image' => $productInfo->image->image,
                'old_price' => $old_price ?? $new_price,
                'purchase_price' => $purchase_price,
                'product_size' => $request->size,
                'product_color' => $request->color,
                'type' => $productInfo->type,
                'category_id' => $productInfo->category_id,
                'free_shipping' => 0,
               
            ],
        ]);

        // AJAX CART EVENT (if needed)
        // You can also add event here for AJAX cart operations
        /*
        $sessionData = [
            'cart_items' => session('cart', []),
            'recently_viewed' => session('recently_viewed', []),
            'wishlist' => session('wishlist', []),
            'last_activity' => session('last_activity'),
            'visit_count' => session('visit_count', 0)
        ];

        $actionData = [
            'qty' => $request->qty ?? 1,
            'product_color' => $request->color,
            'product_size' => $request->size,
            'price' => $new_price,
            'total_price' => $new_price * ($request->qty ?? 1)
        ];

        event(new AddToCart($productInfo, $actionData, auth()->user(), $sessionData));
        */

        return response()->json(['success' => true, 'message' => 'Successfully added to cart!']);
    }

    // Rest of your methods remain unchanged...
    public function campaign_stock(Request $request)
    {
        $product = ProductVariable::where(['product_id' => $request->id, 'color' => $request->color, 'size' => $request->size])->first();

        $status = $product ? true : false;
        $response = [
            'status' => $status,
            'product' => $product
        ];
        return response()->json($response);
    }
    
    public function cart_content(Request $request)
    {
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart', compact('data'));
    }
    
    public function cart_remove(Request $request)
    {
        $remove = Cart::instance('shopping')->update($request->id, 0);
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart', compact('data'));
    }
    
    public function cart_increment(Request $request)
    {
        $item = Cart::instance('shopping')->get($request->id);
        $qty = $item->qty + 1;
        $increment = Cart::instance('shopping')->update($request->id, $qty);
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart', compact('data'));
    }
    
    public function cart_decrement(Request $request)
    {
        $item = Cart::instance('shopping')->get($request->id);
        $qty = $item->qty - 1;
        $decrement = Cart::instance('shopping')->update($request->id, $qty);
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart', compact('data'));
    }
    
    public function cart_count(Request $request)
    {
        $data = Cart::instance('shopping')->count();
        return view('frontEnd.layouts.ajax.cart_count', compact('data'));
    }
    
    public function mobilecart_qty(Request $request)
    {
        $data = Cart::instance('shopping')->count();
        return view('frontEnd.layouts.ajax.mobilecart_qty', compact('data'));
    }

    public function cart_increment_camp(Request $request)
    {
        $item = Cart::instance('shopping')->get($request->id);
        $qty = $item->qty + 1;
        $increment = Cart::instance('shopping')->update($request->id, $qty);
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart_camp', compact('data'));
    }
    
    public function cart_decrement_camp(Request $request)
    {
        $item = Cart::instance('shopping')->get($request->id);
        $qty = $item->qty - 1;
        $decrement = Cart::instance('shopping')->update($request->id, $qty);
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart_camp', compact('data'));
    }
    
    public function cart_content_camp(Request $request)
    {
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart_camp', compact('data'));
    }
    
    public function updateShippingSession(Request $request)
    {
        $shippingMethod = $request->shipping_method;
        $shippingCharge = $request->shipping_charge;
        
        Session::put('shipping_method', $shippingMethod);
        Session::put('shipping', $shippingCharge);
        
        return response()->json([
            'success' => true,
            'shipping' => $shippingCharge,
            'method' => $shippingMethod
        ]);
    }
}