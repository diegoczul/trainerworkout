<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class Invites extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = [
        "userId" => "required",
        "followerId" => "required",
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public function users()
    {
        return $this->belongsTo(Users::class);
    }

    public function sendInvite()
    {
        $user = Users::find($this->userId);
        $name = $user->firstName;
        $this->lastSent = new DateTime;
        $this->save();

        $subject = Lang::get("content.InviteClient", [
            "firstName" => $user->firstName,
            "lastName" => $user->lastName
        ]);

        Mail::queueOn(
            App::environment(),
            'emails.' . Config::get("app.whitelabel") . '.user.' . App::getLocale() . '.inviteFriend',
            ["invite" => $this, "user" => $user, "name" => $name],
            function ($message) use ($user, $subject) {
                $message->to($this->email)
                    ->subject($subject);
            }
        );
    }

    public function sendInviteClient($comments = "")
    {
        $user = Users::find($this->userId);
        $fake = Users::find($this->fakeId);
        $this->lastSent = new DateTime;
        $this->save();

        $email = $this->email;

        $subject = Lang::get("content.InviteClient", [
            "firstName" => $user->firstName,
            "lastName" => $user->lastName
        ]);

        Mail::queueOn(
            App::environment(),
            'emails.' . Config::get("app.whitelabel") . '.user.' . App::getLocale() . '.inviteClient',
            [
                "comments" => $comments,
                "password" => null,
                "invite" => serialize($this),
                "user" => serialize($user),
                "fake" => serialize($fake)
            ],
            function ($message) use ($user, $email, $subject) {
                $message->to($email)
                    ->subject($subject);
            }
        );
    }

    public function completeInvite()
    {
        $this->completed = 1;
        $this->save();

        $friend = new Friends();
        $friend->userId = $this->userId;
        $friend->followingId = $this->fakeId;
        $friend->save();

        $friendBack = new Friends();
        $friendBack->userId = $this->fakeId;
        $friendBack->followingId = $this->userId;
        $friendBack->save();
    }
}
