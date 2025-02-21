<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $lang = !empty(Auth::user()->lang)?Auth::user()->lang:"en";
            App::setLocale($lang);
        }elseif (session()->has('lang')) {
            App::setLocale(session('lang'));
        }else{
            App::setLocale('en');
        }

        return $next($request);
    }
}
