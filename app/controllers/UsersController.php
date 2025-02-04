<?php

class UsersController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /users
	 *
	 * @return Response
	 */
	public function Index()
	{
		
		$user = Auth::user();
		return View::make('trainer.index')
		->with("user",$user);
	}

	public function gym()
	{
		
		
		return View::make('gym');

	}

	public function gymSignUp()
	{
		
		
	return View::make('gymSignUp');

	
	}

	public function trainerIndex()
	{
		
		$user = Auth::user();
		return View::make('trainer.index')
		->with("user",$user);
	}


	public function trainerGetStarted()
	{
		
		$user = Auth::user();
		return View::make('TrainerSignUp')
		->with("user",$user);
	}

	public function trainerGetStartedPaid()
	{
		
		$user = Auth::user();
		return View::make('TrainerSignUp')
		->with("paid","yes")
		->with("user",$user);
	}

	public function indexSettings()
	{
		
		$user = Auth::user();
		$userId = $user->id;
		$permissions = null;
		if($userId != ""){
			$permissions = Helper::checkPremissions(Auth::user()->id,$userId);
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		$userPermissions = array();
		$staticPermissions = Permissions::where("userId",$user->id)->get();
		foreach($staticPermissions as $staticPermission){
			$userPermissions[$staticPermission->widget] = $staticPermission->access;
		}

		if($user){
			return View::make('trainee.settings')
				->with("permissions",$permissions)
				->with("userPermissions",$userPermissions)
				->with("user",$user);
		} else {
			return Redirect::route('Trainee', array(Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->withError(Lang::get("messages.UserNotFound"));
		}
	}



	public function indexMemberships(){
		$user = Auth::user();
		$memberships = Memberships::all();
		$membershipsUser = MembershipsUsers::where("userId",$user->id)->first();
		return View::make('trainer.memberships')
			->with("memberships",$memberships)
			->with("membershipsSelected",$membershipsUser)
			->with("user",Auth::user());
	}

	function rotateRight(){
		$obj = Auth::user();
		
		if($obj){
			if(file_exists($obj->image)){
				$image = Image::make($obj->image);
				$image->rotate(-90);
				$image->save();
				$image = Image::make($obj->thumb);
				$image->rotate(-90);
				$image->save();
			}

			return $this::responseJson(Lang::get("messages.ImageRotated"));
		}
	}

	function rotateLeft(){
		$obj = Auth::user();
	
		if($obj){
			if(file_exists($obj->image)){
				$image = Image::make($obj->image);
				$image->rotate(90);
				$image->save();
				$image = Image::make($obj->thumb);
				$image->rotate(90);
				$image->save();
			}
			

			return $this::responseJson(Lang::get("messages.ImageRotated"));
		}
	}

	public function indexSettingsTrainer()
	{
		
		$user = Auth::user();
		$userId = $user->id;
		$permissions = null;
		if($userId != ""){
			$permissions = Helper::checkPremissions(Auth::user()->id,$userId);
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		$userPermissions = array();
		$staticPermissions = Permissions::where("userId",$user->id)->get();
		$staticPermissionsSettings = UsersSettings::where("userId",$user->id)->get();
		foreach($staticPermissions as $staticPermission){
			$userPermissions[$staticPermission->widget] = $staticPermission->access;
		}
		foreach($staticPermissionsSettings as $staticPermission){
			$userPermissions[$staticPermission->name] = $staticPermission->value;
		}


		
		if($user){
			return View::make('trainer.settings')
				->with("permissions",$permissions)
				->with("userPermissions",$userPermissions)
				->with("user",$user);
		} else {
			return Redirect::route('Trainee', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->withError(Lang::get("messages.UserNotFound"));
		}
	}

	public function confirmEmail($token){
		$user = Users::where("token",$token)->first();
		if($user){
			$user->activated = Helper::now();
			$user->save();
			Auth::loginUsingId($user->id);
			Event::fire('confirmEmail', array(Auth::user()));
			return Redirect::route(strToLower(Auth::user()->userType).'Workouts')->with("message",Lang::get("messages.EmailConfirmed"));
		}else {
			return Redirect::route(strToLower(Auth::user()->userType).'Workouts')->withError(Lang::get("messages.EmailNotConfirmed"));
		}
	}

	

	public function indexSuggestPeople(){
		$userId = Auth::user()->id;
		$search = Input::get("term");
		

		return $this->responseJson(Users::where(function($query) use ($search)
											 {
											       $query->orWhere('firstName', "like","%".$search."%");
											       $query->orWhere('lastName', "like","%".$search."%");
											       $query->orWhere('email', "like","%".$search."%");
											 })
										->whereNotIn("id",array_merge(array(0),Clients::where("trainerId",$userId)->lists("userId")))
				->get());
	}

	public function indexSuggestPeopleWithClients()
	{

		$userId = Auth::user()->id;
		$search = Input::get("term");
		
		$list = Users::whereIn("id",array_merge(array(0),Friends::select("users.id")->where( function($query) use ($userId) { 
					$query->orWhere("userId","=",$userId);
					$query->orWhere("followingId","=",$userId);
				})
				->leftJoin('users', function($join) {
				      $join->on('users.id', '=', 'followingId');
				    })
				->where(function($query) use ($search)
											 {
											       $query->orWhere('firstName', "like","%".$search."%");
											       $query->orWhere('lastName', "like","%".$search."%");
											       $query->orWhere('email', "like","%".$search."%");
											 })->lists("users.id")))->orWhereIn("id",array_merge(array(0),
		Clients::select("users.id")
			->leftJoin('users', function($join) {
			      $join->on('users.id', '=', 'userId');
			    })
			->where(function($query) use ($search)
									 {
									       $query->orWhere('firstName', "like","%".$search."%");
									       $query->orWhere('lastName', "like","%".$search."%");
									       $query->orWhere('email', "like","%".$search."%");
									 })
			->where("trainerId",$userId)->lists("users.id")))->get();
		
		

		return $this->responseJson($list);

	}

	public function registerNewsletter(){

		$validation = Validator::make(Input::all(), array("email"=>"email|required"));
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			MailchimpWrapper::lists()
                        ->subscribe(Config::get("constants.mailChimpNewsletter"), array('email' => Input::get("email"),'email_address' => Input::get("status"),'email' => "subscribed"));
        		return $this->responseJson(Lang::get("messages.newsletter"));
		}
		
	}

	public function settingsSave(){
		$user = Auth::user();

		$permissions = Permissions::where("userId",$user->id);
		$staticPermissions = array(			
										"w_objectives",
										"w_pictures",
										"w_measurements",
										"w_workouts",
										"w_information",
										"w_userMessages",
										"email_notifications",
										"w_publicProfile",
										"newsletter",
										"email_notifications_trainer",
										"email_notifications_workout",
										"email_notifications_client",
										"email_notifications_people",
										"email_notifications_trainer",
										"setting_workout_reminder",
										"setting_workout_reminder_number",
										"setting_weight_reminder_number",
										"setting_measurements_reminder_number",
										"setting_pictures_reminder_number",
										"setting_inactive_reminder_number"
													);



		foreach($staticPermissions as $key){

			if(strpos($key,'setting') === false){

				$permissionFetched = Permissions::where("widget",$key)->where("userId",$user->id)->first();
				if($permissionFetched){
					$perm = Permissions::find($permissionFetched->id);
					$variable = Input::get($key); 
					$perm->access = $variable;
					$perm->save();

				} else {
					$newPermission = new Permissions();
					$newPermission->userId = $user->id;
					$newPermission->widget = $key;
					
					$newPermission->access = Input::get($key); 
					$newPermission->save();
				}
			
			} else {
				$permissionFetched = UsersSettings::where("name",$key)->where("userId",$user->id)->first();
					if($permissionFetched){
						$perm = UsersSettings::find($permissionFetched->id);
						$variable = Input::get($key); 
						$perm->value = $variable;
						$perm->save();

					} else {
						$newPermission = new UsersSettings();
						$newPermission->userId = $user->id;
						$newPermission->name = $key;
						
						$newPermission->value = Input::get($key); 
						$newPermission->save();
					}
			}
		}	

		return $this->responseJson(Lang::get("messages.PermissionsSaved"));

	}

	public function settingsSaveTrainer(){
		$user = Auth::user();

		$permissions = Permissions::where("userId",$user->id);
		$staticPermissions = array(			
										"w_objectives",
										"w_pictures",
										"w_measurements",
										"w_workouts",
										"w_information",
										"w_userMessages",
										"email_notifications",
										"w_publicProfile",
										"newsletter",
										"email_notifications_trainer",
										"email_notifications_workout",
										"email_notifications_client",
										"email_notifications_people",
										"email_notifications_trainer",
										"setting_workout_reminder",
										"setting_workout_reminder_number",
										"setting_weight_reminder_number",
										"setting_measurements_reminder_number",
										"setting_pictures_reminder_number",
										"setting_inactive_reminder_number"
													);



		foreach($staticPermissions as $key){

		if(strpos($key,'setting') === false){

			$permissionFetched = Permissions::where("widget",$key)->where("userId",$user->id)->first();
			if($permissionFetched){
				$perm = Permissions::find($permissionFetched->id);
				$variable = Input::get($key); 
				$perm->access = $variable;
				$perm->save();

			} else {
				$newPermission = new Permissions();
				$newPermission->userId = $user->id;
				$newPermission->widget = $key;
				
				$newPermission->access = Input::get($key); 
				$newPermission->save();
			}
		} else {
				$permissionFetched = UsersSettings::where("name",$key)->where("userId",$user->id)->first();
				if($permissionFetched){
					$perm = UsersSettings::find($permissionFetched->id);
					$variable = Input::get($key); 
					$perm->value = $variable;
					$perm->save();

				} else {
					$newPermission = new UsersSettings();
					$newPermission->userId = $user->id;
					$newPermission->name = $key;
					$newPermission->value = Input::get($key); 
					$newPermission->save();
				}
			}
		}

		Event::fire('updateFeedSettings', array(Auth::user()));

		return $this->responseJson(Lang::get("messages.PermissionsSaved"));


	}

	public function indexProfile()
	{
		
		return View::make('trainee.profile')
			->with("user",Auth::user());
	}

	public function sendFeedback()
	{
		
		return View::make(strToLower(Auth::user()->userType).'.sendFeedback')
			->with("user",Auth::user());
	}

	public function viewWorkoutTrainee()
	{
		
		return View::make('trainee.viewWorkout')
			->with("user",Auth::user());
	}

	public function viewWorkoutsTrainee()
	{
		
		return View::make('trainee.workouts')
			->with("user",Auth::user());
	}
	
	public function indexVideoWord()
	{
		$userId = Auth::user()->id;
		$user = Auth::user();
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
				$user = Users::find($userId);
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		return View::make('widgets.full.videoWord')
			->with("permissions",$permissions)
			->with("user",$user);
	}

	public function indexBioFull()
	{
		$userId = Auth::user()->id;
		$user = Auth::user();
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
				$user = Users::find($userId);
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}


		return View::make('widgets.full.biography')
			->with("permissions",$permissions)
			->with("user",$user);
	}

	public function indexProfileTrainer()
	{
		$logo = Users::find(Auth::user()->id)->activeLogo;

		return View::make('trainer.profile')
			->with("user",Auth::user())
			->with("logo",$logo);
	}

	public function indexTrainee($userId,$userName)
	{
		$user = Users::find($userId);



		$permissions = null;
		if($userId != ""){
			if(Clients::where("trainerId",Auth::user()->id)->where("userId",$userId)->count() == 0){ 

				if(Helper::checkPermissionString($userId,"w_publicProfile") == "no" ){
					return Redirect::route('Trainee', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->withError(Lang::get("messages.PrivateAccount"));
				} elseif(Helper::checkPermissionString($userId,"w_publicProfile") == "friends" and Friends::where("followingId",$viewer)->where("userId",$toView)->count() == 0){

					return Redirect::route('Trainee', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->withError(Lang::get("messages.PrivateAccount"));
				}
			}

			$permissions = Helper::checkPermission(Auth::user()->id,$userId,"w_information");
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPermission(Auth::user()->id,null);
		}

		if($user){
			return View::make('trainee.trainee')
				->with("permissions",$permissions)
				->with("user",$user);
		} else {
			return Redirect::route('Trainee', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->withError(Lang::get("messages.UserNotFound"));
		}
		
	}

	public function indexTrainer($userId,$userName)
	{
		$user = Users::find($userId);



		$permissions = null;
		if($userId != ""){
			if(Clients::where("trainerId",Auth::user()->id)->where("userId",$userId)->count() == 0){ 

				if(Helper::checkPermissionString($userId,"w_publicProfile") == "no" ){
					return Redirect::route(Auth::user()->userType, array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->withError(Lang::get("messages.PrivateAccount"));
				} elseif(Helper::checkPermissionString($userId,"w_publicProfile") == "friends" and Friends::where("followingId",$viewer)->where("userId",$toView)->count() == 0){

					return Redirect::route(Auth::user()->userType, array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->withError(Lang::get("messages.PrivateAccount"));
				}
			}

			$permissions = Helper::checkPermission(Auth::user()->id,$userId,"w_information");
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPermission(Auth::user()->id,null);
		}


		if($user){
			
			return View::make(trim(strtolower(Auth::user()->userType)).'.trainer')
				->with("permissions",$permissions)
				->with("user",$user);
		} else {
			return Redirect::route('Trainee', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->withError(Lang::get("messages.UserNotFound"));
		}
		
	}


	public function globalSearch(){
		//$search = Input::search();

		$user = Auth::user();

		if(Auth::user()->userType == "Trainer"){
			return View::make('trainer.search')
			->with("search",Input::get("search"))
			->with("user",$user);
		} 
		return View::make('trainee.search')
			->with("search",Input::get("search"))
			->with("user",$user);
	}



	public function indexEditTrainee()
	{
		$user = Auth::user();
		return View::make('trainee.editProfile')
		->with("user",$user);;
	}
	

	public function indexEditTrainer()
	{
		$user = Auth::user();
		$permissions = null;
		$userId = Auth::user()->id;

		$logo = Users::find(Auth::user()->id)->activeLogo;


		if($userId != ""){
			$permissions = Helper::checkPremissions(Auth::user()->id,$userId);
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

        
		return View::make('trainer.profile')
		->with("user",$user)
		->with("logo",$logo)
		->with("permissions",$permissions);
	}
	

	public function ApiList()
	{
		
		return $this::responseJson(Users::getList());
		
	}

	public function AddEdit()
	{
		if(Input::has("hiddenUserId") and Input::get("hiddenUserId") != ""){
			return $this->update(Input::get("hiddenUserId"));
		} else {
			return $this->create();
		}		
	}

	public function AddEditBio()
	{

        
		$rules = array(
			"biography" => "max:5000",
			"certifications" => "max:5000",
			"past_experience" => "max:5000",
		);
		$validation = Validator::make(Input::all(), $rules);
		if($validation->fails()){
			//return View::make('trainer.editProfile')->withErrors($validation->messages());
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			//$user = Users::where('UR_Index','=',$id)->get()->first();
			$user = Auth::user();
			$user->biography = Input::get("biography");
			$user->certifications = Input::get("certifications");
			$user->past_experience = Input::get("past_experience");
			$user->save();
			return $this::responseJson(Lang::get("messages.BioSaved"));	
			
		}		
	}

	public function AddEditVideoWord()
	{

		$rules = array(
			"word" => "max:5000",
			"videoLink" => "url",
		);
		$validation = Validator::make(Input::all(), $rules);
		if($validation->fails()){
			//return View::make('trainer.editProfile')->withErrors($validation->messages());
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			//$user = Users::where('UR_Index','=',$id)->get()->first();
			$user = Auth::user();
			$user->word = Input::get("word");
			$user->videoLink = Input::get("video");
			$user->videoKey = Helper::extractYoutubeTag(Input::get("video"));
			$user->save();
			return $this::responseJson(Lang::get("messages.WordSaved"));	
			
		}
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /users/create
	 *
	 * @return Response
	 */
	public function TraineeSignUp()
	{
		
		$user = null;
		
			if(Input::has("invite")){
				$invite = Invites::where("key",Input::get("invite"))->first();
				if($invite){
					if($invite->fakeId != ""){
						$user = Users::find($invite->fakeId);
						if(Input::get("timezone") != ""){
							$user->timezone = Input::get("timezone");
						}
						$password = Input::get("password");
						if(Input::get("password") == "") $password = "TrainerWorkout";
						$user->password = Hash::make($password);
						$user->firstName = ucfirst(Input::get("firstName"));
						$user->lastName = ucfirst(Input::get("lastName"));
						$user->email = strtolower(Input::get("email"));
						$user->phone = Helper::formatPhone(strtolower(Input::get("phoneNumber")));
						$user->userType = "Trainee";
						$user->save();
						$invite->completeInvite();
						Auth::loginUsingId($user->id);
						Event::fire('signUp', array($user));
						

					} else {
						$validation = Users::validate(Input::all());
		if($validation->fails()){
			return Redirect::back()->withInput()->withErrors($validation->messages());
		} else {
						$user = new Users;
						$user->firstName = ucfirst(Input::get("firstName"));
						$user->lastName = ucfirst(Input::get("lastName"));
						$user->email = strtolower(Input::get("email"));
						$user->phone = Helper::formatPhone(strtolower(Input::get("phoneNumber")));
						if(Input::get("timezone") != ""){
							$user->timezone = Input::get("timezone");
						}
						$user->password = Hash::make(Input::get("password"));
						$user->userType = "Trainee";
						$user->save();
						Auth::loginUsingId($user->id);
						Event::fire('signUp', array($user));
					}
					$invite->completeInvite($user);
				}
				}
			} else {
				$validation = Users::validate(Input::all(),array("termsAndConditions"=>"required"));
		if($validation->fails()){
			return Redirect::back()->withInput()->withErrors($validation->messages());
		} else {
				$user = new Users;
				$user->firstName = ucfirst(Input::get("firstName"));
				$user->lastName = ucfirst(Input::get("lastName"));
				$user->email = strtolower(Input::get("email"));
				$user->phone = Helper::formatPhone(strtolower(Input::get("phoneNumber")));
				if(Input::get("timezone") != ""){
					$user->timezone = Input::get("timezone");
				}
				$user->password = Hash::make(Input::get("password"));
				$user->userType = "Trainee";
				$user->save();
				Auth::loginUsingId($user->id);
			}
			}
			
			
			if(Input::has("workout") and Input::get("workout") != ""){
				$workout = Workouts::find(Input::get("workout"));
				$workoutNew = new Workouts();
				$workoutNew->name = $workout->name;
				$workoutNew->shares = 0;
				$workoutNew->views = 0;
				$workoutNew->timesPerformed = 0;
				$workoutNew->objectives = $workout->objectives;
				$workoutNew->userId = Auth::user()->id;
				$workoutNew->authorId = $workout->authorId;
				$workoutNew->availability = "private";
				$workoutNew->save();

				$WorkoutsExercises = WorkoutsExercises::where("workoutId",$workout->id)->get();
				foreach($WorkoutsExercises as $workoutExercise){
					$workoutExerciseNew = new WorkoutsExercises();
					$workoutExerciseNew = $workoutExercise->replicate();
					$workoutExerciseNew->workoutId = $workoutNew->id;
					$workoutExerciseNew->save();

					$templateSets = TemplateSets::where("workoutsExercisesId",$workoutExercise->id)->get();

					foreach($templateSets as $templateSet){
						$templateSetNew = new TemplateSets;
						$templateSetNew = $templateSet->replicate();
						$templateSetNew->workoutId = $workoutNew->id;
						$templateSetNew->workoutsExercisesId = $workoutExerciseNew->id;
						$templateSetNew->save();
					}
				}



				$workoutNew->createSets();
			}

			Invites::where("email",$user->email)->where("completed",0)->update(array("completed"=>1));

			
			Feeds::insertFeed("SignUp",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);

			try{
			 MailchimpWrapper::lists()
                        ->subscribe(Config::get("constants.mailChimpTrainees"), array('email' => Input::get("email"),'email_address' => Input::get("status"),'email' => "subscribed"));
			} catch(Exception $e){
				Log::error($e);
			}

			if(!Auth::user()->membership) Auth::user()->updateToMembership(Config::get("constants.freeTrialMembershipId"));


			return Redirect::route('traineeWorkouts')->with("message",Lang::get("messages.Welcome"))->with("newUser",true);;
		
		
		//$user->save();
	}

	public function TrainerFreeTrialSignUp()
	{
		$validation = Users::validate(Input::all(),array("termsAndConditions"=>"required"));
		if($validation->fails()){
			return Redirect::back()->withInput()->withErrors($validation->messages());
		} else {
			if(!Input::has("termsAndConditions")){
				return Redirect::route("home")->withInput()->withErrors(Lang::get("messages.termsAndConditions"));
			}
			$user = new Users;
			$user->firstName = ucfirst(Input::get("firstName"));
			$user->lastName = ucfirst(Input::get("lastName"));
			$user->email = strtolower(trim(Input::get("email")));
			if(Input::get("timezone") != ""){
				$user->timezone = Input::get("timezone");
			}
			$user->phone = Helper::formatPhone(strtolower(Input::get("phoneNumber")));
			$user->password = Hash::make(Input::get("password"));
			$user->userType = "Trainer";
			$user->lastLogin = date("Y-m-d");
			$user->save();


			$user->sendActivationEmail();


			Auth::loginUsingId($user->id);

			Event::fire('signUp', array($user));

			try{
				if(!Config::get("app.debug")) MailchimpWrapper::lists()->subscribe(Config::get("constants.mailChimpTrainers"), array('email' => $user->email));
			} catch(Exception $e){
	            Log::error("MAILCHIMP Error");
	            Log::error($e);
	            return null;
        	}

			

			if(Session::has("utm")){

				$user->marketing = Session::get("utm");
				$user->save();
				Session::forget('utm');
			}

			$user->freebesTrainer();

			if(Input::get("paid") == "yes"){
				return Redirect::to("/Store/addToCart/63/Membership");
			}
			
		
			if(Session::has("redirect") and Session::get("redirect") != ""){
				

				if(!Auth::user()->membership) Auth::user()->updateToMembership(Config::get("constants.freeTrialMembershipId"));


				return Redirect::route(Session::get("redirect"));
			} else{

				if(!Auth::user()->membership) Auth::user()->updateToMembership(Config::get("constants.freeTrialMembershipId"));


				return Redirect::route('trainerWorkouts', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->with("message",Lang::get("messages.Welcome"));
			}
			
			
		}
		
		//$user->save();
	}



	public function TrainerSignUp()
	{

		$validation = Users::validate(Input::all(),array("termsAndConditions"=>"required"));
		if($validation->fails()){
			return Redirect::back()->withInput()->withErrors($validation->messages());
		} else {
			if(!Input::has("termsAndConditions")){
				return Redirect::back()->withInput()->withErrors(Lang::get("messages.termsAndConditions"));
			}
			$user = new Users;
			$user->firstName = ucfirst(Input::get("firstName"));
			$user->lastName = ucfirst(Input::get("lastName"));
			$user->email = strtolower(Input::get("email"));
			if(Input::get("timezone") != ""){
				$user->timezone = Input::get("timezone");
			}
			$user->password = Hash::make(Input::get("password"));
			$user->userType = "Trainer";
			$user->city = ucfirst(Input::get("city"));
			$user->province = ucfirst(Input::get("province"));
			$user->country = ucfirst(Input::get("country"));
			$user->biography = ucfirst(Input::get("biography"));
			$user->certifications = ucfirst(Input::get("certifications"));
			$user->specialities = ucfirst(Input::get("specialities"));
			$user->past_experience = ucfirst(Input::get("past_experience"));
			$user->lastLogin = date("Y-m-d");
			$user->save();
			Auth::loginUsingId($user->id);
			Event::fire('signUp', array($user));

			if(Session::has("utm")){
				$user->marketing = Session::get("utm");
				$user->save();
				Session::forget('utm');
			}

			$user->freebesTrainer();

			if(Input::has("invite")){
				$invite = Invites::where("key",Input::get("invite"))->first();
				if($invite){
					$invite->completeInvite($user);
				}
			}
			if(Input::has("workout") and Input::get("workout") != ""){
				$workout = Workouts::find(Input::get("workout"));
				$workoutNew = new Workouts();
				$workoutNew->name = $workout->name;
				$workoutNew->shares = 0;
				$workoutNew->views = 0;
				$workoutNew->timesPerformed = 0;
				$workoutNew->objectives = $workout->objectives;
				$workoutNew->userId = Auth::user()->id;
				$workoutNew->authorId = $workout->authorId;
				$workoutNew->availability = "private";
				$workoutNew->save();

				$WorkoutsExercises = WorkoutsExercises::where("workoutId",$workout->id)->get();
				foreach($WorkoutsExercises as $workoutExercise){
					$workoutExerciseNew = new WorkoutsExercises();
					$workoutExerciseNew = $workoutExercise->replicate();
					$workoutExerciseNew->workoutId = $workoutNew->id;
					$workoutExerciseNew->save();

					$templateSets = TemplateSets::where("workoutsExercisesId",$workoutExercise->id)->get();

					foreach($templateSets as $templateSet){
						$templateSetNew = new TemplateSets;
						$templateSetNew = $templateSet->replicate();
						$templateSetNew->workoutId = $workoutNew->id;
						$templateSetNew->workoutsExercisesId = $workoutExerciseNew->id;
						$templateSetNew->save();
					}
				}

				$workoutNew->createSets();
			}
			Feeds::insertFeed("SignUp",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);

			try{
			MailchimpWrapper::lists()
                        ->subscribe(Config::get("constants.mailChimpTrainers"), array('email' => Input::get("email"),'email_address' => Input::get("status"),'email' => "subscribed"));
            } catch(Exception $e){
				Log::error($e);
			}

			if(!Auth::user()->membership) Auth::user()->updateToMembership(Config::get("constants.freeTrialMembershipId"));

			if(Input::get("paid") == "yes"){
				return Redirect::to("/Store/addToCart/64/Membership");
			} else {
				return Redirect::route('trainerWorkouts', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->with("message",Lang::get("messages.Welcome"))->with("newUser",true);
			}
				
			}
		}
					

	
	public function TraineeInvite($key=""){
		$invite = Invites::where("key",$key)->first();
		$inviteEmail = "";
		$inviteFirstName = "";
		$inviteLastName = "";
		if($invite){
			$invite->viewed = 1;
			$invite->save();
			$inviteEmail = $invite->email;
			$inviteFirstName = $invite->firstName;
			$inviteLastName = $invite->lastName;
		}
		return View::make(Helper::translateOverride('TraineeSignUp'))->with("key",$key)->with("invite",$invite);
	}

	public function TraineeInviteWithWorkout($workout){

		return View::make('TraineeSignUp')->with("workout",$workout);
	}

	public function create()
	{
		$validation = Users::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$user = new Users;
			$user->firstName = ucfirst(Input::get("firstName"));
			$user->lastName = ucfirst(Input::get("lastName"));
			$user->email = strtolower(Input::get("email"));
			$user->phone = strtolower(Input::get("phone"));
			$user->userType = "Trainee";
			$user->save();
			return $this::responseJson("User Created");	
		}
		
		//$user->save();
	}

    public function login()
    {
        if (Auth::attempt(array('email' => Input::get("email"), 'password' =>Input::get("password")),true)){
			//Feeds::insertFeed("Welcome",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			Auth::user()->updated_at = date("Y-m-d H:i:s");
			Auth::user()->lastLogin = date("Y-m-d H:i:s");
			Auth::user()->virtual = 0;
			Auth::user()->save();
			Event::fire('login', array(Auth::user()));
			setcookie("TrainerWorkoutUserId", Crypt::encrypt(Auth::user()->id), time() + (86400 * 30)*7, "/"); // 86400 = 1 day

			if(Auth::check()){
				if(Auth::user()->lang != ""){
					App::setLocale(Auth::user()->lang);
				} else {
					App::setLocale(Session::get('lang',"en"));	
				}	
			} else {
				App::setLocale(Session::get('lang',"en"));	
			}

			
			if(Auth::user()->userType == "Trainer"){
			//if(1==1){

                
				return Redirect::route('trainerWorkouts', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->with("message",Lang::get("messages.Welcome"));
			} else {

                
				return Redirect::route('traineeWorkouts', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->with("message",Lang::get("messages.Welcome"));
			}	
			

		} else {
			return Redirect::back()->withInput()->withErrors(Lang::get("messages.WrongLogin"))->withInput();
			//return Redirect::route("home")->withErrors("You have entered a wrong email or password")->withInput();
		}
		

	}

	// public function APILogin(){
	// 	if (Auth::attempt(array('email' => Input::get("email"), 'password' =>Input::get("password")),true)){
	// 		Feeds::insertFeed("Welcome",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
	// 		if(Auth::user()->userType == "Trainer"){
	// 			Tasks::dailyReminderChecker();
	// 		}
	// 		return $this->responseJson(Lang::get("messages.Welcome"));	

	// 	} else {
	// 		return $this->responseJson(Lang::get("messages.WrongLogin"));
	// 	}		
		
	

	// }

	/**
	 * Store a newly created resource in storage.
	 * POST /users
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /users/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return Users::find($id);
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /users/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /users/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$rules = array(
			"firstName" => "required|min:2",
			"lastName" => "required|min:2",
			"email" => "required|email",
			"password" => "",
			"password_confirmation " => "same:password"
		);

		$validation = Validator::make(Input::all(), $rules);
		if($validation->fails()){
			return Redirect::back()->withErrors($validation->messages());
			//return $this::responseJsonErrorValidation($validation->messages());
		} else {
			//$user = Users::where('UR_Index','=',$id)->get()->first();
			$user = Users::find($id);
			$user->firstName = ucfirst(Input::get("firstName"));
			$user->lastName = ucfirst(Input::get("lastName"));
			$user->email = strtolower(Input::get("email"));
			$user->gender = strtolower(Input::get("gender"));
			$user->phone = Helper::formatPhone(strtolower(Input::get("phone")));

			Event::fire('editProfileInformation', array(Auth::user()));

			if(Input::get("password")){
				$user->password = Hash::make(Input::get("password"));
			}
			$user->save();
			return $this::responseJson("User Modified");	
			
		}
		
	}

	public function TraineeSave()
	{

		$rules = array(
			"firstName" => "required|min:2",
			"lastName" => "required|min:2",
			"password" => "",
			"password_confirmation " => "same:password",
			"email" => "required|email|unique:users,email,NULL,id,deleted_at,NULL"
		);
		if(Auth::user()->email != Input::get("email")){
			$rules["email"] = "required|email|unique:users,email,NULL,id,deleted_at,NULL";
		} else {
			$rules["email"] = "required|email";
		}
		$validation = Validator::make(Input::all(), $rules);
		if($validation->fails()){
			return Redirect::back()->withErrors($validation->messages());
		} else {
			//$user = Users::where('UR_Index','=',$id)->get()->first();
			$user = Auth::user();
			$user->firstName = ucfirst(Input::get("firstName"));
			$user->lastName = ucfirst(Input::get("lastName"));
			$user->email = strtolower(Input::get("email"));
			$user->phone = Helper::formatPhone(strtolower(Input::get("phone")));
			$user->birthday = strtolower(Input::get("birthday"));
			$user->gender = strtolower(Input::get("gender"));
			$user->userType = "Trainee";
			if(Input::get("timezone") != ""){
				$user->timezone = Input::get("timezone");
			}
			if(Input::get("password")){
				$user->password = Hash::make(Input::get("password"));
			}
			$user->save();

			Helper::checkUserFolder($user->id);

			if(Input::hasFile("image")) {
					
					$images = Helper::saveImage(Input::file("image"),$user->getPath().Config::get("constants.profilePath")."/".$user->id);
					$user->image = $images["image"];
					$user->thumb = $images["thumb"];
					$user->save();
			}

//			Feeds::insertFeed("UpdateProfile",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			return Redirect::route('TraineeProfile')->with("message",Lang::get("messages.ProfileSaved"));
			
		}
		
	}



	public function TrainerSave()
	{


		$rules = array(
			"firstName" => "required|min:2",
			"lastName" => "required|min:2",
			"password" => "",
			"password_confirmation " => "same:password",
			"email" => "required|email|unique:users,email,NULL,id,deleted_at,NULL"
		);
		if(Auth::user()->email != Input::get("email")){
			$rules["email"] = "required|email|unique:users,email,NULL,id,deleted_at,NULL";
		} else {
			$rules["email"] = "required|email";
		}
		
		$validation = Validator::make(Input::all(), $rules);


		if($validation->fails()){
			return Redirect::route('TrainerProfile')->withErrors($validation->messages());
		} else {
			//$user = Users::where('UR_Index','=',$id)->get()->first();
			$user = Auth::user();
	
				$user->firstName = ucfirst(Input::get("firstName"));
				$user->lastName = ucfirst(Input::get("lastName"));
				$user->email = strtolower(Input::get("email"));
				$user->gender = strtolower(Input::get("gender"));
				$user->phone = Helper::formatPhone(strtolower(Input::get("phone")));
				$user->birthday = strtolower(Input::get("birthday"));
				$user->suite = strtolower(Input::get("suite"));
				$user->Address = strtolower(Input::get("Address"));
				$user->city = strtolower(Input::get("city"));
				$user->street = strtolower(Input::get("street"));
				$user->country = strtolower(Input::get("country"));
				$user->province = strtolower(Input::get("province"));
				$user->userType = "Trainer";
				if(Input::get("timezone") != ""){
					$user->timezone = Input::get("timezone");
				}
				if(Input::get("password")){
					$user->password = Hash::make(Input::get("password"));
				}
				$user->save();

				Helper::checkUserFolder($user->id);
				

				


				if(Input::hasFile("image")) {
						$images = Helper::saveImage(Input::file("image"),$user->getPath().Config::get("constants.profilePath")."/".$user->id);
						$user->image = $images["image"];
						$user->thumb = $images["thumb"];
						$user->save();
				}


				
					
				if(Input::hasFile("logo")) {
					$userlogo = new UserLogos;
					$userlogo->userId = $user->id;
					
					$images = Helper::saveImage(Input::file("logo"),$user->getPath().Config::get("constants.profilePath")."/".$user->id);
					$userlogo->image = $images["image"];
					$userlogo->thumb = $images["thumb"];

					UserLogos::where("userId",$user->id)->update(array("active"=>0));
					$userlogo->active = 1;
					
					$userlogo->save();
				}

				
				

//				Feeds::insertFeed("UpdateProfile",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
				return Redirect::route('TrainerProfile')->with("message",Lang::get("messages.ProfileSaved"));
			} 
			
		
		
		
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /users/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$user = Users::find($id);
		$user->delete();
		return $this::responseJson("User Deleted");	
	}

	public function logout(){
		if(Auth::check()){
			//Feeds::insertFeed("Logout",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			Auth::logout();
			$lang = Session::get("lang");
			Session::flush();
			if (isset($_COOKIE['TrainerWorkoutUserId'])) {
			    unset($_COOKIE['TrainerWorkoutUserId']);
			    setcookie('TrainerWorkoutUserId', '', time() - 3600, '/');
			    //return true;
			} else {
			    //return false;
			}
			if($lang != "") { 
				Session::put("lang",$lang);
				Session::save(); 
			}
		}
		return Redirect::route("home");
	}


	public function loginFacebook() {

    // get data from input
    $code = Input::get( 'code' );

    $OAuth = new OAuth();
    $OAuth::setHttpClient('CurlClient');

    // get fb service
     $fb = $OAuth::consumer( 'Facebook' , Input::get('redirectUri'));

    // check if code is valid

    // if code is provided get user data and sign in
    if ( !empty( $code ) ) {

        // This was a callback request from facebook, get the token
        $token = $fb->requestAccessToken( $code );

        // Send a request with it
        $result = json_decode( $fb->request( '/me?fields=id,first_name,last_name,email' ),true);

        //$message = 'Your unique facebook user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
        //echo $message. "<br/>";

        //Var_dump
        //display whole array().
        //dd($result);

        $findUser = Users::where("email","=",$result["email"])->first();
        if($findUser){
        	if($findUser->fbUsername == "") {
        		$findUser->fbUsername = $result["id"];
        		$findUser->save();
        	}
        	Auth::loginUsingId($findUser->id);
        	Event::fire('loginWithFacebook', array(Auth::user()));
        	Auth::user()->updated_at = date("Y-m-d H:i:s");
        	Auth::user()->lastLogin = date("Y-m-d H:i:s");
        	Auth::user()->virtual = 0;
			Auth::user()->save();

        	if(Auth::user()->userType == "Trainer"){
				//Tasks::dailyReminderChecker();
				return Redirect::route('trainerWorkouts', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->with("message",Lang::get("messages.Welcome"));
			} else {
				return Redirect::route('traineeWorkouts', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->with("message",Lang::get("messages.Welcome"));
			}

        } else {
        	$user = new Users;
			$user->firstName = ucfirst($result["first_name"]);
			$user->lastName = ucfirst($result["last_name"]);
			$user->email = strtolower($result["email"]);
			$user->fbUsername = $result["id"];
			$user->userType = "Trainee";
			$user->userType = "Trainer";
			if(Input::get("timezone") != ""){
				$user->timezone = "US/Eastern";
			}
			$password = str_random(8);
			$user->password = Hash::make($password);

			$image = json_decode(file_get_contents("https://graph.facebook.com/".$result["id"]."/picture?type=large&redirect=false"));
	        $image = $image->data->url;

	        $subject = Lang::get("messages.Emails_registerFB");

			Mail::queueOn(App::environment(),'emails.'.Config::get("app.whitelabel").'.user.'.App::getLocale().'.newFBUser', array("user"=>serialize($user),"name"=>$user->firstName,"password"=>$password), function($message) use ($user,$password,$subject)
			{
			  $message->to($user->email)
			  			->cc(Config::get("constants.activityEmail"))
	          			->subject($subject);
			});
			$user->activated = Helper::now();
			$user->save();


			Helper::checkUserFolder($user->id);
			if($image != "") {
	        		$file = file_get_contents($image);

					$images = Helper::saveImage($file,$user->getPath().Config::get("constants.profilePath")."/".$user->id,$image);
					$user->image = $images["image"];
					$user->thumb = $images["thumb"];
					$user->save();
			}

			Auth::loginUsingId($user->id);
			Event::fire('signUpWithFacebook', array(Auth::user()));
			$user->lastLogin = date("Y-m-d H:i:s");
			$user->freebesTrainer();

			Invites::where("email",$user->email)->where("completed",0)->update(array("completed"=>1));

			return Redirect::route('trainerWorkouts', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->with("message",Lang::get("messages.Welcome"))->with("newUser",true);;
        }
    }
    // if not ask for permission first
    else {
        // get fb authorization
        $url = $fb->getAuthorizationUri();

        // return to facebook login url
         return Redirect::to( (string)$url );
    }

}


public function loginTraineeFacebook($inviteKey="") {

    // get data from input
    $code = Input::get( 'code' );

    // get fb service
    $fb = OAuth::consumer( 'Facebook' );

    // check if code is valid

    // if code is provided get user data and sign in
    if ( !empty( $code ) ) {

        // This was a callback request from facebook, get the token
        $token = $fb->requestAccessToken( $code );

        // Send a request with it
        $result = json_decode( $fb->request( '/me?fields=id,first_name,last_name,email' ),true);

        //$message = 'Your unique facebook user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
        //echo $message. "<br/>";

        //Var_dump
        //display whole array().
        //dd($result);
        if(is_array($result) and array_key_exists("email", $result)){

        $findUser = Users::where("email","=",$result["email"])->first();
        if($findUser){
        	if($findUser->fbUsername == "") {
        		$findUser->fbUsername = $result["id"];
        		$findUser->save();
        	}
        	Auth::loginUsingId($findUser->id);
        	Event::fire('loginWithFacebook', array(Auth::user()));
        	Auth::user()->updated_at = date("Y-m-d H:i:s");
        	Auth::user()->lastLogin = date("Y-m-d H:i:s");
			Auth::user()->save();

			if($inviteKey != ""){
				$invite = Invites::where("key",$inviteKey)->where("completed",0)->first();
				if($invite){
					$toId = $findUser->id;
					$fromId = $invite->fakeId;
					if($fromId != Auth::user()->id){
						Workouts::copyWorkoutsFromTo($fromId,Auth::user()->id);
					}
					$invite->completeInvite();
				}
				
			}

        	if(Auth::user()->userType == "Trainer"){
				//Tasks::dailyReminderChecker();
				return Redirect::route('trainerWorkouts', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->with("message",Lang::get("messages.Welcome"));
			} else {
				return Redirect::route('traineeWorkouts', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->with("message",Lang::get("messages.Welcome"));
			}

		} else {
			Log::error("FACEBOOK ERROR");
			return Redirect::route("home")->with("error","It is not possible to login with Facebook at the moment.");
		}

        } else {
        	$user = new Users;
			$user->firstName = ucfirst($result["first_name"]);
			$user->lastName = ucfirst($result["last_name"]);
			$user->email = strtolower($result["email"]);
			$user->fbUsername = $result["id"];
			$user->userType = "Trainee";
			if(Input::get("timezone") != ""){
				$user->timezone = "US/Eastern";
			}
			$password = str_random(8);
			$user->password = Hash::make($password);

			$image = json_decode(file_get_contents("https://graph.facebook.com/".$result["id"]."/picture?type=large&redirect=false"));
	        $image = $image->data->url;

	        $subject = Lang::get("messages.Emails_registerFB");

			Mail::queueOn(App::environment(),'emails.'.Config::get("app.whitelabel").'.user.'.App::getLocale().'.newFBUser', array("user"=>serialize($user),"name"=>$user->firstName,"password"=>$password), function($message) use ($user,$password,$subject)
			{
			  $message->to($user->email)
			  			->cc(Config::get("constants.activityEmail"))
	          			->subject($subject);
			});
			$user->activated = Helper::now();
			$user->save();


			Helper::checkUserFolder($user->id);
			if($image != "") {
	        		$file = file_get_contents($image);
					$images = Helper::saveImage($file,$user->getPath().Config::get("constants.profilePath")."/".$user->id);
					$user->image = $images["image"];
					$user->thumb = $images["thumb"];
					$user->save();
			}

			Auth::loginUsingId($user->id);
			Event::fire('signUpWithFacebook', array(Auth::user()));
			$user->lastLogin = date("Y-m-d H:i:s");
			$user->freebesTrainer();

			if($inviteKey != ""){
				$invite = Invites::where("key",$inviteKey)->where("completed",0)->first();
				if($invite){
					$toId = $user->id;
					$fromId = $invite->fakeId;
					if($fromId != Auth::user()->id){
						Workouts::copyWorkoutsFromTo($fromId,Auth::user()->id);
					}
					$invite->completeInvite();
				}
			}

			return Redirect::route('trainerWorkouts', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->with("message",Lang::get("messages.Welcome"))->with("newUser",true);;
        }
    }
    // if not ask for permission first
    else {
        // get fb authorization
        $url = $fb->getAuthorizationUri();

        // return to facebook login url
         return Redirect::to( (string)$url );
    }

}


	public function shareOnFacebook(){
		$user = Auth::user();
		$message = "";
		$object = null;
		$type = Input::get("type");
		$url = Input::get("link");
		$url = URL::to($url);
		$name = "";

		if($type == "Exercise"){
			$message = Messages::showFacebookMessage("ShareExercise");
			$object = Exercises::find(Input::get("id"));
			Feeds::insertFeed("SharedExerciseFacebook",$user->id,$user->firstName,$user->lastName);
		} else if($type == "Workout"){
			$message = Messages::showFacebookMessage("ShareWorkout");
			$object = Workouts::find(Input::get("id"));
			Feeds::insertFeed("SharedWorkoutFacebook",$user->id,$user->firstName,$user->lastName);
		} else {
			$message = Messages::showFacebookMessage("GenericFacebook");
			//Feeds::insertFeed("SharedFacebook",$user->id,$user->firstName,$user->lastName);
		}

		$response =  $user->postFBTimeline($user,$message,$url,array("name"=>$object->name),true);
		if($response["error"]){
			return $this->responseJsonError($response["message"]);
		} else {
			return $this->responseJson($response["message"]);
		}


	}

	public function demoSignUp(){
		
		$accountType = Input::get("type");

		$rules = array(
			"email" => "required|email|unique:users,email,NULL,id,deleted_at,NULL"
		);
		$validation = Validator::make(Input::all(), $rules);
		if($validation->fails()){
			return Redirect::back()->withInput()->withErrors($validation->messages());
		} else {
			$user = new Users;
		$user->email = strtolower(Input::get("email"));
			if(Input::get("timezone") != ""){
				$user->timezone = Input::get("timezone");
			}
			$user->userType = $accountType;
			$user->save();
			


			if($user->userType == "Trainer"){
				try{
				 MailchimpWrapper::lists()
                        ->subscribe(Config::get("constants.mailChimpGetEarlyAccessListTrainer"), array('email' => Input::get("email"),'email_address' => Input::get("status"),'email' => "subscribed"));
				} catch(Exception $e){
				Log::error($e);
			}
				$user->freebesTrainer();
			} else{
				$user->freebesTrainee();
				try{
				 MailchimpWrapper::lists()
                        ->subscribe(Config::get("constants.mailChimpGetEarlyAccessListTrainee"), array('email' => Input::get("email"),'email_address' => Input::get("status"),'email' => "subscribed"));
				} catch(Exception $e){
				Log::error($e);
			}
			}


			return View::make('SignUpComplete');

		}
	}

	function personifyFromGroup($userId){
		if(!Groups::checkGroupPermissions(Auth::user()->id,$userId)){
			return Redirect::back()->withErrors(Lang::get("NoPermissions"));
		} else{
			$findUser = Users::find($userId);
	        if($findUser){
	        	Session::put("originalUser",Auth::user());
	        	Auth::loginUsingId($findUser->id);
	   
				return Redirect::route('trainerWorkouts', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->with("message",Lang::get("messages.Welcome"));
			}
		}
		
	}

	function personifyFromGroupBack(){
	

		if(Session::has("originalUser")){
			Auth::loginUsingId(Session::get("originalUser")->id);
			Session::forget('originalUser');
			return Redirect::route('employeeManagement')->with("message",Lang::get("messages.PersonifyBack"));
		}
	} 


	//=======================================================================================================================
	// API
	//=======================================================================================================================


	public function APIRegistration(){

		if(Input::get("type") == "Trainer"){
			$validation = Users::validate(Input::all(),array("termsAndConditions"=>"required"));
			$user = new Users;
			$user->firstName = ucfirst(Input::get("firstName"));
			$user->lastName = ucfirst(Input::get("lastName"));
			$user->email = strtolower(Input::get("email"));
			$user->password = Hash::make(Input::get("password"));
			$user->userType = "Trainer";
			$user->updated_at = date("Y-m-d H:i:s");
			$user->lastLogin = date("Y-m-d H:i:s");
			$user->lastLoginApp = date("Y-m-d H:i:s");
			$user->save();


			Auth::loginUsingId($user->id);
			Event::fire('apiSignUp', array(Auth::user()));

			$user->freebesTrainer();

			Feeds::insertFeed("SignUp",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			
			$result = Helper::APIOK();
			$result["message"] = Lang::get("messages.Welcome");
			$result["data"] = Auth::user();
			return $result;

		} else {

			$user = new Users;
			$user->firstName = ucfirst(Input::get("firstName"));
			$user->lastName = ucfirst(Input::get("lastName"));
			$user->email = strtolower(Input::get("email"));
			$user->password = Hash::make(Input::get("password"));
			$user->userType = "Trainee";
			$user->updated_at = date("Y-m-d H:i:s");
			$user->lastLogin = date("Y-m-d H:i:s");
			$user->lastLoginApp = date("Y-m-d H:i:s");
			$user->save();
			Auth::loginUsingId($user->id);
			Event::fire('apiSignUp', array(Auth::user()));

			$user->freebesTrainee();
		
			Feeds::insertFeed("SignUp",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			$result = Helper::APIOK();
			$result["message"] = Lang::get("messages.Welcome");
			$result["data"] = Auth::user();
			return $result;
		}
		
		//$user->save();
	}

	

	public function APIlogin(){
		$result = array();

		$result["data"] = "";
		$result["status"] = "error";
		$result["message"] = Lang::get("messages.WrongLogin");
		$result["total"] = "";

		if (Auth::attempt(array('email' => Input::get("email"), 'password' =>Input::get("password")),true)){
			Feeds::insertFeed("Welcome",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			Event::fire('apiLogin', array(Auth::user()));
			$user = Auth::user();
			$user->appInstalled = 1;
			$user->updated_at = date("Y-m-d H:i:s");
			$user->lastLogin = date("Y-m-d H:i:s");
			$user->lastLoginApp = date("Y-m-d H:i:s");
			$user->save();

			$result["data"] = Auth::user()->toArray();
			$result["data"]["weight"] = Weights::where("userId",Auth::user()->id)->orderBy("created_at","Desc")->get();
			$result["data"]["objectives"] = Objectives::where("userId",Auth::user()->id)->orderBy("created_at","Desc")->get();
			$result["status"] = "ok";
			$result["message"] = Lang::get("messages.Welcome");
			return $this->responseJson($result);
		} else {
			return $this->responseJson($result);
		}
	}

	public function APIloginAuto(){
		$result = array();

		$result["data"] = "";
		$result["status"] = "error";
		$result["message"] = Lang::get("messages.WrongLogin");
		$result["total"] = "";
		$user = Users::where("email",Input::get("email"))->first();
		if($user){
			$user->updated_at = date("Y-m-d H:i:s");
			$user->lastLogin = date("Y-m-d H:i:s");
			$user->lastLoginApp = date("Y-m-d H:i:s");
			$user->save();
			Auth::loginUsingId($user->id);
			Event::fire('apiLogin', array(Auth::user()));
			Feeds::insertFeed("Welcome",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			$result["data"] = Auth::user()->toArray();
			$result["data"]["weight"] = Weights::where("userId",Auth::user()->id)->orderBy("created_at","Desc")->get();
			$result["data"]["objectives"] = Objectives::where("userId",Auth::user()->id)->orderBy("created_at","Desc")->get();
			$result["status"] = "ok";
			$result["message"] = Lang::get("messages.Welcome");
			return $this->responseJson($result);
		} else {
			return $this->responseJson($result);
		}
	}

	public function APIlogout(){
		$result = Helper::APIOK();
		if(Auth::check()){
			//Feeds::insertFeed("Logout",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			Auth::logout();
		}
		
	}

	public function APIAppSettings(){
		if(Input::get("action") == "app_initiated"){
			$user = Auth::user();
			$user->demoApp = 1;
			$user->save();
		}
		
	}

	public function APIEditProfile(){
		$result = Helper::APIERROR();
		$rules = array(
			"firstName" => "required|min:2",
			"lastName" => "required|min:2",
			"email" => "required|email",
		);

		if(Auth::check()){
		$validation = Validator::make(Input::all(), $rules);
		if($validation->fails()){
			$result = Helper::APIERROR();
			$result["message"] = $validation->messages();
			return $result;
		} else {
			$user = Auth::user();
			if (Input::has("firstName")) $user->firstName = ucfirst(Input::get("firstName"));
			if (Input::has("lastName")) $user->lastName = ucfirst(Input::get("lastName"));
			if (Input::has("email")) $user->email = strtolower(Input::get("email"));
			if (Input::has("phone")) $user->phone = Helper::formatPhone(strtolower(Input::get("phone")));
			if (Input::has("birthday")) $user->birthday = strtolower(Input::get("birthday"));
			if(Input::has("timezone")){
				$user->timezone = Input::get("timezone");
			}
			if(Input::get("password")){
				$user->password = Hash::make(Input::get("password"));
			}
			$user->save();

			Helper::checkUserFolder($user->id);
			if(Input::hasFile("image0")) {
					$images = Helper::saveImage(Input::file("image0"),$user->getPath().Config::get("constants.profilePath")."/".$user->id);
					$user->image = $images["image"];
					$user->thumb = $images["thumb"];
					$user->save();
			}

//			Feeds::insertFeed("UpdateProfile",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);


			$result = Helper::APIOK();
			$result["message"] = Lang::get("messages.ProfileSaved");
			return $result;
			
			
		}
		} else {
			$result = Helper::APIERROR();
			$result["message"] = Lang::get("messages.LoginRequired");
			return $result;
		}
	}



	//=======================================================================================================================
	// CONTROL PANEL
	//=======================================================================================================================
	

	public function _index()
	{
		return View::make('ControlPanel/Users');
	}

	public function _ApiList()
	{
		return $this::responseJson(array("data"=>Users::orderBy("id","DESC")->get()));
	}


	public function _AddEdit()
	{
		if(Input::has("hiddenId") and Input::get("hiddenId") != ""){
			return $this->_update(Input::get("hiddenId"));
		} else {
			return $this->_create();
		}		
	}

	public function _create()
	{
		//

		$rules = array(
			"firstName" => "required|min:2",
			"lastName" => "required|min:2",
			"email" => "required|email|unique:users,email,NULL,id,deleted_at,NULL",
	        "certifications" => "max:1000",
	        "past_experience" => "max:1000",
	        "biography" => "max:1000",
	        "specialities" => "max:1000"
		);
		$validation = Validator::make(Input::all(), $rules);
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$user = new Users;
			$user->firstName = Input::get("firstName");
			$user->lastName = Input::get("lastName");
			$user->email = Input::get("email");
			$user->address = Input::get("address");
			$user->street = Input::get("street");
			$user->city = Input::get("city");
			$user->suite = Input::get("suite");
			$user->phone = Input::get("phone");
			$user->province = Input::get("province");
			$user->country = Input::get("country");
			$user->userType = Input::get("userType");
			if(Input::get("password") != "") $user->password = Hash::make(Input::get("password"));
			$user->fbUsername = Input::get("fbUsername");
			$user->appInstalled = ((Input::get("appInstalled")  == "Yes") ? 1 : 0);
			$user->demoApp = ((Input::get("demoApp")  == "Yes") ? 1 : 0);
			$user->demoWeb = ((Input::get("demoWeb")  == "Yes") ? date("Y-m-d H:i:s") : null);
			$user->timezone = Input::get("timezone");
			$user->birthday = Input::get("birthday");
			$user->biography = Input::get("biography");
			$user->certifications = Input::get("certifications");
			$user->past_experience = Input::get("past_experience");
			$user->word = Input::get("word");
			$user->videoLink = Input::get("videoLink");
			$user->videoKey = Input::get("videoKey");
			$user->specialities = Input::get("specialities");
			$user->save();
			$user->freebesTrainer();
			return $this::responseJson(Messages::showControlPanel("UserCreated"));	
		}
	}

	public function _show($user)
	{
		//
		return Users::find($user);
	}

	public function _update($id)
	{
		//
		$rules = array(
			"firstName" => "required|min:2",
			"lastName" => "required|min:2",
	        "certifications" => "max:1000",
	        "past_experience" => "max:1000",
	        "biography" => "max:1000",
	        "specialities" => "max:1000"
		);
		$validation = Validator::make(Input::all(), $rules);
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$user = Users::find($id);
			$user->firstName = Input::get("firstName");
			$user->lastName = Input::get("lastName");
			$user->email = Input::get("email");
			$user->address = Input::get("address");
			$user->street = Input::get("street");
			$user->city = Input::get("city");
			$user->suite = Input::get("suite");
			$user->phone = Input::get("phone");
			$user->province = Input::get("province");
			$user->country = Input::get("country");
			$user->userType = Input::get("userType");
			if(Input::get("password") != "") $user->password = Hash::make(Input::get("password"));
			$user->fbUsername = Input::get("fbUsername");
			$user->appInstalled = ((Input::get("appInstalled")  == "Yes") ? 1 : 0);
			$user->demoApp = ((Input::get("demoApp")  == "Yes") ? 1 : 0);
			$user->demoWeb = ((Input::get("demoWeb")  == "Yes") ? date("Y-m-d H:i:s") : null);
			$user->timezone = Input::get("timezone");
			$user->birthday = Input::get("birthday");
			$user->biography = Input::get("biography");
			$user->certifications = Input::get("certifications");
			$user->past_experience = Input::get("past_experience");
			$user->word = Input::get("word");
			$user->videoLink = Input::get("videoLink");
			$user->videoKey = Input::get("videoKey");
			$user->specialities = Input::get("specialities");
			$user->save();
			return $this::responseJson(Messages::showControlPanel("UserModified"));	
			
		}
	}

	public function _destroy($id)
	{
		//
		$user = Users::find($id);
		$user->delete();
		return $this::responseJson(Messages::showControlPanel("UserDeleted"));
	}


	public function controlPanelAPIList(){
		return Response::json(array("data"=>Users::orderBy("created_at","DESC")->get()));
	}

	public function controlPanelLoginUserAdmin($id){
	 $findUser = Users::find($id);
        if($findUser){

        	Auth::loginUsingId($findUser->id);
   

        if(Auth::user()->userType == "Trainer"){
				Tasks::dailyReminderChecker();
				return Redirect::route('Trainer', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->with("message",Lang::get("messages.Welcome"));
			} else {
				return Redirect::route('Trainee', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->with("message",Lang::get("messages.Welcome"));
			}
		}

	}

}