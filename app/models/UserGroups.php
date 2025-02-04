<?php

class UserGroups extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(

	);

	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

	public function user(){
		return $this->hasOne("Users","id","userId");
	}

	public function group(){
		return $this->hasOne("Groups","id","groupId");
	}

}

