<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ThemeColor;

class ThemeColorController extends Controller
{
    public function index()
    {
        $data = ThemeColor::first();

        return response()->json([
            'status' => true,
            'theme_colors' => $data
        ]);
    }
}