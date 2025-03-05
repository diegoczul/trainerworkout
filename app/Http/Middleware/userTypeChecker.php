<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;

class userTypeChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $routeArray = explode("/", $request->route()->uri());
            $username = strtolower($user->firstName.$user->lastName);
            if (strtolower($routeArray[0]) != strtolower($user->userType)) {
                if ($user->userType === "Trainer") {
                    return Redirect::route('trainerWorkouts',['userName' => $username]);
                } elseif ($user->userType === "Trainee") {
                    return Redirect::route('traineeWorkouts');
                }
            }
        }

        return $next($request);
    }
}
