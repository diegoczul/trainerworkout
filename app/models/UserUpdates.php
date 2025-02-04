<?php

class UserUpdates extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public function user(){
		 return $this->hasOne("Users","userId","id");
	}

	public function trainer(){
		 return $this->hasMany("Users","trainerId","id");
	}

	public function group(){
		 return $this->hasMany("UserGroups","teamId","id");
	}

	public function workout(){
		 return $this->hasMany("Workouts","auxId","id")->where("type","workout");
	}
}