<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ThemeColorSetting;

class ThemeColorSettingController extends Controller
{
   public function index()
    {
        $setting = ThemeColorSetting::first();
        return view('backEnd.theme_setting.index', compact('setting'));
    }

    // Store/Update theme color
    public function store(Request $request)
    {
        
        $request->validate([
            'color' => 'required|string',
        ]);

        ThemeColorSetting::updateOrCreate(
            ['id' => 1],
            ['color' => $request->color]
        );

        return back()->with('success', 'Theme color updated successfully');
    }

    // API: Send current theme color
    public function getThemeColor()
    {
        $setting = ThemeColorSetting::first();

        return response()->json([
            'theme_color' => $setting ? $setting->color : '#000000'
        ]);
    }
}

