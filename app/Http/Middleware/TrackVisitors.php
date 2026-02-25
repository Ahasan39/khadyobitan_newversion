<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Visit;

class TrackVisitors
{
    public function handle($request, Closure $next)
    {
        Visit::create([
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent')
        ]);

        return $next($request);
    }
}

