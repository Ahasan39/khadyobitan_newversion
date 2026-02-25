<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

/**
 * Response Cache Service
 * 
 * Provides methods to cache HTTP responses for improved performance
 * Automatically generates cache keys based on request parameters
 */
class ResponseCacheService
{
    /**
     * Default cache duration in minutes
     */
    private const DEFAULT_DURATION = 60;

    /**
     * Cache a response
     * 
     * @param string $key Base cache key
     * @param callable $callback Function that returns the data to cache
     * @param int $minutes Cache duration in minutes
     * @param Request|null $request Request object for dynamic key generation
     * @return mixed
     */
    public static function remember(
        string $key, 
        callable $callback, 
        int $minutes = self::DEFAULT_DURATION,
        ?Request $request = null
    ) {
        $cacheKey = self::generateCacheKey($key, $request);
        
        return Cache::remember($cacheKey, $minutes * 60, $callback);
    }

    /**
     * Cache a response with tags (requires Redis or Memcached)
     * 
     * @param array $tags Cache tags for easy invalidation
     * @param string $key Base cache key
     * @param callable $callback Function that returns the data to cache
     * @param int $minutes Cache duration in minutes
     * @param Request|null $request Request object for dynamic key generation
     * @return mixed
     */
    public static function rememberWithTags(
        array $tags,
        string $key,
        callable $callback,
        int $minutes = self::DEFAULT_DURATION,
        ?Request $request = null
    ) {
        $cacheKey = self::generateCacheKey($key, $request);
        
        return Cache::tags($tags)->remember($cacheKey, $minutes * 60, $callback);
    }

    /**
     * Forget a cached response
     * 
     * @param string $key Cache key
     * @param Request|null $request Request object for dynamic key generation
     * @return bool
     */
    public static function forget(string $key, ?Request $request = null): bool
    {
        $cacheKey = self::generateCacheKey($key, $request);
        
        return Cache::forget($cacheKey);
    }

    /**
     * Flush cache by tags (requires Redis or Memcached)
     * 
     * @param array $tags Cache tags to flush
     * @return bool
     */
    public static function flushTags(array $tags): bool
    {
        try {
            Cache::tags($tags)->flush();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Generate a unique cache key based on request parameters
     * 
     * @param string $baseKey Base cache key
     * @param Request|null $request Request object
     * @return string
     */
    private static function generateCacheKey(string $baseKey, ?Request $request = null): string
    {
        if (!$request) {
            return $baseKey;
        }

        // Include query parameters in cache key
        $queryParams = $request->query();
        ksort($queryParams);
        
        $queryString = http_build_query($queryParams);
        
        if (empty($queryString)) {
            return $baseKey;
        }

        return $baseKey . ':' . md5($queryString);
    }

    /**
     * Check if a cache key exists
     * 
     * @param string $key Cache key
     * @param Request|null $request Request object for dynamic key generation
     * @return bool
     */
    public static function has(string $key, ?Request $request = null): bool
    {
        $cacheKey = self::generateCacheKey($key, $request);
        
        return Cache::has($cacheKey);
    }

    /**
     * Get cached value without callback
     * 
     * @param string $key Cache key
     * @param mixed $default Default value if cache miss
     * @param Request|null $request Request object for dynamic key generation
     * @return mixed
     */
    public static function get(string $key, $default = null, ?Request $request = null)
    {
        $cacheKey = self::generateCacheKey($key, $request);
        
        return Cache::get($cacheKey, $default);
    }

    /**
     * Put a value in cache
     * 
     * @param string $key Cache key
     * @param mixed $value Value to cache
     * @param int $minutes Cache duration in minutes
     * @param Request|null $request Request object for dynamic key generation
     * @return bool
     */
    public static function put(
        string $key, 
        $value, 
        int $minutes = self::DEFAULT_DURATION,
        ?Request $request = null
    ): bool {
        $cacheKey = self::generateCacheKey($key, $request);
        
        return Cache::put($cacheKey, $value, $minutes * 60);
    }

    /**
     * Clear all application cache
     * Use with caution in production
     * 
     * @return bool
     */
    public static function clearAll(): bool
    {
        try {
            Cache::flush();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get cache statistics (if supported by driver)
     * 
     * @return array
     */
    public static function getStats(): array
    {
        try {
            $store = Cache::getStore();
            
            if (method_exists($store, 'getMemcached')) {
                return $store->getMemcached()->getStats();
            }
            
            if (method_exists($store, 'getRedis')) {
                return $store->getRedis()->info();
            }
            
            return ['message' => 'Statistics not available for current cache driver'];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Warm up cache with common queries
     * Call this after deployment or cache clear
     * 
     * @return array Results of cache warming
     */
    public static function warmUp(): array
    {
        $results = [];

        try {
            // Cache categories
            $results['categories'] = Cache::remember('categories.all', 3600, function () {
                return \App\Models\Category::where('status', 1)
                    ->select('id', 'name', 'slug')
                    ->get();
            });

            // Cache brands
            $results['brands'] = Cache::remember('brands.all', 3600, function () {
                return \App\Models\Brand::where('status', 1)
                    ->select('id', 'name', 'slug')
                    ->get();
            });

            // Cache settings
            $results['settings'] = Cache::remember('settings.general', 3600, function () {
                return \App\Models\GeneralSetting::first();
            });

            return [
                'success' => true,
                'message' => 'Cache warmed up successfully',
                'cached_items' => count($results)
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Cache warm up failed',
                'error' => $e->getMessage()
            ];
        }
    }
}
