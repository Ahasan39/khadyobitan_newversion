<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

/**
 * Category Observer
 * 
 * Automatically clears cache when categories are created, updated, or deleted
 */
class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        $this->clearCache();
    }

    /**
     * Clear relevant caches
     */
    private function clearCache(): void
    {
        Cache::forget('categories');
        Cache::forget('api.categories.all');
        
        // Clear product caches as they depend on categories
        Cache::forget('api.products.all');
    }
}
