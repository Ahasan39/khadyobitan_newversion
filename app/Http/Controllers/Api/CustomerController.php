<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CouponCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ShippingCharge;
use App\Models\Shipping;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\Review;
use App\Models\SmsGateway;
use App\Models\ProductVariable;
use Carbon\Carbon;
use Response;
use Intervention\Image\Facades\Image;
use Hash;
use Illuminate\Support\Facades\Auth;
use Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use GPBMetadata\Google\Api\Auth as ApiAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Session;


class CustomerController extends Controller
{
    function __construct()
    {
        $this->middleware("auth.jwt", [
            "except" => [
                "register",
                "verify",
                "resendotp",
                "account_verify",
                "login",
                "logout",
                "review",
                "forgot_password",
                "forgot_verify",
                "forgot_resend",
                "setting",
                "order_save",
                "customer_coupon",
                "order_track_result",
                "shippingCharge",
                "forgot_store",
            ],
        ]);
    }
    

    public function login(Request $request)
    {
        // return $request;
        $validator = Validator::make($request->all(), [
            "phone" => "required",
            "password" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    "error" => "validation_error",
                    "message" => $validator->errors(),
                ],
                200
            );
        }

        $user = Customer::where("phone", $request->phone)->first();
        // return $user;

        $credentials = [
            "phone" => $request->phone,
            "password" => $request->password,
        ];
        try {
            if (!($token = Auth::guard("customerapi")->attempt($credentials))) {
                return response()->json(
                    [
                        "error" => "Invalid Credentials",
                    ],
                    401
                );
            }
        } catch (JWTException $e) {
            return response()->json(
                [
                    "error" => "Could not create token",
                ],
                500
            );
        }
        return response()->json(
            [
                "status" => "success",
                "token" => $token,
                "user" => $user,
            ],
            200
        );
    }

   public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'phone' => 'required|unique:customers,phone',
        'password' => 'required|min:6',
        'email' => 'nullable|email'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'validationfail',
            'message' => $validator->errors()
        ], 422);
    }

    // Generate next ID for slug
    $last_id = Customer::orderBy('id', 'desc')->first();
    $last_id = $last_id ? $last_id->id + 1 : 1;

    // Create new customer
    $customer = new Customer();
    $customer->name = $request->name;
    $customer->slug = strtolower(Str::slug($request->name . '-' . $last_id));
    $customer->phone = $request->phone;
    $customer->email = $request->email ?? 'N/A';
    $customer->password = bcrypt($request->password);
    $customer->verify = 1;
    $customer->status = 'active';
    $customer->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Account created successfully',
        'data' => [
            'id' => $customer->id,
            'name' => $customer->name,
            'phone' => $customer->phone,
            'email' => $customer->email,
            'slug' => $customer->slug
        ]
    ], 201);
}
public function review(Request $request)
{
    $this->validate($request, [
        'ratting' => 'required',
        'review'  => 'required',
        'product_id' => 'required|exists:products,id',
    ]);

    // Auth check
   $user = Auth::guard('customerapi')->user();
if (!$user) {
    return response()->json([
        'status' => 'error',
        'message' => 'Unauthorized. Please login to submit a review.'
    ], 401);
}


    // Data save
   $review = new Review();
$review->product_id  = $request->product_id;
$review->review      = $request->review;
$review->ratting     = $request->ratting;
$review->status      = 'pending';
$review->customer_id = $user->id;
$review->name        = $user->name ?? 'N/A';
$review->email       = $user->email ?? 'N/A';
$review->save();


    return response()->json([
        'status'  => 'success',
        'message' => 'Customer review stored successfully!',
        'data'    => $review,
    ]);
}


    public function resendotp(Request $request)
    {
        $member_info = Customer::where("email", $request->email)->first();
        $member_info->verify = rand(1111, 9999);
        $member_info->save();

        $token = "94781234491684132489e2a87f2138dd7150495cde9bf255c32d";
        $message = "প্রিয় গ্রাহক, দিনাজপুর বিডি এর ভেরিফিকেশন কোড $member_info->verify, ধন্যবাদ।";

        $url = "http://api.greenweb.com.bd/api.php";
        $data = [
            "to" => $member_info->phone,
            "message" => "$message",
            "token" => "$token",
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);

        return response()->json([
            "status" => "success",
            "message" => "Member resend OTP successfully",
        ]);
    }

    public function account_verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "otp" => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    "status" => "validationfail",
                    "error" => "validation_error",
                    "message" => $validator->errors(),
                ],
                200
            );
        }
        $customer_email = $request->header('customer_email');
        $customer_info = Customer::where("email", $customer_email)->first();
        // return $customer_info;
        if ($customer_info->verify != $request->otp) {
            return response()->json([
                "status" => "failed",
                "message" => "Your OTP not match",
            ]);
        }

        $customer_info->verify = 1;
        $customer_info->status = "active";
        $customer_info->save();
        
        $credentials = [
            "email" => $customer_email,
            "password" => $request->otp,
        ];
        // return $credentials;
     
        $token = Auth::guard("customerapi")->attempt($credentials);
        // return $token
        

        return response()->json([
            "status" => "success",
            "message" => "Account verify successfully",
            "token" => $token,
        ]);
    }

    public function forgot_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "phone" => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    "status" => "validationfail",
                    "error" => "validation_error",
                    "message" => $validator->errors(),
                ],
                200
            );
        }
        $customer_info = Customer::where("phone", $request->phone)->first();
        if (!$customer_info) {
            return response()->json([
                "status" => "failed",
                "message" => "Your phone number not found",
            ]);
        }
        $customer_info->forgot = mt_rand(1111, 9999);
        $customer_info->save();
        $site_setting = GeneralSetting::where("status", 1)->first();
        $sms_gateway = SmsGateway::where(["status" => 1])->first();
        if ($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                [
                    "api_key" => "$sms_gateway->api_key",
                    "number" => "88" . $request->phone,
                    "senderid" => "$sms_gateway->serderid",
                    "message" => "Dear $customer_info->name!\r\nYour forgot password verify OTP is $customer_info->forgot \r\nThank you for using $site_setting->name",
                ],
            ];
            //   return $data;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data[0]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
        }
        return response()->json([
            "status" => "success",
            "message" => "Your forgot password reset successfully",
            "phone" => $customer_info->phone,
            "forgot" => $customer_info->forgot,
        ]);
    }
    
    public function forgot_verify(Request $request)
{
    //  Validation
    $validator = Validator::make($request->all(), [
        'phone' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => false,
            'message' => $validator->errors()->first(),
        ], 422);
    }

    //  Customer check
    $customer = Customer::where('phone', $request->phone)->first();

    if (!$customer) {
        return response()->json([
            'status'  => false,
            'message' => 'Phone number not found',
        ], 404);
    }

    //  Generate OTP
    $otp = rand(1111, 9999);
    $customer->forgot = $otp;
    $customer->save();

    //  SMS Send
    $site_setting = GeneralSetting::where('status', 1)->first();
    $sms_gateway  = SmsGateway::where(['status' => 1, 'forget_pass' => 1])->first();

    if ($sms_gateway) {
        $data = [
            "api_key"  => $sms_gateway->api_key,
            "number"   => $customer->phone,
            "type"     => 'text',
            "senderid" => $sms_gateway->serderid,
            "message"  => "Dear {$customer->name}, Your OTP is {$otp}. {$site_setting->name}"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $sms_gateway->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        curl_close($ch);
    }

    // API Response
    return response()->json([
        'status'  => true,
        'message' => 'OTP sent successfully',
        'phone'   => $customer->phone
    ], 200);
}

public function forgot_store(Request $request)
{
    // Validation
    $validator = Validator::make($request->all(), [
        'phone'    => 'required|string',
        'otp'      => 'required|numeric',
        'password' => 'required|string|min:6|confirmed',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => false,
            'message' => $validator->errors()->first(),
        ], 422);
    }

    //  Customer check
    $customer = Customer::where('phone', $request->phone)->first();

    if (!$customer) {
        return response()->json([
            'status'  => false,
            'message' => 'Customer not found',
        ], 404);
    }

    //OTP match check
    if ($customer->forgot != $request->otp) {
        return response()->json([
            'status'  => false,
            'message' => 'OTP does not match',
        ], 401);
    }

    // Reset password
    $customer->password = Hash::make($request->password);
    $customer->forgot   = null; // OTP clear
    $customer->save();

    // Success response
    return response()->json([
        'status'  => true,
        'message' => 'Password reset successfully',
    ], 200);
}
    public function logout(Request $request)
    {
        Auth::guard("customerapi")->logout();
        return response()->json([
            "status" => "success",
            "message" => "You are logout successfully",
        ]);
    }

    public function profile()
    {
        $profile = Customer::where("id", Auth::guard("customerapi")->user()->id)
            ->with("cust_area")
            ->first();
        return response()->json([
            "status" => "success",
            "message" => "Data fatch successfully",
            "profile" => $profile,
        ]);
    }

    public function profile_update(Request $request)
{
    $this->validate($request, [
        "name" => "required",
    ]);

    $update_data = Customer::where("id", Auth::guard("customerapi")->user()->id)->first();

    // File upload
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $filename = time() . '-' . strtolower(Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME))) . '.' . $image->getClientOriginalExtension();
        $uploadPath = 'uploads/customer/';
        $image->move(public_path($uploadPath), $filename);
        $imageUrl = $uploadPath . $filename;
    } else {
        $imageUrl = $update_data->image;
    }

    // Update customer info
    $update_data->name = $request->name;
    $update_data->phone = $request->phone;
    $update_data->address = $request->address;
    $update_data->district = $request->district;
    $update_data->area = $request->area;
    $update_data->image = $imageUrl;
    $update_data->save();

    return response()->json([
        "status" => "success",
        "message" => "Your profile updated successfully",
        "data" => $update_data,
    ]);
}


    public function logincheck()
    {
        $profile = Customer::where(
            "id",
            Auth::guard("customerapi")->user()->id
        )->first();
        return response()->json([
            "status" => "success",
            "message" => "Data fatch successfully",
        ]);
    }


    public function order_save(Request $request)
    {
        // ✅ Detect logged-in user (Sanctum)
        $authCustomer = auth('customerapi')->user();
        
        $rules = [
            'address' => 'required',
            'area' => 'required|exists:shipping_charges,id', // Validate shipping area
            'cart' => 'required|array|min:1',
            'cart.*.product_id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
        ];
        
        // Only require name/phone/email if NOT logged in
        if (!$authCustomer) {
            $rules['name'] = 'required';
            $rules['phone'] = 'required';
            $rules['email'] = 'nullable|email';
        }

        $validator = \Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                "status" => "failed",
                "message" => "Validation error",
                "errors" => $validator->errors(),
            ], 422);
        }

        $data = $request->all();

        // Use logged-in customer info if available
        $name = $authCustomer ? $authCustomer->name : $request->name;
        $phone = $authCustomer ? $authCustomer->phone : $request->phone;
        $email = $authCustomer ? $authCustomer->email : ($request->email ?? 'N/A');

        try {
            // Start database transaction
            DB::beginTransaction();

            // ✅ Get shipping area (already validated)
            $shipping_area = ShippingCharge::where('id', $request->area)->first();
            
            if (!$shipping_area) {
                return response()->json([
                    "status" => "failed",
                    "message" => "Invalid shipping area selected",
                ], 400);
            }

            // ✅ Calculate order totals
            $subtotal = 0;
            $totalProductDiscount = 0;
            $cartItems = [];

            foreach ($data["cart"] as $cartitem) {
                // Get product details
                $product = Product::select("id", "name", "new_price", "old_price", "purchase_price", "stock", "type")
                    ->find($cartitem["product_id"]);

                if (!$product) {
                    DB::rollBack();
                    return response()->json([
                        "status" => "failed",
                        "message" => "Product not found: " . $cartitem["product_id"],
                    ], 400);
                }

                // Determine price and variant info
                $itemPrice = $cartitem["new_price"] ?? $product->new_price;
                $quantity = $cartitem["quantity"];
                $productType = 1; // Default to simple product
                $color = null;
                $size = null;
                $itemDiscount = 0;
                $purchasePrice = $product->purchase_price;

                // Handle variant products
                if (!empty($cartitem["options"]) && isset($cartitem["options"][0])) {
                    $option = $cartitem["options"][0];
                    $color = $option["color"] ?? null;
                    $size = $option["size"] ?? null;
                    $productType = $option["type"] ?? 1;
                    $itemDiscount = floatval($option["discount"] ?? 0);

                    // If it's a variable product, get variant details
                    if ($productType == 0 && ($color || $size)) {
                        $variant = ProductVariable::where('product_id', $product->id)
                            ->where('color', $color)
                            ->where('size', $size)
                            ->first();

                        if ($variant) {
                            $itemPrice = $variant->new_price ?? $itemPrice;
                            $purchasePrice = $variant->purchase_price ?? $purchasePrice;
                            
                            // Check variant stock
                            if ($variant->stock < $quantity) {
                                DB::rollBack();
                                return response()->json([
                                    "status" => "failed",
                                    "message" => "Insufficient stock for {$product->name} ({$color}, {$size})",
                                ], 400);
                            }
                        }
                    }
                } else {
                    // Simple product - check stock
                    if ($product->stock < $quantity) {
                        DB::rollBack();
                        return response()->json([
                            "status" => "failed",
                            "message" => "Insufficient stock for {$product->name}",
                        ], 400);
                    }
                }

                // Calculate item total
                $itemTotal = $itemPrice * $quantity;
                $subtotal += $itemTotal;
                $totalProductDiscount += $itemDiscount;

                // Store cart item for later use
                $cartItems[] = [
                    'product' => $product,
                    'product_id' => $product->id,
                    'product_name' => $cartitem["name"] ?? $product->name,
                    'quantity' => $quantity,
                    'sale_price' => $itemPrice,
                    'purchase_price' => $purchasePrice,
                    'product_type' => $productType,
                    'color' => $color,
                    'size' => $size,
                    'discount' => $itemDiscount,
                ];
            }

            // ✅ Apply coupon discount (if provided)
            $couponDiscount = floatval($request->discount ?? 0);
            
            // Validate total discount doesn't exceed subtotal
            $totalDiscount = $totalProductDiscount + $couponDiscount;
            if ($totalDiscount > $subtotal) {
                DB::rollBack();
                return response()->json([
                    "status" => "failed",
                    "message" => "Total discount cannot exceed order subtotal",
                ], 400);
            }

            // ✅ Calculate final amount
            $shippingFee = $shipping_area->amount;
            $finalAmount = $subtotal + $shippingFee - $totalDiscount;

            // Ensure amount is not negative
            if ($finalAmount < 0) {
                $finalAmount = 0;
            }

            // ✅ Determine customer_id
            if ($authCustomer) {
                $customer_id = $authCustomer->id;
            } else {
                $existingCustomer = Customer::where("phone", $phone)->first();

                if ($existingCustomer) {
                    $customer_id = $existingCustomer->id;
                } else {
                    $password = rand(111111, 999999);
                    $store = Customer::create([
                        'name' => $name,
                        'slug' => Str::slug($name),
                        'email' => $email,
                        'phone' => $phone,
                        'password' => bcrypt($password),
                        'verify' => 1,
                        'status' => "active",
                    ]);
                    $customer_id = $store->id;
                }
            }

            // ✅ Create Order
            $order = new Order();
            $order->invoice_id = rand(11111, 99999);
            $order->amount = $finalAmount;
            $order->discount = $totalDiscount;
            $order->shipping_charge = $shippingFee;
            $order->customer_id = $customer_id;
            $order->customer_ip = $request->ip() ?? '';
            $order->order_status = 1;
            $order->note = $request->note;
            $order->save();

            // ✅ Shipping Save
            $shipping = new Shipping();
            $shipping->order_id = $order->id;
            $shipping->customer_id = $customer_id;
            $shipping->name = $name;
            $shipping->phone = $phone;
            $shipping->address = $request->address;
            $shipping->area = $shipping_area->name ?? $shipping_area->area_name ?? "Shipping Area";
            $shipping->save();

            // ✅ Payment Save
            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->customer_id = $customer_id;
            $payment->payment_method = "Cash On Delivery";
            $payment->amount = $finalAmount;
            $payment->payment_status = "pending";
            $payment->save();

            // ✅ Order Details Save
            foreach ($cartItems as $item) {
                $orderDetails = new OrderDetails();
                $orderDetails->order_id = $order->id;
                $orderDetails->product_id = $item['product_id'];
                $orderDetails->product_name = $item['product_name'];
                $orderDetails->sale_price = $item['sale_price'];
                $orderDetails->purchase_price = $item['purchase_price'];
                $orderDetails->qty = $item['quantity'];
                $orderDetails->product_color = $item['color'];
                $orderDetails->product_size = $item['size'];
                $orderDetails->product_type = $item['product_type'];
                $orderDetails->product_discount = $item['discount'];
                $orderDetails->save();
            }

            // Commit transaction
            DB::commit();

            // ✅ SMS Notification (outside transaction)
            try {
                $site_setting = GeneralSetting::where("status", 1)->first();
                $sms_gateway = SmsGateway::where(["status" => 1, "order" => 1])->first();

                if ($sms_gateway && $site_setting) {
                    $url = $sms_gateway->url;
                    $smsData = [
                        "api_key" => $sms_gateway->api_key,
                        "number" => $phone,
                        "type" => "text",
                        "senderid" => $sms_gateway->serderid,
                        "message" => "Dear {$name}!\r\nYour order ({$order->invoice_id}) has been successfully placed.\r\nTotal Bill: {$finalAmount} Taka\r\nThank you for using {$site_setting->name}.",
                    ];

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $smsData);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                    $response = curl_exec($ch);
                    curl_close($ch);
                }
            } catch (\Exception $e) {
                // Log SMS error but don't fail the order
                \Log::error('SMS sending failed: ' . $e->getMessage());
            }

            return response()->json([
                "status" => "success",
                "message" => "Your order has been placed successfully.",
                "data" => [
                    "order_id" => $order->id,
                    "invoice_id" => $order->invoice_id,
                    "amount" => $finalAmount,
                    "subtotal" => $subtotal,
                    "discount" => $totalDiscount,
                    "shipping_charge" => $shippingFee,
                ],
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order creation failed: ' . $e->getMessage());
            
            return response()->json([
                "status" => "failed",
                "message" => "Order creation failed. Please try again.",
                "error" => $e->getMessage(),
            ], 500);
        }
    }

    public function orders()
    {
        $customer = Auth::guard('customerapi')->user();

        // Get all orders with status relation
        $orders = Order::where('customer_id', $customer->id)
            ->with('status')
            ->latest()
            ->get();

        // Get total count
        $totalOrders = $orders->count();

        return response()->json([
            'status' => 'success',
            'message' => 'Customer all orders fetched successfully',
            'total_orders' => $totalOrders,
            'orders' => $orders,
        ]);
    }

    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "old_password" => "required",
            "new_password" => "required",
            "confirm_password" =>
                "required_with:new_password|same:new_password|",
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    "status" => "validationfail",
                    "error" => "validation_error",
                    "message" => $validator->errors(),
                ],
                200
            );
        }
        $customer = Customer::find(Auth::guard("customerapi")->user()->id);
        $hashPass = $customer->password;

        if (Hash::check($request->old_password, $hashPass)) {
            $customer
                ->fill([
                    "password" => Hash::make($request->new_password),
                ])
                ->save();
            return response()->json([
                "status" => "success",
                "Password changed successfully!",
            ]);
        } else {
            return response()->json([
                "status" => "failed",
                "message" => "Old password not match!",
            ]);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            "access_token" => $token,
            "token_type" => "bearer",
            "expires_in" =>
                $this->guard()
                    ->factory()
                    ->getTTL() * 6000,
        ]);
    }
    

    public function guard()
    {
        return Auth::guard("customerapi");
    }

    public function customer_coupon(Request $request)
    {
        $findcoupon = CouponCode::where(
            "coupon_code",
            $request->coupon_code
        )->first();
        if (!$findcoupon) {
            return response()->json([
                "message" => "Oops! The promo code you entered is not valid.",
            ]);
        }

        $currentDate = date("Y-m-d");

        if ($currentDate >= $findcoupon->expiry_date) {
            return response()->json([
                "message" => "Oops! Your promo code has expired.",
            ]);
        }

        if ($currentDate <= $findcoupon->start_date) {
            return response()->json([
                "message" => "Oops! Your promo code is not active yet.",
            ]);
        }

        $totalCart = $request->total_cart;

        if ($totalCart < $findcoupon->buy_amount) {
            return response()->json([
                "message" =>
                    "You need to buy a minimum of " .
                    $findcoupon->buy_amount .
                    " Taka to use this offer.",
            ]);
        }

        if ($findcoupon->offer_type == 1) {
            $discountAmount = ($totalCart * $findcoupon->amount) / 100;
        } else {
            $discountAmount = $findcoupon->amount;
        }

        return response()->json([
            "message" => "Success! Your promo code has been accepted.",
            "coupon_amount" => $discountAmount,
            "coupon_used" => $findcoupon->coupon_code,
        ]);
    }


    public function order_track_result(Request $request)
{
    $phone = $request->phone;
    $invoice_id = $request->invoice_id;

    
    if (!$phone && !$invoice_id) {
        return response()->json(
            ["status" => "error", "message" => "Please provide phone or invoice ID!"],
            400
        );
    }

    $query = Order::with(["shipping", "status", "orderdetails" => function ($q) {
        $q->select("id", "order_id", "product_id", "product_name", "sale_price", "qty","product_size", "product_color","product_type");
    }]);

    if ($phone) {
        $query->whereHas("shipping", function ($q) use ($phone) {
            $q->where("phone", $phone);
        });
    }

    if ($invoice_id) {
        $query->where("invoice_id", $invoice_id);
    }

    $order = $query->get();

    if ($order->isEmpty()) {
        return response()->json(
            ["status" => "error", "message" => "Data not found!"],
            400
        );
    }

    return response()->json(
        ["status" => "success", "order" => $order],
        200
    );
}


    public function invoice($id)
    {
        $order = Order::where([
            "id" => $id,
            "customer_id" => Auth::guard("customerapi")->user()->id,
        ])
            ->with(
                "orderdetails",
                "payment",
                "shipping",
                "customer",
                "orderdetails.image"
            )
            ->firstOrFail();
        $owner = GeneralSetting::where('status',1)->select('id','name','white_logo','dark_logo')->first();
        $companyinfo = Contact::where('status',1)->select('id','hotline','hotmail','phone','email','address')->first();

        return response()->json(["status" => "success", "order" => $order,'owner'=>$owner, 'companyinfo'=>$companyinfo]);
    }
       public function shippingCharge()
{
 
    $charges =ShippingCharge::where('status', 1)
        ->select('id','name', 'amount')
        ->get();

  
    return response()->json([
        'success' => true,
        'data' => $charges
    ]);
}

    
}