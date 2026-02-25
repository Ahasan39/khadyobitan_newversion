<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CheckoutLead;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;

class CheckoutLeadController extends Controller
{
    public function store(Request $request)
    {
        // Get cart data
        $cart = Cart::instance('shopping')->content();
        $cartData = [];
        $totalAmount = 0;

        foreach ($cart as $item) {
            $cartData[] = [
                'id' => $item->id,
                'name' => $item->name,
                'qty' => $item->qty,
                'price' => $item->price,
                'image' => $item->options->image ?? null,
                'size' => $item->options->product_size ?? null,
                'color' => $item->options->product_color ?? null,
            ];
            $totalAmount += $item->price * $item->qty;
        }

        // Calculate total with shipping and discount
        $subtotal = Cart::instance('shopping')->subtotal();
        $subtotal = str_replace(',', '', $subtotal);
        $subtotal = str_replace('.00', '', $subtotal);
        $shipping = Session::get('shipping') ? Session::get('shipping') : 0;
        $coupon = Session::get('coupon_amount') ? Session::get('coupon_amount') : 0;
        $discount = Session::get('discount') ? Session::get('discount') : 0;
        $grandTotal = $subtotal + $shipping - ($discount + $coupon);

        // Check if lead already exists for this phone
        $existingLead = CheckoutLead::where('phone', $request->phone)
            ->where('status', 'pending')
            ->first();

        if ($existingLead) {
            // Update existing lead
            $existingLead->update([
                'name' => $request->name,
                'address' => $request->address,
                'cart_data' => $cartData,
                'total_amount' => $grandTotal,
                'ip' => $request->ip(),
            ]);
        } else {
            // Create new lead
            CheckoutLead::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'cart_data' => $cartData,
                'total_amount' => $grandTotal,
                'ip' => $request->ip(),
                'status' => 'pending',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Lead saved successfully'
        ]);
    }
}
