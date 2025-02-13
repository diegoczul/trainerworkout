<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Ratings extends Model
{
    use SoftDeletes, Translatable;

    protected $fillable = [];
    public $translatedAttributes = ['name'];
    public $useTranslationFallback = true;

    protected $dates = ['deleted_at'];

    public static $rules = [];

    public function trainer()
    {
        return $this->hasOne("App\Models\Users", "id", "ownerId");
    }

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }
}
