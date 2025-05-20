<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StoreDeviceType
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('device_type')) {
//            dd($request->query('device_type'));
            cookie()->queue('device_type', $request->query('device_type'), 60 * 24 * 30); // 30 days
        }

        return $next($request);
    }
}
