<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Force Public Path Handling
|--------------------------------------------------------------------------
*/
$request = Request::capture();

// If the request starts with /public, we need to handle it properly
// This fixes the issue where assets are requested using /public/ path
$uri = $_SERVER['REQUEST_URI'];
if (strpos($uri, '/public/') === 0) {
    // Get the path without query string and strip /public prefix
    $cleanPath = urldecode(parse_url($uri, PHP_URL_PATH));
    $filePath = __DIR__ . $cleanPath;
    
    // Normalize path separators for Windows
    $filePath = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $filePath);

    if (file_exists($filePath) && is_file($filePath)) {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $mimes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'webp' => 'image/webp',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf'
        ];
        
        header('Content-Type: ' . ($mimes[$extension] ?? 'application/octet-stream'));
        header('Access-Control-Allow-Origin: *');
        header('Cache-Control: public, max-age=3600');
        readfile($filePath);
        exit;
    }
}

$kernel = $app->make(Kernel::class);

$response = $kernel->handle($request)->send();

$kernel->terminate($request, $response);
