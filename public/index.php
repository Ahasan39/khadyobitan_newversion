<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$request = Request::capture();

/*
|--------------------------------------------------------------------------
| Local Asset Path Handling
|--------------------------------------------------------------------------
*/
$uri = $request->getRequestUri();
if (strpos($uri, '/public/') === 0) {
    // We are in /public/ folder, so we look for files relative to this folder's parent
    $cleanPath = urldecode(parse_url($uri, PHP_URL_PATH));
    $filePath = __DIR__ . '/..' . $cleanPath;
    $filePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $filePath);

    if (file_exists($filePath) && is_file($filePath)) {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $mimes = [
            'css' => 'text/css', 'js' => 'application/javascript', 
            'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 
            'gif' => 'image/gif', 'svg' => 'image/svg+xml', 'webp' => 'image/webp',
            'ico' => 'image/x-icon', 'woff' => 'font/woff', 'woff2' => 'font/woff2', 'ttf' => 'font/ttf'
        ];
        header('Content-Type: ' . ($mimes[$extension] ?? 'application/octet-stream'));
        header('Access-Control-Allow-Origin: *');
        readfile($filePath);
        exit;
    }
}

$response = tap($kernel->handle($request))->send();

$kernel->terminate($request, $response);
