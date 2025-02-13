<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\Validator;

class ExercisesTypes extends Model
{
    use SoftDeletes, Translatable;

    protected $table = 'exercisestypes';
    protected $fillable = [];
    public $translatedAttributes = ['name'];
    public $useTranslationFallback = true;
    protected $translationForeignKey = 'exercisestypes_id';

    protected $dates = ['deleted_at'];

    public static $rules = [
        'name' => 'required|min:2|max:500',
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }
}
