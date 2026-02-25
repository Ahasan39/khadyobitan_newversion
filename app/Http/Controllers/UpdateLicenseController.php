<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UpdateLicense;
use Illuminate\Support\Str;

class UpdateLicenseController extends Controller
{
   

public function index(Request $request)
{
    $query = UpdateLicense::query();

    if ($request->filled('version')) {
        $query->where('version', 'like', '%' . $request->version . '%');
    }

    $licenses = $query->latest()->paginate(20);

    return view('backEnd.updates.licenses.index', compact('licenses'));
}



public function generate(Request $request)
{
    $request->validate([
        'version' => 'required',
        'quantity' => 'required|integer|min:1|max:100',
    ]);

    for ($i = 0; $i < $request->quantity; $i++) {
        UpdateLicense::create([
            'version' => $request->version,
            'license_key' => strtoupper(Str::uuid()),
        ]);
    }

    return back()->with('success', 'License keys generated.');
}

}
