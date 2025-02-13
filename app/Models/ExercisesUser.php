<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Users;

class ExercisesUser extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = array(
        // Add validation rules if needed
    );

    public function users()
    {
        return $this->hasOne(Users::class, "id", "userId");
    }

    public function exercises()
    {
        return $this->hasOne(Exercises::class, "id", "exerciseId");
    }

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }
}
