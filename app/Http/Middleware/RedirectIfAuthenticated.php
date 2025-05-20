<?php

namespace App\Http\Middleware;

use App\Http\Libraries\Helper;
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
            return $this->redirectUserBasedOnType($user, $request);
        }

        if (Auth::viaRemember()) {
            $user = Auth::user();
            return $this->redirectUserBasedOnType($user, $request);
        }

        if (Cookie::get('TrainerWorkoutUserId') !== null) {
            $this->handleTrainerWorkoutCookie($request);
        }

        return $next($request);
    }

    private function redirectUserBasedOnType($user,Request $request)
    {
        if ($request->has('device_type')) {
            $username = strtolower(Auth::user()->firstName.Auth::user()->lastName);
            if ($user->userType == "Trainer") {
                return Redirect::route('trainerWorkouts',['userName' => $username, 'device_type' => Helper::getDeviceTypeCookie()])->with(['message' => __("messages.Welcome")]);
            } else {
                return Redirect::route('traineeWorkouts',['device_type' => Helper::getDeviceTypeCookie()])->with(['message' => __("messages.Welcome")]);
            }
        }else{
            $username = strtolower(Auth::user()->firstName.Auth::user()->lastName);
            if ($user->userType == "Trainer") {
                return Redirect::route('trainerWorkouts',['userName' => $username])->with(['message' => __("messages.Welcome")]);
            } else {
                return Redirect::route('traineeWorkouts')->with(['message' => __("messages.Welcome")]);
            }
        }
    }

    private function handleTrainerWorkoutCookie($request)
    {
        $encryptedUserId = Cookie::get('TrainerWorkoutUserId');
        if (!empty($encryptedUserId)) {
            $userId = Crypt::decrypt($encryptedUserId);

            if (intval($userId) > 0) {
                Auth::loginUsingId($userId);
                $user = Auth::user();
                if ($user) {
                    return $this->redirectUserBasedOnType($user,$request);
                }
            }
        }
    }
}
