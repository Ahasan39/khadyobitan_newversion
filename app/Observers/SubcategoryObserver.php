<?php

namespace App\Observers;

use App\Models\Subcategory;
use Illuminate\Support\Facades\Cache;

/**
 * Subcategory Observer
 * 
 * Automatically clears cache when subcategories are created, updated, or deleted
 */
class SubcategoryObserver
{
    /**
     * Handle the Subcategory "created" event.
     */
    public function created(Subcategory $subcategory): void
    {
        $this->clearCache($subcategory);
    }

    /**
     * Handle the Subcategory "updated" event.
     */
    public function updated(Subcategory $subcategory): void
    {
        $this->clearCache($subcategory);
    }

    /**
     * Handle the Subcategory "deleted" event.
     */
    public function deleted(Subcategory $subcategory): void
    {
        $this->clearCache($subcategory);
    }

    /**
     * Handle the Subcategory "restored" event.
     */
    public function restored(Subcategory $subcategory): void
    {
        $this->clearCache($subcategory);
    }

    /**
     * Handle the Subcategory "force deleted" event.
     */
    public function forceDeleted(Subcategory $subcategory): void
    {
        $this->clearCache($subcategory);
    }

    /**
     * Clear relevant caches
     */
    private function clearCache(Subcategory $subcategory): void
    {
        // Clear subcategory-specific cache
        if ($subcategory->slug) {
            Cache::forget("api.subcategory.{$subcategory->slug}.products");
        }
        
        // Clear general caches
        Cache::forget('api.products.all');
    }
}
