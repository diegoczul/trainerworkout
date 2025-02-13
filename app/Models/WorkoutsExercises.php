<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkoutsExercises extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public function exercises()
    {
        return $this->hasOne(Exercises::class, 'id', 'exerciseId')->withTrashed();
    }

    public function equipment()
    {
        return $this->hasOne(Equipments::class, 'id', 'equipmentId')->withTrashed();
    }

    public function sets()
    {
        return $this->hasMany(Sets::class, 'workoutsExercisesId', 'id')->orderBy('number', 'ASC');
    }

    public function templateSets()
    {
        return $this->hasMany(TemplateSets::class, 'workoutsExercisesId', 'id')->orderBy('number', 'ASC');
    }
}
