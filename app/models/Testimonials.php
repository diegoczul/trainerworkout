<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Users;

class Testimonials extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public function user()
    {
        return $this->hasOne(Users::class, "id", "userId");
    }

    public function fUser()
    {
        return $this->hasOne(Users::class, "id", "fromUser");
    }
}
