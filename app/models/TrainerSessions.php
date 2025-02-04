<?php

class TrainerSessions extends \Eloquent {

	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(
		"name" => "required|min:2|max:300",
		"description" => "required|max:1000"
	);

	public function users(){
		return $this->hasOne("Users","id","userId");
	}

	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

}