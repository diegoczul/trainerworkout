<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;


class Pictures extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(
		"recordDate" => "required|date"
	);

	public function users(){
		return $this->belongsTo("Users");
	}

	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

}