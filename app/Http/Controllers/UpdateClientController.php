<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use ZipArchive;
use App\Models\UpdateHistory;
use Illuminate\Support\Facades\Artisan;

class UpdateClientController extends Controller
{
    public function showUpdatePage(Request $request)
    {
        $updateApiUrl = env('UPDATE_SERVER_URL');

        $response = Http::withHeaders([
            'X-License-Key' => env('LICENSE_KEY')
        ])->get($updateApiUrl);

        $updates = $response->ok() ? $response->json() : [];

        return view('backEnd.updates.version', compact('updates'));
    }

 public function getUpdateDetails(Request $request)
{
    $request->validate(['version' => 'required']);
    
    $updateApiUrl =  'https://webleez.top/api/updates/details?version=' . $request->version;

    $response = Http::withHeaders([
        'X-License-Key' => env('LICENSE_KEY')
    ])->get($updateApiUrl);

    if (!$response->ok()) {
        \Log::error("Update details API failed", [
            'status' => $response->status(),
            'response' => $response->body()
        ]);
        return response()->json(['status' => 'error', 'message' => 'Failed to get update details']);
    }

    return response()->json([
        'status' => 'success',
        'update' => $response->json()
    ]);
}

    public function runUpdateAjax(Request $request)
    {
        $request->merge(['key' => env('LICENSE_KEY')]);
        return $this->runUpdate($request);
    }

 public function runUpdate(Request $request)
{
    // Check if there's an active cooldown in the session
    if ($request->session()->has('last_update_time')) {
        $lastUpdateTime = $request->session()->get('last_update_time');
        $cooldownPeriod = 60; // 1 minute in seconds
        
        if (time() - $lastUpdateTime < $cooldownPeriod) {
            $remainingTime = $cooldownPeriod - (time() - $lastUpdateTime);
            return response()->json([
                'status' => 'error',
                'message' => "Please wait {$remainingTime} seconds before attempting another update."
            ]);
        }
    }

    if ($request->get('key') !== env('LICENSE_KEY')) {
        abort(403, 'Unauthorized');
    }

    try {
        $request->validate([
            'version' => 'required',
            'download_url' => 'required|url'
        ]);

        $version = $request->version;
        $downloadUrl = $request->download_url;

        $zipPath = storage_path("app/temp_update_{$version}.zip");

        if (parse_url($downloadUrl, PHP_URL_HOST) === request()->getHost()) {
            $relativePath = str_replace(url('/'), '', $downloadUrl);
            $localFile = public_path($relativePath);

            if (!file_exists($localFile)) {
                return response()->json(['status' => 'error', 'message' => "Local file not found: $localFile"]);
            }

            copy($localFile, $zipPath);
        } else {
            $zipResponse = Http::get($downloadUrl);

            if (!$zipResponse->ok()) {
                return response()->json(['status' => 'error', 'message' => 'Failed to download update file.']);
            }

            file_put_contents($zipPath, $zipResponse->body());
        }

        $zip = new ZipArchive;
        if ($zip->open($zipPath) === true) {
            $zip->extractTo(base_path());
            $zip->close();
            unlink($zipPath);

            Artisan::call('migrate', ['--force' => true]);
           
            Http::post('https://webleez.top/api/updates/log-history', [
                'domain' => request()->getHost(),
                'version' => $version,
                'ip_address' => request()->ip(),
                'user_agent' => request()->header('User-Agent'),
                'updated_at_time' => now(),
            ]);

            // Store the current time in session
            $request->session()->put('last_update_time', time());

            return response()->json([
                'status' => 'success',
                'message' => "System updated to version $version successfully!"
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to extract zip.']);
        }

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Update failed: ' . $e->getMessage()
        ]);
    }
}
    
    public function viewLogs()
{
    $logs = UpdateHistory::latest()->paginate(20);

    return view('backEnd.updates.update_logs', compact('logs'));
}

}
