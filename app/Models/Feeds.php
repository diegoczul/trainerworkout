<?php

namespace App\Models;

use App\Http\Libraries\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class Feeds extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = [
        "followingId" => "required",
    ];

    public function user()
    {
        return $this->belongsTo("App\Models\Users", "userId", "id");
    }

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public static function insertFeed($message, $userId, $firstName = "", $lastName = "", $type = "", $link = "", $action = "")
    {
        $final = Lang::get("messages." . $message);
        $user = Users::find($userId);

        $final = str_replace("{firstName}", $user->firstName, $final);
        $final = str_replace("{lastName}", $user->lastName, $final);

        self::insert([
            "message" => $final,
            "userId" => $userId,
            "type" => $type,
            "action" => $action,
            "link" => $link,
            "created_at" => now()
        ]);
    }

    public static function insertFeedUserObject($message, $user, $type = "", $link = "", $action = "")
    {
        $final = $message;
        $userId = $user->id;
        $final = str_replace("{firstName}", $user->firstName, $final);
        $final = str_replace("{lastName}", $user->lastName, $final);
        $final = str_replace("{email}", $user->lastName, $final);

        self::insert([
            "message" => $final,
            "userId" => $userId,
            "type" => $type,
            "action" => $action,
            "link" => $link,
            "created_at" => now()
        ]);
    }

    public static function insertDynamicFeed($message, $userId, $userWhoTriggeredTheFeedObject, $variables, $type = "", $link = "", $action = "")
    {
        $final = Lang::get("messages." . $message);
        $trainers = Clients::where("userId", $userId)->distinct()->pluck("trainerId");

        if ($variables != null) {
            foreach ($variables as $variable => $value) {
                $final = str_replace("{" . $variable . "}", $value, $final);
            }
        }

        if (self::where("userId", $userId)
                ->where("message", $final)
                ->where("type", $type)
                ->where("created_at", ">=", Helper::startOfDay())
                ->where("created_at", "<=", Helper::endOfDay())
                ->count() == 0) {

            self::insert([
                "message" => $final,
                "userId" => $userId,
                "type" => $type,
                "action" => $action,
                "link" => $link,
                "created_at" => now()
            ]);
        }
    }
}
