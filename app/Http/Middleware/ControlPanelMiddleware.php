<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

class ControlPanelMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $string = $user->email;
            $word = "@trainerworkout.com";
            if (
                stripos($string, '@trainerworkout.com') === false &&
                stripos($string, '@trainer-workout.com') === false
            ) {
                if ($user->userType == "Trainer") {
                    return redirect()->route('trainerWorkouts')->withError(Lang::get("messages.NotFound"));
                } else if ($user->userType == "Trainee") {
                    return redirect()->route('traineeWorkouts')->withError(Lang::get("messages.NotFound"));
                }
            }
        } else {
            return redirect()->guest('/');
        }

        return $next($request);
    }
}
