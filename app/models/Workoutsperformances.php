<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Workoutsperformances extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(

	);

	public function user(){
		return $this->hasOne("Users","id","userId");
	}

	public function trainer(){
		return $this->hasOne("Users","id","forTrainer");
	}

	public function workout(){
		return $this->hasOne("Workouts","id","workoutId");
	}

	public function rating(){
		return $this->hasOne("Ratings","id","ratingId");
	}

	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

	public function notifyTrainerPerformance(){



		$fromUser = $this->user;
		$toUser = $this->trainer;
		$workout = $this->workout;
		$workoutPerformance = $this;
		$rating = $this->rating;
		$ratingString = "";
		if($rating) $ratingString = $rating->name;

		$client = Clients::where("trainerId",$toUser->id)->where("userId",$fromUser->id)->first();
		

		if($client and $client->subscribeClient == 1){

		$to_user = $toUser->email;
		$name = ($fromUser->firstName != "") ? $fromUser->getCompleteName() : $fromUser->email;
		$subject = Lang::get("content.emailWorkoutPerformed",array("name"=>$name,"workout"=>$workout->name));

			Mail::queueOn(App::environment(),'emails.'.Config::get("app.whitelabel").'.user.'.App::getLocale().'.workoutPerformed', array("toUser"=>serialize($toUser),"fromUser"=>serialize($fromUser),"workout"=>serialize($workout),"performance"=>serialize($workoutPerformance),"rating"=>serialize($rating),"ratingString"=>$ratingString), function($message) use ($to_user,$fromUser,$subject)
					{
					  $message->to($to_user)
					  			->replyTo($fromUser->email,$fromUser->getCompleteName())
			          			->subject($subject);;
					});


			Event::fire('notifyActivity', array(Auth::user(), $toUser));
		

		} 
		// else {
		// 	$to_user = $toUser->email;
		// 	$name = ($fromUser->firstName != "") ? $fromUser->getCompleteName() : $fromUser->email;
		// 	$subject = Lang::get("content.emailWorkoutPerformed",array("name"=>$name,"workout"=>$workout->name));

		// 		Mail::queueOn(App::environment(),'emails.'.Config::get("app.whitelabel").'.user.'.App::getLocale().'.workoutPerformed', array("toUser"=>serialize($toUser),"fromUser"=>serialize($fromUser),"workout"=>serialize($workout),"performance"=>serialize($workoutPerformance),"rating"=>serialize($rating),"ratingString"=>$ratingString), function($message) use ($to_user,$fromUser,$subject,$toUser)
		// 				{
		// 				  $message->to($fromUser->email)
		// 				  			->replyTo($to_user,$toUser->getCompleteName())
		// 		          			->subject($subject);
		// 		          		});
		// }
	}

}

