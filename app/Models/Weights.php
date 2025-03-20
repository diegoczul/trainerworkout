<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Weights extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = [
        'weight' => 'required|min:2|numeric',
        'dateRecord' => 'date',
        'recordDate' => 'date',
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
