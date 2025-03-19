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
        'tagNameTag' => 'sometimes',
        'tagNameClient' => 'sometimes|required_if:tagNameTag,null',
        'tagName' => 'sometimes',
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }
}
