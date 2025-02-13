<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Tags extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = [
        // Define validation rules here if needed
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }
}
