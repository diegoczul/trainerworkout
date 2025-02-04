<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Equipments extends \Eloquent {
	use SoftDeletingTrait;
	use Dimsav\Translatable\Translatable;
	

	protected $fillable = [];
	public $translatedAttributes = ['name','nameEngine'];
	

	protected $dates = ['deleted_at'];

	public static $rules = array(
		
	);

	

	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

	
	


}