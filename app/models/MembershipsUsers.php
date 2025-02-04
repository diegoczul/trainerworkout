<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;



class MembershipsUsers extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(

	);

	public function users(){
		return $this->hasOne("Users","id","userId");
	}

	public function membership(){
		return $this->hasOne("Memberships","id","membershipId");
	}

	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

	 public function hasMemberhsipExpired(){
        $dateExpiry = date('Y-m-d', strtotime($this->expiry));
        if($dateExpiry < date('Y-m-d')){
            return true;
        } else {
            return false;
        }
    }

}