<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Users;

class Objectives extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = [
        "objective" => "required|min:2|max:500",
        "measureable" => "max:500",
        "dateRecord" => "required|date"
    ];

    public function users()
    {
        return $this->belongsTo(Users::class);
    }

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }
}
