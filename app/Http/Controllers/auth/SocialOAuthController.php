<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Helper;
use App\Models\Users;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class SocialOAuthController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->with(['prompt' => 'select_account'])->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        $socialAuth = Socialite::driver('google')->stateless()->user();

        $user = Users::where('email', $socialAuth->user['email'])->first();
        if(!$user){
            $user = new Users;
            $user->firstName = ucfirst($socialAuth->user['given_name']);
            $user->lastName = ucfirst($socialAuth->user['family_name']);
            $user->email = strtolower(trim($socialAuth->user['email']));

            if ($request->filled('timezone')) {
                $user->timezone = $request->get('timezone');
            }
            $user->password = Hash::make("Admin@123");
            $user->userType = "Trainer";
            $user->lastLogin = date("Y-m-d");
            $user->save();

            $user->sendActivationEmail();
            Auth::loginUsingId($user->id);
            Event::dispatch('signUp', [$user]);

            try {
                if (!Config::get('app.debug')) {
                    MailchimpWrapper::lists()->subscribe(Config::get('constants.mailChimpTrainers'), ['email' => $user->email]);
                }
            } catch (Exception $e) {
                Log::error("MAILCHIMP Error");
                Log::error($e);
                return null;
            }

            if (Session::has('utm')) {
                $user->marketing = Session::get('utm');
                $user->save();
                Session::forget('utm');
            }

            $user->freebesTrainer();

            if ($request->get('paid') == 'yes') {
                return redirect("/Store/addToCart/63/Membership");
            }

            if (Session::has('redirect') && Session::get('redirect') != '') {
                if (!Auth::user()->membership) {
                    Auth::user()->updateToMembership(Config::get('constants.freeTrialMembershipId'));
                }
                return redirect()->route(Session::get('redirect'));
            } else {
                if (!Auth::user()->membership) {
                    Auth::user()->updateToMembership(Config::get('constants.freeTrialMembershipId'));
                }
                return redirect()->route('trainerWorkouts', ['userName' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])->with('message', __('messages.Welcome'));
            }
        }else{
            Auth::loginUsingId($user->id);
            $user->update(['updated_at' => now(), 'lastLogin' => now(), 'virtual' => 0]);

            event('login', [$user]);

            setcookie("TrainerWorkoutUserId", Crypt::encrypt($user->id), time() + (86400 * 30 * 7), "/");

            if ($user->lang) {
                App::setLocale($user->lang);
            } else {
                App::setLocale(Session::get('lang', 'en'));
            }

            $route = $user->userType == 'Trainer' ? 'trainerWorkouts' : 'traineeWorkouts';
            return redirect()->route($route, ['userName' => Helper::formatURLString($user->firstName . $user->lastName)])->with('message', __('messages.Welcome'));
        }
    }
}
