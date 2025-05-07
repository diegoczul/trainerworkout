<?php

namespace App\Http\Controllers\web;

use App\Jobs\ResetPasswordMailJob;
use App\Models\Users;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use App\Models\PasswordReminders;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RemindersController extends BaseController
{

    public function getRemind()
    {
        return View::make('password.remind');
    }


    public function postRemind(Request $request)
    {
        if (Users::where('email', $request->email)->count() != 0) {
            // Generate a reset token
            $token = Str::random(60);

            // Store the token in the password_resets table
            DB::table('password_reminders')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);

            // Send the reset email (you can create a custom email view for this)
            $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);
            ResetPasswordMailJob::dispatch($request->get('email'),$resetUrl);

            return Redirect::route("home")->with('message', "Reset password link sent to your email address. Please check your inbox.");
        }else{
            return Redirect::back()->with('error', 'User not found !');
        }
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
//        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');
//
//        $response = Password::reset($credentials, function ($user, $password) {
//            $user->password = Hash::make($password);
//            $user->save();
//            Auth::loginUsingId($user->id);
//        });
//
//        return match ($response) {
//            Password::PASSWORD_RESET => Redirect::to('/')->with('message', Lang::get("messages.PasswordReset")),
//            Password::INVALID_TOKEN,
//            Password::INVALID_USER => Redirect::back()->with('error', Lang::get($response)),
//            default => throw new \UnhandledMatchError("Unhandled password response: {$response}"),
//        };

        $token = $request->get('token');
        $email = $request->get('email');
        $password = $request->get('password');

        $record = DB::table('password_reminders')->where(['email' => $email, 'token' => $token])->first();

        if (!$record) {
            return Redirect::back()->with('error', Lang::get('passwords.token'));
        }

        $createdAt = Carbon::parse($record->created_at);
        $expires = Config::get('auth.passwords.'.Config::get('auth.defaults.passwords').'.expire', 60);

        if ($createdAt->addMinutes($expires)->isPast()) {
            return Redirect::back()->with('error', Lang::get('passwords.token'));
        }

        $user = Users::where('email', $email)->first();
        if (!$user) {
            return Redirect::back()->with('error', Lang::get('passwords.user'));
        }

        $user->password = Hash::make($password);
        $user->save();

        // Remove the token after successful reset
        DB::table('password_reminders')->where('email', $email)->delete();

        // Auto login the user
        Auth::loginUsingId($user->id);

        return Redirect::to('/')->with('message', Lang::get('messages.PasswordReset'));
    }
}
