<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Sets extends Model
{
    use SoftDeletes;
    protected $fillable = [
        "id",
        "exerciseId",
        "workoutsExercisesId",
        "number",
        "reps",
        "weight",
        "rest",
        "tempo",
        "type",
        "distance",
        "speed",
        "time",
        "notes",
        "workoutId",
        "completed",
        "last",
        "created_at",
        "updated_at",
        "deleted_at",
        "weightKG",
        "bpm",
        "equipmentId",
        "metric",
        "units",
        "restAfter",
    ];
    protected $dates = ['deleted_at'];

    public static $rules = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public function workoutsExercises()
    {
        return $this->belongsTo(WorkoutsExercises::class, "workoutsExercisesId", "id");
    }
}
