<?php

namespace App\Observers;

use App\Models\Campaign;
use Illuminate\Support\Facades\Cache;

/**
 * Campaign Observer
 * 
 * Automatically clears cache when campaigns are created, updated, or deleted
 */
class CampaignObserver
{
    /**
     * Handle the Campaign "created" event.
     */
    public function created(Campaign $campaign): void
    {
        $this->clearCache($campaign);
    }

    /**
     * Handle the Campaign "updated" event.
     */
    public function updated(Campaign $campaign): void
    {
        $this->clearCache($campaign);
    }

    /**
     * Handle the Campaign "deleted" event.
     */
    public function deleted(Campaign $campaign): void
    {
        $this->clearCache($campaign);
    }

    /**
     * Handle the Campaign "restored" event.
     */
    public function restored(Campaign $campaign): void
    {
        $this->clearCache($campaign);
    }

    /**
     * Handle the Campaign "force deleted" event.
     */
    public function forceDeleted(Campaign $campaign): void
    {
        $this->clearCache($campaign);
    }

    /**
     * Clear relevant caches
     */
    private function clearCache(Campaign $campaign): void
    {
        // Clear campaign-specific cache
        if ($campaign->id) {
            Cache::forget("api.campaign.{$campaign->id}.products");
        }
        
        if ($campaign->slug) {
            Cache::forget("api.campaign.{$campaign->slug}.products");
        }
        
        // Clear general caches
        Cache::forget('campaigns.all');
        Cache::forget('api.products.all');
    }
}
