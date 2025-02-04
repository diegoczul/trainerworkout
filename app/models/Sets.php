<?php

class Sets extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(

	);


	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

	public function workoutsExercises(){
		return $this->belongsTo("WorkoutsExercises","workoutsExercisesId","id");
	}



}