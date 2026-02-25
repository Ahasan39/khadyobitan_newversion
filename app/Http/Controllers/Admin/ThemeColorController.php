<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ThemeColor;

class ThemeColorController extends Controller
{
    public function index()
    {
        $data = ThemeColor::first();
      
        return view('backEnd.theme_setting.index', compact('data'));
    }

    public function store(Request $request)
    {
        ThemeColor::updateOrCreate(
            ['id' => 1],
            $request->all()
        );

        return back()->with('success', 'Theme colors updated successfully');
    }
}
