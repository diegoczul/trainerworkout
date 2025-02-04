<?php

class Sharings extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	//$link = 'http://'.$_SERVER['HTTP_HOST'].'/Share/Workout/'.$share->PreviewLink($user->id, 0, base64_decode($_GET['workout']), $_GET['type'] == 'my' ? 'Workout' : 'TrainerWorkout').'/';



	public function toUserObject(){
		return $this->hasOne("Users","id","toUser");
	}

	public function fromUserObject(){
		return $this->hasOne("Users","id","fromUser");
	}



	public static function previewSharing($from_user, $to_user, $aux, $type){
	

            if ($to_user==NULL) $to_user = 0;

            $al = sha1($from_user.$to_user.$aux.$type);

            return $al;
	}

	public static function shareWorkout($from_user, $to_user, $workoutObject, $type,$comments="",$invite=null,$copyMe=true,$copyView=true,$copyPrint=true,$subscribe=true,$lock=true){
		
		if ($to_user==NULL) $to_user = 0;
        
        $newWorkout = Workouts::AddWorkoutToUser($workoutObject->id,$to_user,null,$lock);
        $client = Clients::where("userId",$to_user)->where("trainerId",Auth::user()->id)->first();

        if($subscribe)  {
        	$client->subscribeClient = 1;
        } else {
        	$client->subscribeClient = 0;
        }
        $client->save();

        $link = sha1($from_user.$to_user.$newWorkout->id.$type);


        $workoutPDF = $newWorkout->getPrintPDF();
        $workoutScreeshot = $newWorkout->getImageScreenshot();
        $workoutScreeshotPDF = $newWorkout->getImagePDF();

        
        //$aux
        //$ToUser = Users::where("email",$to_user)->first();
       // if($friend){

        $toUser = Users::find($to_user);
        if($toUser){

        	if(self::where("access_link",$link)->count() > 0){
	        	$sharing = self::where("access_link",$link)->first();
	        	$sharing->viewed = 0;
	        	$sharing->accepted = 0;
	        	$sharing->dateShared = date("Y-m-d H:i:s");
	        	$sharing->toUser = $toUser->id;
	        	$sharing->save();
	        	$fromUser = Users::find($from_user);
	        	$subject = Lang::get("messages.Emails_sharedWorkout");


	        		//Notifications::insertDynamicNotification("SharedWorkout",$toUser->id,$fromUser->id,array("link"=>URL::to("/Share/Workout/".$link."/"),"linkAccept"=>URL::to("/Share/Workout/Accept/".$link."/"),"name"=>$workoutObject->name,"friendFirstName"=>$fromUser->firstName,"friendLastName"=>$fromUser->lastName),true,$sharing->access_link);
					Mail::queueOn(App::environment(),'emails.'.Config::get("app.whitelabel").'.user.'.App::getLocale().'.sharedWorkout', array("sharing"=>serialize($sharing),"invite"=>serialize($invite),"toUser"=>serialize($toUser),"fromUser"=>serialize($fromUser),"comments"=>$comments), function($message) use ($toUser,$sharing,$fromUser,$workoutPDF,$workoutScreeshot,$subject,$workoutScreeshotPDF,$copyMe,$copyView,$copyPrint)


					{
					  $message->to($toUser->email)
					  			->replyTo($fromUser->email,$fromUser->getCompleteName())
					  			//->from(Config::get("mail.from."))
					  			//->cc(Config::get("constants.activityEmail"))
					  			//->cc(Config::get("mail.username"))
			          			->subject($subject);
			          
			          if($copyMe) $message->cc($fromUser->email);
			          if($copyView) {
			          	$message->attach($workoutScreeshot);
				        $message->attach($workoutScreeshotPDF);
			          }

			          if($copyPrint) $message->attach($workoutPDF);


					});
	        	
	        } else {
	        	$sharing = new Sharings();
	        	$sharing->viewed = 0;
	        	$sharing->accepted = 0;
	        	$sharing->dateShared = date("Y-m-d H:i:s");
	        	$sharing->fromUser = $from_user;
	        	$sharing->toUser = $toUser->id;
	        	$sharing->access_link = $link;
	        	$sharing->type = $type;
	        	$sharing->toUser = $toUser->id;
	        	$sharing->aux = $newWorkout->id;
	        	$sharing->save();
	        	$toUser;
	        	$fromUser = Users::find($from_user);
	        	$subject = Lang::get("messages.Emails_sharedWorkout");
	        	if($toUser){


	        		//Notifications::insertDynamicNotification("SharedWorkout",$toUser->id,$fromUser->id,array("link"=>URL::to("/Share/Workout/".$link."/"),"linkAccept"=>URL::to("/Share/Workout/Accept/".$link."/"),"name"=>$workoutObject->name,"friendFirstName"=>$fromUser->firstName,"friendLastName"=>$fromUser->lastName));
					Mail::queueOn(App::environment(),'emails.'.Config::get("app.whitelabel").'.user.'.App::getLocale().'.sharedWorkout', array("sharing"=>serialize($sharing),"invite"=>serialize($invite),"toUser"=>serialize($toUser),"fromUser"=>serialize($fromUser),"comments"=>$comments), function($message) use ($toUser,$sharing,$fromUser,$workoutPDF,$workoutScreeshot,$subject,$workoutScreeshotPDF,$copyMe,$copyView,$copyPrint)


					{
					  $message->to($toUser->email)
					  			->replyTo($fromUser->email,$fromUser->getCompleteName())
					  			//->from($fromUser->email,$fromUser->getCompleteName())
					  			//->cc(Config::get("constants.activityEmail"))
					  			//D->cc(Config::get("mail.username"))
			          			->subject($subject);

			          if($copyMe) $message->cc($fromUser->email);
			          
			          if($copyView) {
			          	$message->attach($workoutScreeshot);
				        $message->attach($workoutScreeshotPDF);
			          }

			          if($copyPrint) $message->attach($workoutPDF);
					});
					
	        	}
	        }
        } else {
        	if(self::where("access_link",$link)->count() > 0){
        		$fromUser =  Auth::user();
	        	$sharing = self::where("access_link",$link)->first();
	        	$sharing->viewed = 0;
	        	$sharing->accepted = 0;
	        	$sharing->dateShared = date("Y-m-d H:i:s");
	        	$sharing->save();
	        	$subject = Lang::get("messages.Emails_sharedWorkout");
	        	if($to_user != ""){


	        		Mail::queueOn(App::environment(),'emails.'.Config::get("app.whitelabel").'.user.'.App::getLocale().'.sharedWorkout', array("sharing"=>serialize($sharing),"invite"=>serialize($invite),"fromUser"=>serialize($fromUser),"comments"=>$comments), function($message) use ($to_user,$fromUser,$sharing,$toUser,$workoutPDF,$workoutScreeshot,$subject,$workoutScreeshotPDF,$copyMe,$copyView,$copyPrint)


					{
					  $message->to($to_user)
					  			->replyTo($fromUser->email,$fromUser->getCompleteName())
			          			->subject($subject);
			          if($copyMe) $message->cc($fromUser->email);
			          if($copyView) {
			          	$message->attach($workoutScreeshot);
				        $message->attach($workoutScreeshotPDF);
			          }

			          if($copyPrint) $message->attach($workoutPDF);
					});
					
	        	}
	        	
	        } else {
	        	$sharing = new Sharings();
	        	$user;
	        	$sharing->viewed = 0;
	        	$sharing->accepted = 0;
	        	$sharing->dateShared = date("Y-m-d H:i:s");
	        	$sharing->fromUser = $from_user;
	        	$sharing->access_link = $link;
	        	$sharing->type = $type;
	        	$sharing->aux = $newWorkout->id;
	        	$sharing->save();
	        	$fromUser = Users::find($from_user);
	        	$subject = Lang::get("messages.Emails_sharedWorkout");
	        	if($to_user != ""){


	        		Mail::queueOn(App::environment(),'emails.'.Config::get("app.whitelabel").'.user.'.App::getLocale().'.sharedWorkout', array("sharing"=>serialize($sharing),"invite"=>serialize($invite),"fromUser"=>serialize($fromUser),"comments"=>$comments), function($message) use ($to_user,$fromUser,$sharing,$fromUser,$workoutPDF,$workoutScreeshot,$subject,$workoutScreeshotPDF,$copyMe,$copyView,$copyPrint)


					{
					  $message->to($to_user)
					  			//->cc(Config::get("constants.activityEmail"))
					  			->replyTo($fromUser->email,$fromUser->getCompleteName())
			          			->subject($subject);
			          if($copyMe) $message->cc($fromUser->email);
			          if($copyView) {
			          	$message->attach($workoutScreeshot);
				        $message->attach($workoutScreeshotPDF);
			          }

			          if($copyPrint) $message->attach($workoutPDF);
					});
					
	        	}
	        	
	        }
        }
	}


}