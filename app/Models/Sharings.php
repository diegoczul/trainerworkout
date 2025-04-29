<?php

namespace App\Models;

use App\Jobs\SharedWorkoutNewMailJob;
use App\Mail\SharedWorkoutEmailNew;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Lang;

class Sharings extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'fromUser',
        'toUser',
        'access_link',
        'type',
        'aux',
        'viewed',
        'accepted',
        'dateShared'
    ];
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
        logger()->info('[shareWorkout] Start sharing workout', [
            'from_user' => $from_user,
            'to_user' => $to_user,
            'workout_id' => $workoutObject->id ?? null,
            'type' => $type,
        ]);

        if ($to_user == null) {
            $to_user = 0;
            logger()->warning('[shareWorkout] to_user is null, defaulting to 0');
        }

        $newWorkout = Workouts::AddWorkoutToUser($workoutObject->id, $to_user, null, $lock);

        if (!$newWorkout) {
            logger()->error('[shareWorkout] Failed to create new workout for user', ['workout_id' => $workoutObject->id]);
            return;
        }

        logger()->info('[shareWorkout] New workout created', ['new_workout_id' => $newWorkout->id]);

        $trainerId = Auth::user()?->id;
        if (!$trainerId) {
            logger()->error('[shareWorkout] No authenticated user found!');
            return;
        }

        $client = Clients::where("userId", $to_user)->where("trainerId", $trainerId)->first();
        if (!$client) {
            logger()->warning('[shareWorkout] No client found', ['userId' => $to_user, 'trainerId' => $trainerId]);
        } else {
            $client->subscribeClient = $subscribe ? 1 : 0;
            $client->save();
            logger()->info('[shareWorkout] Client subscription updated', ['subscribeClient' => $client->subscribeClient]);
        }

        $link = sha1($from_user . $to_user . $newWorkout->id . $type);
        logger()->info('[shareWorkout] Generated access link', ['link' => $link]);

        try {
            $workoutPDF = $newWorkout->getPrintPDF();
            $workoutScreenshot = $newWorkout->getImageScreenshot();
            $workoutScreenshotPDF = $newWorkout->getImagePDF();
            logger()->info('[shareWorkout] Generated workout PDF and screenshots');
        } catch (\Exception $e) {
            logger()->error('[shareWorkout] Error generating PDF or screenshots', ['error' => $e->getMessage()]);
            return;
        }

        $toUser = Users::find($to_user);
        if (!$toUser) {
            logger()->error('[shareWorkout] Target user not found', ['to_user' => $to_user]);
            return;
        }

        $sharing = self::where("access_link", $link)->first();

        if ($sharing) {
            logger()->info('[shareWorkout] Sharing already exists, updating', ['sharing_id' => $sharing->id]);
            $sharing->viewed = 0;
            $sharing->accepted = 0;
            $sharing->dateShared = now();
            $sharing->toUser = $toUser->id;
        } else {
            logger()->info('[shareWorkout] Creating new sharing entry');
            $sharing = new Sharings();
            $sharing->viewed = 0;
            $sharing->accepted = 0;
            $sharing->dateShared = now();
            $sharing->fromUser = $from_user;
            $sharing->toUser = $toUser->id;
            $sharing->access_link = $link;
            $sharing->type = $type;
            $sharing->aux = $newWorkout->id;
        }

        $sharing->save();
        logger()->info('[shareWorkout] Sharing record saved', ['sharing_id' => $sharing->id]);

        $fromUser = Users::find($from_user);
        if (!$fromUser) {
            logger()->error('[shareWorkout] From user not found', ['from_user' => $from_user]);
            return;
        }

        $subject = Lang::get("messages.Emails_sharedWorkout");
        $lang = App::getLocale();

        try {
            logger()->info('[shareWorkout] Dispatching SharedWorkoutNewMailJob', [
                'to_user_email' => $toUser->email ?? null,
                'subject' => $subject,
                'lang' => $lang,
            ]);
            SharedWorkoutNewMailJob::dispatch(
                $sharing,
                $invite,
                $toUser,
                $fromUser,
                $comments,
                $workoutScreenshot,
                $workoutScreenshotPDF,
                $workoutPDF,
                $subject,
                $copyMe,
                $copyView,
                $copyPrint,
                $lang
            );
            logger()->info('[shareWorkout] SharedWorkoutNewMailJob dispatched successfully');
        } catch (\Exception $e) {
            logger()->error('[shareWorkout] Failed to dispatch email job', ['error' => $e->getMessage()]);
        }
    }
}
