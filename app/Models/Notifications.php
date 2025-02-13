<?php

namespace App\Models;

use Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Messages;
use Users;

class Notifications extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = array(
        "message" => "required",
    );

    public function user()
    {
        return $this->belongsTo("App\Models\Users", "fromId", "id");
    }

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public static function insertNotification($message, $userId, $firstName = "", $lastName = "")
    {
        $final = Messages::notification($message);
        $final = str_replace("{firstName}", $firstName, $final);
        $final = str_replace("{lastName}", $lastName, $final);

        $user = Users::find($userId);
        if ($user) {
            if (Helper::checkPermission(Auth::user()->id, "email_notifications")) {
                Mail::queueOn(App::environment(), 'emails.' . Config::get("app.whitelabel") . '.user.' . App::getLocale() . '.notification',
                    array("notification" => $final, "toUser" => serialize($user)), function ($message) use ($user) {
                        $message->to($user->email)
                            ->subject($user->firstName . " " . Lang::get("Emails_notification"));
                    });
            }

            self::insert(array(
                "message" => $final,
                "userId" => $userId,
                "created_at" => date('Y-m-d H:i:s')
            ));
        }
    }

    public static function checkIfTrainerNotifiedTodayWorkout($userId, $trainerId = "")
    {
        if ($trainerId != "") {
            $result = self::where("userId", $trainerId)->where("fromId", $userId)->where("message", "like", '%has completed an exercise%')->count();
            return $result > 0;
        }
        return false;
    }

    public static function insertDynamicNotification($message, $toUserId, $userWhoTriggeredTheNotificationId, $variables, $replace = true, $link = "", $action = "", $type = "", $display = "top")
    {
        $final = $message;
        $toUser = null;
        $fromUser = null;

        if ($userWhoTriggeredTheNotificationId != null) {
            if (is_numeric($userWhoTriggeredTheNotificationId)) {
                $fromUser = Users::find($userWhoTriggeredTheNotificationId);
            } else {
                $fromUser = $userWhoTriggeredTheNotificationId;
                $userWhoTriggeredTheNotificationId = $fromUser->id;
            }
        }

        if (is_numeric($toUserId)) {
            $toUser = Users::find($toUserId);
        } else {
            $toUser = $toUserId;
            $toUserId = $toUser->id;
        }

        if ($replace) {
            $noti = Messages::notification($message);
            $level = $noti[1];
            $final = $noti[0];
        } else {
            $level = "client";
        }

        foreach ($variables as $variable => $value) {
            $final = str_replace("{" . $variable . "}", $value, $final);
        }

        if ($toUser) {
            if (Helper::checkPermission($toUser->id, "email_notifications") && Helper::checkPermission($toUser->id, "email_notifications_" . $level)) {
                Mail::queueOn(App::environment(), 'emails.' . Config::get("app.whitelabel") . '.user.' . App::getLocale() . '.notification',
                    array("notification" => $final, "toUser" => serialize($toUser), "fromUser" => serialize($fromUser)), function ($message) use ($toUser) {
                        $message->to($toUser->email)
                            ->subject($toUser->firstName . " " . Lang::get("Emails_notification"));
                    });
            }

            self::insert(array(
                "message" => $final,
                "link" => $link,
                "userId" => $toUser->id,
                "fromId" => $fromUser ? $fromUser->id : null,
                "type" => $type,
                "action" => $action,
                "display" => $display,
                "created_at" => date('Y-m-d H:i:s')
            ));
        }
    }

    public static function readNotifications($userId)
    {
        $results = self::whereNull("viewed")->where("userId", $userId)->get();
        foreach ($results as $result) {
            $result->viewed = date("Y-m-d H:i:s");
            $result->save();
        }
    }

    public static function respondToAction($action, $targetUserId, $link = "")
    {
        $messageResponder = "sendMessageToUser(" . $targetUserId . ",\"" . Auth::user()->userType . "\")";
        if ($action == "message") {
            return "onClick='" . $messageResponder . "'";
        }

        if ($action == "workout") {
            $messageResponder = "window.location=\"/" . $link . "\"";
            return "onClick='" . $messageResponder . "'";
        }
        return "onClick='" . $messageResponder . "'";
    }

    public static function displayIcon($iconType)
    {
        $icons = array(
            "reminderWeightUpdateNew" => "img/UpdatedWeight.png",
            "reminderPicturesUpdateNew" => "img/NewPicture.png",
            "reminderMeasurementUpdateNew" => "img/BodyMeasurement.png",
            "measurements" => "img/BodyMeasurement.png",
            "objectiveAdded" => "img/NewObjective.png",
            "measuremtnsAdded" => "img/BodyMeasurement.png",
            "weightAdded" => "img/UpdatedWeight.png",
            "picturesAdded" => "img/NewPicture.png",
            "workoutPerformed" => "img/UpdateWeights.png",
            "workoutChangedWeight" => "img/UpdateWeights.png",
            "changedReps" => "img/UpdateWeights.png",
            "changedDistance" => "img/UpdateWeights.png",
            "changedTime" => "img/UpdateWeights.png",
            "changedSpeed" => "img/UpdateWeights.png",
        );

        return $icons[$iconType] ?? "";
    }
}
