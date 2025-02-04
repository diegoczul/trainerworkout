<?php


//Event::fire('signUpWithFacebook', array(Auth::user()));


//#############################################################################
// USER RELATED EVENTS
//#############################################################################
Event::listen('signUp', function($user)
{

	if(Config::get("app.debug") == false and Auth::check()){
		

		
	try{
		Feeds::insertFeedUserObject("{email} has signed up",$user);

		

		if(Auth::user()->userType == "Trainer") {

			$metadata = array(
		  "user_type" => Auth::user()->userType,
		  //"user_affiliation_type" => "user-sign-up",
		  //"affiliation" => "user-sign-up",
		  "membership_plan" => (Auth::user()->getTrainerWorkoutMembership() and Auth::user()->getTrainerWorkoutMembership()->membership) ? Auth::user()->getTrainerWorkoutMembership()->membership->name : "",
		  "number_of_clients" => Auth::user()->getNumberOfClients(),
		  "number_of_workouts" => Auth::user()->getNumberOfWorkouts(),
		  "number_of_exercises" => Auth::user()->getNumberOfExercises(),
		  "utm_source" => Auth::user()->marketing,
		);


			Helper::Intercom()->createEvent(array(
		  "event_name" => "user-sign-up",
		  "created_at" => Helper::dateToUnix(date("Y-m-d H:i:s")),
		  "email" => $user->email,
		  "metadata" => $metadata
		));

			Helper::Intercom()->users->update([
			  "email" => $user->email,
			  "custom_attributes" => $metadata
			]);

		}

	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});

Event::listen('apiSignUp', function($user)
{

});

Event::listen('signUpWithFacebook', function($user)
{
	// $metadata = array(
	//   "client-name" => $name
	// );
	try{
	Feeds::insertFeedUserObject("{email} has signed up with Facebook",$user);


		 if(Auth::user()->userType == "Trainer") {

			$metadata = array(
		  "user_type" => Auth::user()->userType,
		  //"user_affiliation_type" => "user-sign-up",
		  //"affiliation" => "user-sign-up",
		  "membership_plan" => (Auth::user()->getTrainerWorkoutMembership() and Auth::user()->getTrainerWorkoutMembership()->membership) ? Auth::user()->getTrainerWorkoutMembership()->membership->name : "",
		  "number_of_clients" => Auth::user()->getNumberOfClients(),
		  "number_of_workouts" => Auth::user()->getNumberOfWorkouts(),
		  "number_of_exercises" => Auth::user()->getNumberOfExercises(),
		  "utm_source" => Auth::user()->marketing,
		);


			Helper::Intercom()->createEvent(array(
		  "event_name" => "user-sign-up-facebook",
		  "created_at" => Helper::dateToUnix(date("Y-m-d H:i:s")),
		  "email" => $user->email,
		  "metadata" => $metadata
		));

			Helper::Intercom()->users->update([
			  "email" => $user->email,
			  "custom_attributes" => $metadata
			]);

		}

	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
});

Event::listen('login', function($user)
{
	// $metadata = array(
	//   "client-name" => $name
	// );
	if(Config::get("app.debug") == false and Auth::check()){
	try{
	Feeds::insertFeedUserObject("{email} logged in",$user);
	
		  if(Auth::user()->userType == "Trainer") {

			$metadata = array(
		  "user_type" => Auth::user()->userType,
		  //"user_affiliation_type" => "user-sign-up",
		  //"affiliation" => "user-sign-up",
		  "membership_plan" => (Auth::user()->getTrainerWorkoutMembership() and Auth::user()->getTrainerWorkoutMembership()->membership) ? Auth::user()->getTrainerWorkoutMembership()->membership->name : "",
		  "number_of_clients" => Auth::user()->getNumberOfClients(),
		  "number_of_workouts" => Auth::user()->getNumberOfWorkouts(),
		  "number_of_exercises" => Auth::user()->getNumberOfExercises(),
		  "utm_source" => Auth::user()->marketing,
		);


			Helper::Intercom()->createEvent(array(
		  "event_name" => "user-login",
		  "created_at" => Helper::dateToUnix(date("Y-m-d H:i:s")),
		  "email" => $user->email
		));

	

		}

	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});

Event::listen('apiLogin', function($user)
{
	
	
});


Event::listen('loginWithFacebook', function($user)
{
	
	if(Config::get("app.debug") == false and Auth::check()){
	try{
	Feeds::insertFeedUserObject("{email} logged in with Facebook",$user);
	

		  if(Auth::user()->userType == "Trainer") {

			$metadata = array(
		  "user_type" => Auth::user()->userType,
		  //"user_affiliation_type" => "user-sign-up",
		  //"affiliation" => "user-sign-up",
		  "membership_plan" => (Auth::user()->getTrainerWorkoutMembership() and Auth::user()->getTrainerWorkoutMembership()->membership) ? Auth::user()->getTrainerWorkoutMembership()->membership->name : "",
		  "number_of_clients" => Auth::user()->getNumberOfClients(),
		  "number_of_workouts" => Auth::user()->getNumberOfWorkouts(),
		  "number_of_exercises" => Auth::user()->getNumberOfExercises(),
		  "utm_source" => Auth::user()->marketing,
		);


			Helper::Intercom()->createEvent(array(
		  "event_name" => "user-login-facebook",
		  "created_at" => Helper::dateToUnix(date("Y-m-d H:i:s")),
		  "email" => $user->email
		));

			

		}



	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});

Event::listen('confirmEmail', function($user)
{
	
	if(Config::get("app.debug") == false and Auth::check()){
	try{
	Feeds::insertFeedUserObject("{email} confirmed his email",$user);
	
	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});



//#############################################################################
// WORKOUT RELATED EVENTS
//#############################################################################


//Event::fire('createAWorkout', array(Auth::user(),$workout->name));

Event::listen('searchWorkout', function($user,$searchQuery)
{
	
	
});

Event::listen('createAWorkout', function($user,$workoutName)
{
	$metadata = array(
	  "workout-name" => $workoutName,
	);
	if(Config::get("app.debug") == false){
	try{

		if(Auth::user()->userType == "Trainer") {
	Helper::Intercom()->createEvent(array(
	  "event_name" => "workout-created",
	  "created_at" => Helper::dateToUnix(date("Y-m-d H:i:s")),
	  "email" => $user->email,
	  "metadata" => $metadata
	));

}
	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});


Event::listen('duplicateWorkout', function($user,$workoutName)
{
	

	$metadata = array(
	  "workout-name" => $workoutName,
	);


	if(Config::get("app.debug") == false and Auth::check()){
	try{
	Feeds::insertFeedUserObject("{firstName} {lastName} has duplicated a workout",$user);
	

	if(Auth::user()->userType == "Trainer") Helper::Intercom()->createEvent(array(
		  "event_name" => "user-duplicate-workout",
		  "created_at" => Helper::dateToUnix(date("Y-m-d H:i:s")),
	  "email" => $user->email,
	  "metadata" => $metadata
		));


	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});


Event::listen('notifyActivity', function($user,$trainer)
{
	
	if(Config::get("app.debug") == false and Auth::check()){
	try{
	Feeds::insertFeedUserObject("{firstName} {lastName} has performed a workout",$user);
	
	$metadata = array(
	  "client_name" => $user->getCompleteName(),
	);


	if(Auth::user()->userType == "Trainer") Helper::Intercom()->createEvent(array(
		  "event_name" => "user-notify-performance",
		  "created_at" => Helper::dateToUnix(date("Y-m-d H:i:s")),
		 "email" => $user->email,
	  "metadata" => $metadata
		));


	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});


Event::listen('archiveWorkout', function($user,$workoutName)
{
	
	if(Config::get("app.debug") == false and Auth::check()){
	try{
	Feeds::insertFeedUserObject("{firstName} {lastName} has archived a workout",$user);
	

$metadata = array(
	  "workout_name" => $workoutName,
	);


	if(Auth::user()->userType == "Trainer") Helper::Intercom()->createEvent(array(
		  "event_name" => "user-archive-workout",
		  "created_at" => Helper::dateToUnix(date("Y-m-d H:i:s")),
		  "email" => $user->email,
	  "metadata" => $metadata
		));


	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});

Event::listen('unArchiveWorkout', function($user,$workoutName)
{
	
	if(Config::get("app.debug") == false and Auth::check()){
	try{
	Feeds::insertFeedUserObject("{firstName} {lastName} has unarchived a workout",$user);
	

$metadata = array(
	  "workout_name" => $workoutName,
	);


	if(Auth::user()->userType == "Trainer") Helper::Intercom()->createEvent(array(
		  "event_name" => "user-unarchive-workout",
		  "email" => $user->email,
		  "created_at" => Helper::dateToUnix(date("Y-m-d H:i:s")),
	  "metadata" => $metadata
		));


	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});

Event::listen('userNewWorkout', function($user)
{

	
});

Event::listen('editAWorkout', function($user,$workoutName)
{
	
	if(Config::get("app.debug") == false and Auth::check()){
	try{
	Feeds::insertFeedUserObject("{firstName} {lastName} has edited a workout",$user);
	
	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});


Event::listen('shareAWorkout', function($user,$userId)
{


	
	if(Config::get("app.debug") == false and Auth::check()){
	try{
	Feeds::insertFeedUserObject("{firstName} {lastName} has shared a workout",$user);
	

	if(Auth::user()->userType == "Trainer") Helper::Intercom()->createEvent(array(
		  "event_name" => "user-share-workout",
		  "created_at" => Helper::dateToUnix(date("Y-m-d H:i:s")),
		  "email" => $user->email,
		));


	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});

Event::listen('shareAWorkouts', function($user,$userId)
{


	
	if(Config::get("app.debug") == false and Auth::check()){
	try{
	Feeds::insertFeedUserObject("{firstName} {lastName} has shared a workout",$user);
	

	if(Auth::user()->userType == "Trainer") Helper::Intercom()->createEvent(array(
		  "event_name" => "user-share-workout",
		  "created_at" => Helper::dateToUnix(date("Y-m-d H:i:s")),
		  "email" => $user->email,
		));


	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});

Event::listen('printWorkout', function($user,$workoutName)
{

	Log::error("print Event");
	if(Config::get("app.debug") == false and Auth::check()){
	try{

		Log::error("print Event");


	Feeds::insertFeedUserObject("{firstName} {lastName} has printed a workout",$user);
	
	$metadata = array(
	  "workout_name" => $workoutName,
	);


	if(Auth::user()->userType == "Trainer") Helper::Intercom()->createEvent(array(
		  "event_name" => "user-print-workout",
		  "created_at" => Helper::dateToUnix(date("Y-m-d H:i:s")),
		  "email" => $user->email,
		  "metadata" => $metadata
		));

	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});

Event::listen('printWorkouts', function($user)
{


	if(Config::get("app.debug") == false and Auth::check()){
	try{
	Feeds::insertFeedUserObject("{firstName} {lastName} has printed a workout",$user);
	


	if(Auth::user()->userType == "Trainer") Helper::Intercom()->createEvent(array(
		  "event_name" => "user-print-workouts",
		  "created_at" => Helper::dateToUnix(date("Y-m-d H:i:s")),
		  "email" => $user->email
		));


	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});

Event::listen('viewWorkout', function($user,$workoutName)
{
	
	if(Config::get("app.debug") == false and Auth::check()){
	try{
	Feeds::insertFeedUserObject("{firstName} {lastName} has viewed a workout",$user);
	
	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});

Event::listen('pdfWorkout', function($user,$workoutName)
{
	
	if(Config::get("app.debug") == false and Auth::check()){
	try{
	Feeds::insertFeedUserObject("{firstName} {lastName} has created PDF version of a workout",$user);
	
	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});


Event::listen('deleteAWorkout', function($user)
{
	if(Config::get("app.debug") == false and Auth::check()){
	try{
	Feeds::insertFeedUserObject("{firstName} {lastName} has deleted a workout",$user);
	
	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});


Event::listen('createTag', function($user,$workoutName,$tagName)
{
	
	if(Config::get("app.debug") == false and Auth::check()){
	try{
	Feeds::insertFeedUserObject("{firstName} {lastName} has created a tag",$user);
	
$metadata = array(
	  "workout_name" => $workoutName,
		  "tag_name" => $tagName,
	);

	Helper::Intercom()->createEvent(array(
		  "event_name" => "user-create-tag-workout",
		  
		  "created_at" => Helper::dateToUnix(date("Y-m-d H:i:s")),
		  "email" => $user->email,
		  "metadata" => $metadata
		));


	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});

Event::listen('removeTagWorkout', function($user,$workoutName,$tagName)
{
	
	if(Config::get("app.debug") == false and Auth::check()){
	try{
	Feeds::insertFeedUserObject("{firstName} {lastName} has untag a tag",$user);
	
	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});

Event::listen('destroyTag', function($user,$tagName)
{
	
	if(Config::get("app.debug") == false and Auth::check()){
	try{
	Feeds::insertFeedUserObject("{firstName} {lastName} has deleted a tag",$user);
	
	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});


//#############################################################################
// JS TRIGGERED RELATED EVENTS
//#############################################################################

Event::listen('jsTriggeredEvent', function($eventName,$metas)
{	
	
});








Event::listen('messageClient', function($user,$name)
{
	
});

Event::listen('messageNoneClient', function($user,$name)
{
	
});

Event::listen('messagePersonalTrainer', function($user,$name)
{
	
});

Event::listen('updateFeedSettings', function($user)
{
	
});

Event::listen('editProfileInformation', function($user)
{
	
	if(Config::get("app.debug") == false and Auth::check()){
	try{
	Feeds::insertFeedUserObject("{firstName} {lastName} has edited his profile information",$user);
	
	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});

Event::listen('sendInviteToClient', function($user,$userId)
{
	
	if(Config::get("app.debug") == false and Auth::check()){
	try{
	Feeds::insertFeedUserObject("{firstName} {lastName} has invited a client",$user);
	
	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});

Event::listen('sendInviteToNotClient', function($user,$email)
{
	
});








Event::listen('addedAWorkoutMarket', function($user,$author,$price)
{
	
});

Event::listen('addedAnExercise', function($user,$exerciseName)
{
	
	if(Config::get("app.debug") == false and Auth::check()){
	try{
		Feeds::insertFeedUserObject("{firstName} {lastName} has created an Exercise",$user);
	

$metadata = array(
	  "exercise_name" => $exerciseName,
	);


	if(Auth::user()->userType == "Trainer") Helper::Intercom()->createEvent(array(
		  "event_name" => "user-create-exercise",
		  
		  "created_at" => Helper::dateToUnix(date("Y-m-d H:i:s")),
		  "email" => $user->email,
		  "metadata" => $metadata
		));


	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});


Event::listen('deletedAnExercise', function($user,$exerciseName)
{
	
	if(Config::get("app.debug") == false and Auth::check()){
	try{
		Feeds::insertFeedUserObject("{firstName} {lastName} has deleted an exercise",$user);
	
	} catch(Exception $e){
            Log::error("Handle Error");
            Log::error($e);
            return null;
        }
    }
});
?>