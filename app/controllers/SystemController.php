<?php

class SystemController extends \BaseController {


	public function index()
	{
		
		return View::make("ControlPanel.index");

	}


	public function syncWithStripeAndCheckMemberships(){
		
		if(Config::get("app.debug")){
			Stripe::setApiKey(Config::get("constants.STRIPETestsecret_key"));
		} else {
			Stripe::setApiKey(Config::get("constants.STRIPEsecret_key"));
		}
		$users = Users::all();
		$interval = "month";
       	$quantity = 1;
       	$today = date("Y-m-d");

		foreach($users as $user){
			//Perform a check that every user should have 1 ONLY membership and if noone 1 free trial

			$usermems = MembershipsUsers::where("userId",$user->id)->orderBy("subscriptionStripeKey","DESC")->get();

			$stripe = false;
			$first = true;
			if(count($usermems) > 0){
				foreach($usermems as $usermem){
					if($first){
						$first = false;
					} else {
						if($usermem->subscriptionStripeKey == "") $usermem->delete();
					}

				}
			} else {
				$memberhsip = Memberships::find(Config::get("constants.freeTrialMembershipId"));
				if($membership->durationType == "yearly"){
	                $interval = "years";
	            }elseif ($membership->durationType == "monthly"){
	                $interval = "months";
	            } else {
	                $interval = "days";
	            }

				$mem = new MembershipsUsers();
                $mem->membershipId = Config::get("constants.freeTrialMembershipId");
                $mem->expiry = date('Y-m-d', strtotime($today." + ".$quantity." ".$interval));
                $mem->registrationDate = date("Y-m-d");
                $mem->userId = $user->id;
                $mem->save();
			}



			//Check if the user has a stripe membership.
			$subscription = $user->getStripeSubscription();
			if ($subscription){
			    $user->updateStripeMembership($subscription->plan->id);
			} else {
				$membership = $user->getTrainerWorkoutMembership();
				if($membership){
					if($membership->hasMemberhsipExpired()){
						$user->updateToMembership(Config::get("constants.freeTrialMembershipId"));
					} else {
						//DO NOTHING
					}
				} else{
					$user->updateToMembership(Config::get("constants.freeTrialMembershipId"));
				}
			}
		}

	}

	public function fixUsedExercises(){
		$exercises = Exercises::get();
		foreach($exercises as $exercise){
			$exercise->used = WorkoutsExercises::where("exerciseId",$exercise->id)->count();
			$exercise->save();
		}
	}


	public function dailyActivity(){

		Log::info("Running DAILY ACTIVITY");
		$this->ControlPanelFeeds();
		Log::info("Sending confirmation Emails DAILY ACTIVITY");
		$this->reminderConfirmEmails();
		Log::info("Syncing with Stripe");
		$this->syncWithStripeAndCheckMemberships();


		//$this->sendTrainerClientWorkoutRevision();
		//$this->sendTrainerWeightReminder();
		//$this->sendTrainerPicturesReminder();
		//$this->sendTrainerMeasurementsReminder();
		//$this->sendTrainerInactiveReminder();
		//Tasks::dailyReminderChecker();

	}

	public function reminderConfirmEmails(){

		$users = Users::where(function($query2){ $query2->orWhere("activated",""); $query2->orWhereNull("activated") ;})->get();
		$intervals = array(2,5,10,15);
	
		foreach($intervals as $i){
		foreach($users as $user){
			$interval = $i;
			$date = $user->created_at;
			$toCompareDate = date('Y-m-d', strtotime($date." + ".$interval." days"));

			if($user->token == "") {
				 $guid = GUID::generate();
		         $user->token = $guid;
		         $user->save();
			}

			if($toCompareDate == date("Y-m-d")){
				$lang = $user->lang;
				if($lang == "") $lang = "en";
				$subject = Lang::get("messages.Emails_ReminderTrainerWorkoutEmailConfirmation");
				Mail::queueOn(App::environment(),'emails.'.Config::get("app.whitelabel").'.user.'.$lang.'.reminderActivateEmail', array("user"=>serialize($user)), function($message) use ($user,$subject)
		            {
		              $message->to($user->email)
		                        ->subject($subject);
		            });
			}
		}
		}

	}

	public function sendFeedback(){
		$feedback = Input::get("feedback");
		$date = date("Y-m-d");
		$user = null;
		//$email = Config::get("mail.username");
		$email = Config::get("app.feedbackEmail");
		if(Auth::check()) $user = Auth::user();
		Mail::queueOn(App::environment(),'ControlPanel.emails.feedback', array("date"=>$date, "user"=>serialize($user), "feedback"=>$feedback), function($message) use ($date,$user,$email)
			{
			  $message->to($email)
	          			->subject("Feeedback sent ".$date);
			});
		if(Auth::check()){
			if(Auth::user()->userType == "Trainer") return Redirect::route("trainerWorkouts")->with("message",Lang::get("messages.thankyoufeedback")); 
			if(Auth::user()->userType == "Trainee") return Redirect::route("traineeWorkouts")->with("message",Lang::get("messages.thankyoufeedback"));
			return Redirect::route("home")->with("message",Lang::get("messages.thankyoufeedback")); 
		} 
		return Redirect::route("home")->with("message",Lang::get("messages.thankyoufeedback"));
	}

	public function weeklyActivity(){


	}

	public function ControlPanelFeeds(){
		$feeds = Feeds::with("user")->whereNull("reported_at")->orderBy("reported_at","Desc")->get();
		
		$date = date("Y-m-d");
		$email = Config::get("mail.username");
		Feeds::whereNull("reported_at")->update(array("reported_at"=>date("Y-m-d")));
		Mail::queueOn(App::environment(),'ControlPanel.emails.feeds', array("date"=>$date,"feeds"=>serialize($feeds)), function($message) use ($email,$date)
				{
				  $message->to($email)
		          			->subject("Activity of ".$date);
				});
	}

	public static function sendTrainerClientWorkoutRevision(){
		$trainers = Users::where("userType","Trainer")->get();

		foreach($trainers as $trainer){
			$interval = 14; //DEFAULT 14 DAYS reminder
			$times = 8; //DEFAULT 30 times reminder
			$setting = UsersSettings::where("userId",$trainer->id)->where("name","setting_workout_reminder")->first();
			$settingTimes = UsersSettings::where("userId",$trainer->id)->where("name","setting_workout_reminder_number")->first();
			if($setting) $interval = $setting->value;
			if($settingTimes) $times = $settingTimes->value;
			$clients = Clients::where("trainerId",$trainer->id)->where("userId","!=",Config::get("constants.onboardingClient"))->get();
			foreach($clients as $client){
				$workouts = Workouts::where("authorId",$trainer->id)->where("userId",$client->userId)->get();
				foreach($workouts as $workout){
					$date = $workout->created_at;
					if($workout->lastRevized != "") $date = $workout->lastRevized;
					$toCompareDate = date('Y-m-d', strtotime($workout->lastRevized." + ".$interval." days"));	
					if($toCompareDate < date("Y-m-d") or $times < $workout->timesPerformedRevized){
//						Notifications::insertDynamicNotification("reminderWorkoutRevision",$trainer->id,$client->userId,array("clientFirstName"=>$client->user->firstName,"clientLastName"=>$client->user->lastName,"workoutName"=>$workout->name,"clientLink"=>$client->link()),true,null,"message",null,"feed");
						$workout->lastRevized = date("Y-m-d H:i:s");
						$workout->timesPerformedRevized = 0;
						$workout->save();
					}
				}
			}
		}
	}


	public static function sendTrainerWeightReminder(){
		$trainers = Users::where("userType","Trainer")->get();
		foreach($trainers as $trainer){
			$interval = 14; //DEFAULT 14 DAYS reminder
			$setting = UsersSettings::where("userId",$trainer->id)->where("name","setting_weight_reminder_number")->first();
			if($setting) $interval = $setting->value;
			$clients = Clients::where("trainerId",$trainer->id)->where("userId","!=",Config::get("constants.onboardingClient"))->get();
			foreach($clients as $client){
				$lastWeight = Weights::where("userId",$client->user->id)->orderBy("created_at","DESC")->first();
				if($lastWeight){
					$date = $lastWeight->created_at;
					if($lastWeight->reminded != "") $date = $lastWeight->reminded;
					$toCompareDate = date('Y-m-d', strtotime($date." + ".$interval." days"));
					if($toCompareDate < date("Y-m-d")){
						//Notifications::insertDynamicNotification("reminderWeightUpdate",$trainer->id,$client->userId,array("days"=>Helper::days($lastWeight->created_at),"clientFirstName"=>$client->user->firstName,"clientLastName"=>$client->user->lastName,"clientLink"=>$client->link()),true,null,"message",null,"feed");
						$lastWeight->reminded = date("Y-m-d H:i:s");
						$lastWeight->save();
					}
				} else {
						//if(Notifications::where("userId",$trainer->id)->where("fromId",$client->userId)->where("type","reminderWeightUpdateNew")->count() == 0) Notifications::insertDynamicNotification("reminderWeightUpdateNew",$trainer->id,$client->userId,array("clientFirstName"=>$client->user->firstName,"clientLastName"=>$client->user->lastName,"clientLink"=>$client->link()),true,null,"message","reminderWeightUpdateNew","feed");
				}
			}
		}
	}

	public static function sendTrainerPicturesReminder(){
		$trainers = Users::where("userType","Trainer")->get();

		foreach($trainers as $trainer){
			$interval = 14; //DEFAULT 14 DAYS reminder
			$setting = UsersSettings::where("userId",$trainer->id)->where("name","setting_pictures_reminder_number")->first();
			if($setting) $interval = $setting->value;
			$clients = Clients::where("trainerId",$trainer->id)->where("userId","!=",Config::get("constants.onboardingClient"))->get();
			foreach($clients as $client){
				$picture = Pictures::where("userId",$client->user->id)->orderBy("created_at","DESC")->first();
				if($picture){
					$date = $picture->created_at;
					if($picture->reminded != "") $date = $picture->reminded;
					$toCompareDate = date('Y-m-d', strtotime($date." + ".$interval." days"));
					if($toCompareDate < date("Y-m-d")){
						//Notifications::insertDynamicNotification("reminderPicturesUpdate",$trainer->id,$client->userId,array("days"=>Helper::days($picture->created_at),"clientFirstName"=>$client->user->firstName,"clientLastName"=>$client->user->lastName,"clientLink"=>$client->link()),true,null,"message",null,"feed");
						$picture->reminded = date("Y-m-d H:i:s");
						$picture->save();
					}
				} else {
						//if(Notifications::where("userId",$trainer->id)->where("fromId",$client->userId)->where("type","reminderPicturesUpdateNew")->count() == 0) Notifications::insertDynamicNotification("reminderPicturesUpdateNew",$trainer->id,$client->userId,array("clientFirstName"=>$client->user->firstName,"clientLastName"=>$client->user->lastName,"clientLink"=>$client->link()),true,null,"message","reminderPicturesUpdateNew","feed");
				}
			}
		}
	}

	public static function sendTrainerMeasurementsReminder(){
		$trainers = Users::where("userType","Trainer")->get();

		foreach($trainers as $trainer){
			$interval = 14; //DEFAULT 14 DAYS reminder
			$setting = UsersSettings::where("userId",$trainer->id)->where("name","setting_measurements_reminder_number")->first();
			if($setting) $interval = $setting->value;
			$clients = Clients::where("trainerId",$trainer->id)->where("userId","!=",Config::get("constants.onboardingClient"))->get();
			foreach($clients as $client){
				$measurement = Measurements::where("userId",$client->user->id)->orderBy("created_at","DESC")->first();
				if($measurement){
					$date = $measurement->created_at;
					if($measurement->reminded != "") $date = $measurement->reminded;
					$toCompareDate = date('Y-m-d', strtotime($date." + ".$interval." days"));
					if($toCompareDate < date("Y-m-d")){
						//Notifications::insertDynamicNotification("reminderMeasurementUpdate",$trainer->id,$client->userId,array("days"=>Helper::days($measurement->created_at),"clientFirstName"=>$client->user->firstName,"clientLastName"=>$client->user->lastName,"clientLink"=>$client->link()),true,null,"message",null,"feed");
						$measurement->reminded = date("Y-m-d H:i:s");
						$measurement->save();
					}
				} else {
						//if(Notifications::where("userId",$trainer->id)->where("fromId",$client->userId)->where("type","reminderMeasurementUpdateNew")->count() == 0) Notifications::insertDynamicNotification("reminderMeasurementUpdateNew",$trainer->id,$client->userId,array("clientFirstName"=>$client->user->firstName,"clientLastName"=>$client->user->lastName,"clientLink"=>$client->link()),true,null,"message","reminderMeasurementUpdateNew","feed");
				}
			}
		}
	}

	public static function sendTrainerInactiveReminder(){
		$trainers = Users::where("userType","Trainer")->get();

		foreach($trainers as $trainer){
			$interval = 14; //DEFAULT 14 DAYS reminder
			$setting = UsersSettings::where("userId",$trainer->id)->where("name","setting_weight_reminder_number")->first();
			if($setting) $interval = $setting->value;
			$clients = Clients::where("trainerId",$trainer->id)->where("userId","!=",Config::get("constants.onboardingClient"))->get();
			foreach($clients as $client){
				//WEB
				$date = $client->user->created_at;
				if($client->user->updated_at != "") $date = $client->user->updated_at;
				$toCompareDate = date('Y-m-d', strtotime($date." + ".$interval." days"));
				if($toCompareDate < date("Y-m-d")){
					//Notifications::insertDynamicNotification("reminderInactivity",$trainer->id,$client->userId,array("phone"=>$client->user->phone,"clientFirstName"=>$client->user->firstName,"clientLastName"=>$client->user->lastName,"clientLink"=>$client->link()),true,null,"message",null,"feed");
					$client->user->updated_at = date("Y-m-d H:i:s");
					$client->user->save();
				}

			}
		}
	}

	public function migrateWorkout($fromUserId,$toUserId){

		 $newWorkout = Workouts::copyWorkoutsFromTo($fromUserId,$toUserId);
	}

	public function migrateWorkouts($workoutNumber=""){
		
		$cutInDate = "2016-08-23";

		ini_set('max_execution_time', 420000);
		set_time_limit(420000);

		if($workoutNumber == ""){
			//$workouts = Workouts::where("created_at","<",$cutInDate)->orderBy("created_at","DESC")->get();
			$workouts = Workouts::where("created_at","<",$cutInDate)->whereIn("id",array())->orderBy("created_at","DESC")->get();
		} else {
			$workouts = Workouts::where("id","=",$workoutNumber)->get();
		}
		




		$json = array();
		$jsonRest = array();

		foreach($workouts as $workout){


			

			$groups = WorkoutsGroups::where("workoutId",$workout->id)->get(); 

			foreach($groups as $group){
				$workoutExercises = WorkoutsExercises::where("workoutId",$workout->id)->where("groupId",$group->id)->orderBy("id","ASC")->get();

				$jsonGroup = array();

				$jsonRestGroup = new stdClass();


				if(count($workoutExercises) > 1){
					$arr = array();
					$jsonRestGroup->circuitStyle = ($group->circuitType != "") ? $group->circuitType : "rounds";
					$arrayRestBetweenCircuitExercises = unserialize($group->restBetweenCircuitExercises);
					
					if(is_array($arrayRestBetweenCircuitExercises)){
						
						foreach($arrayRestBetweenCircuitExercises as $element) {
							array_push($arr, $element);
						}
						
					}

					$jsonRestGroup->restBetweenCircuitExercises = $arr;

					if($jsonRestGroup->circuitStyle == "emom"){
						$jsonRestGroup->circuitEmom = ($group->circuitEmom == 0) ? 1 : $group->circuitEmom;
					}

					if($jsonRestGroup->circuitStyle == "amrap"){
						$jsonRestGroup->circuitMaxTime = ($group->circuitMaxTime == 0) ? 1 : $group->circuitMaxTime;
					}

					$jsonRestGroup->circuitRound = ($group->intervals == 0) ? 1 : $group->intervals;
					$jsonRestGroup->circuitRest = $group->rest;
				}
				$jsonRestGroup->type = $group->type;
				$jsonRestGroup->restTime = $group->restAfter;
				

				
				foreach($workoutExercises as $exercise){

					$sub = new stdClass();
					
					$sub->repType = ($exercise->metric != "") ? $exercise->metric : "rep";
					if($sub->repType == "reps") $sub->repType = "rep";
					$sub->metric = ($exercise->units != "") ? $exercise->units : "imperial";
					$sub->notes = ($exercise->notes != "") ? $exercise->notes : "";
					$sub->tempo1 = ($exercise->tempo1 != "") ? $exercise->tempo1 : "";
					$sub->tempo2 = ($exercise->tempo2 != "") ? $exercise->tempo2 : "";
					$sub->tempo3 = ($exercise->tempo3 != "") ? $exercise->tempo3 : "";
					$sub->tempo4 = ($exercise->tempo4 != "") ? $exercise->tempo4 : "";
					$sub->restBetweenSets = array();

					$ex = Exercises::withTrashed()->find($exercise->exerciseId);
					$ex->equipmentId = $exercise->equipmentId;

					$sub->exercise = $ex;

					$sets = TemplateSets::where("workoutsExercisesId",$exercise->id)->orderBy("number","ASC")->get();

					$repsType = array();
					$weights = array();
					$reps = array();
					$speeds = array();
					$distances = array();
					$times = array();
					$hrs = array();
					$restBetweenSets = array();


					foreach($sets as $set){
						

						if($set->metric == ""){
							array_push($repsType,"rep");
						} else {
							array_push($repsType,$set->metric);
						}

						if($set->metric == "time" or ($set->metric == "rep" and $set->type == "cardio")){
							array_push($reps,$set->time);
						} else {
							array_push($reps,$set->reps);
						}

						if($set->weight == ""){
							array_push($weights,0);
						} else {
							array_push($weights,$set->weight);
						}

						
						array_push($speeds,($set->speed != "") ? $set->speed : "");
						array_push($distances,($set->distance != "") ? $set->distance : "");
						array_push($times,($set->time != "") ? $set->time : "");
						array_push($hrs,($set->bpm != "") ? $set->bpm : "");
						array_push($restBetweenSets,$set->rest);
					}

					array_pop($restBetweenSets);
					$sub->repsType = $repsType;
					$sub->weights = $weights;
					$sub->hrs = $hrs;
					$sub->repArray = $reps;
					$sub->speeds = $speeds;
					$sub->times = $times;
					$sub->distances = $distances;
					$sub->restBetweenSets = $restBetweenSets;
		
					array_push($jsonGroup,$sub);
				}

				
				array_push($json,$jsonGroup);
				array_push($jsonRest,$jsonRestGroup);
			}

		$workout->exerciseGroup = json_encode($json);
		$workout->exerciseGroupRest = json_encode($jsonRest);

		$workout->save();

			
		}

		// Helper::printLastQuery(100);
		// print_r("JSON");
		// print_r(json_encode($json));
		// print_r("</br>");
		// print_r("</br>");
		// print_r("</br>JSONREST");
		// print_r(json_encode($jsonRest));
	}



	public function changeLanguange($locale){
		$url = URL::previous();
		
		$url = str_replace(Config::get("app.url"),"",$url);

		$routes = Lang::get("routes");
		$routes = array_flip($routes);
		$base = (array_key_exists($url, $routes)) ? $routes[$url] : "";
	


		App::setLocale($locale);
		Session::put("lang",$locale);
		Session::save();
		if(Auth::check()){
			Auth::user()->lang = $locale;
			Auth::user()->save();
		}
		
		$urlTranslated = Lang::get("routes.".$base);
		
		
		if($base == ""){ 
			if ( ! Request::header('referer')){
				return Redirect::route("home");
			} else {
				return Redirect::back()->with('message',Lang::get("messages.LanguageChanged"));
			}
		    
		} else {
			return Redirect::to($urlTranslated)->with('message',Lang::get("messages.LanguageChanged"));
		    $exists = true;
		}
	
	}

	public function _indexScripts(){
		return View::make("ControlPanel.MaintenanceScripts")
		->with("users",Users::select(DB::raw("concat('id: ',id,' - ',firstName,' ',lastName,' ',email) as name "),"id")->orderBy("firstName","ASC")->orderBy("lastName","ASC")->lists("name","id"))
		->with("workouts",Workouts::withTrashed()->with("user")->orderBy("workouts.id","DESC")->get());
	}




	public function fixExercisesTranslations(){

		$previous_locale = App::getLocale();;
		$outputToPrint = array();

		
		$exercises = Exercises::all();
		foreach($exercises as $exercise){
			$name = "";
			$has = array();
			$dontHave = array();

			foreach(Config::get("app.locale_available") as $locale){
				$translation = $exercise->getTranslation($locale,false);
				if(!$translation or $translation->name == ""){
					array_push($dontHave,$locale);
				} else {
					array_push($has,$locale);
				}
			}

			if(count($dontHave) > 0){
				$winLocale = "en";
				if(count($has) == 1 and $has[0] == "en") $winLocale = $has[0];
				if(count($has) > 1 and in_array($has,"en")){
					$winLocale = "en";
				} else {
					if(count($has) == 0) $winLocale = "en";
				}
			}

			foreach($dontHave as $dont){
				$translation = $exercise->getTranslation($locale,false);
				if(!$translation or $translation->name == ""){
					$ex = $exercise->translateOrNew($dont);
					$final = "";
					$subTranslation = $exercise->getTranslation($winLocale,false);
					$row = DB::select(DB::raw("Select * from exercises where id = ".$exercise->id));
					//dd($row);
					if($subTranslation and $subTranslation->name != "") { 
						$final = $subTranslation->name;
					} else if(is_array($row) && array_key_exists(0, $row) && $row[0]->name != ""){
						$final = $row[0]->name;
					} else {
						$final = "NEEDS TRANSLATIONS ON ALL LANGUAGES";
					}
					$ex->name = $final;
					$ex->exercises_id = $exercise->id;
					$ex->created_at = date('Y-m-d H:i:s');
					$ex->save();
					array_push($outputToPrint,$ex);
				}
			}
		}

		App::setLocale($previous_locale);

		return $this::responseJson($outputToPrint);

		}


		public function removeUserFromDatabase(){
			$userId = Input::get("userId");

			Appointments::where("userId",$userId)->forceDelete();
			Availabilities::where("userId",$userId)->forceDelete();
			Clients::where("userId",$userId)->delete();
			Feeds::where("userId",$userId)->forceDelete();
			Friends::where("userId",$userId)->forceDelete();
			Invites::where("userId",$userId)->forceDelete();
			Measurements::where("userId",$userId)->forceDelete();
			MembershipsUsers::where("userId",$userId)->forceDelete();
			Notifications::where("userId",$userId)->forceDelete();
			Objectives::where("userId",$userId)->forceDelete();
			Permissions::where("userId",$userId)->forceDelete();
			Pictures::where("userId",$userId)->forceDelete();
			SessionsUsers::where("userId",$userId)->forceDelete();
			Sharings::where("fromUser",$userId)->forceDelete();
			Tags::where("userId",$userId)->forceDelete();
			Tasks::where("userId",$userId)->forceDelete();
			UserUpdates::where("userId",$userId)->forceDelete();
			Weights::where("userId",$userId)->forceDelete();
			Workoutsperformances::where("userId",$userId)->delete();
			

			$workouts = Workouts::where("userId",$userId)->get();
			foreach($workouts as $workout){
				$workout->forceDelete();
			}

			Users::where("id",$userId)->delete();

			return $this::responseJson("Completed");
		}


		public function restoreWorkout($workoutId){
			$workoutId = Input::get("workoutId");		

		
			$workout = Workouts::withTrashed()->find($workoutId);
			$workout->restore();
			return $this::responseJson("Completed");

		}


		public function workoutsToRestore(){
			$workoutId = Input::get("workoutId");		

		
			$workout = Workouts::withTrashed()->find($workoutId);
			$workout->restore();
			return $this::responseJson("Completed");

		}

	

}
