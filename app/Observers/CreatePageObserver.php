<?php

namespace App\Observers;

use App\Models\CreatePage;
use Illuminate\Support\Facades\Cache;

/**
 * CreatePage Observer
 * 
 * Automatically clears cache when pages are created, updated, or deleted
 */
class CreatePageObserver
{
    /**
     * Handle the CreatePage "created" event.
     */
    public function created(CreatePage $page): void
    {
        $this->clearCache();
    }

    /**
     * Handle the CreatePage "updated" event.
     */
    public function updated(CreatePage $page): void
    {
        $this->clearCache();
    }

    /**
     * Handle the CreatePage "deleted" event.
     */
    public function deleted(CreatePage $page): void
    {
        $this->clearCache();
    }

    /**
     * Handle the CreatePage "restored" event.
     */
    public function restored(CreatePage $page): void
    {
        $this->clearCache();
    }

    /**
     * Handle the CreatePage "force deleted" event.
     */
    public function forceDeleted(CreatePage $page): void
    {
        $this->clearCache();
    }

    /**
     * Clear relevant caches
     */
    private function clearCache(): void
    {
        Cache::forget('pages');
    }
}
