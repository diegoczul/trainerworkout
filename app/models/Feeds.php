<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Feeds extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(
		"followingId" => "required",
	);

	public function user(){
		return $this->belongsTo("Users","userId","id");
	}


	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

	public static function insertFeed($message,$userId,$firstName="",$lastName="",$type="",$link="",$action=""){
		$final = Lang::get("messages.".$message);
		$user = Users::find($userId);
		//$trainers = Clients::where("userId",$userId)->distinct()->lists("trainerId");
		
		$final = str_replace("{firstName}",$user->firstName,$final);
		$final = str_replace("{lastName}",$user->lastName,$final);

		//if(count($trainers > 0)){
		//	foreach($trainers as $trainer){
		//		Notifications::insertDynamicNotification($final,$trainer,$userId,array(),false);
		//	}
		//}

		self::insert( array(
						"message"		=>	$final,
						"userId"		=>	$userId,
						"type"			=>	$type,
						"action"		=>	$action,
						"link"			=>	$link,
						"created_at"	=> 	date('Y-m-d H:i:s')
						)
			);
	}

	public static function insertFeedUserObject($message,$user,$type="",$link="",$action=""){
	
		$final = $message;
		$userId = $user->id;
		$final = str_replace("{firstName}",$user->firstName,$final);
		$final = str_replace("{lastName}",$user->lastName,$final);
		$final = str_replace("{email}",$user->lastName,$final);

		self::insert( array(
						"message"		=>	$final,
						"userId"		=>	$userId,
						"type"			=>	$type,
						"action"		=>	$action,
						"link"			=>	$link,
						"created_at"	=> 	date('Y-m-d H:i:s')
						)
			);
	}

	public static function insertDynamicFeed($message,$userId,$userWhoTriggeredTheFeedObject,$variables,$type="",$link="",$action=""){
		$final = Lang::get("messages.".$message);
		$trainers = Clients::where("userId",$userId)->distinct()->lists("trainerId");
		if($variables != null){
			foreach($variables as $variable => $value){
				$final = str_replace("{".$variable."}",$value,$final);
			}
		}



		if(Feeds::where("userId",$userId)->where("message",$final)->where("type",$type)->where("created_at",">=",Helper::startOfDay())->where("created_at","<=",Helper::endOfDay())->count() == 0){
			self::insert( array(
							"message"		=>	$final,
							"userId"		=>	$userId,
							"type"			=>	$type,
							"action"		=>	$action,
							"link"			=>	$link,
							"created_at"	=> 	date('Y-m-d H:i:s')
							)
			);
		}
		
	}
}