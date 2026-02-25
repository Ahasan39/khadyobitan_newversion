<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'customer' => $request->user('customer') ? [
                    'id' => $request->user('customer')->id,
                    'name' => $request->user('customer')->name,
                    'email' => $request->user('customer')->email,
                    'phone' => $request->user('customer')->phone,
                ] : null,
            ],
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'error' => fn () => $request->session()->get('error'),
                'success' => fn () => $request->session()->get('success'),
            ],
            'csrf_token' => csrf_token(),
            'app' => [
                'name' => config('app.name'),
                'url' => config('app.url'),
            ],
            'cart' => [
                'count' => \Cart::count(),
                'total' => \Cart::total(),
            ],
        ];
    }
}
