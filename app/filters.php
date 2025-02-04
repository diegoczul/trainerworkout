<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{


	if(Request::secure())
    {

    
    if(Config::get("app.debug")) return Redirect::to(Request::path());

    } else {

     	
    }
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('/');
		}
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) {
			$user = Auth::user();
			if($user->userType == "Trainer"){
				return Redirect::route('trainerWorkouts', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->with("message",Lang::get("messages.Welcome"));
			} else {
				return Redirect::route('traineeWorkouts', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->with('message',Lang::get("messages.Welcome"));
			}
		
	}
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

Route::filter('marketing', function()
{
	
	$inputs = Request::all();
	$utm = array();
	if(is_array($inputs) and count($inputs) > 0){
		//Log::errors($inputs);
		foreach($inputs as $param=>$value){
			if(strpos($a,'utm_') !== false){
				array_push($utm,$param."=>".$value);
			}
		}
	}
	if(count($utm) > 0){
		Session::put("marketing",$utm);
	}
});


Route::filter('userTypeChecker',function($route = "", $request = "", $value = ""){
	
	if(Auth::check()){
		$user = Auth::user();
		$routeArray = explode("/",$route->uri());
		
		if(strtolower($routeArray[0]) != strtolower($user->userType)){

			if($user->userType == "Trainer"){
				return Redirect::route('trainerWorkouts')->withError(Lang::get("messages.NotFound"));
			} else if ($user->userType == "Trainee") {
				return Redirect::route('traineeWorkouts')->withError(Lang::get("messages.NotFound"));
			}
		}
	}
});

Route::filter('controlpanel',function($route = "", $request = "", $value = ""){
	
	if(Auth::check()){
		$user = Auth::user();
		$string = $user->email;
		$word = "@trainerworkout.com";

		if (stripos($string, $word) === FALSE) {

				if($user->userType == "Trainer"){
					return Redirect::route('trainerWorkouts')->withError(Lang::get("messages.NotFound"));
				} else if ($user->userType == "Trainee") {
					return Redirect::route('traineeWorkouts')->withError(Lang::get("messages.NotFound"));
				}
			
		} else {

		}
	} else {
		return Redirect::guest('/');
	}
});

Route::filter('checkIfLoggedInAndRedirect',function($route = "", $request = "", $value = ""){

	if(Auth::check()){
		$user = Auth::user();
		//$routeArray = explode("/",$route->uri());
		
		//if(strtolower($routeArray[0]) != strtolower($user->userType)){
			if($user->userType == "Trainer"){
				return Redirect::route('trainerWorkouts')->with('message',Lang::get("messages.Welcome"));
			} else {
				return Redirect::route('traineeWorkouts')->with('message',Lang::get("messages.Welcome"));
			}
		//}
	} else {
		if (Auth::viaRemember())
		{
			$user = Auth::user();
			if($user->userType == "Trainer"){
				return Redirect::route('trainerWorkouts')->with('message',Lang::get("messages.Welcome"));
			} else {
				return Redirect::route('traineeWorkouts')->with('message',Lang::get("messages.Welcome"));
			}
		} elseif(Cookie::get('TrainerWorkoutUserId') !== null){
			//dd($_COOKIE['TrainerWorkoutUserId']);
			if($_COOKIE['TrainerWorkoutUserId'] != ""){
				$userId = Crypt::decrypt($_COOKIE['TrainerWorkoutUserId']);
				
				if(intval($userId) > 0) { 
					Auth::loginUsingId($userId);
					$user = Auth::user();
					if($user){
						if($user->userType == "Trainer"){
							return Redirect::route('trainerWorkouts')->with('message',Lang::get("messages.Welcome"));
						} else {
							return Redirect::route('traineeWorkouts')->with('message',Lang::get("messages.Welcome"));
						}
					}
				}
			}
		}
	}
});