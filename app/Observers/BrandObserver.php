<?php

namespace App\Observers;

use App\Models\Brand;
use Illuminate\Support\Facades\Cache;

/**
 * Brand Observer
 * 
 * Automatically clears cache when brands are created, updated, or deleted
 */
class BrandObserver
{
    /**
     * Handle the Brand "created" event.
     */
    public function created(Brand $brand): void
    {
        $this->clearCache($brand);
    }

    /**
     * Handle the Brand "updated" event.
     */
    public function updated(Brand $brand): void
    {
        $this->clearCache($brand);
    }

    /**
     * Handle the Brand "deleted" event.
     */
    public function deleted(Brand $brand): void
    {
        $this->clearCache($brand);
    }

    /**
     * Handle the Brand "restored" event.
     */
    public function restored(Brand $brand): void
    {
        $this->clearCache($brand);
    }

    /**
     * Handle the Brand "force deleted" event.
     */
    public function forceDeleted(Brand $brand): void
    {
        $this->clearCache($brand);
    }

    /**
     * Clear relevant caches
     */
    private function clearCache(Brand $brand): void
    {
        // Clear brand-specific cache
        if ($brand->slug) {
            Cache::forget("api.brand.{$brand->slug}.products");
        }
        
        // Clear general caches
        Cache::forget('brands.all');
        Cache::forget('api.products.all');
    }
}
