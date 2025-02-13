<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use UserGroups;
use Users;

class Groups extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public function countUsers()
    {
        return UserGroups::where("groupId", $this->id)->count();
    }

    public static function checkGroupPermissions($requester, $toPersonifyWhom)
    {
        $user = Users::find($requester);
        if ($user) {
            $toUser = Users::find($toPersonifyWhom);
            $group = UserGroups::where("userId", $user->id)->first();
            if ($group) {
                if ($group->role == "Owner" || $group->role == "Admin") {
                    return true;
                }
            }
        }
        return false;
    }
}
