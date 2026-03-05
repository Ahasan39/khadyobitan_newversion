<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\CreatePage;

class InertiaPageController extends Controller
{
    /**
     * Display About page
     */
    public function about()
    {
        $page = CreatePage::where('slug', 'about')->first();

        return Inertia::render('About', [
            'page' => $page,
            'currentPath' => '/about',
        ]);
    }

    /**
     * Display Contact page
     */
    public function contact()
    {
        return Inertia::render('Contact', [
            'currentPath' => '/contact',
        ]);
    }

    /**
     * Display FAQ page
     */
    public function faq()
    {
        $page = CreatePage::where('slug', 'faq')->first();

        return Inertia::render('FAQ', [
            'page' => $page,
            'currentPath' => '/faq',
        ]);
    }

    /**
     * Display Privacy Policy page
     */
    public function privacy()
    {
        $page = CreatePage::where('slug', 'privacy-policy')->first();

        return Inertia::render('Privacy', [
            'page' => $page,
            'currentPath' => '/privacy',
        ]);
    }

    /**
     * Display Terms & Conditions page
     */
    public function terms()
    {
        $page = CreatePage::where('slug', 'terms-conditions')->first();

        return Inertia::render('Terms', [
            'page' => $page,
            'currentPath' => '/terms',
        ]);
    }

    /**
     * Display Shipping Policy page
     */
    public function shipping()
    {
        $page = CreatePage::where('slug', 'shipping-policy')->first();

        return Inertia::render('ShippingPolicy', [
            'page' => $page,
            'currentPath' => '/shipping',
        ]);
    }

    /**
     * Display Return Policy page
     */
    public function returns()
    {
        $page = CreatePage::where('slug', 'return-policy')->first();

        return Inertia::render('ReturnPolicy', [
            'page' => $page,
            'currentPath' => '/returns',
        ]);
    }

    /**
     * Display generic page by slug
     */
    public function show($slug)
    {
        $page = CreatePage::where('slug', $slug)->firstOrFail();

        return Inertia::render('Page', [
            'page' => $page,
            'currentPath' => "/{$slug}",
        ]);
    }
}
