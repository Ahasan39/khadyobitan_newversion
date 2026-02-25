<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CheckoutContent;
use Illuminate\Http\Request;

class CheckoutContentController extends Controller
{
    public function index()
    {
        $contents = CheckoutContent::all()->keyBy('key_name');
        return view('backEnd.admin.checkout_content', compact('contents'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            CheckoutContent::updateOrCreate(
                ['key_name' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Checkout content updated successfully!');
    }
}


