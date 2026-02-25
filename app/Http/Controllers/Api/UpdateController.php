<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Update;
use App\Models\Client;
use App\Models\UpdateHistory;
use App\Models\UpdateLicense;


class UpdateController extends Controller
{

public function latest(Request $request)
{
    $licenseKey = $request->header('X-License-Key');
    
    // if ($licenseKey !== env('LICENSE_KEY')) {
    //     return response()->json(['error' => 'Invalid license key'], 401);
    // }

    $updates = Update::latest()->get()->map(function ($update) {
        $filePath = storage_path('app/public/' . $update->file_path);

        return [
            'version' => $update->version,
            'title' => $update->title,
            'description' => $update->description,
            'download_url' => 'https://webleez.top/storage/app/public/' . $update->file_path,
            'force_update' => $update->force_update,
            'release_date' => $update->created_at->format('Y-m-d'),
            'file_size' => file_exists($filePath) ? filesize($filePath) / (1024 * 1024) : 0
        ];
    });

    return response()->json($updates);
}

public function details(Request $request)
{
    $version = $request->input('version');
    $update = Update::where('version', $version)->firstOrFail();
    
    return response()->json([
        'version' => $update->version,
        'title' => $update->title,
        'description' => $update->description,
        'download_url' => 'https://webleez.top/storage/app/public/' . $update->file_path,
        'release_date' => $update->created_at->format('Y-m-d'),
        'changelog' => $update->changelog ? json_decode($update->changelog) : []
    ]);
}

    public function upload(Request $request)
{
    $request->validate([
        'version' => 'required',
        'file' => 'required|file|mimes:zip|max:102400', // Max 100MB
    ]);

    $file = $request->file('file');
    $fileName = "update_{$request->version}_" . time() . ".zip";
    $path = $file->storeAs('updates', $fileName, 'public');

    $update = Update::create([
        'version' => $request->version,
        'title' => $request->title ?? "Update {$request->version}",
        'description' => $request->description ?? '',
        'file_path' => $path,
        'force_update' => $request->force_update ?? false,
    ]);

    return response()->json([
        'message' => 'Update uploaded successfully',
        'download_url' => secure_url('storage/' . $path)
    ]);
}
    
    public function form()
{
    return view('update_upload');
}

    public function logHistory(Request $request)
    {
    $request->validate([
        'domain' => 'required',
        'version' => 'required',
        'updated_at_time' => 'required|date',
    ]);

    UpdateHistory::create([
        'domain' => $request->domain,
        'version' => $request->version,
        'ip_address' => $request->ip(),
        'user_agent' => $request->user_agent,
        'updated_at_time' => $request->updated_at_time,
    ]);

    return response()->json(['status' => 'success', 'message' => 'History logged successfully']);
}




public function validateLicense(Request $request)
{
 
    $request->validate([
        'version' => 'required',
        'license_key' => 'required',
    ]);

    $license = UpdateLicense::where('license_key', $request->license_key)
                ->where('version', $request->version)
                ->first();

    if (!$license) {
        return response()->json(['status' => 'error', 'message' => 'Invalid license key']);
    }

    if ($license->is_used) {
        return response()->json(['status' => 'error', 'message' => 'License key already used']);
    }

    // মার্ক অ্যাজ ইউজড
    $license->update(['is_used' => true, 'used_at' => now()]);

    return response()->json(['status' => 'success', 'message' => 'License verified']);
}




}
