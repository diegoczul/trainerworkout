<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExercisesTypesTranslation extends Model
{
    protected $table = 'exercisestypes_translations';
    protected $fillable = ['name'];

    public $timestamps = false;
}
