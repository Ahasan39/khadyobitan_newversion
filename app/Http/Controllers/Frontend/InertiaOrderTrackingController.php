<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Order;

class InertiaOrderTrackingController extends Controller
{
    /**
     * Display order tracking page
     */
    public function index()
    {
        return Inertia::render('OrderTracking', [
            'currentPath' => '/track-order',
        ]);
    }

    /**
     * Search for order
     */
    public function search(Request $request)
    {
        try {
            $validated = $request->validate([
                'invoice_id' => 'required|string',
                'email' => 'required|email',
            ]);

            $order = Order::where('invoice_id', $validated['invoice_id'])
                ->where('customer_email', $validated['email'])
                ->with('details')
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display order confirmation page
     */
    public function confirmation($invoiceId)
    {
        $order = Order::where('invoice_id', $invoiceId)
            ->with('orderdetails', 'payment', 'shipping', 'customer')
            ->first();

        if (!$order) {
            return redirect('/')->with('error', 'Order not found');
        }

        // Get shipping info (customer address) or fall back to customer model
        $shippingInfo = $order->shipping;
        $customerInfo = $order->customer;

        // Transform order to format expected by frontend
        $orderData = [
            'orderId' => $order->invoice_id,
            'items' => $order->orderdetails->map(function ($detail) {
                return [
                    'name' => $detail->product_name,
                    'slug' => '',
                    'price' => $detail->sale_price,
                    'quantity' => $detail->qty,
                    'weight' => $detail->weight ?? 'Default',
                ];
            })->toArray(),
            'form' => [
                'name' => $shippingInfo->name ?? $customerInfo->name ?? 'Guest',
                'phone' => $shippingInfo->phone ?? $customerInfo->phone ?? '',
                'email' => $customerInfo->email ?? '',
                'address' => $shippingInfo->address ?? $customerInfo->address ?? '',
                'district' => $shippingInfo->area ?? '',
                'notes' => '',
            ],
            'payment' => $order->payment->payment_method ?? 'cod',
            'subtotal' => $order->orderdetails->sum(fn ($d) => $d->sale_price * $d->qty),
            'shipping' => $order->shipping_charge ?? 0,
            'total' => $order->amount,
            'date' => $order->created_at->format('j F Y'),
        ];

        return Inertia::render('OrderConfirmation', [
            'order' => $orderData,
            'currentPath' => "/order-confirmation/{$invoiceId}",
        ]);
    }
}
