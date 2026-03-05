<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\Customer;

class InertiaAccountController extends Controller
{
    /**
     * Display customer account dashboard
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $orders = Order::where('customer_id', $customer->id)
            ->with('orderdetails', 'shipping')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Transform orders to format expected by frontend
        $transformedOrders = [
            'data' => $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_id' => $order->invoice_id,
                    'total' => $order->amount,
                    'status' => $order->order_status,
                    'created_at' => $order->created_at,
                    'order_details' => $order->orderdetails,
                ];
            }),
            'total' => $orders->total(),
        ];

        return Inertia::render('Account', [
            'customer' => $customer,
            'orders' => $transformedOrders,
            'currentPath' => '/account',
        ]);
    }

    /**
     * Display customer profile edit page
     */
    public function editProfile()
    {
        $customer = Auth::guard('customer')->user();

        return Inertia::render('ProfileEdit', [
            'customer' => $customer,
            'currentPath' => '/account/profile-edit',
        ]);
    }

    /**
     * Update customer profile
     */
    public function updateProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email,' . Auth::guard('customer')->id(),
                'phone' => 'required|string|max:20',
                'address' => 'nullable|string',
            ]);

            $customer = Auth::guard('customer')->user();
            $customer->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'customer' => $customer,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display change password page
     */
    public function changePassword()
    {
        return Inertia::render('ChangePassword', [
            'currentPath' => '/account/change-password',
        ]);
    }

    /**
     * Update customer password
     */
    public function updatePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'current_password' => 'required|string',
                'password' => 'required|string|min:6|confirmed',
            ]);

            $customer = Auth::guard('customer')->user();

            if (!Hash::check($validated['current_password'], $customer->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect',
                ], 422);
            }

            $customer->update([
                'password' => Hash::make($validated['password']),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display customer orders
     */
    public function orders()
    {
        $customer = Auth::guard('customer')->user();
        $orders = Order::where('customer_id', $customer->id)
            ->with('orderdetails', 'shipping')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Transform for frontend
        $transformedOrders = [
            'data' => $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_id' => $order->invoice_id,
                    'total' => $order->amount,
                    'status' => $order->order_status,
                    'created_at' => $order->created_at,
                    'order_details' => $order->orderdetails,
                ];
            }),
            'total' => $orders->total(),
            'current_page' => $orders->currentPage(),
            'last_page' => $orders->lastPage(),
        ];

        return Inertia::render('Orders', [
            'orders' => $transformedOrders,
            'currentPath' => '/account/orders',
        ]);
    }

    /**
     * Display single order details
     */
    public function orderDetail($id)
    {
        $customer = Auth::guard('customer')->user();
        $order = Order::where('id', $id)
            ->where('customer_id', $customer->id)
            ->with('orderdetails', 'shipping', 'payment')
            ->first();

        if (!$order) {
            return redirect()->route('account.orders')->with('error', 'Order not found.');
        }

        // Transform for frontend
        $transformedOrder = [
            'id' => $order->id,
            'order_id' => $order->invoice_id,
            'total' => $order->amount,
            'status' => $order->order_status,
            'shipping_charge' => $order->shipping_charge,
            'discount' => $order->discount,
            'created_at' => $order->created_at,
            'items' => $order->orderdetails->map(function ($detail) {
                return [
                    'name' => $detail->product_name,
                    'price' => $detail->sale_price,
                    'quantity' => $detail->qty,
                ];
            }),
            'shipping' => $order->shipping ? [
                'name' => $order->shipping->name,
                'phone' => $order->shipping->phone,
                'address' => $order->shipping->address,
            ] : null,
            'payment' => $order->payment ? [
                'method' => $order->payment->payment_method,
                'status' => $order->payment->payment_status,
            ] : null,
        ];

        return Inertia::render('OrderDetail', [
            'order' => $transformedOrder,
            'currentPath' => "/account/orders/{$id}",
        ]);
    }
}
