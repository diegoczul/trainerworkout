<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Tags extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(
		
	);

	

	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

	
	


}