<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Compress Response Middleware
 * 
 * Compresses HTTP responses to reduce bandwidth and improve load times
 * Works in conjunction with server-level compression (.htaccess)
 */
class CompressResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only compress if client accepts gzip
        if (!$this->shouldCompress($request, $response)) {
            return $response;
        }

        // Get response content
        $content = $response->getContent();

        // Compress content
        $compressed = gzencode($content, 6); // Compression level 6 (balance between speed and size)

        // Set compressed content
        $response->setContent($compressed);

        // Set headers
        $response->headers->set('Content-Encoding', 'gzip');
        $response->headers->set('Content-Length', strlen($compressed));
        $response->headers->remove('Transfer-Encoding');

        return $response;
    }

    /**
     * Determine if response should be compressed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return bool
     */
    private function shouldCompress(Request $request, Response $response): bool
    {
        // Check if gzip is available
        if (!function_exists('gzencode')) {
            return false;
        }

        // Check if client accepts gzip
        $acceptEncoding = $request->header('Accept-Encoding', '');
        if (stripos($acceptEncoding, 'gzip') === false) {
            return false;
        }

        // Don't compress if already compressed
        if ($response->headers->has('Content-Encoding')) {
            return false;
        }

        // Only compress text-based content
        $contentType = $response->headers->get('Content-Type', '');
        $compressibleTypes = [
            'text/html',
            'text/css',
            'text/javascript',
            'application/javascript',
            'application/json',
            'application/xml',
            'text/xml',
            'text/plain',
        ];

        foreach ($compressibleTypes as $type) {
            if (stripos($contentType, $type) !== false) {
                return true;
            }
        }

        return false;
    }
}
