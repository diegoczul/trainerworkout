<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class WorkoutsGroups extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public function workoutsExercises()
    {
        return $this->belongsTo(WorkoutsExercises::class, 'workoutsExercisesId', 'id');
    }

    public function getExercises()
    {
        return WorkoutsExercises::with('exercises')->where('groupId', $this->id)->orderBy('order');
    }

    public function getExercisesImagesCircuit()
    {
        $images = [];
        $exercises = $this->getExercises()->get();

        foreach ($exercises as $index => $exercise) {
            $images[$index] = $exercise->exercises->image;
        }

        return $images;
    }
}
