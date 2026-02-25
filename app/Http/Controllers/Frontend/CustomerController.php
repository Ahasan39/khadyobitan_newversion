<?php

namespace App\Http\Controllers\Frontend;

use shurjopayv2\ShurjopayLaravelPackage8\Http\Controllers\ShurjopayController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Customer;
use App\Models\District;
use App\Models\Order;
use App\Models\CheckoutContent;
use App\Models\ShippingCharge;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Shipping;
use App\Models\Review;
use App\Models\PaymentGateway;
use App\Models\SmsGateway;
use App\Models\GeneralSetting;
use App\Models\CouponCode;
use App\Models\OrderNotificationSetting;
use App\Models\UpdatePermission;
use Mail;
use App\Events\OrderPlaced;
use App\Jobs\SendOrderSms;
use App\Jobs\SendOrderNotifications;




class CustomerController extends Controller
{

    function __construct()
    {
        $this->middleware('customer', ['except' => ['register', 'customer_coupon', 'coupon_remove', 'store', 'verify', 'resendotp', 'account_verify', 'login', 'signin', 'logout', 'checkout', 'forgot_password', 'forgot_verify', 'forgot_reset', 'forgot_store', 'forgot_resend', 'order_save', 'order_save_draft', 'order_success', 'order_track', 'order_track_result']]);
    }
    public function customer_coupon(Request $request)
    {
        $findcoupon = CouponCode::where('coupon_code', $request->coupon_code)->first();
        if ($findcoupon == NULL) {
            Toastr::error('Opps! your entered promo code is not valid');
            return back();
        } else {
            $currentdata = date('Y-m-d');
            $expiry_date = $findcoupon->expiry_date;
            if ($currentdata <= $expiry_date) {
                $totalcart = Cart::instance('shopping')->subtotal();
                $totalcart = str_replace('.00', '', $totalcart);
                $totalcart = str_replace(',', '', $totalcart);
                if ($totalcart >= $findcoupon->buy_amount) {
                    if ($totalcart >= $findcoupon->buy_amount) {
                        if ($findcoupon->offer_type == 1) {
                            $discountammount = (($totalcart * $findcoupon->amount) / 100);
                            Session::forget('coupon_amount');
                            Session::put('coupon_amount', $discountammount);
                            Session::put('coupon_used', $findcoupon->coupon_code);
                        } else {
                            Session::put('coupon_amount', $findcoupon->amount);
                            Session::put('coupon_used', $findcoupon->coupon_code);
                        }
                        Toastr::success('Success! your promo code accepted');
                        return back();
                    }
                } else {
                    Toastr::error('You need to buy a minimum of ' . $findcoupon->buy_amount . ' Taka to get the offer');
                    return back();
                }
            } else {
                Toastr::error('Opps! Sorry your promo code date expaire');
                return back();
            }
        }
    }
    public function coupon_remove(Request $request)
    {
        Session::forget('coupon_amount');
        Session::forget('coupon_used');
        Session::forget('discount');
        Toastr::success('Success', 'Your coupon remove successfully');
        return back();
    }

    public function review(Request $request)
    {
        $this->validate($request, [
            'ratting' => 'required',
            'review' => 'required',
        ]);

        // data save
        $review = new Review();
        $review->name = Auth::guard('customer')->user()->name ? Auth::guard('customer')->user()->name : 'N / A';
        $review->email = Auth::guard('customer')->user()->email ? Auth::guard('customer')->user()->email : 'N / A';
        $review->product_id = $request->product_id;
        $review->review = $request->review;
        $review->ratting = $request->ratting;
        $review->customer_id = Auth::guard('customer')->user()->id;
        $review->status = 'pending';
        $review->save();

        Toastr::success('Thanks, Your review send successfully', 'Success!');
        return redirect()->back();
    }

    public function login()
    {
        return view('frontEnd.layouts.customer.login');
    }

    public function signin(Request $request)
    {
        $auth_check = Customer::where('phone', $request->phone)->first();
        if ($auth_check) {
            if (Auth::guard('customer')->attempt(['phone' => $request->phone, 'password' => $request->password])) {
                Toastr::success('You are login successfully', 'success!');
                if ($request->review == 1) {
                    return redirect()->back();
                }
                if (Cart::instance('shopping')->count() > 0) {
                    return redirect()->route('customer.checkout');
                }
                return redirect()->intended('customer/account');
            }
            Toastr::error('message', 'Opps! your phone or password wrong');
            return redirect()->back();
        } else {
            Toastr::error('message', 'Sorry! You have no account');
            return redirect()->back();
        }
    }

    public function register()
    {
        return view('frontEnd.layouts.customer.register');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|unique:customers',
            'password' => 'required|min:6'
        ]);

        $last_id = Customer::orderBy('id', 'desc')->first();
        $last_id = $last_id ? $last_id->id + 1 : 1;
        $store = new Customer();
        $store->name = $request->name;
        $store->slug = strtolower(Str::slug($request->name . '-' . $last_id));
        $store->phone = $request->phone;
        $store->email = $request->email;
        $store->password = bcrypt($request->password);
        $store->verify = 1;
        $store->status = 'active';
        $store->save();

        Toastr::success('Success', 'Account Create Successfully');
        return redirect()->route('customer.login');
    }
    public function verify()
    {
        return view('frontEnd.layouts.customer.verify');
    }
    public function resendotp(Request $request)
    {
        $customer_info = Customer::where('phone', session::get('verify_phone'))->first();
        $customer_info->verify = rand(1111, 9999);
        $customer_info->save();
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where('status', 1)->first();
        if ($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "number" => $customer_info->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "message" => "Dear $customer_info->name!\r\nYour account verify OTP is $customer_info->verify \r\nThank you for using $site_setting->name"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        }
        Toastr::success('Success', 'Resend code send successfully');
        return redirect()->back();
    }
    public function account_verify(Request $request)
    {
        $this->validate($request, [
            'otp' => 'required',
        ]);
        $customer_info = Customer::where('phone', session::get('verify_phone'))->first();
        if ($customer_info->verify != $request->otp) {
            Toastr::error('Success', 'Your OTP not match');
            return redirect()->back();
        }

        $customer_info->verify = 1;
        $customer_info->status = 'active';
        $customer_info->save();
        Auth::guard('customer')->loginUsingId($customer_info->id);
        return redirect()->route('customer.account');
    }
    public function forgot_password()
    {
        return view('frontEnd.layouts.customer.forgot_password');
    }

    public function forgot_verify(Request $request)
    {
        $customer_info = Customer::where('phone', $request->phone)->first();
        if (!$customer_info) {
            Toastr::error('Your phone number not found');
            return back();
        }
        $customer_info->forgot = rand(1111, 9999);
        $customer_info->save();
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where(['status' => 1, 'forget_pass' => 1])->first();
        if ($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "number" => $customer_info->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "message" => "Dear $customer_info->name!\r\nYour forgot password verify OTP is $customer_info->forgot \r\nThank you for using $site_setting->name"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        }

        session::put('verify_phone', $request->phone);
        Toastr::success('Your account register successfully');
        return redirect()->route('customer.forgot.reset');
    }

    public function forgot_resend(Request $request)
    {
        $customer_info = Customer::where('phone', session::get('verify_phone'))->first();
        $customer_info->forgot = rand(1111, 9999);
        $customer_info->save();
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where(['status' => 1])->first();
        if ($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "number" => $customer_info->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "message" => "Dear $customer_info->name!\r\nYour forgot password verify OTP is $customer_info->forgot \r\nThank you for using $site_setting->name"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        }

        Toastr::success('Success', 'Resend code send successfully');
        return redirect()->back();
    }
    public function forgot_reset()
    {
        if (!Session::get('verify_phone')) {
            Toastr::error('Something wrong please try again');
            return redirect()->route('customer.forgot.password');
        }
        ;
        return view('frontEnd.layouts.customer.forgot_reset');
    }
    public function forgot_store(Request $request)
    {

        $customer_info = Customer::where('phone', session::get('verify_phone'))->first();

        if ($customer_info->forgot != $request->otp) {
            Toastr::error('Success', 'Your OTP not match');
            return redirect()->back();
        }

        $customer_info->forgot = 1;
        $customer_info->password = bcrypt($request->password);
        $customer_info->save();
        if (Auth::guard('customer')->attempt(['phone' => $customer_info->phone, 'password' => $request->password])) {
            Session::forget('verify_phone');
            Toastr::success('You are login successfully', 'success!');
            return redirect()->intended('customer/account');
        }
    }
    public function account()
    {
        return view('frontEnd.layouts.customer.account');
    }
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        Toastr::success('You are logout successfully', 'success!');
        return redirect()->route('customer.login');
    }
   public function checkout()
   {
    $shippingcharge = ShippingCharge::where(['status' => 1, 'website' => 1])->get();
    $select_charge = ShippingCharge::where(['status' => 1, 'website' => 1])->first();
    $bkash_gateway = PaymentGateway::where(['status' => 1, 'type' => 'bkash'])->first();
    $shurjopay_gateway = PaymentGateway::where(['status' => 1, 'type' => 'shurjopay'])->first();
    Session::put('shipping', $select_charge->amount);
    $districts = District::distinct()->select('district')->orderBy('district', 'asc')->get();

    // Load checkout content texts
    $fields = [
        'checkout_title', 'name_label', 'phone_label', 'address_label',
        'shipping_method_label', 'shipping_inside_dhaka', 'shipping_outside_dhaka',
        'payment_method_label', 'payment_cash_on_delivery', 'order_button'
    ];
    $checkout_contents = CheckoutContent::
        whereIn('key_name', $fields)
        ->pluck('value', 'key_name');

    return view('frontEnd.layouts.customer.checkout', compact(
        'shippingcharge', 'bkash_gateway', 'shurjopay_gateway', 'districts', 'checkout_contents'
    ));
}


    public function order_save_draft(Request $request)
{
    $this->validate($request, [
        'name' => 'required',
        'phone' => 'required',
        'address' => 'required',
        'area' => 'required',
    ]);

    if (Cart::instance('shopping')->count() <= 0) {
        return response()->json(['error' => 'cart is empty'], 400);
    }

    $subtotal = Cart::instance('shopping')->subtotal();
    $subtotal = str_replace(',', '', $subtotal);
    $subtotal = str_replace('.00', '', $subtotal);
    $discount = Session::get('discount', 0) + Session::get('coupon_amount', 0);
    $shipping_area = ShippingCharge::where('id', $request->area)->first();

    // Get or create customer
    $exits_customer = Customer::where('phone', $request->phone)->select('phone', 'id')->first();
    
    if (Auth::guard('customer')->user()) {
        $customer_id = Auth::guard('customer')->user()->id;
    } else {
        if ($exits_customer) {
            $customer_id = $exits_customer->id;
        } else {
            $password = rand(111111, 999999);
            $store = new Customer();
            $store->name = $request->name;
            $store->slug = $request->name;
            $store->phone = $request->phone;
            $store->password = bcrypt($password);
            $store->verify = 1;
            $store->status = 'active';
            $store->save();
            $customer_id = $store->id;
        }
    }

    // Check if incomplete order already exists for this customer
    $order = Order::where([
        'order_status' => 9, // Incomplete status
        'customer_id' => $customer_id
    ])->first();

    // If incomplete order exists, UPDATE it. Otherwise, CREATE new one
    if ($order) {
        // UPDATE existing incomplete order
        $order->amount = ($subtotal + $shipping_area->amount) - $discount;
        $order->discount = $discount ? $discount : 0;
        $order->web_discount = $discount ? $discount : 0;
        $order->shipping_charge = $shipping_area->amount;
        $order->customer_ip = $request->ip();
        $order->order_type = Session::get('free_shipping') ? 'digital' : 'goods';
        $order->note = $request->note;
        $order->save();

        // Update shipping
        $shipping = Shipping::where('order_id', $order->id)->first();
        if ($shipping) {
            $shipping->name = $request->name;
            $shipping->phone = $request->phone;
            $shipping->address = $request->address;
            $shipping->area = $shipping_area ? $shipping_area->name : 'Free Shipping';
            $shipping->save();
        }

        // Update payment
        $payment = Payment::where('order_id', $order->id)->first();
        if ($payment) {
            $payment->payment_method = $request->payment_method;
            $payment->amount = $order->amount;
            $payment->save();
        }

        // Delete removed items from cart
        foreach ($order->orderdetails as $orderdetail) {
            $item = Cart::instance('shopping')->content()->where('id', $orderdetail->product_id)->first();
            if (!$item) {
                $orderdetail->delete();
            }
        }

        // Update or add cart items
        foreach (Cart::instance('shopping')->content() as $cart) {
            $order_detail = OrderDetails::where('order_id', $order->id)
                                       ->where('product_id', $cart->id)
                                       ->first();
            
            if ($order_detail) {
                // Update existing detail
                $order_detail->sale_price = $cart->price;
                $order_detail->qty = $cart->qty;
                $order_detail->product_color = $cart->options->product_color;
                $order_detail->product_size = $cart->options->product_size;
                $order_detail->save();
            } else {
                // Create new detail
                $order_detail = new OrderDetails();
                $order_detail->order_id = $order->id;
                $order_detail->product_id = $cart->id;
                $order_detail->product_name = $cart->name;
                $order_detail->sale_price = $cart->price;
                $order_detail->purchase_price = $cart->options->purchase_price;
                $order_detail->product_color = $cart->options->product_color;
                $order_detail->product_size = $cart->options->product_size;
                $order_detail->product_type = $cart->options->type;
                $order_detail->qty = $cart->qty;
                $order_detail->save();
            }
        }

    } else {
        // CREATE new incomplete order
        $order = new Order();
        $order->invoice_id = rand(11111, 99999);
        $order->amount = ($subtotal + $shipping_area->amount) - $discount;
        $order->discount = $discount ? $discount : 0;
        $order->web_discount = $discount ? $discount : 0;
        $order->shipping_charge = $shipping_area->amount;
        $order->customer_id = $customer_id;
        $order->customer_ip = $request->ip();
        $order->order_type = Session::get('free_shipping') ? 'digital' : 'goods';
        $order->order_status = 9; // Incomplete status
        $order->note = $request->note;
        $order->save();

        // Create shipping
        $shipping = new Shipping();
        $shipping->order_id = $order->id;
        $shipping->customer_id = $customer_id;
        $shipping->name = $request->name;
        $shipping->phone = $request->phone;
        $shipping->address = $request->address;
        $shipping->area = $shipping_area ? $shipping_area->name : 'Free Shipping';
        $shipping->save();

        // Create payment
        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->customer_id = $customer_id;
        $payment->payment_method = $request->payment_method;
        $payment->amount = $order->amount;
        $payment->payment_status = 'draft';
        $payment->save();

        // Create order details
        foreach (Cart::instance('shopping')->content() as $cart) {
            $order_details = new OrderDetails();
            $order_details->order_id = $order->id;
            $order_details->product_id = $cart->id;
            $order_details->product_name = $cart->name;
            $order_details->sale_price = $cart->price;
            $order_details->purchase_price = $cart->options->purchase_price;
            $order_details->product_color = $cart->options->product_color;
            $order_details->product_size = $cart->options->product_size;
            $order_details->product_type = $cart->options->type;
            $order_details->qty = $cart->qty;
            $order_details->save();
        }
    }

    // Store order ID in session
    Session::put('order_id', $order->id);

    return response()->json([
        'message' => 'Incomplete order saved successfully!', 
        'order_id' => $order->id
    ]);
}
public function order_save(Request $request)
{
    $this->validate($request, [
        'name' => 'required',
        'phone' => 'required',
        'address' => 'required',
    ]);

    $order_id = Session::get('order_id');
    
    if (!$order_id) {
        $draftResponse = $this->order_save_draft($request);
        $responseData = $draftResponse->getData();
        
        if ($draftResponse->getStatusCode() == 200 && isset($responseData->order_id)) {
            $order_id = $responseData->order_id;
            Session::put('order_id', $order_id);
        } else {
            Toastr::error('Something went wrong! Please try again.', 'Failed!');
            return redirect()->back();
        }
    }

    if (Cart::instance('shopping')->count() <= 0) {
        Toastr::error('Your shopping cart is empty', 'Failed!');
        return redirect()->back();
    }

    $subtotal = Cart::instance('shopping')->subtotal();
    $subtotal = str_replace(',', '', $subtotal);
    $subtotal = str_replace('.00', '', $subtotal);
    $discount = Session::get('discount', 0) + Session::get('coupon_amount', 0);
    $shipping_area = ShippingCharge::where('id', $request->area)->first();

    // Get customer ID
    if (Auth::guard('customer')->user()) {
        $customer_id = Auth::guard('customer')->user()->id;
    } else {
        $exits_customer = Customer::where('phone', $request->phone)->first();
        if ($exits_customer) {
            $customer_id = $exits_customer->id;
        } else {
            $password = rand(111111, 999999);
            $store = new Customer();
            $store->name = $request->name;
            $store->slug = $request->name;
            $store->phone = $request->phone;
            $store->password = bcrypt($password);
            $store->verify = 1;
            $store->status = 'active';
            $store->save();
            $customer_id = $store->id;
        }
    }

    $order = Order::where('id', $order_id)->first();
    
    if (!$order) {
        Toastr::error('Order not found. Please try again.', 'Failed!');
        return redirect()->back();
    }

    // Update order to confirmed status
    $order->invoice_id = rand(11111, 99999);
    $order->amount = ($subtotal + $shipping_area->amount) - $discount;
    $order->discount = $discount ? $discount : 0;
    $order->web_discount = $discount ? $discount : 0;
    $order->shipping_charge = $shipping_area->amount;
    $order->customer_id = $customer_id;
    $order->customer_ip = $request->ip();
    $order->order_type = Session::get('free_shipping') ? 'digital' : 'goods';
    $order->order_status = 1;
    $order->note = $request->note;
    $order->save();

    // Update shipping
    $shipping = Shipping::where('order_id', $order_id)->first();
    if ($shipping) {
        $shipping->name = $request->name;
        $shipping->phone = $request->phone;
        $shipping->address = $request->address;
        $shipping->area = $shipping_area ? $shipping_area->name : 'Free Shipping';
        $shipping->save();
    } else {
        $shipping = new Shipping();
        $shipping->order_id = $order->id;
        $shipping->customer_id = $customer_id;
        $shipping->name = $request->name;
        $shipping->phone = $request->phone;
        $shipping->address = $request->address;
        $shipping->area = $shipping_area ? $shipping_area->name : 'Free Shipping';
        $shipping->save();
    }

    // Update payment
    $payment = Payment::where('order_id', $order_id)->first();
    if ($payment) {
        $payment->payment_method = $request->payment_method;
        $payment->amount = $order->amount;
        $payment->payment_status = 'pending';
        $payment->save();
    } else {
        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->customer_id = $customer_id;
        $payment->payment_method = $request->payment_method;
        $payment->amount = $order->amount;
        $payment->payment_status = 'pending';
        $payment->save();
    }

    // Sync order details
    foreach ($order->orderdetails as $orderdetail) {
        $item = Cart::instance('shopping')->content()->where('id', $orderdetail->product_id)->first();
        if (!$item) {
            $orderdetail->delete();
        }
    }

    foreach (Cart::instance('shopping')->content() as $cart) {
        $exits = OrderDetails::where('order_id', $order_id)
                             ->where('product_id', $cart->id)
                             ->first();
        
        if ($exits) {
            $order_details = OrderDetails::find($exits->id);
            $order_details->product_discount = $cart->options->product_discount ?? 0;
            $order_details->sale_price = $cart->price;
            $order_details->qty = $cart->qty;
            $order_details->save();
        } else {
            $order_details = new OrderDetails();
            $order_details->order_id = $order->id;
            $order_details->product_id = $cart->id;
            $order_details->product_name = $cart->name;
            $order_details->sale_price = $cart->price;
            $order_details->purchase_price = $cart->options->purchase_price;
            $order_details->product_color = $cart->options->product_color;
            $order_details->product_size = $cart->options->product_size;
            $order_details->product_type = $cart->options->type;
            $order_details->qty = $cart->qty;
            $order_details->save();
        }
    }

    // Clear cart and sessions
    Cart::instance('shopping')->destroy();
    Session::forget('free_shipping');
    Session::forget('order_id');
    Session::put('purchase_event', 'true');

    Toastr::success('Thanks, Your order has been placed successfully', 'Success!');
    
    // ===== এখানে শুধু প্রয়োজনীয় data পাস করুন =====
    
    // SMS Job - শুধু primitive data পাস করুন
    SendOrderSms::dispatch(
        $order->id,
        $order->invoice_id,
        $order->amount,
        $request->phone,
        $request->name
    );
    
    // Notification Job - Array পাস করুন (primitive types)
    SendOrderNotifications::dispatch(
        $order->id,
        [
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'payment_method' => $request->payment_method
        ]
    );
     if ($request->payment_method == 'bkash') {
        return redirect('/bkash/checkout-url/create?order_id=' . $order->id);
    } elseif ($request->payment_method == 'shurjopay') {
        $info = array(
            'currency' => "BDT",
            'amount' => $order->amount,
            'order_id' => uniqid(),
            'discsount_amount' => 0,
            'disc_percent' => 0,
            'client_ip' => $request->ip(),
            'customer_name' => $request->name,
            'customer_phone' => $request->phone,
            'email' => "customer@gmail.com",
            'customer_address' => $request->address,
            'customer_city' => $request->area,
            'customer_state' => $request->area,
            'customer_postcode' => "1212",
            'customer_country' => "BD",
            'value1' => $order->id
        );
        $shurjopay_service = new ShurjopayController();
        return $shurjopay_service->checkout($info);
    } else {
        $this->fireOrderPlacedEvent($request, $order);
        return redirect('customer/order-success/' . $order->id);
    }
}

/**
 * Send order notifications to admin/staff via WhatsApp and Email
 */
private function sendOrderNotifications($order, $request)
{
    // Check if order notification is enabled
    $permission = UpdatePermission::where('key', 'order_notification')
    ->where('status', 1)
    ->first();
    if (!$permission) {
        return; // Notification disabled
    }

    // Get notification settings
    $notificationSettings = OrderNotificationSetting::get();
    if (count($notificationSettings) == 0) {
        return; // No active notification settings
    }

    // Prepare order details message
    $orderMessage = $this->prepareOrderMessage($order, $request);

    foreach ($notificationSettings as $setting) {
        // Send WhatsApp notification
        if ($setting->whatsapp_number) {
            $this->sendWhatsAppNotification($setting->whatsapp_number, $orderMessage);
        }

        // Send Email notification
        if ($setting->email_address) {
            $this->sendEmailNotification($setting->email_address, $order, $orderMessage);
        }
    }
}



/**
 * Prepare order message for notifications
 */
private function prepareOrderMessage($order, $request)
{
    $site_setting = GeneralSetting::where('status', 1)->first();
    
    $message = "ðŸ”” New Order Received!\n\n";
    $message .= "Invoice ID: {$order->invoice_id}\n";
    $message .= "Customer Name: {$request->name}\n";
    $message .= "Phone: {$request->phone}\n";
    $message .= "Address: {$request->address}\n";
    $message .= "Area: {$request->area}\n";
    $message .= "Total Amount: {$order->amount} BDT\n";
    $message .= "Payment Method: {$request->payment_method}\n";
    $message .= "Order Time: " . now()->format('d M Y, h:i A') . "\n\n";
    
    // Add order items
    $message .= "Order Items:\n";
    foreach ($order->orderdetails as $detail) {
        $message .= "- {$detail->product_name} (Qty: {$detail->qty}) - {$detail->sale_price} BDT\n";
    }
    
    $message .= "\nThank you!\n{$site_setting->name}";
    
    return $message;
}

/**
 * Send WhatsApp notification
 */
private function sendWhatsAppNotification($number, $message)
{
    $instanceId = env('WHATSAPP_INSTANCE_ID');
    $token = env('WHATSAPP_TOKEN');
    $url = "https://api.ultramsg.com/{$instanceId}/messages/chat";

    $data = [
        'token' => $token,
        'to' => $number, // Example: 8801XXXXXXXXX
        'body' => $message,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    // Optional: লগ দেখতে চাওলে
    \Log::info('WhatsApp Response: ' . $response);
}

/**
 * Send Email notification
 */
private function sendEmailNotification($email, $order, $message)
{
    try {
        $site_setting = GeneralSetting::where('status', 1)->first();
        
        Mail::send([], [], function ($mail_message) use ($email, $order, $message, $site_setting) {
            $mail_message->to($email)
                ->subject("New Order Received - Invoice #{$order->invoice_id}")
                ->html(nl2br($message));
            
            if ($site_setting && $site_setting->email) {
                $mail_message->from($site_setting->email, $site_setting->name);
            }
        });
    } catch (\Exception $e) {
        // Log error but don't stop order process
        \Log::error('Email notification failed: ' . $e->getMessage());
    }
}
    private function fireOrderPlacedEvent($request, $order)
{
    // Get cart items before they're destroyed
    $cartItems = [];
    foreach (Cart::instance('shopping')->content() as $item) {
        $cartItems[] = [
            'id' => $item->id,
            'name' => $item->name,
            'qty' => $item->qty,
            'price' => $item->price,
            'total' => $item->qty * $item->price,
            'size' => $item->options->product_size ?? null,
            'color' => $item->options->product_color ?? null,
            'image' => $item->options->image ?? null,
            'slug' => $item->options->slug ?? null
        ];
    }

    // Prepare order data
    $orderData = [
        'name' => $request->name,
        'phone' => $request->phone,
        'address' => $request->address,
        'area' => $request->area,
        'payment_method' => $request->payment_method,
        'subtotal' => $order->amount - $order->shipping_charge + $order->discount,
        'shipping_cost' => $order->shipping_charge,
        'discount' => $order->discount,
        'total' => $order->amount
    ];
    
    // Prepare session data
    $sessionData = [
        'cart_items' => session('cart', []),
        'recently_viewed' => session('recently_viewed', []),
        'wishlist' => session('wishlist', []),
        'last_activity' => session('last_activity'),
        'visit_count' => session('visit_count', 0)
    ];
    
    // Fire the OrderPlaced event
    event(new OrderPlaced($orderData, $cartItems, auth()->guard('customer')->user(), $sessionData));
}

    public function orders()
    {
        $orders = Order::where('customer_id', Auth::guard('customer')->user()->id)->with('status')->latest()->get();
        return view('frontEnd.layouts.customer.orders', compact('orders'));
    }
    public function order_success($id)
    {
        $order = Order::where('id', $id)->firstOrFail();
        return view('frontEnd.layouts.customer.order_success', compact('order'));
    }
    public function invoice(Request $request)
    {
        $order = Order::where(['id' => $request->id, 'customer_id' => Auth::guard('customer')->user()->id])->with('orderdetails', 'payment', 'shipping', 'customer')->firstOrFail();

        $orders = Order::where(['id' => $request->id, 'customer_id' => Auth::guard('customer')->user()->id])->with('orderdetails')->first();
        // return $orders;
        return view('frontEnd.layouts.customer.invoice', compact('order', 'orders'));
    }
    public function pdfreader(Request $request)
    {
        $order = Order::where(['id' => $request->id, 'customer_id' => Auth::guard('customer')->user()->id])->with('orderdetails', 'payment', 'shipping', 'customer')->firstOrFail();

        $orders = Order::where(['id' => $request->id, 'customer_id' => Auth::guard('customer')->user()->id])->with('orderdetails')->first();
        // return $orders;
        return view('frontEnd.layouts.customer.pdfreader', compact('order', 'orders'));
    }


    public function order_note(Request $request)
    {
        $order = Order::where(['id' => $request->id, 'customer_id' => Auth::guard('customer')->user()->id])->firstOrFail();
        return view('frontEnd.layouts.customer.order_note', compact('order'));
    }
    public function profile_edit(Request $request)
    {
        $profile_edit = Customer::where(['id' => Auth::guard('customer')->user()->id])->firstOrFail();
        $districts = District::distinct()->select('district')->get();
        $areas = District::where(['district' => $profile_edit->district])->select('area_name', 'id')->get();
        return view('frontEnd.layouts.customer.profile_edit', compact('profile_edit', 'districts', 'areas'));
    }
    public function profile_update(Request $request)
    {
        $update_data = Customer::where(['id' => Auth::guard('customer')->user()->id])->firstOrFail();

        $image = $request->file('image');
        if ($image) {
            // image with intervention
            $name = time() . '-' . $image->getClientOriginalName();
            $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name);
            $name = strtolower(Str::slug($name));
            $uploadpath = 'public/uploads/customer/';
            $imageUrl = $uploadpath . $name;
            $img = Image::make($image->getRealPath());
            $img->encode('webp', 90);
            $width = 120;
            $height = 120;
            $img->resize($width, $height);
            $img->save($imageUrl);
        } else {
            $imageUrl = $update_data->image;
        }

        $update_data->name = $request->name;
        $update_data->phone = $request->phone;
        $update_data->email = $request->email;
        $update_data->address = $request->address;
        $update_data->district = $request->district;
        $update_data->area = $request->area;
        $update_data->image = $imageUrl;
        $update_data->save();

        Toastr::success('Your profile update successfully', 'Success!');
        return redirect()->route('customer.account');
    }

    public function order_track()
    {
        return view('frontEnd.layouts.customer.order_track');
    }

    public function order_track_result(Request $request)
    {

        $phone = $request->phone;
        $invoice_id = $request->invoice_id;

        if ($phone != null && $invoice_id == null) {
            $order = DB::table('orders')
                ->join('shippings', 'orders.id', '=', 'shippings.order_id')
                ->where(['shippings.phone' => $request->phone])
                ->get();
        } else if ($invoice_id && $phone) {
            $order = DB::table('orders')
                ->join('shippings', 'orders.id', '=', 'shippings.order_id')
                ->where(['orders.invoice_id' => $request->invoice_id, 'shippings.phone' => $request->phone])
                ->get();
        }

        if ($order->count() == 0) {

            Toastr::error('message', 'Something Went Wrong !');
            return redirect()->back();
        }

        //   return $order->count();

        return view('frontEnd.layouts.customer.tracking_result', compact('order'));
    }


    public function change_pass()
    {
        return view('frontEnd.layouts.customer.change_password');
    }

    public function password_update(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required_with:new_password|same:new_password|'
        ]);

        $customer = Customer::find(Auth::guard('customer')->user()->id);
        $hashPass = $customer->password;

        if (Hash::check($request->old_password, $hashPass)) {

            $customer->fill([
                'password' => Hash::make($request->new_password)
            ])->save();

            Toastr::success('Success', 'Password changed successfully!');
            return redirect()->route('customer.account');
        } else {
            Toastr::error('Failed', 'Old password not match!');
            return redirect()->back();
        }
    }
}
