<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class ExercisesEquipments extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'exerciseId' ];
    protected $dates = ['deleted_at'];

    public static $rules = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public function equipments()
    {
        return $this->hasOne(Equipments::class, 'id', 'equipmentId');
    }
}
