<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            return $this->redirectUserBasedOnType($user);
        }

        if (Auth::viaRemember()) {
            $user = Auth::user();
            return $this->redirectUserBasedOnType($user);
        }

        if (Cookie::get('TrainerWorkoutUserId') !== null) {
            $this->handleTrainerWorkoutCookie();
        }

        return $next($request);
    }

    private function redirectUserBasedOnType($user)
    {
        $username = strtolower(Auth::user()->firstName.Auth::user()->lastName);
        if ($user->userType == "Trainer") {
            return Redirect::route('trainerWorkouts',['userName' => $username])->with(['message' => __("messages.Welcome")]);
        } else {
            return Redirect::route('traineeWorkouts',['userName' => $username])->with(['message' => __("messages.Welcome")]);
        }
    }

    private function handleTrainerWorkoutCookie()
    {
        $encryptedUserId = Cookie::get('TrainerWorkoutUserId');
        if (!empty($encryptedUserId)) {
            $userId = Crypt::decrypt($encryptedUserId);

            if (intval($userId) > 0) {
                Auth::loginUsingId($userId);
                $user = Auth::user();
                if ($user) {
                    return $this->redirectUserBasedOnType($user);
                }
            }
        }
    }
}
