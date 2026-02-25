<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// Logging function for debugging
function debug_log($message) {
    file_put_contents(__DIR__ . '/server_debug.log', "[" . date('Y-m-d H:i:s') . "] " . $message . PHP_EOL, FILE_APPEND);
}

// Handle /public/* requests by stripping the '/public' prefix
if (strpos($uri, '/public/') === 0) {
    // Strip '/public' (7 characters)
    $actualPath = substr($uri, 7); 
    $file = __DIR__ . '/public' . $actualPath;
    
    // Normalize path for Windows/Unix consistency
    $file = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file);
    
    if (file_exists($file) && is_file($file)) {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'webp' => 'image/webp',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'ico' => 'image/x-icon',
        ];
        
        $contentType = $mimeTypes[$extension] ?? 'application/octet-stream';
        header('Content-Type: ' . $contentType);
        header('Cache-Control: public, max-age=3600');
        readfile($file);
        exit;
    } else {
        debug_log("FAILED to find file: " . $file . " for URI: " . $uri);
    }
}

// Fallback for requests without /public/ prefix
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require_once __DIR__.'/public/index.php';
