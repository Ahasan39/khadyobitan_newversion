<?php

namespace App\Http\Controllers\Admin;

use Spatie\Analytics\Facades\Analytics;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\OrderStatus;
use App\Models\ProductVariable;
use App\Models\SmsGateway;
use App\Models\GeneralSetting;
use Carbon\Carbon;
use Spatie\Analytics\Period;
use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\FilterExpression;
use Google\Analytics\Data\V1beta\Filter\StringFilter;
use Google\Analytics\Data\V1beta\Filter\StringFilter\MatchType;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['locked', 'unlocked']);
    }

    public function dashboard(Request $request)
    {
   
            $order_statuses = OrderStatus::where('status', 1)->withCount('orders')->get();
            $total_sale = Order::where('order_status', 6)->sum('amount');
            $today_order = Order::whereDate('created_at',  Carbon::today())->count();
            $today_sales = Order::where('order_status', 6)->whereDate('created_at',  Carbon::today())->sum('amount');
            $current_month_sale = Order::where('order_status', 6)->whereMonth('created_at', Carbon::now()->month)->sum('amount');
            $total_order = Order::count();
            $current_month_order = Order::whereMonth('created_at', Carbon::now()->month)->count();
            $total_customer = Customer::count();
            $latest_order = Order::latest()->limit(5)->with('customer', 'product', 'product.image')->get();
            $latest_customer = Customer::latest()->limit(5)->get();

            // Chart data preparation
            $dates = [];
            $startDate = Carbon::now()->subDays(29);
            for ($i = 0; $i < 30; $i++) {
                $dates[] = $startDate->copy()->addDays($i)->format('Y-m-d');
            }

            $payments = Order::selectRaw('DATE(created_at) as date, SUM(amount) as total_amount')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->groupBy('date')
                ->pluck('total_amount', 'date');

            $totals = [];
            foreach ($dates as $date) {
                $totals[] = isset($payments[$date]) ? $payments[$date] : 0;
            }

            $dates_json = json_encode($dates);
            $totals_json = json_encode($totals);

            // Low stock products
            $products = Product::select('id', 'name', 'type', 'stock', 'stock_alert')
                ->where('type', 1)
                ->where('stock', '<=', DB::raw('stock_alert'))
                ->with('image')
                ->limit(10)
                ->get();

            $variables = ProductVariable::whereHas('product', function ($query) {
                $query->whereRaw('product_variables.stock <= products.stock_alert');
            })->with('product', 'image')
                ->limit(10)
                ->get();

            // Date range filter
            if ($request->start_date && $request->end_date) {
                $total_order = Order::whereBetween('created_at', [$request->start_date, $request->end_date])->count();
                $return_amount = Order::where('order_status', '8')->whereBetween('created_at', [$request->start_date, $request->end_date])->sum('amount');
                $total_complete = Order::where('order_status', '6')->whereBetween('created_at', [$request->start_date, $request->end_date])->count();
                $total_process = Order::whereNotIn('order_status', ['1', '6', '7', '8'])->whereBetween('created_at', [$request->start_date, $request->end_date])->count();
                $total_return = Order::where('order_status', '9')->whereBetween('created_at', [$request->start_date, $request->end_date])->count();
                $delivery_amount = Order::where('order_status', '6')->whereBetween('created_at', [$request->start_date, $request->end_date])->sum('amount');
                $total_amount = Order::whereBetween('created_at', [$request->start_date, $request->end_date])->sum('amount');
                $process_amount = Order::whereNotIn('order_status', ['1', '6', '7', '8'])->whereBetween('created_at', [$request->start_date, $request->end_date])->sum('amount');
            } else {
                $total_order = Order::count();
                $return_amount = Order::where('order_status', '8')->sum('amount');
                $total_complete = Order::where('order_status', '6')->count();
                $total_process = Order::whereNotIn('order_status', ['1', '6', '7', '8'])->count();
                $total_return = Order::where('order_status', '9')->count();
                $delivery_amount = Order::where('order_status', '6')->sum('amount');
                $total_amount = Order::sum('amount');
                $process_amount = Order::whereNotIn('order_status', ['1', '6', '7', '8'])->sum('amount');
            }

            return view('backEnd.admin.dashboard', compact(
                'order_statuses',
                'total_sale',
                'today_order',
                'today_sales',
                'current_month_sale',
                'total_order',
                'current_month_order',
                'total_customer',
                'latest_order',
                'dates_json',
                'totals_json',
                'products',
                'variables',
                'total_order',
                'return_amount',
                'total_complete',
                'total_process',
                'delivery_amount',
                'total_amount',
                'process_amount',
                'total_return',
                'latest_customer'
            ));

        
    }

    public function changepassword()
    {
        try {
            return view('backEnd.admin.changepassword');
        } catch (\Exception $e) {
            \Log::error('Change password page failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to load change password page');
            return redirect()->route('dashboard');
        }
    }

    public function newpassword(Request $request)
    {
        try {
            $this->validate($request, [
                'old_password' => 'required',
                'new_password' => 'required',
                'confirm_password' => 'required_with:new_password|same:new_password|'
            ]);

            $user = User::findOrFail(Auth::id());
            $hashPass = $user->password;

            if (Hash::check($request->old_password, $hashPass)) {
                $user->fill([
                    'password' => Hash::make($request->new_password)
                ])->save();

                Toastr::success('Success', 'Password changed successfully!');
                return redirect()->route('dashboard');
            } else {
                Toastr::error('Failed', 'Old password not match!');
                return back();
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Toastr::error('Error', 'Validation failed');
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('User not found for password change. ID: ' . Auth::id());
            Toastr::error('Error', 'User not found');
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            \Log::error('Password change failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to change password. Please try again.');
            return back();
        }
    }

    public function locked()
    {
        try {
            Session::put('locked', true);
            return view('backEnd.auth.locked');
        } catch (\Exception $e) {
            \Log::error('Lock screen failed: ' . $e->getMessage());
            return redirect()->route('login');
        }
    }

    public function unlocked(Request $request)
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('login');
            }

            $password = $request->password;
            if (Hash::check($password, Auth::user()->password)) {
                Session::forget('locked');
                Toastr::success('Success', 'You are logged in successfully!');
                return redirect()->route('dashboard');
            }

            Toastr::error('Failed', 'Your password not match!');
            return back();

        } catch (\Exception $e) {
            \Log::error('Unlock screen failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to unlock screen');
            return redirect()->route('login');
        }
    }

    public function send_sms()
    {
        try {
            $customers = Customer::all();
            return view('backEnd.smssend.index', compact('customers'));
        } catch (\Exception $e) {
            \Log::error('SMS send page failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to load SMS send page');
            return redirect()->route('dashboard');
        }
    }

    public function send_sms_post(Request $request)
    {
        try {
            $this->validate($request, [
                'customer_id' => 'required',
                'text' => 'required',
            ]);

            $site_setting = GeneralSetting::where('status', 1)->first();
            $sms_gateway = SmsGateway::where(['status' => 1])->first();

            if (!$sms_gateway) {
                Toastr::error('Error', 'SMS gateway not configured');
                return redirect()->back();
            }

            if ($request->customer_id == 'all') {
                $customers = Customer::all();
                $successCount = 0;
                $failCount = 0;

                foreach ($customers as $customer) {
                    try {
                        $customer_info = Customer::find($customer->id);

                        if (!$customer_info || !$customer_info->phone) {
                            $failCount++;
                            continue;
                        }

                        $url = "$sms_gateway->url";
                        $data = [
                            "api_key" => "$sms_gateway->api_key",
                            "number" => $customer_info->phone,
                            "type" => 'text',
                            "senderid" => "$sms_gateway->serderid",
                            "message" => "$request->text. \r\nThank you for using $site_setting->name"
                        ];

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                        $response = curl_exec($ch);
                        
                        if (curl_errno($ch)) {
                            \Log::error('SMS send failed for customer ' . $customer_info->phone . ': ' . curl_error($ch));
                            $failCount++;
                        } else {
                            $successCount++;
                        }
                        
                        curl_close($ch);

                    } catch (\Exception $e) {
                        \Log::error('SMS send failed for customer ID ' . $customer->id . ': ' . $e->getMessage());
                        $failCount++;
                        continue;
                    }
                }

                if ($successCount > 0) {
                    Toastr::success('Success', "SMS sent successfully to $successCount customers" . ($failCount > 0 ? " ($failCount failed)" : ""));
                } else {
                    Toastr::error('Error', 'Failed to send SMS to all customers');
                }

            } else {
                $customer_info = Customer::findOrFail($request->customer_id);

                if (!$customer_info->phone) {
                    Toastr::error('Error', 'Customer phone number not found');
                    return redirect()->back();
                }

                $url = "$sms_gateway->url";
                $data = [
                    "api_key" => "$sms_gateway->api_key",
                    "number" => $customer_info->phone,
                    "type" => 'text',
                    "senderid" => "$sms_gateway->serderid",
                    "message" => "$request->text. \r\nThank you for using $site_setting->name"
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                $response = curl_exec($ch);

                if (curl_errno($ch)) {
                    \Log::error('SMS send failed: ' . curl_error($ch));
                    Toastr::error('Error', 'Failed to send SMS');
                    curl_close($ch);
                    return redirect()->back();
                }

                curl_close($ch);
                Toastr::success('Success', 'SMS sent successfully');
            }

            return redirect()->route('admin.send_sms');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Customer not found for SMS. ID: ' . $request->customer_id);
            Toastr::error('Error', 'Customer not found');
            return redirect()->back();
        } catch (\Illuminate\Validation\ValidationException $e) {
            Toastr::error('Error', 'Validation failed');
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('SMS send failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to send SMS. Please try again.');
            return redirect()->back();
        }
    }
}