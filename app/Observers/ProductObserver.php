<?php

namespace App\Observers;

use App\Models\Product;
use App\Http\Controllers\Api\OptimizedFrontendController;
use Illuminate\Support\Facades\Cache;

/**
 * Product Observer
 * 
 * Automatically clears cache when products are created, updated, or deleted
 * This ensures that cached data stays fresh and accurate
 */
class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->clearCache($product);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $this->clearCache($product);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $this->clearCache($product);
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        $this->clearCache($product);
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        $this->clearCache($product);
    }

    /**
     * Clear relevant caches for the product
     */
    private function clearCache(Product $product): void
    {
        // Clear general product caches
        Cache::forget('api.products.all');
        Cache::forget('api.products.featured');
        Cache::forget('api.products.top_rated');

        // Clear category-specific cache
        if ($product->category_id) {
            Cache::forget("api.category.{$product->category_id}.products");
        }

        // Clear subcategory-specific cache
        if ($product->subcategory_id && $product->subcategory) {
            Cache::forget("api.subcategory.{$product->subcategory->slug}.products");
        }

        // Clear childcategory-specific cache
        if ($product->childcategory_id && $product->childcategory) {
            Cache::forget("api.childcategory.{$product->childcategory->slug}.products");
        }

        // Clear brand-specific cache
        if ($product->brand_id && $product->brand) {
            Cache::forget("api.brand.{$product->brand->slug}.products");
        }

        // Clear campaign-specific cache if applicable
        if ($product->campaign_id) {
            Cache::forget("api.campaign.{$product->campaign_id}.products");
        }
    }
}
