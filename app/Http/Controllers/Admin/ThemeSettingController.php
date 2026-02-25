<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ThemeSetting;

class ThemeSettingController extends Controller
{
    public function index()
    {
        $setting = ThemeSetting::first();
        return view('backEnd.theme_setting.index', compact('setting'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'header_color' => 'nullable|string|max:20',
            'footer_color' => 'nullable|string|max:20',
            'text_color' => 'nullable|string|max:20',
            'button_color' => 'nullable|string|max:20',
            'add_to_cart_color' => 'nullable|string|max:20',
            'price_color' => 'nullable|string|max:20',
            'single_order_color' => 'nullable|string|max:20',
            'checkout_header_color' => 'nullable|string|max:20',
            'checkout_order_color' => 'nullable|string|max:20',
        ]);

        $setting = ThemeSetting::first();

        if ($setting) {
            $setting->update($request->all());
        } else {
            ThemeSetting::create($request->all());
        }

        return redirect()->back()->with('success', 'Theme color updated successfully!');
    }
}

