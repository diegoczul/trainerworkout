<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
class MembershipsUsers extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = array();

    public function users()
    {
        return $this->hasOne(Users::class, "id", "userId");
    }

    public function membership()
    {
        return $this->hasOne(Memberships::class, "id", "membershipId");
    }

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public function hasMembershipExpired()
    {
        $dateExpiry = date('Y-m-d', strtotime($this->expiry));
        return $dateExpiry < date('Y-m-d');
    }
}
