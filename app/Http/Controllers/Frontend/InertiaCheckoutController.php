<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\ShippingCharge;
use App\Models\District;

class InertiaCheckoutController extends Controller
{
    /**
     * Display checkout page
     */
    public function index()
    {
        $cartItems = Cart::instance('shopping')->content();
        
        // Allow checkout even if server cart is empty (frontend may have local cart)
        $shippingCharges = ShippingCharge::where('status', 1)->get();
        $districts = District::select('id', 'district')->distinct()->get();
        $customer = Auth::guard('customer')->user();

        return Inertia::render('Checkout', [
            'cartItems' => $cartItems,
            'shippingCharges' => $shippingCharges,
            'districts' => $districts,
            'customer' => $customer,
            'subtotal' => Cart::instance('shopping')->subtotal(),
            'shippingCost' => session('shipping', 0),
            'total' => Cart::instance('shopping')->total() + session('shipping', 0),
            'currentPath' => '/checkout',
        ]);
    }

    /**
     * Store order
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email',
                'customer_phone' => 'required|string|max:20',
                'customer_address' => 'required|string',
                'district' => 'required|string',
                'area' => 'nullable|string',
                'payment_method' => 'required|string',
                'notes' => 'nullable|string',
                'items' => 'nullable|array',
                'items.*.product_id' => 'required_with:items|integer',
                'items.*.name' => 'required_with:items|string',
                'items.*.price' => 'required_with:items|numeric',
                'items.*.quantity' => 'required_with:items|integer|min:1',
            ]);

            // Check server cart first
            $cartItems = Cart::instance('shopping')->content();
            $useLocalCart = $cartItems->isEmpty() && !empty($validated['items']);

            if ($cartItems->isEmpty() && empty($validated['items'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty',
                ], 422);
            }

            // Calculate total
            if ($useLocalCart) {
                $subtotal = collect($validated['items'])->sum(fn($item) => $item['price'] * $item['quantity']);
            } else {
                $subtotal = floatval(str_replace(',', '', Cart::instance('shopping')->total()));
            }
            $shippingCost = $subtotal >= 1000 ? 0 : 60;
            $total = $subtotal + $shippingCost;

            // Create order
            $order = new Order();
            $order->invoice_id = 'INV-' . time();
            $order->customer_id = Auth::guard('customer')->id() ?? null;
            $order->customer_name = $validated['customer_name'];
            $order->customer_email = $validated['customer_email'];
            $order->customer_phone = $validated['customer_phone'];
            $order->customer_address = $validated['customer_address'];
            $order->district = $validated['district'];
            $order->area = $validated['area'] ?? $validated['district'];
            $order->amount = $total;
            $order->order_status = 'pending';
            $order->notes = $validated['notes'] ?? null;
            $order->save();

            // Create order details
            if ($useLocalCart) {
                // Use items from frontend local cart
                foreach ($validated['items'] as $item) {
                    $orderDetail = new OrderDetails();
                    $orderDetail->order_id = $order->id;
                    $orderDetail->product_id = $item['product_id'];
                    $orderDetail->product_name = $item['name'];
                    $orderDetail->sale_price = $item['price'];
                    $orderDetail->qty = $item['quantity'];
                    $orderDetail->save();
                }
            } else {
                // Use server cart items
                foreach ($cartItems as $item) {
                    $orderDetail = new OrderDetails();
                    $orderDetail->order_id = $order->id;
                    $orderDetail->product_id = $item->id;
                    $orderDetail->product_name = $item->name;
                    $orderDetail->sale_price = $item->price;
                    $orderDetail->qty = $item->qty;
                    $orderDetail->save();
                }
            }

            // Create payment record
            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->customer_id = Auth::guard('customer')->id() ?? null;
            $payment->payment_method = $validated['payment_method'];
            $payment->amount = $order->amount;
            $payment->payment_status = 'pending';
            $payment->save();

            // Clear server cart
            Cart::instance('shopping')->destroy();
            session(['shipping' => 0]);

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order_id' => $order->id,
                'invoice_id' => $order->invoice_id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get areas by district
     */
    public function getAreas(Request $request)
    {
        try {
            $areas = District::where('district', $request->district)
                ->select('id', 'area_name')
                ->get();

            return response()->json([
                'success' => true,
                'areas' => $areas,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
