<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Helper;

class Friends extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = [
        "followingId" => "required",
    ];

    public function user()
    {
        return $this->belongsTo("App\Models\Users", "followingId", "id");
    }

    public function myuser()
    {
        return $this->belongsTo("App\Models\Users", "userId", "id");
    }

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public static function checkFollower($following)
    {
        $friend = self::where("userId", Auth::user()->id)
            ->where("followingId", $following)
            ->count();

        return $friend > 0;
    }

    public function getURL()
    {
        if ($this->user()->exists()) {
            return Helper::userType($this->user->userType) . "/" . $this->followingId . "/" . Helper::formatURLString($this->user->firstName . $this->user->lastName);
        } else {
            return Auth::user()->userType;
        }
    }
}
