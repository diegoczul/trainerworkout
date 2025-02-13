<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Users;

class TrainerSessions extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = [
        "name" => "required|min:2|max:300",
        "description" => "required|max:1000",
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public function users()
    {
        return $this->hasOne(Users::class, "id", "userId");
    }
}
