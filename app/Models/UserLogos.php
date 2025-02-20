<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class UserLogos extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = [
        // Add validation rules here if needed
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public function user()
    {
        return $this->hasOne(Users::class, "id", "userId");
    }
}
