<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;


class Weights extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(
		"weight" => "required|min:2|numeric",
		"dateRecord" => "date",
		"recordDate" => "date"
	);

	public function users(){
		return $this->belongsTo("Users");
	}

	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

}