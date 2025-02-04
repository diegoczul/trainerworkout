<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Bodygroups extends \Eloquent {
	use SoftDeletingTrait;
	use Dimsav\Translatable\Translatable;

	protected $fillable = [];
	public $translatedAttributes = ['name','description'];
	public $useTranslationFallback = true;

	protected $dates = ['deleted_at'];

	public static $rules = array(
		"name" => "required|min:2|max:500",
		"description" => "max:500",
		"equipment" => "max:500"
	);


	public static function validate($data){
		return Validator::make($data, static::$rules);
	}



}