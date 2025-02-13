<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DateTime;
use Users;

class Tasks extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = [
        "dateStart" => "required|date",
        "value" => "required|max:500",
    ];

    public function user()
    {
        return $this->hasOne("App\Models\Users", "id", "targetId");
    }

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public static function dailyReminderChecker()
    {
        $date = new DateTime('today');
        $user = Auth::user();
        $final = "";

        // Check tasks
        $tasks = self::where("userId", "=", $user->id)
            ->where("dateStart", "<", $date->format("Y-m-d H:i:s"))
            ->whereNull("reminded")
            ->where("type", "task")
            ->get();
        $replace = [];
        foreach ($tasks as $task) {
            $friend = $user->id;
            if ($task->targetId != "") {
                $final = "TaskDueFriend";
                $friend = $task->targetId;
                $friendObj = Users::find($friend);
                $replace = ["firstName" => $friendObj->firstName, "lastName" => $friendObj->lastName];
            } else {
                $final = "TaskDue";
            }

            Notifications::insertDynamicNotification($final, $user->id, $friend, $replace, true);
            $task->reminded = date("Y-m-d H:i:s");
            $task->save();
        }

        // Check appointments
        $appointments = Appointments::where("userId", "=", $user->id)
            ->where("dateStart", "<", $date->format("Y-m-d H:i:s"))
            ->whereNull("reminded")
            ->get();
        $friend = $user->id;
        foreach ($appointments as $appointment) {
            $friend = $user->id;
            $replace = [];

            if ($appointment->targetId != "") {
                $final = "AppointmentDueFriend";
                $friend = $appointment->targetId;
                $friendObj = Users::find($friend);
                $replace = ["firstName" => $friendObj->firstName, "lastName" => $friendObj->lastName];
            } else {
                $final = "AppointmentDue";
            }

            Notifications::insertDynamicNotification($final, $user->id, $friend, $replace, true);
            $appointment->reminded = date("Y-m-d H:i:s");
            $appointment->save();
        }

        // Check reminders
        $reminders = self::where("userId", "=", $user->id)
            ->where("dateStart", "<", $date->format("Y-m-d H:i:s"))
            ->whereNull("reminded")
            ->where("type", "reminder")
            ->get();
        $friend = $user->id;
        foreach ($reminders as $reminder) {
            $friend = $user->id;
            if ($reminder->targetId != "") {
                $final = "ReminderDueFriend";
                $friend = $reminder->targetId;
                $friendObj = Users::find($friend);
                $replace = ["firstName" => $friendObj->firstName, "lastName" => $friendObj->lastName];
            } else {
                $final = "ReminderDue";
            }

            Notifications::insertDynamicNotification($final, $user->id, $friend, $replace, true);
            $reminder->reminded = date("Y-m-d H:i:s");
            $reminder->save();
        }
    }
}
