<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Appointments extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(
		"appointment" => "required|max:300",
		"dateStart" => "required|date",
		"dateEnd" => "required|date"
	);

	public function user(){
		return $this->hasOne("Users","id","targetId");
	}

	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

}