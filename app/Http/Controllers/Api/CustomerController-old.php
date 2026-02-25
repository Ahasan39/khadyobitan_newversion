<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use Carbon\Carbon;
use Response;
use Image;
use Hash;
use Auth;
use Mail;
use Str;
use DB;

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
                "order_track",
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
           
            "email" => "required",
           
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

        $customerfind = Customer::where("email", $request->email)->first();
        // return $customerfind;
        
        if(empty($customerfind)){
        $last_id = Customer::orderBy("id", "desc")->count();
        // return $last_id;
        $last_id = $last_id + 1;
        $otp = rand('1111','9999');
        $store = new Customer();
        $store->name = $request->name;
        $store->slug = strtolower(Str::slug($request->name . "-" . $last_id));
        $store->phone = $request->phone;
        $store->password = bcrypt($otp);
        $store->email = $request->email;
        $store->verify = $otp;
        $store->status = "active";
        // return $store;
        $store->save();

        return response()->json([
            "status" => "success",
            "message" => "A 4 digit opt has been sent to your email address",
            "email" => $store->email,
            "verify" => $store->verify,
        ]);
        }else{
            $otp = rand('1111','9999');
            $update = $customerfind;
            $update->verify = $otp;
            $update->password = bcrypt($otp);
            $update->save();
            
             return response()->json([
            "status" => "success",
            "message" => "A 4 digit opt has been sent to your email address",
            "email" => $customerfind->email,
            "verify" => $customerfind->verify,
            ]);
        }
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
        $validator = Validator::make($request->all(), [
            "otp" => "required",
            "password" => "required",
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
        //  return $request;
        $forgot_customer = getallheaders()["forgot_customer"] ?? "";
        $customer_info = Customer::where("phone", $forgot_customer)->first();
        if ($customer_info->forgot != $request->otp) {
            return response()->json([
                "status" => "failed",
                "message" => "Your OTP not match",
            ]);
        }
        $customer_info->forgot = 1;
        $customer_info->password = bcrypt($request->password);
        $customer_info->save();
        return response()->json([
            "status" => "success",
            "message" => "Your forgot password reset successfully",
        ]);
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
        // return $request;
        $this->validate($request, [
            "name" => "required",
        ]);
        $update_data = Customer::where([
            "id" => Auth::guard("customerapi")->user()->id,
        ])->first();
        $image = $request->file("image");
        if ($image) {
            // image with intervention
            $name = time() . "-" . $image->getClientOriginalName();
            $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', ".webp", $name);
            $name = strtolower(Str::slug($name));
            $uploadpath = "public/uploads/customer/";
            $imageUrl = $uploadpath . $name;
            $img = Image::make($image->getRealPath());
            $img->encode("webp", 90);
            $width = 120;
            $height = 120;
            $img->resize($width, $height);
            $img->save($imageUrl);
            $imageUrl = $imageUrl;
        } else {
            $imageUrl = $update_data->image;
        }

        $update_data->name = $request->name;
        $update_data->phone = $request->phone;
        $update_data->address = $request->address;
        $update_data->district = $request->district;
        $update_data->area = $request->area;
        $update_data->image = $imageUrl;
        $update_data->save();
        return response()->json([
            "status" => "success",
            "message" => "Your profile update successfully",
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
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        
        $data = $request->all();
       
        if ($data["cart"]) {
            $subtotal = 0;
            $emi_amount = 0;
            $down_payment = 0;
            $monthly_installment = 0;
            foreach ($data["cart"] as $cartitem) {
                $price = Product::select("id", "new_price", "old_price")->find(
                    $cartitem["product_id"]
                );
                
                
                $subtotal += $price->new_price * $cartitem["quantity"];
                
             if (!empty($cartitem["options"]) && isset($cartitem["options"][0])) {
                    $firstOption = $cartitem["options"][0];
                
                    if (!empty($firstOption["emiamount"])) {
                        $emi_amount = $firstOption["emiamount"];
                    }
                
                    if (!empty($firstOption["downpayment"])) {
                        $down_payment = $firstOption["downpayment"];
                    }
                }
                // return $cartitem;
            }
            
            $shipping_area  = ShippingCharge::where('id', $request->area)->first();
            $discount = $request->discount;
            $amount = $subtotal + $shipping_area->shippingfee - $discount;
            $exits_customer = Customer::where("phone", $request->phone)
                ->select("phone", "id")
                ->first();
            if ($exits_customer) {
                $customer_id = $exits_customer->id;
            } else {
                $password = rand(111111, 999999);
                $store = new Customer();
                $store->name = $request->name;
                $store->slug = $request->name;
                $store->email = $request->email ?? "N/A";
                $store->phone = $request->phone;
                $store->password = bcrypt($password);
                $store->verify = 1;
                $store->status = "active";
                $store->save();
                $customer_id = $store->id;
            }
            $customer = Customer::where('id',$customer_id)->first();

            $order                      = new Order();
            $order->invoice_id          = rand(11111, 99999);
            $order->amount              = $amount;
            $order->discount            = $discount ? $discount : 0;
            $order->shipping_charge     = $shipping_area->amount;
            $order->customer_id         = $customer_id;
            $order->customer_ip         = $request->ip() ?? '';
            $order->emi_amount          = $emi_amount ?? 0;
            $order->down_payment        = $down_payment ?? 0;
            $order->monthly_installment = $monthly_installment ?? 0;
            $order->order_status        = 1;
            $order->note                = $request->note;
            $order->save();

            // // shipping data save
            $shipping = new Shipping();
            $shipping->order_id = $order->id;
            $shipping->customer_id = $customer_id;
            $shipping->name = $request->name;
            $shipping->phone = $request->phone;
            $shipping->address = $request->address;
            // $shipping->district = $request->district;
            $shipping->area = $request->area
                ? $shipping_area->area_name
                : "Free Shipping";
            $shipping->save();

            // payment data save
            
            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->customer_id = $customer_id;
            $payment->payment_method = "Cash On Delivery";
            $payment->amount = $order->amount;
            $payment->payment_status = "pending";
            $payment->save();

            foreach ($data["cart"] as $cart) {
                $product = Product::select(
                    "id",
                    "new_price",
                    "old_price",
                    "purchase_price"
                )->find($cart["product_id"]);
                
                $order_details                  =   new OrderDetails();
                $order_details->order_id        =   $order->id;
                
                $order_details->seller_id       =   $cart->options->seller_id ?? 1;
                $order_details->product_id      =   $cart["product_id"];
                $order_details->product_name    =   $cart["name"];
                $order_details->sale_price      =   $product ? $product->new_price : 0;
               
                $order_details->purchase_price = $product ? $product->purchase_price
                    : 0;
                     
                // $order_details->product_color   =   $cart['options']["product_color"];
                // $order_details->product_size    =   $cart['options']["product_size"];
                // $order_details->product_region  =   $cart['options']['region'];
                
                if (!empty($cart['options']) && isset($cart['options'][0])) {
                    $option = $cart['options'][0]; // get the first option object
                
                    $order_details->product_color   = $option["color"] ?? null;
                    $order_details->product_size    = $option["size"] ?? null;
                    $order_details->product_region  = $option["region"] ?? null;
                }

               
                $order_details->product_type    =   $cart->options->type ?? 1;
                
                $order_details->qty             = $cart["quantity"];
                 
                $order_details->save();
            }
            // return  $order_details;
            // Send SMS to the customer
            $site_setting = GeneralSetting::where("status", 1)->first();
            $sms_gateway = SmsGateway::where([
                "status" => 1,
                "order" => "1",
            ])->first();

            $sms_gateway = SmsGateway::where(['status' => 1, 'order' => '1'])->first();
            if ($sms_gateway) {
              $url = "$sms_gateway->url";
                $data = [
                    "api_key" => "$sms_gateway->api_key",
                    "number" => $request->phone,
                    "type" => 'text',
                    "senderid" => "$sms_gateway->serderid",
                    "message" => "Dear $request->name!\r\nYour order ($order->invoice_id) has been successfully placed. Track your order https://gomobilebd.com/customer/order-track and Total Bill $order->amount\r\nThank you for using $site_setting->name"
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
            return response()->json([
                "status" => "success",
                "message" => "Your order place successfully",
            ]);
        } else {
            return response()->json([
                "status" => "failed",
                "message" => "Order failed",
            ]);
        }
    }

    public function orders()
    {
        $orders = Order::where(
            "customer_id",
            Auth::guard("customerapi")->user()->id
        )
            ->with("status")
            ->latest()
            ->get();
        return response()->json([
            "status" => "success",
            "Data fetch successfully",
            "orders" => $orders,
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

    public function order_track(Request $request)
    {
        $phone = $request->phone;
        $invoice_id = $request->invoice_id;
        $order = Order::all();
        if ($phone != null && $invoice_id == null) {
            $order = DB::table("orders")
                ->join("shippings", "orders.id", "=", "shippings.order_id")
                ->where(["shippings.phone" => $request->phone])
                ->get();
            return response()->json(
                ["status" => "error", "message" => "Data not found!"],
                400
            );
        } elseif ($invoice_id && $phone) {
            $order = Order::with(["shipping", "status","orderdetails"])
                ->whereHas("shipping", function ($query) use ($request) {
                    $query->where("phone", $request->phone);
                })
                ->where("invoice_id", $request->invoice_id)
                ->get();

            return response()->json(
                ["status" => "success", "order" => $order],
                200
            );
        }

        if ($order->count() == 0) {
            return response()->json(
                ["status" => "error", "message" => "Something Went Wrong."],
                400
            );
        }

        return response()->json(
            ["status" => "error", "message" => "Something Went Wrong."],
            400
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
}
