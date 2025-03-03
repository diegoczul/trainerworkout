<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class ExercisesBodyGroups extends Model
{
    use SoftDeletes;

    protected $table = 'exercises_bodygroups';
    protected $fillable = ['exerciseId'];  // Add any attributes that should be mass assignable
    protected $dates = ['deleted_at']; // Ensure the 'deleted_at' field is treated as a date

    public static $rules = [
        // Add your validation rules here if needed
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public function bodygroup()
    {
        return $this->hasOne(BodyGroups::class, "id", "bodygroupId");
    }
}
