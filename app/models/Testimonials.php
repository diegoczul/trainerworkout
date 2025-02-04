<?php

class Testimonials extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(

	);

	public function user(){
		return $this->hasOne("Users","id","userId");
	}

	public function fUser(){
		return $this->hasOne("Users","id","fromUser");
	}

	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

}