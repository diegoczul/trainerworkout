<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use App\Models\PasswordReminders;
use Illuminate\Http\Request;

class RemindersController extends BaseController
{

    public function getRemind()
    {
        return View::make('password.remind');
    }


    public function postRemind(Request $request)
    {
        $response = Password::sendResetLink(['email' => $request->get('email')], function ($message) {
            $message->subject(Lang::get("messages.NewPassword"));
        });

        return match ($response) {
            Password::RESET_LINK_SENT => Redirect::route("home")->with('message', Lang::get($response)),
            Password::INVALID_USER => Redirect::back()->with('error', Lang::get($response)),
        };
    }


    public function getReset($token = null)
    {
        if (is_null($token)) {
            abort(404);
        }

        $reminder = PasswordReminders::where("token", $token)->first();
        $email = $reminder ? $reminder->email : "";

        return View::make('password.reset')
            ->with('token', $token)
            ->with("email", $email);
    }


    public function postReset(Request $request)
    {
        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');

        $response = Password::reset($credentials, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
            Auth::loginUsingId($user->id);
        });

        return match ($response) {
            Password::PASSWORD_RESET => Redirect::to('/')->with('message', Lang::get("messages.PasswordReset")),
            Password::INVALID_PASSWORD, Password::INVALID_TOKEN, Password::INVALID_USER => Redirect::back()->with('error', Lang::get($response)),
        };
    }
}
