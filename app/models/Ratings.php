<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Ratings extends \Eloquent {
	use SoftDeletingTrait;
	use Dimsav\Translatable\Translatable;

	protected $fillable = [];
	public $translatedAttributes = ['name'];
	public $useTranslationFallback = true;
	
	protected $dates = ['deleted_at'];

	public static $rules = array(
		
	);

	public function trainer(){
		return $this->hasOne("Users","id","ownerId");
	}

	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

}