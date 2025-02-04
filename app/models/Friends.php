<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Friends extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(
		"followingId" => "required",
	);

	public function user(){
		return $this->belongsTo("Users","followingId","id");
	}

	public function myuser(){
		return $this->belongsTo("Users","userId","id");
	}

	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

	public static function checkFollower($following){
		$friend = parent::where("userId",Auth::user()->id)->where("followingId",$following)->count();
		if($friend > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getURL(){

		if(count($this->user()) > 0){
			return Helper::userType($this->user->userType)."/".$this->followingId."/".Helper::formatURLString($this->user->firstName.$this->user->lastName );
		} else {
			return Auth::user()->userType;
		}
        
    }


}