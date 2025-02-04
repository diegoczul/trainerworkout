<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;



class Memberships extends \Eloquent {
	use SoftDeletingTrait;
	use Dimsav\Translatable\Translatable;
	protected $fillable = [];
	public $translatedAttributes = ['name','description','features'];
	public $useTranslationFallback = true;

	public static $rules = array(

	);

	public function users(){
		return $this->belongsTo("Users");
	}

	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

	
	public static function checkMembership($user){

		$numberOfClients = Clients::where("trainerId",$user->id)->count();
		$membership = MembershipsUsers::where("userId",$user->id)->first();
		if($membership){
			$mem = Memberships::find($membership->membershipId);
			if($mem->type == "clients"){
				if($membership->expiry > date("Y-m-d")){
					
					if($numberOfClients < $mem->clientesAllowed){
						
						$membership->userId = $user->id;
						//DEFAULT MEMBERSHIP
						$membership->membershipId = 3;
						$membership->registrationDate = date("Y-m-d H:i:s");
						if($mem->durationType == "monthly"){
							$membership->expiry = date('Y-m-d H:i:s', strtotime('+1 month'));
						} else {
							$membership->expiry = date('Y-m-d H:i:s', strtotime('+1 year'));
						}
						$membership->save();

						return "";
					} else {
						return Lang::get("messages.UpgradeMembership");
					}
				} else {
					
					if(($mem->id == 1 || $mem->id == 3) and ($numberOfClients < $mem->clientesAllowed)){
						$membership->expiry = date('Y-m-d H:i:s', strtotime('+1 year'));
						$membership->save();
						return "";
					}
					return Lang::get("messages.MembershipExpired");
				}
			}

			if($mem->type == "workouts"){
				$workoutsAllowed = $mem->workoutsAllowed;
				$workoutsUser = Workouts::where("userId",$user->id)->where(function($query){ $query->orWhere("status","Released"); } )->count();


				if($membership->expiry > date("Y-m-d")){

					
					if($workoutsUser > $workoutsAllowed){
						return Lang::get("messages.UpgradeMembershipWorkouts".$workoutsAllowed);
					} else {
						return "";
					}
				} else {

					if($workoutsUser < $mem->workoutsAllowed){
						if($mem->durationType == "monthly") $membership->expiry = date('Y-m-d H:i:s', strtotime('+1 month'));
						if($mem->durationType == "yearly") $membership->expiry = date('Y-m-d H:i:s', strtotime('+1 year'));
						$membership->save();
						return "";
					}
					return Lang::get("messages.MembershipExpiredWorkouts".$workoutsAllowed);
				}
			}
		} else {
			//DEFAULT MEMBERSHIP
			$mem = Memberships::find(Config::get("constants.defaultMembership"));
			$membership = new MembershipsUsers();
			$membership->userId = Auth::user()->id;
			$membership->membershipId = $mem->id;
			$membership->expiry = date('Y-m-d H:i:s', strtotime(Config::get("constants.defaultMembershipExpiry")));
			$membership->save();

			return "";
		}
	}

}