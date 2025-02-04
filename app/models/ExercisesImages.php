<?php

class ExercisesImages extends \Eloquent {
	
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];
}