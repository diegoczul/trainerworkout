<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;



class Measurements extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(
		"recordDate" => "required|date",
		"chest" => "numeric",
		"abdominals" => "numeric",
		"bicepsLeft" => "numeric",
		"bicepsRight" => "numeric",
		"forearmLeft" => "numeric",
		"forearmRight" => "numeric",
		"legsLeft" => "numeric",
		"legsRight" => "numeric",
		"calfLeft" => "numeric",
		"calfRight" => "numeric",
		"waist" => "numeric",
	);

	public function users(){
		return $this->belongsTo("Users");
	}

	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

}