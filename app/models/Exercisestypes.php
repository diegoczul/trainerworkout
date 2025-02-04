<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Exercisestypes extends \Eloquent {
	use SoftDeletingTrait;
	use Dimsav\Translatable\Translatable;

	protected $fillable = [];
	public $translatedAttributes = ['name'];
	public $useTranslationFallback = true;


	protected $dates = ['deleted_at'];

	public static $rules = array(
		"name" => "required|min:2|max:500",
	);


	public static function validate($data){
		return Validator::make($data, static::$rules);
	}



}