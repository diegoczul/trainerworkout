<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class ExercisesEquipments extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(

	);


	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

	public function equipments(){
		return $this->hasOne("Equipments","id","equipmentId");
	}


}