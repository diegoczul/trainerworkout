<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class ExercisesExercisestypes extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(

	);


	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

	public function exercisestypes(){
		return $this->hasOne("Exercisestypes","id","exercisestypesId");
	}


}