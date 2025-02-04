<?php

class Groups extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(

	);

	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

	function countUsers(){
		return UserGroups::where("groupId",$this->id)->count();
	}

	public static function checkGroupPermissions($requester,$toPersonifyWhom){
		$user = Users::find($requester);
		if($user){
			$toUser = Users::find($toPersonifyWhom);
			$group = UserGroups::where("userId",$user->id)->first();
			if($group){
				if($group->role == "Owner" or "Admin"){
					return true;
				} else {
					return false;
				}
			}
		}
		return false;
	}
}

