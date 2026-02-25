<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Brian2694\Toastr\Facades\Toastr;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Customer;
use App\Models\OrderStatus;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Shipping;
use App\Models\ShippingCharge;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Courierapi;
use App\Models\Expense;
use App\Models\ExpenseCategories;
use App\Models\ProductVariable;
use App\Models\PurchaseDetails;
use App\Models\District;
use Carbon\Carbon;
use App\Events\OrderStatusChanged;
use Illuminate\Support\Facades\Log;


class OrderController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:order-list|order-create|order-edit|order-delete', ['only' => ['index', 'order_store', 'order_edit']]);
        $this->middleware('permission:order-create', ['only' => ['order_store', 'order_create']]);
        $this->middleware('permission:order-edit', ['only' => ['order_edit', 'order_update']]);
        $this->middleware('permission:order-delete', ['only' => ['destroy']]);
        $this->middleware('permission:order-invoice', ['only' => ['invoice']]);
        $this->middleware('permission:order-process', ['only' => ['process', 'order_process']]);
        $this->middleware('permission:order-process', ['only' => ['process']]);
    }

    /**
     * Get Pathao cities and stores safely
     */
    private function getPathaoData($pathao_info)
    {
        if (!$pathao_info) {
            return ['cities' => [], 'stores' => []];
        }

        try {
            $response = Http::withOptions(['verify' => false])
                ->timeout(10)
                ->get($pathao_info->url . '/api/v1/countries/1/city-list');
            $pathaocities = $response->successful() ? $response->json() : [];
            
            $response2 = Http::withOptions(['verify' => false])
                ->timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $pathao_info->token,
                    'Content-Type' => 'application/json',
                ])->get($pathao_info->url . '/api/v1/stores');
            $pathaostore = $response2->successful() ? $response2->json() : [];
            
            return ['cities' => $pathaocities, 'stores' => $pathaostore];
        } catch (\Exception $e) {
            \Log::warning('Pathao API Error: ' . $e->getMessage());
            return ['cities' => [], 'stores' => []];
        }
    }
  public function search(Request $request)
{
    try {
        $qty = 1;
        $keyword = $request->keyword;

        if (is_numeric($keyword)) {
            // Search for the product or product variable by barcode
            $product = Product::where('pro_barcode', $keyword)->with('image')->first();
            $var_product = !$product ? ProductVariable::where('pro_barcode', $keyword)->with('product.image')->first() : null;

            if ($product || $var_product) {
                $product_data = $product ?: $var_product->product;
                $purchase_price = $product ? $product->purchase_price : $var_product->purchase_price;
                $old_price = $product ? $product->old_price : $var_product->old_price;
                $new_price = $product ? $product->new_price : $var_product->new_price;
                $stock = $product ? $product->stock : $var_product->stock;
                $product_id = $product ? $product->id : $var_product->product_id;
                $product_name = $product ? $product->name : $var_product->product->name;
                $product_slug = $product ? $product->slug : $var_product->product->slug;
                $product_image = $product ? $product->image->image : $var_product->image;
                $product_type = $product ? $product->type : $var_product->product->type;
                $product_size = $var_product->size ?? null;
                $product_color = $var_product->color ?? null;

                $cartitem = Cart::instance('sale')->content()->where('id', $product_id)->first();
                $cart_qty = $cartitem ? $cartitem->qty + $qty : $qty;

                if ($stock < $cart_qty) {
                    Toastr::error('Product stock limit over', 'Failed!');
                    return response()->json(['status' => 'limitover', 'message' => 'Your stock limit is over']);
                }

                $cartinfo = Cart::instance('sale')->add([
                    'id' => $product_id,
                    'name' => $product_name,
                    'qty' => $qty,
                    'price' => $new_price,
                    'options' => [
                        'slug' => $product_slug,
                        'image' => $product_image,
                        'old_price' => $old_price,
                        'purchase_price' => $purchase_price,
                        'product_size' => $product_size,
                        'product_color' => $product_color,
                        'type' => $product_type
                    ],
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Your Product Added'
                ]);
            } else {
                return response()->json([
                    'status' => 'notfound',
                    'message' => 'Product not found'
                ]);
            }
        } else {
            // If not numeric, search for products by name or barcode
            $products = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'stock')
                ->with('image', 'variables');

            if ($keyword) {
                $products = $products->where('name', 'LIKE', '%' . $keyword . "%")
                    ->orWhere('pro_barcode', 'LIKE', '%' . $keyword . "%");
            }

            $products = $products->get();
            if (empty($request->keyword)) {
                $products = [];
            }
            return view('backEnd.order.search', compact('products'));
        }
    } catch (\Exception $e) {
        // Catch any unexpected error and log it
        \Log::error('Search Error: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => 'Something went wrong.']);
    }
}


public function index($slug, Request $request)
{
    try {
        if ($slug == 'all') {
            $order_status = (object) [
                'name' => 'All',
                'orders_count' => Order::count(),
            ];
            $show_data = Order::orderBy('id','desc')->with('shipping', 'status');
            if ($request->keyword) {
                $show_data = $show_data->where(function ($query) use ($request) {
                    $query->orWhere('invoice_id', 'LIKE', '%' . $request->keyword . '%')
                        ->orWhereHas('shipping', function ($subQuery) use ($request) {
                            $subQuery->where('phone', $request->keyword);
                        });
                });
            }
            $show_data = $show_data->paginate(50);
        } else {
            $order_status = OrderStatus::where('slug', $slug)->withCount('orders')->first();
            if (!$order_status) {
                throw new \Exception("Order status not found");
            }

            if ($slug == 'pending') {
                Order::where(['order_status' => $order_status->id])
                    ->where('is_seen', false)
                    ->update(['is_seen' => true]);
            }

            $show_data = Order::where(['order_status' => $order_status->id])
                ->orderBy('id','desc')
                ->with('shipping', 'status')
                ->paginate(50);
        }

        $users = User::get();
        $steadfast = Courierapi::where(['status' => 1, 'type' => 'steadfast'])->first();
        $pathao_info = Courierapi::where(['status' => 1, 'type' => 'pathao'])->select('id', 'type', 'url', 'token', 'status')->first();

        $pathaoData = $this->getPathaoData($pathao_info);
        $pathaocities = $pathaoData['cities'];
        $pathaostore = $pathaoData['stores'];

        return view('backEnd.order.index', compact('show_data', 'order_status', 'users', 'steadfast', 'pathaostore', 'pathaocities'));
    } catch (\Exception $e) {
        \Log::error('Index Error: ' . $e->getMessage());
        Toastr::error('Something went wrong', 'Error');
        return redirect()->back();
    }
}


public function extraorders($slug, Request $request)
{
    try {
        if ($slug == 'all') {
            $order_status = (object) [
                'name' => 'All',
                'orders_count' => Order::where('order_stype', '>', 0)->count(),
            ];

            $show_data = Order::where('order_stype', '>', 0)
                ->orderBy('id','desc')
                ->with('shipping', 'status');

            if ($request->keyword) {
                $show_data = $show_data->where(function ($query) use ($request) {
                    $query->orWhere('invoice_id', 'LIKE', '%' . $request->keyword . '%')
                        ->orWhereHas('shipping', function ($subQuery) use ($request) {
                            $subQuery->where('phone', $request->keyword);
                        });
                });
            }
            $show_data = $show_data->paginate(50);
        } else {
            $order_status = OrderStatus::where('slug', $slug)->withCount('orders')->first();
            if (!$order_status) {
                throw new \Exception("Order status not found");
            }

            $show_data = Order::where(['order_status' => $order_status->id])
                ->where('order_stype', '>', 0)
                ->orderBy('id','desc')
                ->with('shipping', 'status')
                ->paginate(50);
        }

        $users = User::get();
        $steadfast = Courierapi::where(['status' => 1, 'type' => 'steadfast'])->first();
        $pathao_info = Courierapi::where(['status' => 1, 'type' => 'pathao'])->select('id', 'type', 'url', 'token', 'status')->first();

        if ($pathao_info) {
            $response = Http::get($pathao_info->url . '/api/v1/countries/1/city-list');
            $pathaocities = $response->json();
            $response2 = Http::withHeaders([
                'Authorization' => 'Bearer ' . $pathao_info->token,
                'Content-Type' => 'application/json',
            ])->get($pathao_info->url . '/api/v1/stores');
            $pathaostore = $response2->json();
        } else {
            $pathaocities = [];
            $pathaostore = [];
        }

        return view('backEnd.extraorder.index', compact('show_data', 'order_status', 'users', 'steadfast', 'pathaostore', 'pathaocities'));
    } catch (\Exception $e) {
        \Log::error('Extra Orders Error: ' . $e->getMessage());
        Toastr::error('Something went wrong', 'Error');
        return redirect()->back();
    }
}


// Example for pathaocity method
public function pathaocity(Request $request)
{
    try {
        $pathao_info = Courierapi::where(['status' => 1, 'type' => 'pathao'])->select('id', 'type', 'url', 'token', 'status')->first();
        if ($pathao_info) {
            $response = Http::get($pathao_info->url . '/api/v1/cities/' . $request->city_id . '/zone-list');
            return response()->json($response->json());
        } else {
            return response()->json([]);
        }
    } catch (\Exception $e) {
        \Log::error('Pathao City Error: ' . $e->getMessage());
        return response()->json([]);
    }
}


    
    public function pathaozone(Request $request)
    {
        try {
        $pathao_info = Courierapi::where(['status' => 1, 'type' => 'pathao'])->select('id', 'type', 'url', 'token', 'status')->first();
        if ($pathao_info) {
            $response = Http::get($pathao_info->url . '/api/v1/zones/' . $request->zone_id . '/area-list');
            $pathaoareas = $response->json();
            return response()->json($pathaoareas);
        } else {
            return response()->json([]);
        }
        } catch (\Exception $e) {
        \Log::error('Pathao Zone Error: ' . $e->getMessage());
        return response()->json([]);
    }
    }

public function order_pathao(Request $request)
{
    try {
        $order = Order::with('shipping')->find($request->id);

        // Check if order exists
        if (!$order) {
            Toastr::error('Order not found', 'Error');
            return redirect()->back();
        }

        $order_count = OrderDetails::where('order_id', $order->id)->count();

        // Get Pathao API info
        $pathao_info = Courierapi::where(['status' => 1, 'type' => 'pathao'])
            ->select('id', 'type', 'url', 'token', 'status')
            ->first();

        if (!$pathao_info) {
            Toastr::error('Pathao API not configured', 'Error');
            return redirect()->back();
        }

        // Prepare and send the request to Pathao
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $pathao_info->token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post($pathao_info->url . '/api/v1/orders', [
            'store_id' => $request->pathaostore,
            'merchant_order_id' => $order->invoice_id,
            'sender_name' => 'Test',
            'sender_phone' => $order->shipping ? $order->shipping->phone : '',
            'recipient_name' => $order->shipping ? $order->shipping->name : '',
            'recipient_phone' => $order->shipping ? $order->shipping->phone : '',
            'recipient_address' => $order->shipping ? $order->shipping->address : '',
            'recipient_city' => $request->pathaocity,
            'recipient_zone' => $request->pathaozone,
            'recipient_area' => $request->pathaoarea,
            'delivery_type' => 48,
            'item_type' => 2,
            'special_instruction' => 'Special note- product must be check after delivery',
            'item_quantity' => $order_count,
            'item_weight' => 0.5,
            'amount_to_collect' => round($order->amount),
            'item_description' => 'Special note- product must be check after delivery',
        ]);

        // Check if the response is successful
        if ($response->successful()) {
            $order->order_status = 5; // Update order status
            $order->save();

            Toastr::success('Order sent to Pathao successfully');
            return redirect()->back();
        } else {
            // Handle API failure
            $message = $response->json()['message'] ?? 'Courier order failed';
            Toastr::error($message, 'Courier Order Failed');
            return redirect()->back();
        }
    } catch (\Exception $e) {
        // Catch unexpected errors
        \Log::error('Pathao Order Error: ' . $e->getMessage());
        Toastr::error('Something went wrong while sending order to Pathao', 'Error');
        return redirect()->back();
    }
}


    public function invoice($invoice_id)
    {
        try{
            
        $order = Order::where(['invoice_id' => $invoice_id])->with('orderdetails', 'payment', 'shipping', 'customer')->firstOrFail();
        return view('backEnd.order.invoice', compact('order'));
        }catch(\Exception $e) {
              \Log::error('Invoice Error: ' . $e->getMessage());
        Toastr::error('Something went wrong while fetching the invoice', 'Error');
        return redirect()->back();
        }
    }

    public function process($invoice_id)
    {
        try{
            $data = Order::where(['invoice_id' => $invoice_id])->select('id', 'invoice_id', 'order_status', 'order_type')->with('orderdetails', 'shipping')->first();
        $shippingcharge = Shippingcharge::get();
        return view('backEnd.order.process', compact('data', 'shippingcharge'));
        }catch(\Exception $e) {
              \Log::error('Process Error: ' . $e->getMessage());
        Toastr::error('Something went wrong while fetching the invoice', 'Error');
        return redirect()->back();
        }
        
    }

    public function order_process(Request $request)
    {

    try{
         $link = OrderStatus::find($request->status)->slug;
        $order = Order::with('payment')->find($request->id);
        $order_status = $order->order_status;
        $order->order_status = $request->status;
        $order->admin_note = $request->admin_note;
        $order->save();

        $shipping_update = Shipping::where('order_id', $order->id)->first();
        $shipping_update->name = $request->name;
        $shipping_update->phone = $request->phone;
        $shipping_update->address = $request->address;
        // $shipping_update->district = $request->district;
        $shipping_update->area = $request->area;


     if ($request->status == 2 && $order_status != 2) {

    $orders_details = OrderDetails::where('order_id', $order->id)->get();

    foreach ($orders_details as $order_detail) {

        if ($order_detail->product_type == 1) {

            $product = Product::find($order_detail->product_id);

            if ($product) {
                $product->stock = $product->stock - $order_detail->qty;
                $product->save();
            } else {
                \Log::error("Product not found for simple product ID: " . $order_detail->product_id);
            }

        } else {

            $product = ProductVariable::where([
                'product_id' => $order_detail->product_id,
                'color'      => $order_detail->product_color,
                'size'       => $order_detail->product_size
            ])->first();

            if ($product) {
                $product->stock = $product->stock - $order_detail->qty;
                $product->save();
            } else {
                \Log::error("Variant not found for product ID: " . $order_detail->product_id);
            }
        }
    }
}



        Toastr::success('Success', 'Order status change successfully');
        return redirect('admin/order/' . $link);
        
        
    }catch(\Exception $e) {
              \Log::error('Order status: ' . $e->getMessage());
        Toastr::error('Something went wrong while fetching the invoice', 'Error');
        return redirect()->back();
        }
       
    }

    public function order_return(Request $request)
    {
        
        try{
             $order_detail = OrderDetails::select('id', 'order_id', 'product_id', 'sale_price', 'qty', 'is_return')->where('id', $request->id)->first();
        $order_detail->is_return = 1;
        $order_detail->save();

        $order = Order::find($order_detail->order_id);
        if ($order->order_status == 5) {
            $order->amount -= $order_detail->sale_price;
            $order->save();
        }

        if ($order->order_status == 5) {
            $product = Product::select('id', 'name', 'stock')->find($order_detail->product_id);
            $product->stock += $order_detail->qty;
            $product->save();
        }

        Toastr::success('Success', 'Order item return successfully');
        return back();
        }catch(\Exception $e){
             \Log::error('Order status: ' . $e->getMessage());
        Toastr::error('Something went wrong while fetching the invoice', 'Error');
        return redirect()->back();
        }
       
    }

    public function destroy(Request $request)
    {
        $order = Order::where('id', $request->id)->delete();
        $order_details = OrderDetails::where('order_id', $request->id)->delete();
        $shipping = Shipping::where('order_id', $request->id)->delete();
        $payment = Payment::where('order_id', $request->id)->delete();
        Toastr::success('Success', 'Order delete success successfully');
        return redirect()->back();
    }

    public function order_assign(Request $request)
    {
        $products = Order::whereIn('id', $request->input('order_ids'))->update(['user_id' => $request->user_id]);
        return response()->json(['status' => 'success', 'message' => 'Order user id assign']);
    }

   public function order_status(Request $request)
{
    try {
        // Get orders before updating to capture old status
        $orders = Order::whereIn('id', $request->input('order_ids'))
            ->with(['orderdetails', 'shipping', 'payment'])
            ->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'No orders found'
            ], 404);
        }

        // Capture old status (assuming all orders have same status initially)
        $oldStatus = $orders->first()->order_status;

        // Update order status
        $updatedCount = Order::whereIn('id', $request->input('order_ids'))
            ->update(['order_status' => $request->order_status]);

        // Handle stock reduction for processing status (status 2)
        if ($request->order_status == 2) {
            foreach ($orders as $order) {
                $orders_details = OrderDetails::where('order_id', $order->id)->get();
                
                foreach ($orders_details as $order_detail) {
                    try {
                        if ($order_detail->product_type == 1) {
                            // Simple product
                            $product = Product::find($order_detail->product_id);
                            if ($product && $product->stock >= $order_detail->qty) {
                                $product->stock -= $order_detail->qty;
                                $product->save();
                            } else {
                                Log::warning('Insufficient stock for product', [
                                    'product_id' => $order_detail->product_id,
                                    'required' => $order_detail->qty,
                                    'available' => $product->stock ?? 0
                                ]);
                            }
                        } else {
                            // Variable product
                            $product = ProductVariable::where([
                                'product_id' => $order_detail->product_id,
                                'color' => $order_detail->product_color,
                                'size' => $order_detail->product_size
                            ])->first();
                            
                            if ($product && $product->stock >= $order_detail->qty) {
                                $product->stock -= $order_detail->qty;
                                $product->save();
                            } else {
                                Log::warning('Insufficient stock for variable product', [
                                    'product_id' => $order_detail->product_id,
                                    'color' => $order_detail->product_color,
                                    'size' => $order_detail->product_size,
                                    'required' => $order_detail->qty,
                                    'available' => $product->stock ?? 0
                                ]);
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error('Error updating product stock', [
                            'order_id' => $order->id,
                            'product_id' => $order_detail->product_id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }
        }

        // Refresh orders to get updated data
        $updatedOrders = Order::whereIn('id', $request->input('order_ids'))
            ->with(['orderdetails', 'shipping', 'payment'])
            ->get();

        // Fire the event for webhook
        event(new OrderStatusChanged(
            $updatedOrders,
            $oldStatus,
            $request->order_status,
            auth()->user() // Current authenticated user
        ));

        return response()->json([
            'status' => 'success',
            'message' => 'Order status changed successfully',
            'updated_count' => $updatedCount,
            'old_status' => $oldStatus,
            'new_status' => $request->order_status
        ]);

    } catch (\Exception $e) {
        Log::error('Order status update failed', [
            'error' => $e->getMessage(),
            'order_ids' => $request->input('order_ids'),
            'new_status' => $request->order_status
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to update order status: ' . $e->getMessage()
        ], 500);
    }
}

 // Bulk delete orders
public function bulk_destroy(Request $request)
{
    try {
        $orders_id = $request->order_ids ?? [];
        if (empty($orders_id)) {
            return response()->json(['status' => 'failed', 'message' => 'No orders selected']);
        }

        foreach ($orders_id as $order_id) {
            Order::where('id', $order_id)->delete();
            OrderDetails::where('order_id', $order_id)->delete();
            Shipping::where('order_id', $order_id)->delete();
            Payment::where('order_id', $order_id)->delete();
        }

        return response()->json(['status' => 'success', 'message' => 'Orders deleted successfully']);
    } catch (\Exception $e) {
        \Log::error('Bulk Delete Error: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => 'Something went wrong while deleting orders']);
    }
}

// Print selected orders
public function order_print(Request $request)
{
    try {
        $orders = Order::whereIn('id', $request->input('order_ids', []))
            ->with('orderdetails', 'payment', 'shipping', 'customer')
            ->get();

        $view = view('backEnd.order.print', ['orders' => $orders])->render();
        return response()->json(['status' => 'success', 'view' => $view]);
    } catch (\Exception $e) {
        \Log::error('Order Print Error: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => 'Something went wrong while printing orders']);
    }
}

// Bulk courier orders
public function bulk_courier($slug, Request $request)
{
    try {
        // Get courier API configuration
        $courier_info = Courierapi::where('type', $slug)->first();
        
        if (!$courier_info) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Courier API not configured for ' . ucfirst($slug)
            ]);
        }

        // Check if API is active (if status column exists)
        if (isset($courier_info->status) && $courier_info->status != 1) {
            return response()->json([
                'status' => 'error', 
                'message' => ucfirst($slug) . ' courier API is not active'
            ]);
        }

        // Get order IDs from request
        $orders_id = $request->order_ids ?? [];
        
        if (empty($orders_id)) {
            return response()->json([
                'status' => 'error', 
                'message' => 'No orders selected'
            ]);
        }

        $success_count = 0;
        $failed_count = 0;
        $errors = [];

        foreach ($orders_id as $order_id) {
            try {
                $order = Order::with('shipping')->find($order_id);
                
                if (!$order) {
                    $failed_count++;
                    $errors[] = "Order ID {$order_id} not found";
                    continue;
                }

                // Check if order already sent to courier
                if ($order->courier_tracker) {
                    $failed_count++;
                    $errors[] = "Order {$order->invoice_id} already sent to courier";
                    continue;
                }

                // Check if order has shipping info
                if (!$order->shipping) {
                    $failed_count++;
                    $errors[] = "Order {$order->invoice_id} has no shipping information";
                    continue;
                }

                // Prepare consignment data for Steadfast
                $consignmentData = [
                    'invoice' => $order->invoice_id,
                    'recipient_name' => $order->shipping->name ?? 'N/A',
                    'recipient_phone' => $order->shipping->phone ?? '',
                    'recipient_address' => $order->shipping->address ?? '',
                    'cod_amount' => (int) $order->amount,
                ];

                // Send request to Steadfast API
                $client = new Client();
                $response = $client->post($courier_info->url, [
                    'json' => $consignmentData,
                    'headers' => [
                        'Api-Key' => $courier_info->api_key,
                        'Secret-Key' => $courier_info->secret_key,
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ],
                    'timeout' => 30,
                ]);

                $responseData = json_decode($response->getBody(), true);
                
                // Check if API request was successful
                if (isset($responseData['status']) && $responseData['status'] == 200) {
                    // Update order with courier information
                    $order->order_status = 5; // In Courier status
                    $order->courier = $slug;
                    $order->courier_tracker = $responseData['consignment']['consignment_id'] ?? null;
                    $order->save();
                    
                    $success_count++;
                    
                    \Log::info("Order {$order->invoice_id} sent to {$slug} successfully", [
                        'tracking_id' => $order->courier_tracker
                    ]);
                } else {
                    $failed_count++;
                    $error_message = $responseData['message'] ?? 'Unknown error from courier API';
                    $errors[] = "Order {$order->invoice_id}: {$error_message}";
                    
                    \Log::error("Failed to send order {$order->invoice_id} to {$slug}", [
                        'response' => $responseData
                    ]);
                }

            } catch (\GuzzleHttp\Exception\RequestException $e) {
                $failed_count++;
                $error_msg = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
                $errors[] = "Order {$order->invoice_id}: API request failed - " . $error_msg;
                
                \Log::error("Courier API request failed for order {$order->invoice_id}", [
                    'error' => $error_msg
                ]);
            } catch (\Exception $e) {
                $failed_count++;
                $errors[] = "Order {$order->invoice_id}: " . $e->getMessage();
                
                \Log::error("Error processing order {$order->invoice_id}", [
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Prepare response message
        $message = "{$success_count} order(s) sent to " . ucfirst($slug) . " successfully";
        if ($failed_count > 0) {
            $message .= ". {$failed_count} order(s) failed.";
        }

        return response()->json([
            'status' => $success_count > 0 ? 'success' : 'error',
            'message' => $message,
            'success_count' => $success_count,
            'failed_count' => $failed_count,
            'errors' => $errors
        ]);

    } catch (\Exception $e) {
        \Log::error('Bulk Courier Error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'status' => 'error', 
            'message' => 'Something went wrong while sending orders to courier: ' . $e->getMessage()
        ]);
    }
}

// Auto update courier status (Steadfast)
public function auto_status_update()
{
    try {
        $orders = Order::select('id','courier_tracker','courier','order_status')
            ->whereNotNull('courier_tracker')
            ->where('courier', 'steadfast')
            ->whereIn('order_status', [3,4,5,7,9])
            ->get();

        if ($orders->isEmpty()) {
            Toastr::info('No orders to update', 'Info');
            return back();
        }

        $courier_info = Courierapi::where(['status' => 1, 'type' => 'steadfast'])->first();
        if (!$courier_info) throw new \Exception('Courier API not found');

        $responses = Http::pool(function ($pool) use ($orders, $courier_info) {
            $requests = [];
            foreach ($orders as $order) {
                $requests[] = $pool->withHeaders([
                    'Api-Key' => $courier_info->api_key,
                    'Secret-Key' => $courier_info->secret_key,
                    'Accept' => 'application/json',
                ])->get('https://portal.packzy.com/api/v1/status_by_cid/' . $order->courier_tracker);
            }
            return $requests;
        });

        foreach ($orders as $index => $order) {
            $responseData = $responses[$index]->json();
            if (($responseData['status'] ?? 0) == 200) {
                $courier_status = $responseData['delivery_status'] ?? 'unknown';
                if ($courier_status !== "unknown") {
                    $orderstatus = Orderstatus::where('slug', $courier_status)->first();
                    if ($orderstatus) {
                        if ($orderstatus->id == 6) {
                            $orderDetails = OrderDetails::where('order_id', $order->id)->get();
                            foreach ($orderDetails as $detail) {
                                if ($detail->type == 1) {
                                    $product = Product::find($detail->product_id);
                                    if ($product) {
                                        $product->stock -= $detail->qty;
                                        $product->save();
                                    }
                                } else {
                                    $product = ProductVariable::where([
                                        'product_id' => $detail->product_id,
                                        'color' => $detail->product_color,
                                        'size' => $detail->product_size
                                    ])->first();
                                    if ($product) {
                                        $product->stock -= $detail->qty;
                                        $product->save();
                                    }
                                }
                            }
                        }
                        $order->order_status = $orderstatus->id;
                        $order->save();
                    }
                }
            }
        }

        Toastr::success('Courier parcel auto update successfully', 'Success!');
        return back();
    } catch (\Exception $e) {
        \Log::error('Auto Status Update Error: ' . $e->getMessage());
        Toastr::error('Something went wrong during courier status update', 'Error');
        return back();
    }
}

// Order creation page
public function order_create()
{
    try {
        $products = Product::select('id', 'name', 'new_price', 'type', 'status')->get();
        $cartinfo = Cart::instance('sale')->content();
        $shippingcharge = Shippingcharge::get();

        // Clear POS sessions
        Session::put('pos_shipping', null);
        Session::forget(['pos_discount', 'product_discount', 'cpaid', 'cdue']);

        return view('backEnd.order.create', compact('products', 'cartinfo', 'shippingcharge'));
    } catch (\Exception $e) {
        \Log::error('Order Create Error: ' . $e->getMessage());
        Toastr::error('Something went wrong while loading order creation page', 'Error');
        return back();
    }
}


 // Store a new order
public function order_store(Request $request)
    {
        // return $request->all();
        if ($request->guest_customer) {
            $this->validate($request, [
                'guest_customer' => 'required',
            ]);
            $customer = Customer::find($request->guest_customer);

            $area = ShippingCharge::where('pos', 1)->first();
            $name = $customer->name;
            $phone = $customer->phone;
            $address = $area->name;
            $area = $area->id;
        } else {
            $this->validate($request, [
                'name' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'area' => 'required',
            ]);
            $name = $request->name;
            $phone = $request->phone;
            $address = $request->address;
            $area = $request->area;
        }

        if (Cart::instance('sale')->count() <= 0) {
            Toastr::error('Your shopping empty', 'Failed!');
            return redirect()->back();
        }

        $subtotal = Cart::instance('sale')->subtotal();
        $subtotal = str_replace(',', '', $subtotal);
        $subtotal = str_replace('.00', '', $subtotal);
        $discount = Session::get('pos_discount') + Session::get('product_discount');

        $shipping_area = ShippingCharge::where('id', $area)->first();

        $exits_customer = Customer::where('phone', $phone)->select('phone', 'id')->first();
        if ($exits_customer) {
            $customer_id = $exits_customer->id;
        } else {
            $password = rand(111111, 999999);
            $store = new Customer();
            $store->name = $name;
            $store->slug = $name;
            $store->phone = $phone;
            $store->password = bcrypt($password);
            $store->verify = 1;
            $store->status = 'active';
            $store->save();
            $customer_id = $store->id;
        }

        $customer = Customer::find($customer_id);

        // order data save
        $order = new Order();
        $order->invoice_id = rand(11111, 99999);
        $order->amount = ($subtotal + $shipping_area->amount) - $discount;
        $order->discount = $discount ? $discount : 0;
        $order->shipping_charge = $shipping_area->amount;
        $order->customer_id = $customer_id;
        $order->order_status = 1;
        $order->user_id = Auth::user()->id;
        $order->note = $request->note;
        $order->save();

        // shipping data save
        $shipping = new Shipping();
        $shipping->order_id = $order->id;
        $shipping->customer_id = $customer_id;
        $shipping->name = $name;
        $shipping->phone = $phone;
        $shipping->address = $address;
        $shipping->area = $request->area ? $shipping_area->name : 'Free Shipping';
        $shipping->save();

        // payment data save
        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->customer_id = $customer_id;
        $payment->payment_method = 'Cash On Delivery';
        $payment->amount = $order->amount;
        $payment->payment_status = 'pending';
        $payment->save();

        // order details data save
        foreach (Cart::instance('sale')->content() as $cart) {
            // return $cart;
            $order_details = new OrderDetails();
            $order_details->order_id = $order->id;
            $order_details->product_id = $cart->id;
            $order_details->product_name = $cart->name;
            $order_details->purchase_price = $cart->options->purchase_price;
            $order_details->product_discount = $cart->options->product_discount;
            $order_details->sale_price = $cart->price;
            $order_details->product_color = $cart->options->product_color;
            $order_details->product_size = $cart->options->product_size;
            $order_details->product_type = $cart->options->type;
            $order_details->qty = $cart->qty;
            $order_details->save();
            // return  $order_details;
            if ($order_details->product_type == 1) {
                $product = Product::find($order_details->product_id);
                $product->stock -= $order_details->qty;
                $product->save();
            } else {
                $product = ProductVariable::where(['product_id' => $order_details->product_id])->orWhere('color', $order_details->product_color)->first();
                $product->stock -= $order_details->qty;
                $product->save();
            }
        }
        Cart::instance('sale')->destroy();
        Session::forget('sale');
        Session::forget('pos_discount');
        Session::forget('product_discount');
        Session::forget('cpaid');
        Session::forget('cdue');
        Toastr::success('Thanks, Your order place successfully', 'Success!');
        return redirect()->route('admin.orders', ['slug' => 'all']);
    }

// Add product to cart
public function cart_add(Request $request)
{
    try {
        $product = Product::with('image')->find($request->id);
        if (!$product) throw new \Exception('Product not found');

        $var_product = ProductVariable::where(['product_id' => $request->id, 'color' => $request->color, 'size' => $request->size])->first();

        if ($product->type == 0 && $var_product) {
            $purchase_price = $var_product->purchase_price;
            $old_price = $var_product->old_price;
            $new_price = $var_product->new_price;
            $stock = $var_product->stock;
        } else {
            $purchase_price = $product->purchase_price;
            $old_price = $product->old_price;
            $new_price = $product->new_price;
            $stock = $product->stock;
        }

        $qty = 1;
        $cartitem = Cart::instance('sale')->content()->where('id', $product->id)->first();
        $cart_qty = $cartitem ? $cartitem->qty + $qty : $qty;

        if ($stock < $cart_qty) {
            Toastr::error('Product stock limit exceeded', 'Failed!');
            return response()->json(['status' => 'limitover', 'message' => 'Your stock limit is over']);
        }

        $cartinfo = Cart::instance('sale')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $qty,
            'price' => $new_price,
            'weight' => 1,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image->image ?? '',
                'old_price' => $old_price,
                'purchase_price' => $purchase_price,
                'product_size' => $request->size,
                'product_color' => $request->color,
                'type' => $product->type,
            ],
        ]);

        return response()->json(['status' => 'success', 'cartinfo' => $cartinfo]);
    } catch (\Exception $e) {
        \Log::error('Cart Add Error: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => 'Something went wrong while adding product to cart']);
    }
}

// Get cart content
public function cart_content()
{
    try {
        $cartinfo = Cart::instance('sale')->content();
        return view('backEnd.order.cart_content', compact('cartinfo'));
    } catch (\Exception $e) {
        \Log::error('Cart Content Error: ' . $e->getMessage());
        Toastr::error('Failed to load cart content', 'Error');
        return back();
    }
}

// Get cart details including discount
public function cart_details()
{
    try {
        $cartinfo = Cart::instance('sale')->content();
        $discount = 0;
        foreach ($cartinfo as $cart) {
            $discount += $cart->options->product_discount ?? 0 * $cart->qty;
        }
        Session::put('product_discount', $discount);

        return view('backEnd.order.cart_details', compact('cartinfo'));
    } catch (\Exception $e) {
        \Log::error('Cart Details Error: ' . $e->getMessage());
        Toastr::error('Failed to load cart details', 'Error');
        return back();
    }
}

    public function cart_increment(Request $request)
    {
        $qty = $request->qty + 1;
        $cartinfo = Cart::instance('sale')->update($request->id, $qty);
        return response()->json($cartinfo);
    }
    public function cart_decrement(Request $request)
    {
        $qty = $request->qty - 1;
        $cartinfo = Cart::instance('sale')->update($request->id, $qty);
        return response()->json($cartinfo);
    }
    public function cart_remove(Request $request)
    {
        $remove = Cart::instance('sale')->remove($request->id);
        $cartinfo = Cart::instance('sale')->content();
        return response()->json($cartinfo);
    }
    public function product_discount(Request $request)
    {
        $discount = $request->discount;
        $cart = Cart::instance('sale')->content()->where('rowId', $request->id)->first();
        $cartinfo = Cart::instance('sale')->update($request->id, [
            'options' => [
                'slug' => $cart->slug,
                'image' => $cart->options->image,
                'purchase_price' => $cart->options->old_price,
                'purchase_price' => $cart->options->purchase_price,
                'product_size' => $cart->options->size,
                'product_color' => $cart->options->color,
                'product_discount' => $request->discount,
                'type' => $cart->options->type,
                'details_id' => $cart->options->details_id
            ],
        ]);
        return response()->json($cartinfo);
    }
    public function cart_shipping(Request $request)
    {
        $shipping = ShippingCharge::where(['status' => 1, 'id' => $request->id])->first()->amount;
        Session::put('pos_shipping', $shipping);
        return response()->json($shipping);
    }

    public function cart_clear(Request $request)
    {
        $cartinfo = Cart::instance('sale')->destroy();
        Session::forget('pos_shipping');
        Session::forget('pos_discount');
        Session::forget('product_discount');
        Toastr::error('Your shopping empty', 'Failed!');
        return redirect()->back();
    }

 // Edit order and load cart
public function order_edit($invoice_id)
{
    try {
        $order = Order::with(['orderdetails.image', 'shipping'])
            ->where('invoice_id', $invoice_id)
            ->firstOrFail();

        $products = Product::select('id', 'name', 'new_price')
            ->where('status', 1)->get();

        $shippingcharge = Shippingcharge::get();

        // Clear existing cart
        Cart::instance('sale')->destroy();

        // Set session values for discounts and shipping
        Session::put('product_discount', $order->discount);
        Session::put('pos_shipping', $order->shipping_charge);
        Session::put('pos_discount', $order->web_discount);

        // Add order details to cart
        foreach ($order->orderdetails as $detail) {
            Cart::instance('sale')->add([
                'id' => $detail->product_id,
                'name' => $detail->product_name,
                'qty' => $detail->qty,
                'price' => $detail->sale_price,
                'weight' => $detail->weight ?? 1,
                'options' => [
                    'image' => $detail->image->image ?? '',
                    'purchase_price' => $detail->purchase_price,
                    'product_discount' => $detail->product_discount,
                    'product_size' => $detail->product_size,
                    'product_color' => $detail->product_color,
                    'details_id' => $detail->id,
                    'type' => $detail->product_type,
                ],
            ]);
        }

        $cartinfo = Cart::instance('sale')->content();
        $shippinginfo = $order->shipping;

        return view('backEnd.order.edit', compact(
            'products', 'cartinfo', 'shippingcharge', 'shippinginfo', 'order'
        ));

    } catch (\Exception $e) {
        \Log::error('Order Edit Error: ' . $e->getMessage());
        Toastr::error('Failed to load order for editing', 'Error');
        return redirect()->back();
    }
}

// Update order after editing
public function order_update(Request $request)
{
    try {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
        ]);

        if (Cart::instance('sale')->count() <= 0) {
            Toastr::error('Your shopping cart is empty', 'Failed!');
            return redirect()->back();
        }

        $subtotal = str_replace([',', '.00'], '', Cart::instance('sale')->subtotal());
        $discount = (Session::get('pos_discount') ?? 0) + (Session::get('product_discount') ?? 0);

        $shipping_area = Shippingcharge::find($request->area);
        if (!$shipping_area) throw new \Exception('Shipping area not found');

        // Find or create customer
        $existing_customer = Customer::where('phone', $request->phone)->first();
        if ($existing_customer) {
            $customer_id = $existing_customer->id;
        } else {
            $password = rand(111111, 999999);
            $customer = Customer::create([
                'name' => $request->name,
                'slug' => $request->name,
                'phone' => $request->phone,
                'password' => bcrypt($password),
                'verify' => 1,
                'status' => 'active',
            ]);
            $customer_id = $customer->id;
        }

        $order = Order::findOrFail($request->order_id);
        $order->update([
            'amount' => ($subtotal + $shipping_area->amount) - $discount,
            'discount' => $discount,
            'shipping_charge' => $shipping_area->amount,
            'customer_id' => $customer_id,
            'note' => $request->note,
        ]);

        // Update shipping
        $shipping = Shipping::where('order_id', $order->id)->first();
        if ($shipping) {
            $shipping->update([
                'customer_id' => $customer_id,
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'area' => $request->area ? $shipping_area->name : 'Free Shipping',
            ]);
        }

        // Update payment
        $payment = Payment::where('order_id', $order->id)->first();
        if ($payment) {
            $payment->update([
                'customer_id' => $customer_id,
                'amount' => $order->amount,
            ]);
        }

        // Remove deleted products from order details
        foreach ($order->orderdetails as $detail) {
            $cart_item = Cart::instance('sale')->content()->where('id', $detail->product_id)->first();
            if (!$cart_item) {
                $detail->delete();
            }
        }

        // Update or create order details
        foreach (Cart::instance('sale')->content() as $cart) {
            $order_detail = OrderDetails::find($cart->options->details_id ?? 0);
            if ($order_detail) {
                $order_detail->update([
                    'sale_price' => $cart->price,
                    'qty' => $cart->qty,
                    'product_discount' => $cart->options->product_discount ?? 0,
                ]);
            } else {
                OrderDetails::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->id,
                    'product_name' => $cart->name,
                    'purchase_price' => $cart->options->purchase_price ?? 0,
                    'sale_price' => $cart->price,
                    'qty' => $cart->qty,
                    'product_discount' => $cart->options->product_discount ?? 0,
                ]);
            }
        }

        // Clear cart and sessions
        Cart::instance('sale')->destroy();
        Session::forget(['pos_shipping', 'pos_discount', 'product_discount', 'cpaid']);

        Toastr::success('Order updated successfully', 'Success!');
        return redirect('admin/order/pending');

    } catch (\Exception $e) {
        \Log::error('Order Update Error: ' . $e->getMessage());
        Toastr::error('Failed to update order', 'Error');
        return redirect()->back();
    }
}


    public function purchase_report()
    {
        $purchase = PurchaseDetails::with('product')->orderBy('id','desc')->paginate(100);
        return view('backEnd.reports.purchase', compact('purchase'));
    }
    public function order_report(Request $request)
    {
        $users = User::where('status', 1)->get();
        $orders = OrderDetails::with('shipping', 'order')->whereHas('order', function ($query) {
            $query->where('order_status', 6);
        });
        if ($request->keyword) {
            $orders = $orders->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        if ($request->user_id) {
            $orders = $orders->whereHas('order', function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            });
        }
        if ($request->start_date && $request->end_date) {
            $orders = $orders->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
        $total_purchases = $orders->sum(DB::raw('purchase_price * qty'));
        $total_item = $orders->sum('qty');
        $total_sales = $orders->sum(DB::raw('sale_price * qty'));
        $orders = $orders->paginate(50);
        return view('backEnd.reports.order', compact('orders', 'users', 'total_purchases', 'total_item', 'total_sales'));
    }
    public function return_report(Request $request)
    {
        $orders = OrderDetails::where('is_return', '=', 1)->with('shipping', 'order')->whereHas('order', function ($query) {
            $query->where('order_status', 5);
        });
        if ($request->keyword) {
            $orders = $orders->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        if ($request->start_date && $request->end_date) {
            $orders = $orders->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
        $total_purchases = $orders->sum(DB::raw('purchase_price * qty'));
        $total_item = $orders->sum('qty');
        $total_sales = $orders->sum(DB::raw('sale_price * qty'));
        $orders = $orders->paginate(100);
        return view('backEnd.reports.return_report', compact('orders', 'total_purchases', 'total_item', 'total_sales'));
    }
    public function stock_report(Request $request)
    {
        $products = Product::select('id', 'name', 'new_price', 'purchase_price', 'stock', 'type')
            ->where('status', 1);
        if ($request->keyword) {
            $products = $products->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        if ($request->category_id) {
            $products = $products->where('category_id', $request->category_id);
        }
        if ($request->start_date && $request->end_date) {
            $products = $products->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
        $total_purchase = $products->sum(DB::raw('purchase_price * stock'));
        $total_stock = $products->sum('stock');
        $total_price = $products->sum(DB::raw('new_price * stock'));
        $products = $products->with('variables')->paginate(50);
        $categories = Category::where('status', 1)->get();
        return view('backEnd.reports.stock', compact('products', 'categories', 'total_purchase', 'total_stock', 'total_price'));
    }
    public function expense_report(Request $request)
    {
        $data = Expense::where('status', 1);
        if ($request->keyword) {
            $data = $data->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        if ($request->category_id) {
            $data = $data->where('expense_cat_id', $request->category_id);
        }
        if ($request->start_date && $request->end_date) {
            $data = $data->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
        $data = $data->paginate(10);
        $expensecategories = ExpenseCategories::where('status', 1)->get();
        // return $expensecategories;
        return view('backEnd.reports.expense', compact('data', 'expensecategories'));
    }
    public function loss_profit(Request $request)
    {
        if ($request->start_date && $request->end_date) {
            $total_expense = Expense::where('status', 1)->whereBetween('created_at', [$request->start_date, $request->end_date])->sum('amount');
            $total_purchase = OrderDetails::whereHas('order', function ($query) use ($request) {
                $query->where('order_status', 6)
                    ->whereBetween('created_at', [$request->start_date, $request->end_date]);
            })->sum(DB::raw('purchase_price * qty'));

            $total_sales = OrderDetails::whereHas('order', function ($query) use ($request) {
                $query->where('order_status', 6)
                    ->whereBetween('created_at', [$request->start_date, $request->end_date]);
            })->sum(DB::raw('sale_price * qty'));
        } else {
            $total_expense = Expense::where('status', 1)->sum('amount');
            $total_purchase = OrderDetails::whereHas('order', function ($query) {
                $query->where('order_status', 6);
            })->sum(DB::raw('purchase_price * qty'));

            $total_sales = OrderDetails::whereHas('order', function ($query) {
                $query->where('order_status', 6);
            })->sum(DB::raw('sale_price * qty'));
        }

        return view('backEnd.reports.loss_profit', compact('total_expense', 'total_purchase', 'total_sales'));
    }

    public function order_paid(Request $request)
    {
        $customer = Customer::select('id', 'phone', 'due')->where('phone', $request->phone)->first();
        if ($customer) {
            Session::put('cdue', $customer->due);
        }
        $amount = $request->amount;
        Session::put('cpaid', $amount);
        return response()->json($amount);
    }
    public function status_update(){
        Toastr::success('Demo testing this option desibled', 'Alert!');
        return redirect()->back();
    }
    public function fraud_checker(Request $request)
    {
        // $headers = [
        //     'email' => 'zadumia441@gmail.com',
        //     'api_key' => 'JQAGMXHUUNPAAA5W',
        // ];
        // $body = [
        //     'phone' => $shipping->phone,
        // ];
        // $response = Http::withHeaders($headers)
        //     ->post('https://fraudchecker.webleez.com/api/v1/fraud-checker', $body);
        // $result = $response->json();

        /*
         "total_parcel": 0,
            "success_parcel": 0,
            "cancelled_parcel": 0,
            "success_ratio": 0
         */

        $shipping = Shipping::where('order_id', $request->id)->first();
        $phone = $shipping->phone;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://bdcourier.com/api/courier-check?phone=" . urlencode($phone),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ZkEEfBAEBRxVkgcLpR3Z5e3sPHQ6dy0XViGTqYyg4clRjj06rRKmAs41Smp2'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);

        $result = $data;

        $name = $result['name'] ?? 'N/A';
        $phone = $shipping->phone ?? 'N/A';

        $steadfast_total = $result['courierData']['steadfast']['total_parcel'] ?? 0;
        $steadfast_success = $result['courierData']['steadfast']['success_parcel'] ?? 0;
        $steadfast_cancel = $result['courierData']['steadfast']['cancelled_parcel'] ?? 0;

        $pathao_total = $result['courierData']['pathao']['total_parcel'] ?? 0;
        $pathao_success = $result['courierData']['pathao']['success_parcel'] ?? 0;
        $pathao_cancel = $result['courierData']['pathao']['cancelled_parcel'] ?? 0;

        $redx_total = $result['courierData']['redx']['total_parcel'] ?? 0;
        $redx_success = $result['courierData']['redx']['success_parcel'] ?? 0;
        $redx_cancel = $result['courierData']['redx']['cancelled_parcel'] ?? 0;

        $paperfly_total = $result['courierData']['paperfly']['total_parcel'] ?? 0;
        $paperfly_success = $result['courierData']['paperfly']['success_parcel'] ?? 0;
        $paperfly_cancel = $result['courierData']['paperfly']['cancelled_parcel'] ?? 0;

        $total_parcel = $result['courierData']['summary']['total_parcel'] ?? 0;
        $total_success = $result['courierData']['summary']['success_parcel'] ?? 0;
        $total_cancel = $result['courierData']['summary']['cancelled_parcel'] ?? 0;
        $status = $result['status'] ?? 'N/A';

        return view('backEnd.order.fraud_checker', compact(
            'status',
            'name',
            'phone',
            'steadfast_total',
            'steadfast_success',
            'steadfast_cancel',
            'pathao_total',
            'pathao_success',
            'pathao_cancel',
            'redx_total',
            'redx_success',
            'redx_cancel',
            'paperfly_total',
            'paperfly_success',
            'paperfly_cancel',
            'total_parcel',
            'total_success',
            'total_cancel'
        ));
    }
}
