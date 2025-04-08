<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Helper;
use App\Models\Invites;
use App\Models\Users;
use App\Services\SendGridSubscriptionService;
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
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Newsletter\Facades\Newsletter;

class SocialOAuthController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function redirectToGoogle($role)
    {
        return Socialite::driver('google')
            ->with([
                'prompt' => 'select_account',
                'redirect_uri' => route('auth.google-callback',['role' => $role]),
            ])
            ->redirect();
    }

    public function handleGoogleCallback($role,Request $request){
        if ($request->has('error')){
            return redirect()->route('login');
        }
        if ($role == 'Trainer')
            return $this->handleGoogleCallbackTrainer($request);
        else
            return $this->handleGoogleCallbackTrainee($request);
    }

    public function handleGoogleCallbackTrainer(Request $request)
    {
        $socialAuth = Socialite::driver('google')
            ->with([
                'prompt' => 'select_account',
                'redirect_uri' => route('auth.google-callback',['role' => "Trainer"]),
            ])
            ->stateless()
            ->user();

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
                    $sendGridService = new SendGridSubscriptionService();
                    $sendGridService->subscribeToList(['email' => $request->get("email")],config('constants.sendgridTrainer'));
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

    public function handleGoogleCallbackTrainee(Request $request)
    {
        $socialAuth = Socialite::driver('google')
            ->with([
                'prompt' => 'select_account',
                'redirect_uri' => route('auth.google-callback',['role' => "Trainee"]),
            ])
            ->stateless()
            ->user();

        $user = Users::where('email', $socialAuth->user['email'])->first();
        if(!$user){
            $user = new Users;
            $password = Hash::make(Str::random(8));
            $user->fill([
                'firstName' => ucfirst($socialAuth->user['given_name']),
                'lastName' => ucfirst($socialAuth->user['family_name']),
                'email' => strtolower(trim($socialAuth->user['email'])),
                'userType' => "Trainee",
                'password' => $password
            ]);
            $image = $socialAuth->user['picture'];
            $user->activated = now();
            $user->save();

//            $subject = __("messages.Emails_registerFB");
//            Mail::to($user->email)
////                ->cc(config("constants.activityEmail"))
//                ->queue(new NewUserMail($user, $password, $subject));

            Helper::checkUserFolder($user->id);
            if (isset($image) && !empty($image)) {
                $file = file_get_contents($image);
                $images = Helper::saveImage($file, $user->getPath() . config("constants.profilePath") . "/" . $user->id, $image);
                $user->update([
                    'image' => $images["image"],
                    'thumb' => $images["thumb"]
                ]);
            }

            Auth::loginUsingId($user->id);
            $user->update(['lastLogin' => now()]);
            $user->freebesTrainer();

            Invites::where("email", $user->email)->where("completed", 0)->update(["completed" => 1]);
            return redirect()->route('traineeWorkouts')->with("message", __("messages.Welcome"))->with("newUser", true);
        }else{
            Auth::loginUsingId($user->id);
            if ($user->password == "") {
                $password = Hash::make(Str::random(8));
                $user->password = $password;
                $user->save();
            }
            Auth::user()->update([
                'updated_at' => now(),
                'lastLogin' => now(),
                'virtual' => 0,
            ]);

            Invites::where("email", Auth::user()->email)->where("completed", 0)->update(["completed" => 1]);

            return Auth::user()->userType === "Trainer" ? redirect()->route('trainerWorkouts') : redirect()->route('traineeWorkouts')->with("message", __("messages.Welcome"));
        }
    }
}
