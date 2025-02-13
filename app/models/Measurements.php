<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Users;

class Measurements extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = [
        "recordDate" => "required|date",
        "chest" => "numeric",
        "abdominals" => "numeric",
        "bicepsLeft" => "numeric",
        "bicepsRight" => "numeric",
        "forearmLeft" => "numeric",
        "forearmRight" => "numeric",
        "legsLeft" => "numeric",
        "legsRight" => "numeric",
        "calfLeft" => "numeric",
        "calfRight" => "numeric",
        "waist" => "numeric",
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
