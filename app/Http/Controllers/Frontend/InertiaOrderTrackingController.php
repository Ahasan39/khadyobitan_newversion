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
            ->with('details')
            ->firstOrFail();

        return Inertia::render('OrderConfirmation', [
            'order' => $order,
            'currentPath' => "/order-confirmation/{$invoiceId}",
        ]);
    }
}
