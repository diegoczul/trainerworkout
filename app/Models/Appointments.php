<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Appointments extends Model
{
    use SoftDeletes;

    protected $fillable = [];

    public static $rules = [
        "appointment" => "required|max:300",
        "dateStart" => "required|date",
        "dateEnd" => "required|date"
    ];

    public function user()
    {
        return $this->hasOne(Users::class, "id", "targetId");
    }

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }
}
