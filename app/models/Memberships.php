<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class Memberships extends Model
{
    use SoftDeletes, Translatable;

    protected $fillable = [];
    public $translatedAttributes = ['name', 'description', 'features'];
    public $useTranslationFallback = true;

    public static $rules = [
        // Define rules if needed
    ];

    public function users()
    {
        return $this->belongsTo(Users::class);
    }

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public static function checkMembership($user)
    {
        $numberOfClients = Clients::where("trainerId", $user->id)->count();
        $membership = MembershipsUsers::where("userId", $user->id)->first();

        if ($membership) {
            $mem = Memberships::find($membership->membershipId);
            if ($mem->type == "clients") {
                if ($membership->expiry > now()) {
                    if ($numberOfClients < $mem->clientesAllowed) {
                        $membership->userId = $user->id;
                        // DEFAULT MEMBERSHIP
                        $membership->membershipId = 3;
                        $membership->registrationDate = now();
                        $membership->expiry = $mem->durationType == "monthly"
                            ? now()->addMonth()
                            : now()->addYear();
                        $membership->save();

                        return "";
                    } else {
                        return Lang::get("messages.UpgradeMembership");
                    }
                } else {
                    if (($mem->id == 1 || $mem->id == 3) && ($numberOfClients < $mem->clientesAllowed)) {
                        $membership->expiry = now()->addYear();
                        $membership->save();
                        return "";
                    }
                    return Lang::get("messages.MembershipExpired");
                }
            }

            if ($mem->type == "workouts") {
                $workoutsAllowed = $mem->workoutsAllowed;
                $workoutsUser = Workouts::where("userId", $user->id)
                    ->where(function($query) {
                        $query->orWhere("status", "Released");
                    })
                    ->count();

                if ($membership->expiry > now()) {
                    if ($workoutsUser > $workoutsAllowed) {
                        return Lang::get("messages.UpgradeMembershipWorkouts" . $workoutsAllowed);
                    } else {
                        return "";
                    }
                } else {
                    if ($workoutsUser < $mem->workoutsAllowed) {
                        $membership->expiry = $mem->durationType == "monthly"
                            ? now()->addMonth()
                            : now()->addYear();
                        $membership->save();
                        return "";
                    }
                    return Lang::get("messages.MembershipExpiredWorkouts" . $workoutsAllowed);
                }
            }
        } else {
            // DEFAULT MEMBERSHIP
            $mem = Memberships::find(config('constants.defaultMembership'));
            $membership = new MembershipsUsers();
            $membership->userId = Auth::user()->id;
            $membership->membershipId = $mem->id;
            $membership->expiry = now()->addYears(config('constants.defaultMembershipExpiry'));
            $membership->save();

            return "";
        }
    }
}
