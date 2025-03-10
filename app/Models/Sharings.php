<?php

namespace App\Models;

use App\Mail\SharedWorkoutEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;

class Sharings extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public function toUserObject()
    {
        return $this->hasOne(Users::class, "id", "toUser");
    }

    public function fromUserObject()
    {
        return $this->hasOne(Users::class, "id", "fromUser");
    }

    public static function previewSharing($from_user, $to_user, $aux, $type)
    {
        if ($to_user == NULL) $to_user = 0;

        return sha1($from_user . $to_user . $aux . $type);
    }

    public static function shareWorkout(
        $from_user,
        $to_user,
        $workoutObject,
        $type,
        $comments = "",
        $invite = null,
        $copyMe = true,
        $copyView = true,
        $copyPrint = true,
        $subscribe = true,
        $lock = true
    ) {
        if ($to_user == NULL) $to_user = 0;

        $newWorkout = Workouts::AddWorkoutToUser($workoutObject->id, $to_user, null, $lock);
        $client = Clients::where("userId", $to_user)->where("trainerId", Auth::user()->id)->first();

        if ($subscribe) {
            $client->subscribeClient = 1;
        } else {
            $client->subscribeClient = 0;
        }
        $client->save();

        $link = sha1($from_user . $to_user . $newWorkout->id . $type);

        $workoutPDF = $newWorkout->getPrintPDF();
//        $workoutScreeshot = $newWorkout->getImageScreenshot();
        $workoutScreeshot = "";
//        $workoutScreeshotPDF = $newWorkout->getImagePDF(); // THIS THING IS CAUSING ERROR
        $workoutScreeshotPDF = "";

        $toUser = Users::find($to_user);
        if ($toUser) {
            if (self::where("access_link", $link)->count() > 0) {
                $sharing = self::where("access_link", $link)->first();
                $sharing->viewed = 0;
                $sharing->accepted = 0;
                $sharing->dateShared = now();
                $sharing->toUser = $toUser->id;
                $sharing->save();

                $fromUser = Users::find($from_user);
                $subject = Lang::get("messages.Emails_sharedWorkout");
                $lang = App::getLocale();
                Mail::queue(new SharedWorkoutEmail($sharing, $invite, $toUser, $fromUser, $comments, $workoutScreeshot, $workoutScreeshotPDF, $workoutPDF, $subject, $copyMe, $copyView, $copyPrint,$lang));
            } else {
                $sharing = new Sharings();
                $sharing->viewed = 0;
                $sharing->accepted = 0;
                $sharing->dateShared = now();
                $sharing->fromUser = $from_user;
                $sharing->toUser = $toUser->id;
                $sharing->access_link = $link;
                $sharing->type = $type;
                $sharing->aux = $newWorkout->id;
                $sharing->save();

                $fromUser = Users::find($from_user);
                $subject = Lang::get("messages.Emails_sharedWorkout");
                $lang = App::getLocale();
                Mail::queue(new SharedWorkoutEmail($sharing, $invite, $toUser, $fromUser, $comments, $workoutScreeshot, $workoutScreeshotPDF, $workoutPDF, $subject, $copyMe, $copyView, $copyPrint,$lang));
            }
        }
    }
}
