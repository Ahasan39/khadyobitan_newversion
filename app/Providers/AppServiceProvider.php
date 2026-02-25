<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\GeneralSetting;
use App\Models\Category;
use App\Models\SocialMedia;
use App\Models\Contact;
use App\Models\CreatePage;
use App\Models\OrderStatus;
use App\Models\EcomPixel;
use App\Models\GoogleTagManager;
use App\Models\CouponCode;
use App\Models\Order;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Subcategory;
use App\Models\Campaign;
use App\Models\CheckoutLead;
use App\Observers\ProductObserver;
use App\Observers\CategoryObserver;
use App\Observers\SubcategoryObserver;
use App\Observers\CampaignObserver;
use App\Observers\BrandObserver;
use App\Observers\CreatePageObserver;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
public function boot()
{
    // Observers
    Product::observe(ProductObserver::class);
    Brand::observe(BrandObserver::class);
    Category::observe(CategoryObserver::class);
    Subcategory::observe(SubcategoryObserver::class);
    Campaign::observe(CampaignObserver::class);
    CreatePage::observe(CreatePageObserver::class);

    // View Composer
    view()->composer('*', function ($view) {

        //  Long-lived cache for rarely changing data (7 days)
        $generalsetting = Cache::remember('generalsetting', now()->addDays(7), function () {
            return GeneralSetting::where('status', 1)->first();
        });

        $coupon = Cache::remember('coupon', now()->addDays(7), function () {
            return CouponCode::where('status', 1)->first();
        });

        $contact = Cache::remember('contact', now()->addDays(7), function () {
            return Contact::where('status', 1)->first();
        });

        $socialicons = Cache::remember('socialicons', now()->addDays(7), function () {
            return SocialMedia::where('status', 1)->get();
        });

        $pages = Cache::remember('pages', now()->addDays(7), function () {
            return CreatePage::where('status', 1)->get();
        });

        $orderstatus = Cache::remember('orderstatus', now()->addDays(7), function () {
            return OrderStatus::where('status', 1)->get();
        });

        $pixels = Cache::remember('pixels', now()->addDays(7), function () {
            return EcomPixel::where('status', 1)->get();
        });

        $gtm_code = Cache::remember('gtm_code', now()->addDays(7), function () {
            return GoogleTagManager::get();
        });

        //  Frequently changing data (short cache)
        $categories = Cache::remember('categories', 60*60, function () {
            return Category::where('status', 1)
                ->select('id','name','slug','status','image')
                ->get();
        });

        $neworder = Cache::remember('neworder_count', 60, function () {
            return Order::where('order_status','1')->where('is_seen', false)->count();
        });

        $pendingorder = Cache::remember('pending_orders', 60, function () {
            return Order::where('order_status','1')->where('is_seen', false)
                ->latest()->limit(9)->get();
        });

        $incompleteOrdersCount = Cache::remember('incomplete_orders_count', 60, function () {
            return CheckoutLead::where('status', 'pending')->count();
        });

        // Pass all data to views
        $view->with([
            'generalsetting' => $generalsetting,
            'coupon' => $coupon,
            'categories' => $categories,
            'contact' => $contact,
            'socialicons' => $socialicons,
            'pages' => $pages,
            'neworder' => $neworder,
            'pendingorder' => $pendingorder,
            'orderstatus' => $orderstatus,
            'pixels' => $pixels,
            'gtm_code' => $gtm_code,
            'incompleteOrdersCount' => $incompleteOrdersCount,
        ]);
    });
}

}
