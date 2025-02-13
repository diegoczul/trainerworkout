<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExercisesTranslation extends Model
{
    protected $fillable = ['name', 'description', 'nameEngine'];
    public $timestamps = false;
}
