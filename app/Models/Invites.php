<?php

namespace App\Models;

use App\Mail\InviteClientMail;
use App\Mail\InviteFriendMail;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
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

        $lang = App::getLocale();
        Mail::to($this->email)->queue(new InviteFriendMail($subject,$this,$user,$name,$lang));
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

        $lang = App::getLocale();
        Mail::to($email)->queue(new InviteClientMail($subject,$comments,$this,$user,$fake,$lang));
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
