<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExercisesTranslation extends Model
{
    protected $table = "exercises_translations";

    protected $fillable = [
        "exercises_id",
        "name",
        "description",
        "nameEngine",
        "locale",
        "created_at",
        "deleted_at",
        "updated_at",
    ];

    public $timestamps = false;
}
