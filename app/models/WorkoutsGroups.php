<?php

class WorkoutsGroups extends \Eloquent {
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

	public function getExercises(){
		return WorkoutsExercises::with("exercises")->where("groupId",$this->id)->orderBy("order");
	}

	public function getExercisesImagesCircuit(){
		$images = array();
		$exercises = $this->getExercises()->get();
	
		$index = 0;
		$images = array();

		foreach($exercises as $exercise){
			
			$images[$index] = $exercise->exercises->image;
			$index++;
		}
		
		return $images;
	}



}