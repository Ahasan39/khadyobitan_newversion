<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Customer;

class InertiaAuthController extends Controller
{
    /**
     * Display login page
     */
    public function showLogin()
    {
        // If already logged in, redirect to account
        if (Auth::guard('customer')->check()) {
            return redirect()->route('account.show');
        }
        
        return Inertia::render('Login', [
            'currentPath' => '/login',
        ]);
    }

    /**
     * Handle login (supports both email and phone)
     */
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        // Check if customer exists
        $customer = Customer::where('phone', $request->phone)->first();
        
        if (!$customer) {
            return back()->withErrors([
                'phone' => 'No account found with this phone number.',
            ]);
        }

        if (Auth::guard('customer')->attempt(['phone' => $request->phone, 'password' => $request->password])) {
            $request->session()->regenerate();

            // If cart has items, redirect to checkout
            if (Cart::instance('shopping')->count() > 0) {
                return redirect()->intended(route('checkout.show'));
            }

            return redirect()->intended(route('account.show'));
        }

        return back()->withErrors([
            'password' => 'The provided credentials are incorrect.',
        ]);
    }

    /**
     * Display register page
     */
    public function showRegister()
    {
        // If already logged in, redirect to account
        if (Auth::guard('customer')->check()) {
            return redirect()->route('account.show');
        }
        
        return Inertia::render('Login', [
            'currentPath' => '/register',
            'isRegister' => true,
        ]);
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:customers,phone',
            'email' => 'nullable|email',
            'password' => 'required|string|min:6',
        ]);

        $last_id = Customer::orderBy('id', 'desc')->first();
        $last_id = $last_id ? $last_id->id + 1 : 1;

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->slug = strtolower(Str::slug($request->name . '-' . $last_id));
        $customer->phone = $request->phone;
        $customer->email = $request->email ?? '';
        $customer->password = bcrypt($request->password);
        $customer->verify = 1;
        $customer->status = 'active';
        $customer->save();

        Auth::guard('customer')->login($customer);

        return redirect()->route('account.show')->with('success', 'Account created successfully!');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }
}
