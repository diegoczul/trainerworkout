<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class UsersSettings extends Model {

    use SoftDeletes;
    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = [
        'name' => 'required|min:2|max:300',
        'value' => 'required|max:1000',
    ];

    public function users(){
        return $this->hasOne(Users::class,'id','userId');
    }

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }
}
