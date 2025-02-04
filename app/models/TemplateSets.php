<?php

class TemplateSets extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(

	);


	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

	public function exercises(){
		return $this->hasMany("exercises","id","exerciseId");
	}



}