<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Pictures extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = [
        "recordDate" => "required|date"
    ];

    public function users()
    {
        return $this->belongsTo(Users::class);
    }

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public function getFrontUrlAttribute($file){
        if(!empty($file)){
            return asset($file);
        }else{
            return null;
        }
    }
    public function getBackUrlAttribute($file){
        if(!empty($file)){
            return asset($file);
        }else{
            return null;
        }
    }
    public function getLeftUrlAttribute($file){
        if(!empty($file)){
            return asset($file);
        }else{
            return null;
        }
    }
    public function getRightUrlAttribute($file){
        if(!empty($file)){
            return asset($file);
        }else{
            return null;
        }
    }
    public function getThumbFrontUrlAttribute($file){
        if(!empty($file)){
            return asset($file);
        }else{
            return null;
        }
    }
    public function getThumbBackUrlAttribute($file){
        if(!empty($file)){
            return asset($file);
        }else{
            return null;
        }
    }
    public function getThumbLeftUrlAttribute($file){
        if(!empty($file)){
            return asset($file);
        }else{
            return null;
        }
    }
    public function getThumbRightUrlAttribute($file){
        if(!empty($file)){
            return asset($file);
        }else{
            return null;
        }
    }
}
