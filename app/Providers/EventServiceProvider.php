<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\ProductViewed;
use App\Listeners\SendProductViewWebhook;
use App\Events\OrderPlaced;
use App\Listeners\SendOrderPlacedWebhook;
use App\Events\AddToCart;
use App\Listeners\SendAddToCartWebhook;
use App\Events\OrderNow;
use App\Listeners\SendOrderNowWebhook;
use App\Events\OrderStatusChanged;
use App\Listeners\SendOrderStatusWebhook;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ProductViewed::class => [
            SendProductViewWebhook::class,
        ],
        AddToCart::class => [
            SendAddToCartWebhook::class,
        ],
        OrderNow::class => [
            SendOrderNowWebhook::class,
        ],
        OrderPlaced::class => [
            SendOrderPlacedWebhook::class,
        ],
        OrderStatusChanged::class => [
            SendOrderStatusWebhook::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
    
}
