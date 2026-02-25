<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;

class PaymentBanner
{
   
     public function handle($request, Closure $next)
    {
        
        if ($request->is('/')) {

            $dueDate = env('PAYMENT_DUE_DATE');
            $enabled = env('SHOW_PAYMENT_BANNER', false);

            if ($enabled && $dueDate) {
                if (Carbon::now()->gt(Carbon::parse($dueDate))) {
                   
                    return response()->view('partials.payment-warning');
                }
            }
        }

        return $next($request);
    }
}

