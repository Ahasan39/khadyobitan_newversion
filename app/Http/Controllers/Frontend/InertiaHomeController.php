<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Banner;

class InertiaHomeController extends Controller
{
    /**
     * Display the homepage with all featured products and categories
     */
    public function index()
    {
        $homeData = Cache::remember('home_page_data', 60 * 60, function() {
            return [
                'sliders' => $this->getSliders(),
                'sliderrightads' => $this->getSliderRightAds(),
                'popup_banner' => $this->getPopupBanner(),
                'hotdeal_top' => $this->getHotDealTop(),
                'new_arrival' => $this->getNewArrival(),
                'top_rated' => $this->getTopRated(),
                'top_selling' => $this->getTopSelling(),
                'homecategory' => $this->getHomeCategory(),
                'brands' => $this->getBrands(),
            ];
        });

        $all_products = Product::where('status', 1)
            ->with('image')
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'category_id')
            ->paginate(12);

        return Inertia::render('Index', array_merge($homeData, [
            'all_products' => $all_products,
            'currentPath' => '/',
        ]));
    }

    /**
     * Get slider banners
     */
    private function getSliders()
    {
        return Banner::where(['status' => 1, 'category_id' => 1])
            ->select('id', 'image', 'link')
            ->get();
    }

    /**
     * Get right side ads
     */
    private function getSliderRightAds()
    {
        return Banner::where(['status' => 1, 'category_id' => 2])
            ->select('id', 'image', 'link')
            ->limit(2)
            ->get();
    }

    /**
     * Get popup banner
     */
    private function getPopupBanner()
    {
        return Banner::where(['status' => 1, 'category_id' => 3])
            ->select('id', 'image', 'link')
            ->first();
    }

    /**
     * Get hot deal products
     */
    private function getHotDealTop()
    {
        return Product::where(['status' => 1, 'topsale' => 1])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'category_id')
            ->orderBy('id', 'DESC')
            ->withCount('variable')
            ->limit(12)
            ->get();
    }

    /**
     * Get new arrival products
     */
    private function getNewArrival()
    {
        return Product::where(['status' => 1, 'new_arrival' => 1])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'category_id')
            ->orderBy('id', 'DESC')
            ->withCount('variable')
            ->limit(12)
            ->get();
    }

    /**
     * Get top rated products
     */
    private function getTopRated()
    {
        return Product::where(['status' => 1, 'top_rated' => 1])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'category_id')
            ->orderBy('id', 'DESC')
            ->withCount('variable')
            ->limit(12)
            ->get();
    }

    /**
     * Get top selling products
     */
    private function getTopSelling()
    {
        return Product::where(['status' => 1, 'top_selling' => 1])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'category_id')
            ->orderBy('id', 'DESC')
            ->withCount('variable')
            ->limit(12)
            ->get();
    }

    /**
     * Get home page categories
     */
    private function getHomeCategory()
    {
        return Category::where(['front_view' => 1, 'status' => 1])
            ->select('id', 'name', 'slug', 'front_view', 'status')
            ->orderBy('id', 'ASC')
            ->get();
    }

    /**
     * Get brands
     */
    private function getBrands()
    {
        return Brand::where(['status' => 1])
            ->select('id', 'name', 'image', 'slug')
            ->orderBy('id', 'ASC')
            ->get();
    }
}
