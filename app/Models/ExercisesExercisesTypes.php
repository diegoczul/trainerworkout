<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class ExercisesExercisesTypes extends Model
{
    use SoftDeletes;
protected $table = 'exercises_exercisestypes';
    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public function exercisestypes()
    {
        return $this->hasOne(ExercisesTypes::class, 'id', 'exercisestypesId');
    }
}
