<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Objectives extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(
		"objective" => "required|min:2|max:500",
		"measureable" => "max:500",
		"dateRecord" => "required|date"
	);

	public function users(){
		return $this->belongsTo("Users");
	}

	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

}