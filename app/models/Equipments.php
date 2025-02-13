<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\Validator;

class Equipments extends Model
{
    use SoftDeletes, Translatable;

    protected $fillable = [];

    public $translatedAttributes = ['name', 'nameEngine'];

    public static $rules = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }
}
