<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UpdatePermission;

class UpdatePermissionController extends Controller
{
    public function index()
    {
        $permissions = UpdatePermission::all();
        return view('backEnd.permissions.update-permission', compact('permissions'));
    }

    public function update(Request $request)
    {
        
        $data = $request->input('permissions', []);

        
        UpdatePermission::query()->update(['status' => false]);

       
        foreach ($data as $id) {
            UpdatePermission::where('id', $id)->update(['status' => true]);
        }

        return redirect()->route('backEnd.permissions.index')->with('success', 'Permissions updated successfully!');
    }
}
