<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;



class ExercisesUser extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(

	);

	public function users(){
		return $this->hasOne("Users","id","userId");
	}

	public function exercises(){
		return $this->hasOne("Exercises","id","exerciseId");
	}

	public static function validate($data){
		return Validator::make($data, static::$rules);
	}


}