<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\Validator;

class BodyGroups extends Model implements TranslatableContract
{
    use SoftDeletes, Translatable;

    protected $table = 'bodygroups';
    protected $fillable = [];

    public $translatedAttributes = ['name', 'description'];
    public $useTranslationFallback = true;
    protected $translationForeignKey = 'bodygroups_id';

    public static $rules = [
        "name" => "required|min:2|max:500",
        "description" => "max:500",
        "equipment" => "max:500"
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }
}
