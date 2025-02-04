<?php

class WorkoutsExercises extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];


	public function exercises(){
		return $this->hasOne("Exercises","id","exerciseId")->withTrashed();
	}

	public function equipment(){
		return $this->hasOne("Equipments","id","equipmentId")->withTrashed();
	}

	public function sets(){
		return $this->hasMany("Sets","workoutsExercisesId","id")->orderBy("number","ASC");
	}

	public function templateSets(){
		return $this->hasMany("TemplateSets","workoutsExercisesId","id")->orderBy("number","ASC");
	}


}