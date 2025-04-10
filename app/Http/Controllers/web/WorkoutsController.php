<?php
namespace App\Http\Controllers\web;

use App\Http\Libraries\Helper;
use App\Models\BodyGroups;
use App\Models\Clients;
use App\Models\Equipments;
use App\Models\Exercises;
use App\Models\ExercisesTypes;
use App\Models\Feeds;
use App\Models\Memberships;
use App\Models\Notifications;
use App\Models\Ratings;
use App\Models\Sets;
use App\Models\Sharings;
use App\Models\Tags;
use App\Models\TemplateSets;
use App\Models\Users;
use App\Models\UserUpdates;
use App\Models\Workouts;
use App\Models\WorkoutsExercises;
use App\Models\WorkoutsGroups;
use App\Models\WorkoutsPerformances;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\Snappy\Facades\SnappyImage;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Illuminate\Process\Exceptions\ProcessTimedOutException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Facades\Agent;
use Knp\Snappy\Image;
use LynX39\LaraPdfMerger\PdfManage;
use ZipArchive;

class WorkoutsController extends BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /objectives
	 *
	 * @return Response
	 */

	public $pageSize = 24;
	public $pageSizeFull = 9;
	public $pageSizeTrending = 2;
	public $pageSizeFullTrending = 8;


	public function index(Request $request)
	{
		$userId = Auth::user()->id;
		$permissions = null;
		$search = "";
		$filters = array();

		$archive = false;


		if($request->has("userId") && !empty($request->get("userId"))){
			$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"),"w_workouts");
			if($permissions["view"]){
				$userId = $request->get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}




		if($request->has("arrayData") && !empty($request->get("arrayData"))){
			$arrayData = json_decode($request->get("arrayData"),true);
			if(is_array($arrayData)){
				if(array_key_exists("search", $arrayData) and $arrayData["search"] != "") $search = $arrayData["search"];
				if(array_key_exists("archive", $arrayData) and $arrayData["archive"] == "true") $archive = true;
			}
		}

		if($request->has("pageSize") && !empty($request->get('pageSize'))) $this->pageSize = $request->get("pageSize") + $this->pageSize;
		if($search == ""){
			$workouts = Workouts::where("userId",$userId)->orderBy("created_at","Desc")->take($this->pageSize);
		} else {
			$workouts = Workouts::search($search)->where("userId",$userId)->orderBy("created_at","Desc")->take($this->pageSize);
		}

		$countArchiveWorkouts = Workouts::whereNotNull("archived_at")->where("userId",$userId)->count();
		if($countArchiveWorkouts == 0) $archive = false;

		if($archive) {
			$workouts = $workouts->whereNotNull("archived_at");
		} else {
			$workouts = $workouts->whereNull("archived_at");
		}

		$workouts = $workouts->get();


		$options = array("duplicate"=>true,"edit"=>true,"archive"=>true,"delete"=>true,"select"=>true,"click"=>false);


		return view("widgets.base.workouts")
			->with("workouts",$workouts)
			->with("countArchiveWorkouts",$countArchiveWorkouts)
			->with("permissions",$permissions)
			->with("options",$options)
			->with("search",$search)
			->with("archive",$archive)
			->with("total",Workouts::where("userId","=",$userId)->count());
	}

	public function indexWorkoutsLibrary(Request $request)
	{

		$userId = Auth::user()->id;
		$permissions = null;
		$search = "";
		$filters = array();

		$archive = false;

		$client = 0;


		if($request->has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"),"w_workouts");
			if($permissions["view"]){
				$userId = $request->get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}



		$startFrom = false;

		if($request->has("arrayData")){
			$arrayData = json_decode($request->get("arrayData"),true);
			if(is_array($arrayData)){
				if(array_key_exists("search", $arrayData) and $arrayData["search"] != "") $search = $arrayData["search"];
				if(array_key_exists("archive", $arrayData) and $arrayData["archive"] == "true") $archive = true;
				if(array_key_exists("client", $arrayData)) $client = $arrayData["client"];
				if(array_key_exists("startFrom", $arrayData)) $startFrom = true;
			}
		}

		if($request->has("pageSize")) $this->pageSize = $request->get("pageSize") + $this->pageSize;
		if($search == ""){
			$workouts = Workouts::where("userId",$userId)->orderBy("created_at","Desc")->take($this->pageSize);
		} else {
			$workouts = Workouts::search($search)->where("userId",$userId)->orderBy("created_at","Desc")->take($this->pageSize);
		}

		$countArchiveWorkouts = Workouts::whereNotNull("archived_at")->where("userId",$userId)->count();
		if($countArchiveWorkouts == 0) $archive = false;

		if($archive) {
			$workouts = $workouts->whereNotNull("archived_at");
		} else {
			$workouts = $workouts->whereNull("archived_at");
		}

		$workouts = $workouts->where("status","released");
		$workouts = $workouts->get();


		$options = array("duplicate"=>true,"edit"=>true,"archive"=>true,"delete"=>true,"select"=>true,"click"=>false);

		$clientObject = Clients::find($client);
		$client = $clientObject->userId;


		return view("widgets.base.workoutsLibrary")
			->with("workouts",$workouts)
			->with("countArchiveWorkouts",$countArchiveWorkouts)
			->with("permissions",$permissions)
			->with("options",$options)
			->with("client",$client)
			->with("startFrom",$startFrom)
			->with("search",$search)
			->with("archive",$archive)
			->with("total",Workouts::where("userId","=",$userId)->count());
	}

	public function indexCreate(Request $request)
	{

		$userId = Auth::user()->id;
		$permissions = null;
		$search = "";
		$filters = array();

		$archive = false;


		if($request->has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"),"w_workouts");
			if($permissions["view"]){
				$userId = $request->get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		$addWorkout	= false;
		$click	= true;


		if($request->has("arrayData")){
			$arrayData = json_decode($request->get("arrayData"),true);
			if(is_array($arrayData)){
				if(array_key_exists("search", $arrayData) and $arrayData["search"] != "") $search = $arrayData["search"];
				if(array_key_exists("archive", $arrayData) and $arrayData["archive"] == "true") $archive = true;
				if(array_key_exists("addWorkout", $arrayData) and $arrayData["addWorkout"] == "true") { $addWorkout = true; $click = false; }
			}
		}

		if($request->has("pageSize")) $this->pageSize = $request->get("pageSize") + $this->pageSize;
		if($search == ""){
			$workouts = Workouts::where("userId",$userId)->orderBy("created_at","Desc")->take($this->pageSize);
		} else {
			$workouts = Workouts::search($search)->where("userId",$userId)->orderBy("created_at","Desc")->take($this->pageSize);
		}

		$countArchiveWorkouts = Workouts::whereNotNull("archived_at")->where("userId",$userId)->count();
		if($countArchiveWorkouts == 0) $archive = false;

		if($archive) {
			$workouts = $workouts->whereNotNull("archived_at");
		} else {
			$workouts = $workouts->whereNull("archived_at");
		}

		$workouts = $workouts->get();

		$options = array("duplicate"=>false,"edit"=>false,"archive"=>false,"delete"=>false,"select"=>false,"click"=>$click,"add"=>$addWorkout);
		$client = 0;
		if($request->has("arrayData")){
			$arrayData = json_decode($request->get("arrayData"),true);
			if(is_array($arrayData)){
				if(array_key_exists("client", $arrayData) and $arrayData["client"] != "") $client = $arrayData["client"];
			}
		}


		return view("widgets.base.workouts")
			->with("client",$client)
			->with("options",$options)
			->with("workouts",$workouts)
			->with("countArchiveWorkouts",$countArchiveWorkouts)
			->with("permissions",$permissions)
			->with("search",$search)
			->with("archive",$archive)
			->with("total",Workouts::where("userId","=",$userId)->count());
	}



	// public function APIindex()
	// {

	// 	$userId = Auth::user()->id;
	// 	$permissions = null;
	// 	if($request->has("userId")){
	// 		$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"));
	// 		if($permissions["view"]){
	// 			$userId = $request->get("userId");
	// 		}
	// 	} else {
	// 		$permissions = Helper::checkPremissions(Auth::user()->id,null);
	// 	}

	// 	if($request->has("pageSize")) $this->pageSize = $request->get("pageSize") + $this->pageSize;

	// 	$response = array();
	// 	$response["data"] = Workouts::where("userId",$userId)->orderBy("created_at","Desc")->take($this->pageSize)->get();
	// 	$response["permissions"] = $permissions;
	// 	$response["total"] = Workouts::where("userId","=",$userId)->count();

	// 	return $this->responseJson($response);d
	// }

	public function searchWorkout(Request $request){
		$search = $request->get("search");
		$archive = false;



		if($request->has("arrayData") && !empty($request->get("arrayData"))){
			$arrayData = json_decode($request->get("arrayData"));
			if(array_key_exists("search", $arrayData) and $arrayData["search"] != "") $search = $arrayData["search"];
			if(array_key_exists("archive", $arrayData) and $arrayData["archive"] == "true") $archive = true;
		}
		if($request->has("pageSize") && !empty($request->get('pageSize'))) $this->pageSize = $request->get("pageSize") + $this->pageSize;
		if($request->has("archive") and $request->get("archive") == "true")  $archive = true;

		$userId = Auth::user()->id;
		$permissions = null;
		if($request->has("userId") && !empty($request->get("userId"))){
			$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"),"w_workouts");
			if($permissions["view"]){
				$userId = $request->get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		$workouts = Workouts::search($search)->where("userId",$userId)->orderBy("created_at","Desc")->take($this->pageSize);

		if($archive) {
			$workouts = $workouts->whereNotNull("archived_at");
		} else {
			$workouts = $workouts->whereNull("archived_at");
		}

		$countArchiveWorkouts = Workouts::whereNotNull("archived_at")->where("userId",$userId)->count();
		if($countArchiveWorkouts == 0) $archive = false;


		$workouts = $workouts->get();

		return view("widgets.base.workouts")
			->with("workouts",$workouts)
			->with("countArchiveWorkouts",$countArchiveWorkouts)
			->with("archive",$archive)
			->with("permissions",$permissions)
			->with("search",$search)
			->with("total",count(Workouts::search($search)->where("userId","=",$userId)->get()));
	}

	public function indexTrendingWorkouts(Request $request)
	{

		$userId = Auth::user()->id;
		$user = Auth::user();
		//$permissions = null;
		//if($request->has("userId")){
		//	$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"));
		//	if($permissions["view"]){
				$userId = $request->get("userId");
		//	}
		//} else {
		//	$permissions = Helper::checkPremissions(Auth::user()->id,null);
		//}

		if($request->has("pageSize")) $this->pageSizeTrending = $request->get("pageSize") + $this->pageSizeTrending;

		return view("widgets.base.trendingWorkouts")
			->with("trendingWorkouts",Workouts::forSale()->take($this->pageSizeTrending)->get())
			->with("user",$user)
			->with("total",Workouts::forSale()->count());
	}

	public function workoutSales(){

		return view("widgets.base.workoutSales")
		->with("sales",Workouts::where("userId","=",0)->get())
		->with("total",Workouts::where("userId","=",0)->count());
	}

	public function subscribeTrainer(Request $request){
		$id = $request->get("workoutId");
		$workout = Workouts::find($id);
		$total = 0;
		if($workout){
			if($request->get("subscribeToWorkout") == "true"){
				$childWorkouts = Workouts::select("id")->where("master",$workout->id)->where("authorId",$workout->authorId)->pluck("id");
				$updates = UserUpdates::where("trainerId",Auth::user()->id)->where(function($query) use($workout,$childWorkouts){ $query->orWhere("auxId", $workout->id); $query->orWhereIn("auxId",$childWorkouts); })->where("type","workout")->get();
				foreach($updates as $update){
					if($update){
						$update->subscribe = 1;
						$update->save();
						$total++;
					}
				}

				if(count($updates) == 0){
					$update = new UserUpdates;
					$update->trainerId = Auth::user()->id;
					$update->userId = $workout->userId;
					$update->auxId = $workout->id;
					$update->parentAuxId = $workout->master;
					$update->type = "workout";
					$update->subscribe = 1;
					$update->save();
					$total++;
				}

				return $this->responseJson(__("messages.SubscribedToWorkout",array("workouts"=>$total)));
			} else{
				$childWorkouts = Workouts::select("id")->where("master",$workout->id)->where("authorId",$workout->authorId)->pluck("id");
				$updates = UserUpdates::where("trainerId",Auth::user()->id)->where(function($query) use($workout,$childWorkouts){ $query->orWhere("auxId", $workout->id); $query->orWhereIn("auxId",$childWorkouts); })->where("type","workout")->get();
				foreach($updates as $update){
					if($update){
						$update->subscribe = 0;
						$update->save();
						$total++;
					}
				}

				if(count($updates) == 0){
					$update = new UserUpdates;
					$update->trainerId = Auth::user()->id;
					$update->userId = $workout->userId;
					$update->auxId = $workout->id;
					$update->parentAuxId = $workout->master;
					$update->type = "workout";
					$update->subscribe = 0;
					$update->save();
					$total++;
				}
				return $this->responseJson(__("messages.NotSubscribedToWorkout",array("workouts"=>$total)));
			}

		} else{
			return $this->responseJsonError(__("messages.NotSubscribedToWorkout",array("workouts"=>$total)));
		}
	}

	public function indexMarket(){

		return view("workoutMarket");
	}

	public function shareWorkoutIndex($workoutId=""){
		$workout = Workouts::find($workoutId);

		return view("popups.shareWorkout")
			->with("workout",$workout);
	}

	public function addToWorkoutClient($workoutId,$clientId){
		$client= Clients::find($clientId);
		$workout = Workouts::find($workoutId);

		Sharings::shareWorkout(Auth::user()->id,$client->user->id,$workout,"Workout");

		return redirect()->route("TrainerClients")->with("message",__("messages.workoutAddedToClient"));
	}

	public function AddToMyWorkouts($workoutId){

		$workout = Workouts::find($workoutId);

		$workoutNew = new Workouts();
		$workoutNew->name = $workout->name;
		$workoutNew->shares = 0;
		$workoutNew->views = 0;
		$workoutNew->timesPerformed = 0;
		$workoutNew->objectives = $workout->objectives;
		$workoutNew->userId = Auth::user()->id;
		$workoutNew->authorId = $workout->authorId;
		$workoutNew->availability = "private";
		$workoutNew->parentWorkout = $workout->id;
		$workoutNew->save();
		$workout->shares++;
		$workout->save();

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

		return redirect()->route(Auth::user()->userType,array('username' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->with("message",__("messages.SharedWorkoutAdded"));
	}


	public function acceptWorkoutBySharingLink($link){
		$sharing = Sharings::where("access_link",$link)->first();
		if($sharing){
			$sharing->viewed = 1;
			$sharing->save();
			$user = Users::find($sharing->fromUser);

			if($sharing->type == "Workout"){
				$workoutId = $sharing->aux;
				$workout = Workouts::find($workoutId);
				if($workout){
					$workoutNew = new Workouts();
					$workoutNew->name = $workout->name;
					$workoutNew->shares = 0;
					$workoutNew->views = 0;
					$workoutNew->timesPerformed = 0;
					$workoutNew->objectives = $workout->objectives;
					$workoutNew->userId = Auth::user()->id;
					$workoutNew->authorId = $workout->authorId;
					$workoutNew->trainerMonitoringId = $user->id;
					$workoutNew->availability = "private";
					$workoutNew->parentWorkout = $workout->id;
					$workoutNew->save();
					$workout->shares++;
					$workout->save();

					Notifications::insertDynamicNotification("ViewedSharing",$workout->authorId,Auth::user()->id,array("firstName" => Auth::user()->firstName,"lastName" => Auth::user()->lastName ,"workoutName" => $workout->name),true);

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

					Notifications::where("link",$sharing->access_link)->update(array("message"=>Messages::showNotification("WorkoutSharedAdded")));

					if(Auth::check()){
						if(array_key_exists("HTTP_REFERER", $_SERVER) and $_SERVER['HTTP_REFERER'] != ""){
							return $this->responseJson(__("messages.SharedWorkoutAdded"));
						} else {
							return redirect()->route(Auth::user()->userType, array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->with("message",__("messages.SharedWorkoutAdded"));
						}
					} else {
						return redirect()->route('home')->with("message",__("messages.SharedWorkoutAdded"));
					}

				}
			}
		}

		return redirect()->route(Auth::user()->userType,array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->withError(__("messages.Oups"));
	}



	public function ShareByEmail(Request $request){

		$validation = Validator::make($request->all(),array("email" => "required"));
		if($validation->fails()){
			return $this->responseJsonError(__("messages.EmailNotValid"));
		}

		$workoutsString = $request->get("workoutId");
		$workoutsArray = explode(",",$workoutsString);

		$subscribe = false;


		if($request->get("subscribeToWorkout") == "true") $subscribe = true;


		foreach($workoutsArray as $workoutId){
			$workout = Workouts::find($workoutId);
			if($workout){

				if($workout->canThisWorkoutBeShared(Auth::user())){
						$user = null;
			        	$stringOfUsersToShare = $request->get("email");
			        	$arrayOfUsersToShare = explode(",",$stringOfUsersToShare);
			        	foreach($arrayOfUsersToShare as $email){
			        		$email = trim($email);
			        		if($email != ""){
				        		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
						        	if(Users::where("email",$email)->count() > 0){
						        		$user = Users::where("email",$email)->first();
						        	} else {
						        		$user = new Users();
						        		$user->userType = "Trainee";
						        		$user->email = $email;
						        		$user->save();
						        	}

						        	$client = Auth::user()->addClient($user);

									$invite = Auth::user()->sendInvite($user);

									$copyMe = false;
									$copyView = false;
									$copyPrint = false;
									$lock = false;

									if($request->get("copyMe") ==  "true") $copyMe = true;
									if($request->get("copyPrint") ==  "true") $copyView = true;
									if($request->get("copyPrint") ==  "true") $copyPrint = true;
									if($request->get("lock") ==  "true") $lock = true;



						        	Event::dispatch('shareAWorkout', array(Auth::user(),$user->id));
						        	$comments = $request->get("comments");


									Sharings::shareWorkout(Auth::user()->id,$user->id,$workout,"Workout",$comments,$invite,$copyMe,$copyView,$copyPrint,$subscribe,$lock);

									/////IF IT IS A PERSONAL TRAINER



									//Feeds::insertFeed("NewWorkoutShared",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
								} else {
									return $this->responseJsonError(__("messages.WrongEmailAddress"));
								}
							}
						}
				} else {
					return $this->responseJsonError(__("messages.WorkoutCannotBeShared"));
				}
			}
		}
		return $this->responseJson(__("messages.WorkoutShared"));
	}

	public function ShareByUser(Request $request){


		$workout = Workouts::find($request->get("workoutId"));
		$user = Users::find($request->get("user"));
		if($user){
		if($workout){
			if($workout->canThisWorkoutBeShared(Auth::user())){


			        if(Clients::checkIfTrainerHasClient(Auth::user()->id,$user->id)){
			        	Clients::trainerAddWorkoutToClient(Auth::user()->id,$user->id,$workout->id);
			        	Notifications::insertDynamicNotification("WorkoutAddedByTrainer",$user->id,Auth::user()->id,array("firstName" => Auth::user()->firstName,"lastName" => Auth::user()->lastName ,"workoutName" => $workout->name),true);
//			        	Feeds::insertFeed("NewWorkoutSharedClient",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			        	return $this->responseJson(__("messages.WorkoutSharedToClient"));
			        }

					Sharings::shareWorkout(Auth::user()->id,$user->id,$workout,"Workout");
				//	Feeds::insertFeed("NewWorkoutShared",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);

					Event::dispatch('shareAWorkout', array(Auth::user(),$user->id));

					return $this->responseJson(__("messages.WorkoutShared"));

			} else {
				return $this->responseJsonError(__("messages.WorkoutCannotBeShared"));
			}
		}
		} else {
				return $this->responseJsonError(__("messages.NotFound"));
		}
	}

	public function ShareByLink(Request $request){


		$workout = Workouts::find($request->get("workoutId"));

		if($workout){
			if($workout->canThisWorkoutBeShared(Auth::user())){
					Sharings::shareWorkout(Auth::user()->id,"",$workout,"Workout");
//					Feeds::insertFeed("NewWorkoutShared",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);

					Event::dispatch('shareAWorkout', array(Auth::user(),""));

					return $this->responseJson(__("messages.WorkoutShared"));

			} else {
				return $this->responseJsonError(__("messages.WorkoutCannotBeShared"));
			}
		}
	}

	public function PrintWorkout($workoutId,$locale="en"){

		$workout = Workouts::find($workoutId);
		$user = Users::find($workout->userId);
		$workout->incrementViews();

		$user = Users::find($workout->userId);

		if($user->lang != "") {
			App::setLocale($user->lang);
		} else {
			App::setLocale($locale);
		}

		// return Response::make(file_get_contents($workout->getPrintPDF()), 200, [
		//     'Content-Type' => 'application/pdf',
		//     'Content-Disposition' => 'inline; filename="'.$filename.'"'
		// ]);
         Event::dispatch('printWorkout', array($user,$workout->name));

		return view("workoutPrint")
			->with("user",$user)
			->with("workout",$workout)
			->with("groups",$workout->getGroups()->get())
			->with("exercises",$workout->getExercises()->get());
	}
    public function PrintWorkoutImage($workoutId,$locale="en"){
        $workout = Workouts::find($workoutId);


        $user = Users::find($workout->userId);
        $tags = $workout->tags;
        $tagsArray = explode(",",$tags);
        $tags = Tags::whereIn("name",$tagsArray)->where("userId",$workout->userId)->get();
        $tagsClient = Tags::where("type","user")->where("userId",$workout->userId)->get();
        $tagsTags = Tags::where("type","tag")->where("userId",$workout->userId)->get();
        if($user->lang != "") {
            App::setLocale($user->lang);
        } else {
            App::setLocale($locale);
        }
        if($workout){
            $workout->incrementViews();
            return view("workoutImage")
                ->with("workout",$workout)
                ->with("user",$user)
                ->with("tags",$tags)
                ->with("tagsTags",$tagsTags)
                ->with("tagsClient",$tagsClient)
                ->with("groups",$workout->getGroups()->get())
                ->with("exercises",$workout->getExercises()->get());
        }
	}

	public function PrintWorkoutPDF($workoutId){
		$workout = Workouts::find($workoutId);
		$user = Users::find($workout->userId);
		$workout->incrementViews();

		Event::dispatch('printWorkout', array($user,$workout->name));

		$user = Users::find($workout->userId);

		return Response::make(file_get_contents($workout->getPrintPDF()), 200, [
		    'Content-Type' => 'application/pdf',
		    'Content-Disposition' => 'inline; filename="'.$workout->name.'"'
		]);
	}

    public function PrintWorkoutAndroidPDF($workoutId){
		$workout = Workouts::find($workoutId);
		$user = Users::find($workout->userId);
		$workout->incrementViews();

		Event::dispatch('printWorkout', array($user,$workout->name));
        return $workout->getPrintPDF(false);
	}

	public function PrintWorkouts($workoutIds){
		$workoutsArray = explode(",",$workoutIds);
		foreach($workoutsArray as $workoutId){
			$workout = Workouts::find($workoutId);
			$workout->incrementViews();
		}

        Event::dispatch('printWorkouts',Auth::user());

        $user = Auth::user();
		return view("workoutPrints")
			->with("user",$user)
			->with("workouts",$workoutsArray);
	}


	public function openWorkoutBySharingLink($link){
		$sharing = Sharings::where("access_link",$link)->first();
		if(!Auth::check()){
            $workoutId = $sharing->aux;
            $workout = Workouts::find($workoutId);
            $tags = $workout->tags;
            $agent = new Agent();
            return view('visitor.share-workout')
                ->with("workout",$workout)
                ->with("agent",$agent)
                ->with("workoutId",$workoutId)
                ->with("groups",$workout->getGroups()->get())
                ->with("exercises",$workout->getExercises()->get());
//			if($sharing->toUserObject){
//				$invite = Invites::where("userId",$sharing->fromUser)->where("completed",0)->where("fakeId",$sharing->toUser)->first();
//				if($invite){
//					return view('TraineeSignUp')->with("key",$invite->key)->with("invite",$invite);
//				} else {
//
//					return redirect()->route("login");
//				}
//			} else {
//				return view('TraineeSignUp');
//			}
		}

		//DEFAULT AUTH
		if($sharing){
			$sharing->viewed = 1;
			$sharing->save();
			$user = Users::find($sharing->fromUser);
			//Notifications::insertNotification("ViewedSharing",$user);

			if($sharing->type == "Workout"){
				$workoutId = $sharing->aux;
				$workout = Workouts::find($workoutId);

				if($workout and $workout->userId == Auth::user()->id){
                    return $this->viewWorkout($workout->id,null,null);
				} else {
					if(Auth::user()){
						return redirect()->route('traineeWorkouts')->withError(__("messages.WorkoutNotFound"));
					} else{
						return redirect()->route("home")->withError(__("messages.WorkoutNotFound"));
					}
				}
			}
		} else {
			return $this->responseJsonError(__("messages.NotFound"));
		}

	}

	public function previewWorkout($workoutId,$workoutName="",$workoutAuthor=""){

		$workout = Workouts::find($workoutId);
		if($workout){
			$workout->incrementViews();

			if(Auth::check()){
                return view("workoutShare")
                    ->with("workout",$workout)
                    ->with("user",Auth::user())
                    ->with("sale",true)
                    ->with("exercises",$workout->getExercises()->get());
            } else {
                return view("workoutVisitor")
                    ->with("workout",$workout)
                    ->with("sale",true)
                    ->with("exercises",$workout->getExercises()->get());
            }
        }

	}

	public function indexWorkoutMarket(Request $request){
		$user = Auth::user();
		if($request->has("pageSize")) $this->pageSizeTrending = $request->get("pageSize") + $this->pageSizeTrending;
		return view("widgets.base.workoutMarket")
			->with("newWorkouts",Workouts::forsale()->take($this->pageSizeTrending)->orderBy("updated_at","DESC")->get())
			->with("user",$user)
			->with("newTotal",Workouts::forsale()->count());
	}

	public function indexWorkoutMarketFull(Request $request){
		$user = Auth::user();
		if($request->has("pageSize")) $this->pageSizeFullTrending = $request->get("pageSize") + $this->pageSizeFullTrending;
		return view("widgets.full.workoutMarket")
			->with("freeWorkouts",Workouts::forsaleFree()->take($this->pageSizeFullTrending)->get())
			->with("paidWorkouts",Workouts::forsalePaid()->take($this->pageSizeFullTrending)->get())
			->with("newWorkouts",Workouts::forsale()->take($this->pageSizeFullTrending)->orderBy("updated_at","DESC")->get())
			->with("freeTotal",Workouts::forsaleFree()->count())
			->with("paidTotal",Workouts::forsalePaid()->count())
			->with("newTotal",Workouts::forsale()->count());
	}

	public function indexWorkoutTrainerFull(Request $request){

		$userId = Auth::user()->id;
		$user = Auth::user();
		$permissions = null;
		if($request->has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"));
			if($permissions["view"]){
				$userId = $request->get("userId");
				$user = Users::find($userId);
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		return view("widgets.full.workoutsTrainer")
			->with("freeWorkouts",Workouts::forsaleFree()->where("authorId",$user->id)->take($this->pageSize)->get())
			->with("paidWorkouts",Workouts::forsalePaid()->where("authorId",$user->id)->take($this->pageSize)->get())
			->with("newWorkouts",Workouts::forsale()->where("authorId",$user->id)->take($this->pageSize)->orderBy("updated_at","DESC")->get())
			->with("freeTotal",Workouts::forsaleFree()->count())
			->with("paidTotal",Workouts::forsalePaid()->count())
			->with("newTotal",Workouts::forsale()->count());
	}

	public function indexWorkoutTrainer(Request $request){

		$userId = Auth::user()->id;
		$user = Auth::user();
		$permissions = null;

		if($request->has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"));
			if($permissions["view"]){
				$userId = $request->get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		return view("widgets.base.workoutsTrainer")
			->with("freeWorkouts",Workouts::forsaleFree()->where("authorId",$user->id)->take($this->pageSize)->get())
			->with("paidWorkouts",Workouts::forsalePaid()->where("authorId",$user->id)->take($this->pageSize)->get())
			->with("newWorkouts",Workouts::forsale()->where("authorId",$user->id)->take($this->pageSize)->orderBy("updated_at","DESC")->get())
			->with("freeTotal",Workouts::forsaleFree()->count())
			->with("paidTotal",Workouts::forsalePaid()->count())
			->with("newTotal",Workouts::forsale()->count());
	}

	public function indexWorkoutsClient(Request $request){

		$userId = Auth::user()->id;
		$user = Auth::user();
		$permissions = null;
		$search = "";
		$client = 0;
		$total = 0;
		$archive = false;


		$arrayData = json_decode($request->get("arrayData"),true);


		if($request->has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"),"w_workouts");
			if($permissions["view"]){
				$userId = $request->get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}



		if($request->has("arrayData")){
			$arrayData = json_decode($request->get("arrayData"),true);
			if(array_key_exists("search", $arrayData) and $arrayData["search"] != "") $search = $arrayData["search"];
			if(array_key_exists("client", $arrayData) and $arrayData["client"] != "") $client = $arrayData["client"];
			if(array_key_exists("archive", $arrayData) and $arrayData["archive"] == "true") $archive = true;
		}


		$client = Clients::find($client);



		if($request->has("pageSize")) $this->pageSize = $request->get("pageSize") + $this->pageSize;
		if($client and $client->user){
			if($search == ""){
				$workouts = Workouts::where("userId",$client->user->id)->orderBy("created_at","Desc");
				$total = Workouts::where("userId",$client->user->id)->orderBy("created_at","Desc")->count();
			} else {
				$workouts = Workouts::search($search)->where("userId",$client->user->id)->orderBy("created_at","Desc");
				$total = Workouts::search($search)->where("userId",$client->user->id)->orderBy("created_at","Desc")->count();
			}
		}

		if($archive) {
			$workouts = $workouts->whereNotNull("archived_at");
		} else {
			$workouts = $workouts->whereNull("archived_at");
		}

		$workouts = $workouts->take($this->pageSize)->get();


		$countArchiveWorkouts = Workouts::whereNotNull("archived_at")->where("userId",$client->user->id)->count();
		if($countArchiveWorkouts == 0) $archive = false;

		return view("widgets.base.workoutsClient")
			->with("countArchiveWorkouts",$countArchiveWorkouts)
			->with("archive",$archive)
			->with("client",$client)
			->with("workouts",$workouts)
			->with("permissions",$permissions)
			->with("search",$search)
			->with("total",$total);
	}

	public function indexWorkoutTrainee(Request $request){

		$userId = Auth::user()->id;
		$user = Auth::user();
		$permissions = null;
		$archive = false;

		if($request->has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"));
			if($permissions["view"]){
				$userId = $request->get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		if($request->has("pageSize")) $this->pageSize = $request->get("pageSize") + $this->pageSize;

		if($request->has("arrayData")){
			$arrayData = json_decode($request->get("arrayData"),true);
			if(is_array($arrayData)){
				if(array_key_exists("archive", $arrayData) and $arrayData["archive"] == "true") $archive = true;
			}
		}


		$workouts = Workouts::where("userId",$user->id)->Released()->orderBy("updated_at","Desc");
		$workoutsTotal = Workouts::where("userId",$user->id)->Released()->orderBy("updated_at","Desc");

		if($archive) {
			$workouts = $workouts->whereNotNull("archived_at");
		} else {
			$workouts = $workouts->whereNull("archived_at");
		}

		if($archive) {
			$workoutsTotal = $workoutsTotal->whereNotNull("archived_at");
		} else {
			$workoutsTotal = $workoutsTotal->whereNull("archived_at");
		}

		$workouts = $workouts->take($this->pageSize)->get();
		$workoutsTotal = $workoutsTotal->get()->count();

		return view("widgets.base.workoutsTrainee")
			->with("archive",$archive)
			->with("workouts",$workouts)
			->with("workoutsTotal",$workoutsTotal);
	}

	public function startWorkoutPerformance(Request $request){
		$workoutId = $request->get("workoutId");
		$performanceId = $request->get("performanceId");
		$workout = Workouts::find($workoutId);
		$performance = WorkoutsPerformances::find($performanceId);
		if(!$performance and $workout){
			$performance = WorkoutsPerformances::where("userId",Auth::user()->id)->whereNull("dateCompleted")->where("workoutId",$workout->id)->first();
			if($performance)  return $this::responseJson($performance);
			$performance = new WorkoutsPerformances;
			$performance->userId = Auth::user()->id;
			$performance->workoutId = $workout->id;
			$performance->forTrainer = $workout->authorId;
			$performance->save();
		}

		return $this::responseJson($performance);

	}

	public function discartOldPerformance(Request $request){

		$workoutId = $request->get("workoutId");

        WorkoutsPerformances::where("userId",Auth::user()->id)->whereNull("dateCompleted")->where("workoutId",$workoutId)->delete();

	}

	public function saveProgressPerformance(Request $request){
		$performanceId = $request->get("performanceId");
		$seconds = $request->get("totalSeconds");
		$performance = WorkoutsPerformances::find($performanceId);
		if($performance){
			$performance->timeInSeconds = $seconds;
			$performance->save();
		}

		return $this::responseJson("Progress Saved");
	}

	public function performWorkout(Request $request){
		$workoutId = $request->get("workoutId");
		$performanceId = $request->get("workoutPerformanceId");

		if($performanceId == 0){
			$workoutId = $request->get("workoutId");
			$workout = Workouts::find($workoutId);
			if($workout){
				$performance = new WorkoutsPerformances;
				$performance->userId = Auth::user()->id;
				$performance->workoutId = $workout->id;
				$performance->forTrainer = $workout->authorId;
				$performance->timeInSeconds = ($request->get("totaltime") != "") ? $request->get("totaltime")*60 : $request->get("totalTime")*60;
				$performance->dateCompleted = Carbon::now();
				$performance->comments = $request->get("performanceComments");
				$performance->ratingId = $request->get("rating");
				$performance->save();

				$performance->notifyTrainerPerformance();

				return redirect()->route(strtolower(Auth::user()->userType)."Workouts")->with("message",__("messages.Your performance has been tracked"));

			} else {
				return redirect()->route(strtolower(Auth::user()->userType)."Workouts")->with("error",__("messages.Oops"));
			}
		} else {
			$performance = WorkoutsPerformances::find($performanceId);
			if($performance){
				$performance->timeInSeconds = ($request->get("totaltime") != "") ? $request->get("totaltime")*60 : $request->get("totalTime")*60;
				$performance->dateCompleted = Carbon::now();
				$performance->comments = $request->get("performanceComments");
				$performance->ratingId = $request->get("rating");
				$performance->save();

				$performance->notifyTrainerPerformance();



				return redirect()->route(strtolower(Auth::user()->userType)."Workouts")->with("message",__("messages.Your performance has been tracked"));
			} else {

				return redirect()->route(strtolower(Auth::user()->userType)."Workouts")->with("error",__("messages.Oops"));
			}
		}


	}

	public function indexWorkouts(Request $request)
	{

		$userId = Auth::user()->id;
		$permissions = null;
		if($request->has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"));
			if($permissions["view"]){
				$userId = $request->get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		if($request->has("pageSize")) $this->pageSize = $request->get("pageSize") + $this->pageSize;

		return view("trainee.workouts");
	}


	public function indexWorkoutsTrainer(Request $request)
	{

		$userId = Auth::user()->id;
		$permissions = null;
		if($request->has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"));
			if($permissions["view"]){
				$userId = $request->get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		if($request->has("pageSize")) $this->pageSize = $request->get("pageSize") + $this->pageSize;

		return view("trainer.workouts");
	}

	public function clientWorkouts($id,$clientName,Request $request)
	{

		$userId = Auth::user()->id;
		$permissions = null;
		$client = Clients::with("user")->find($id);

		if($request->has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"));
			if($permissions["view"]){
				$userId = $request->get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		if($request->has("pageSize")) $this->pageSize = $request->get("pageSize") + $this->pageSize;

		return view("trainer.clientWorkouts")
		->with("client",$client);
	}



	public function addCustomPicture(Request $request){
		$user = Auth::user();
		$workoutExercise = WorkoutsExercises::find($request->get("workoutExercise"));
		$exerciseImage = new ExercisesImages();
		$exerciseImage->userId = $user->id;
		$exerciseImage->exerciseId = $workoutExercise->exerciseId;
		if($request->has("availability")) $exerciseImage->availability = "public";
		$exerciseImage->save();

		Helper::checkUserFolder($user->id);
			if($request->hasFile("customPicture")) {
					$images = Helper::saveImage($request->file("customPicture"),$user->getPath().Config::get("constants.exercisesCustomPath")."/".$exerciseImage->id);
					$exerciseImage->image = $images["image"];
					$exerciseImage->thumb = $images["thumb"];
			}
		$exerciseImage->save();
		$data = array();
		$data["image"] = $exerciseImage->image;
		$data["message"] = __("messages.pictureAdded");
		$data["error"] = "";
		return $this::responseJson($data);
	}

	public function exercisePerformance($workoutExericseId){
		$datay1 = array();
		$datayReps1 = array();
		$datay2  = array();
		$y1 = array();
		$sets = Sets::where("workoutsExercisesId","=",$workoutExericseId)->where("completed",1)->orderBy('updated_at', 'ASC')->get();
		$offset = 0;
		$offset = floor($sets->count()/8);
		$x = 0;

		if ($sets->count() > 1){
           	foreach ($sets as $set){
           		if($x >= $offset){
						array_push($datay1,$set->weight);
						array_push($datayReps1,$set->reps);
						array_push($datay2,$set->weight*$set->reps);
						array_push($y1,Helper::date($set->updated_at));
						$x = 0;
				}
				$x++;
           	}
       	}
       	return view("popups.performance")

			->with("sets",$sets)
			->with("datay1",($datay1))
			->with("datayReps1",($datayReps1))
			->with("datay2",($datay2))
			->with("y1",($y1));
	}

	public function indexFull(Request $request)
	{

		$userId = Auth::user()->id;
		$permissions = null;
		if($request->has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"));
			if($permissions["view"]){
				$userId = $request->get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}
		if($request->has("pageSize")) $this->pageSize = $request->get("pageSize") + $this->pageSize;

		return view("widgets.full.objectives")
			->with("objectives",Workouts::where("userId","=",$userId)->orderBy('recordDate', 'ASC')->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("total",Workouts::where("userId","=",$userId)->count());
	}

	public function saveEditWorkout(Request $request){
		$workoutId = $request->get("workoutId");
		$workout = Workouts::find($workoutId);
		$validation = Workouts::validate($request->all());
		if($validation->fails()){

			return redirect()->to("/Workout/Edit/".$workoutId."/")->withErrors($validation->messages());
		} else {

		//$sets = $request->get("set_reps");

		$workout->description = $request->get("description");
		$workout->name = $request->get("name");
		//$workout->objectives = $request->get("objectives");
		if($request->get("available") == "Yes") { $workout->availability = "public"; $workout->sale = 1; };
		if($request->get("available") == "No") { $workout->availability = "private"; $workout->sale = 0; }
		//$workout->price = $request->get("price");
		//$workout->category = $request->get("category");
		$workout->save();
		return redirect()->to($workout->getURL())
				->with("message",__("messages.WorkoutSaved"));
		}
	}

	public function viewWorkoutNoName($id,$author){
		return $this->viewWorkout($id,"",$author);
	}



	public function viewWorkout($id,$name,$author){
		$workoutId = $id;
		$workout = Workouts::find($id);
		$user = Auth::user();
		if($workout){
			if($workout->status == "Draft"){
				return redirect()->to( __("routes./Trainer/CreateWorkout/").$workout->id);
			}
			$workout->incrementViews();

			$tags = $workout->tags;
			$tagsArray = explode(",",$tags);
			$tags = Tags::whereIn("name",$tagsArray)->where("userId",$workout->userId)->get();
			$tagsClient = Tags::where("type","user")->where("userId",$workout->userId)->get();
			$tagsTags = Tags::where("type","tag")->where("userId",$workout->userId)->get();
			$agent = new Agent();


			$ratings = Ratings::where("ownerId",$workout->authorId)->orderBy("value","ASC")->get();

			if(count($ratings) == 0) $ratings = Ratings::where(function($query){$query->orwhereNull("ownerId"); $query->orWhere("ownerId",0);})->orderBy("value","ASC")->get();

			Event::dispatch('viewWorkout', array(Auth::user(),$workout->name));

			return view("workout")
				->with("workout",$workout)
				->with("agent",$agent)
				->with("user",$user)
				->with("workoutId",$workoutId)
				->with("tags",$tags)
				->with("ratings",$ratings)
				->with("tagsTags",$tagsTags)
				->with("tagsClient",$tagsClient)
				->with("groups",$workout->getGroups()->get())
				->with("exercises",$workout->getExercises()->get());
		} else {
			if(Auth::user()){
				return redirect()->route('Trainee', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->withError(__("messages.WorkoutNotFound"));
			} else{
				return redirect()->route("home")->withError(__("messages.WorkoutNotFound"));
			}
		}
	}

	public function viewWorkoutInternal($id,$locale="en",$name="",$author=""){
		$workoutId = $id;
		$workout = Workouts::find($id);


		$user = Users::find($workout->userId);
		$tags = $workout->tags;
			$tagsArray = explode(",",$tags);
		$tags = Tags::whereIn("name",$tagsArray)->where("userId",$workout->userId)->get();
			$tagsClient = Tags::where("type","user")->where("userId",$workout->userId)->get();
			$tagsTags = Tags::where("type","tag")->where("userId",$workout->userId)->get();
		if($user->lang != "") {
			App::setLocale($user->lang);
		} else {
			App::setLocale($locale);
		}
		if($workout){
			$workout->incrementViews();
			return view("workoutImage")
				->with("workout",$workout)
				->with("user",$user)
				->with("tags",$tags)
				->with("tagsTags",$tagsTags)
				->with("tagsClient",$tagsClient)
				->with("groups",$workout->getGroups()->get())
				->with("exercises",$workout->getExercises()->get());
		} else {
			if(Auth::user()){
				return redirect()->route('Trainee', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->withError(__("messages.WorkoutNotFound"));
			} else{
				return redirect()->route("home")->withError(__("messages.WorkoutNotFound"));
			}
		}
	}

	public function viewWorkoutPDF($id,$name,$author){



		$workoutId = $id;
		$workout = Workouts::find($id);
		$user = Auth::user();
		$data = array();
		$data["workout"] = $workout;
		$data["user"] = $user;
		$data["groups"] = $workout->getGroups()->get();
		$data["exercises"] = $workout->getExercises()->get();
		if($workout){
			Event::dispatch('pdfWorkout', array(Auth::user(),$workout->name));
			$workout->incrementViews();
			$pdf = PDF::loadView('workoutPrint', $data);
			$pdf->setOptions(array(
				"orientation" => "landscape",

			));
			return $pdf->download(Helper::formatURLString($workout->name." ".$workout->author->getCompleteName())."_grid.pdf");
		} else {
			if(Auth::user()){
				return redirect()->route('Trainee', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->withError(__("messages.WorkoutNotFound"));
			} else{
				return redirect()->route("home")->withError(__("messages.WorkoutNotFound"));
			}
		}
	}

	public function viewWorkoutImage($id,$name,$author){



		$workoutId = $id;
		$workout = Workouts::find($id);
		if($workout){

			$image = Image2::loadFile(URL::to($workout->getURLImage()));
			return $image->download(Helper::formatURLString($workout->name." ".$workout->author->getCompleteName()).".jpg");
		} else {
			if(Auth::user()){
				return redirect()->route('Trainee', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->withError(__("messages.WorkoutNotFound"));
			} else{
				return redirect()->route("home")->withError(__("messages.WorkoutNotFound"));
			}
		}
	}

	public function createUserDownload($workouts,$param1,$param2=""){

		$workoutsString = $workouts;
		$workouts = explode(",",$workoutsString);
		$jpeg=false;
		$pdf=false;

		if($param1 == "JPEG" || $param2 == "JPEG"){
			$jpeg=true;
		}

		if($param1 == "PDF" || $param2 == "PDF"){
			$pdf=true;
		}


		$path = storage_path()."/temp/".Auth::user()->id;
		$name = "Workouts ".Helper::replaceWinCompatible(Helper::now()).".zip";
		$zipFilePath = $path."/".$name;



		if(!File::isDirectory($path)){
            File::makeDirectory($path);
        } else {
        	File::deleteDirectory($path);
        	File::makeDirectory($path);
        }

        $zip = new ZipArchive();

        if(Config::get("app.debug")) Log::error($zipFilePath);
        if ($zip->open($zipFilePath, ZipArchive::OVERWRITE|ZipArchive::CREATE) === TRUE) {
        	$counter = 1;
			foreach($workouts as $workout){
				$workout = Workouts::find($workout);
				$user = Auth::user();
				if($workout){

                    if ($jpeg) {
                        try {
                            $imageData = $this->PrintWorkoutImage($workout->id);
                            $imagePath = $path . "/" . Helper::formatURLString($counter . " - " . $workout->name . " " . $workout->author->getCompleteName()) . ".jpg";
                            $image = SnappyImage::loadHTML($imageData);
                            $image->setOption('enable-local-file-access', true);
                            $image->setTimeout(300);
                            $image->save($imagePath);
                        } catch (ProcessTimedOutException $e) {
                            Log::error("Snappy image generation timed out: " . $e->getMessage());
                            throw $e;
                        } catch (\Exception $e) {
                            Log::error("Image generation failed: " . $e->getMessage());
                            throw $e;
                        }

                        // Add the image to the ZIP file
                        $zip->addFile($imagePath, Helper::formatURLString($counter . " - " . $workout->name . " " . $workout->author->getCompleteName()) . ".jpg");

                        // Add the PDF to the ZIP file
                        $zip->addFile($workout->getImagePDF(), Helper::formatURLString($counter . " - " . $workout->name . " " . $workout->author->getCompleteName()) . ".pdf");
                    }

					if($pdf){
                        $html = $this->PrintWorkout($workout->id);
						$pdf = SnappyPdf::loadHTML($html);
						$pdfPath = $path."/".Helper::formatURLString($counter." - ".$workout->name." ".$workout->author->getCompleteName())."_grid.pdf";
						$name_temp = $path."/".Helper::formatURLString($counter." - ".$workout->name." ".$workout->author->getCompleteName())."_grid.pdf";
						$pdf->save($name_temp);

                        $merger = (new PdfManage())->init();
                        $merger->addPDF($name_temp);
                        $merger->addPDF(public_path(Config::get("constants.gridPDF")));
                        $merger->merge('L', ['file' => $name_temp]);

						$zip->addFile($pdfPath,Helper::formatURLString($counter." - ".$workout->name." ".$workout->author->getCompleteName())."_grid.pdf");
					}

					Event::dispatch('printWorkouts', array($user,$workout->name));
				}
				$counter++;
			}
			$zip->close();

//			App::finish(function($request, $response) use ($path){
//			    File::deleteDirectory($path);
//			});


			$headers = array(
              'Content-Type: application/zip',
            );
			return Response::download($zipFilePath, $name, $headers)->deleteFileAfterSend(true);
		}
	}


    public function createNewWorkout(Request $request){
		$userId = Auth::user()->id;
		$permissions = null;
		if($request->has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"));
			if($permissions["view"]){
				$userId = $request->get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		Event::dispatch('userNewWorkout', array(Auth::user()));

		return view("trainee.createWorkout")
			->with("permissions",$permissions)
			->with("total",Workouts::where("userId","=",$userId)->count());
	}


	public function createNewWorkoutTrainer(Request $request,$workoutId=""){
		$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
		$create = true;

		$userId = Auth::user()->id;
		if($request->has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"));
			if($permissions["view"]){
				$userId = $request->get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		//Session::forget("workoutIdInProgress");
		//dd(Session::get("workoutIdInProgress"));

		if($workoutId == ""){
			if($pageWasRefreshed) {
                if(Session::has("workoutIdInProgress") && !empty(Session::has("workoutIdInProgress"))){
			   		$workout = Workouts::find(Session::get("workoutIdInProgress"));
			   		if($workout){
			   			$create = false;
			   		}
			   }
			}

			if($create){
				$workout = new Workouts();
				$workout->name = $request->get("workoutName");
				//$workout->price = $workoutDetails["price"];

				$workout->sale = 0;
				$workout->availability = "private";
				$workout->shares = 0;
				$workout->views = 0;
				$workout->timesPerformed = 0;
				$workout->userId = $userId;
				$workout->authorId = $userId;
				$workout->status = "Draft";
				$workout->version = Config::get("constants.version");
				$workout->save();

				$workout->master = $workout->id;
				$workout->save();

				Session::put("workoutIdInProgress",$workout->id);
				Session::save();
			}

		} else {
			$workout = Workouts::find($workoutId);
		}

		$tags = Tags::where("userId",Auth::user()->id)->get();
		return view("trainer.createWorkout")
			->with("workout",$workout)
			->with("permissions",$permissions)
			->with("tags",$tags)
			->with("bodygroups",BodyGroups::select("id","name")->where("main",1)->orderBy("name")->get())
			->with("bodygroupslist",BodyGroups::select(["id","name"])->where("main",1)->orderBy("name")->pluck("name","id"))
			->with("equipments",Equipments::select("id","name")->orderBy("name")->get())
			->with("equipmentsList",Equipments::select("id","name")->orderBy("name")->pluck("name","id"))
			->with("exercisesTypes",ExercisesTypes::select("id","name")->orderBy("name")->get())
			->with("total",Workouts::where("userId","=",$userId)->count());
	}

	public function editWorkout($workoutId="",$name="",$author="",$client=""){
		$userId = Auth::user()->id;
		$permissions = null;

		// if($client != ""){
		// 	$clientObject = Clients::find($client);
		// 	$client = $clientObject->userId;

		// 	$workout = Workouts::find($workoutId);
		// 	$workoutNew = $workout->replicate();
		// 	$workoutNew->name = $request->get("name");
		// 	$workoutNew->save();
		// 	$workoutNew->resetWorkout();

		// 	$workoutNew->duplicateTemplateFrom($workout);

		// 	$workoutNew->createSets();
		// }

		$workout = Workouts::find($workoutId);
        if($workout->canThisWorkoutBeShared(Auth::user())){
            $tags = array();

            if($workout){
                if(date($workout->created_at) <= date('2016-09-04') and $workout->status == "Released"){
                    $controller = new SystemController();
                    $controller->migrateWorkouts($workout->id);
                } else if($workout->exerciseGroupRest == "" or $workout->exerciseGroupsRest == "[]"){
                    Log::error("ExerciseGroupRest is Emtpy, recreating");
                    $controller = new SystemController();
                    $controller->migrateWorkouts($workout->id);
                }
                $permissions = Helper::checkPremissions(Auth::user()->id,$workout->userId);
                if(!$permissions["edit"]){
                    //redirect()->route("trainerWorkouts")->withError(__("messages.permissions"));
                }
            } else {
                redirect()->route("trainerWorkouts")->withError(__("messages.permissions"));
            }


            $tags = Tags::where("userId",Auth::user()->id)->get();

            Event::dispatch('editAWorkout', array(Auth::user(),$workout->name));

            return view("trainer.createWorkout")
                ->with("workout",$workout)
                ->with("client",$client)
                ->with("tags",$tags)
                ->with("bodygroups",BodyGroups::select("id","name")->where("main",1)->orderBy("name")->get())
                ->with("bodygroupslist",BodyGroups::select("id","name")->where("main",1)->orderBy("name")->pluck("name","id"))
                ->with("equipmentsList",Equipments::select("id","name")->pluck("name","id"))
                ->with("equipments",Equipments::select("id","name")->orderBy("name")->get())
                ->with("exercisesTypes",ExercisesTypes::select("id","name")->orderBy("name")->get())
                ->with("total",Workouts::where("userId","=",$userId)->count());
        }else{
            return redirect()->route('trainerWorkouts')->with('error',__("messages.WorkoutCannotBeEdited"));
        }
	}

	public function assignWorkoutToClientEdit($client="",$workoutId){
		$userId = Auth::user()->id;
		$permissions = null;


		$workout = Workouts::find($workoutId);
		$tags = array();




		if($workout){
		if(date($workout->created_at) <= date('2016-09-04') and $workout->status == "Released"){
			$controller = new SystemController();
			$controller->migrateWorkouts($workout->id);
		} else if($workout->exerciseGroupRest == "" or $workout->exerciseGroupsRest == "[]"){
			Log::error("ExerciseGroupRest is Emtpy, recreating");
			$controller = new SystemController();
			$controller->migrateWorkouts($workout->id);
		}
		$permissions = Helper::checkPremissions(Auth::user()->id,$workout->userId);
		if(!$permissions["edit"]){
            //redirect()->route("trainerWorkouts")->withError(__("messages.permissions"));
        }
    	} else {
    		redirect()->route("trainerWorkouts")->withError(__("messages.permissions"));
    	}


		$tags = Tags::where("userId",Auth::user()->id)->get();

		Event::dispatch('editAWorkout', array(Auth::user(),$workout->name));

		return view("trainer.createWorkout")
			->with("workout",$workout)
			->with("client",$client)
			->with("tags",$tags)
			->with("bodygroups",BodyGroups::select("id","name")->where("main",1)->orderBy("name")->get())
			->with("bodygroupslist",BodyGroups::select("id","name")->where("main",1)->orderBy("name")->pluck("name","id"))
			->with("equipmentsList",Equipments::select("id","name")->orderBy("name")->pluck("name","id"))
			->with("equipments",Equipments::select("id","name")->orderBy("name")->get())
			->with("exercisesTypes",ExercisesTypes::select("id","name")->orderBy("name")->get())
			->with("total",Workouts::where("userId","=",$userId)->count());


	}

	public function createNewWorkoutTrainerToClient($clientId,Request $request){

		$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
		$create = true;
		$workout = null;
		$workoutId = null;

		$userId = Auth::user()->id;
		$permissions = null;
		if($request->has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"));
			if($permissions["view"]){
				$userId = $request->get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}
		$workout = null;

		//Session::forget("workoutIdInProgress");
		//dd(Session::get("workoutIdInProgress"));

		if($workoutId == ""){
			if($pageWasRefreshed) {
			 	if(Session::get("workoutIdInProgress") != ""){
			   		$workout = Workouts::find(Session::get("workoutIdInProgress"));
			   		if($workout){
			   			$create = false;
			   		}
			   }
			}

			if($create){
				$workout = new Workouts();
				$workout->name = $request->get("workoutName");
				//$workout->price = $workoutDetails["price"];

				$workout->sale = 0;
				$workout->availability = "private";
				$workout->shares = 0;
				$workout->views = 0;
				$workout->timesPerformed = 0;
				$workout->userId = $userId;
				$workout->authorId = $userId;
				$workout->status = "Draft";
				$workout->version = Config::get("constants.version");
				$workout->save();

				$workout->master = $workout->id;
				$workout->save();

				Session::put("workoutIdInProgress",$workout->id);
				Session::save();
			}

		} else {
			$workout = Workouts::find($workoutId);
		}

		$tags = Tags::where("userId",Auth::user()->id)->get();

		return view("trainer.createWorkout")
			->with("workout",$workout)
			->with("client",$clientId)
			->with("permissions",$permissions)
			->with("tags",$tags)
			->with("bodygroups",BodyGroups::select("id","name")->where("main",1)->orderBy("name")->get())
			->with("bodygroupslist",BodyGroups::select("id","name")->where("main",1)->orderBy("name")->pluck("name","id"))
			->with("equipments",Equipments::select("id","name")->orderBy("name")->get())
			->with("equipmentsList",Equipments::select("id","name")->pluck("name","id"))
			->with("exercisesTypes",ExercisesTypes::select("id","name")->orderBy("name")->get())
			->with("total",Workouts::where("userId","=",$userId)->count());
	}

	public function addSetsReturnTable(Request $request){
		$workoutExerciseId = $request->get("workoutExerciseId");
		$workoutExercise = WorkoutsExercises::find($workoutExerciseId);
		$workoutExercise->updated_at = date("Y-m-d H:i:s");
		$workoutExercise->save();
		$workout = Workouts::find($workoutExercise->workoutId);
		$workout->createNewSetsExercise($workoutExerciseId);

		$exercise = WorkoutsExercises::with("exercises")->find($workoutExerciseId);

		return view("widgets.full.workoutExercise")
			->with("exercise",$exercise)
			->with("workout",$workout);
	}

	public function addSets(Request $request){
		$workoutExerciseId = $request->get("workoutExerciseId");
		$workoutExercise = WorkoutsExercises::find($workoutExerciseId);
		$workoutExercise->updated_at = date("Y-m-d H:i:s");
		$workoutExercise->save();
		$workout = Workouts::find($workoutExercise->workoutId);
		$workout->createNewSetsExercise($workoutExerciseId);

	}

	public function saveSingleSet(Request $request){

		$set = Sets::find($request->get("set"));
		$workoutExercise = WorkoutsExercises::find($set->workoutsExercisesId);
		$workout = Workouts::find($workoutExercise->workoutId);
		if($set){
			//if($request->get("type") != "cardio"){
				//NOTIFY TRAINER IF WEIGHT CHANGES
				if($request->get("weight") != $set->weight) {
					Feeds::insertDynamicFeed("changedWeight",$workout->userId,$workout->userId,array("firstName"=>$workout->user->firstName,"lastName"=>$workout->user->lastName,"exercise"=>$workoutExercise->exercises->name,"workout"=>$workout->name),"workoutChangedWeight",$workout->getURL(),"workout");
					//if($workout->trainerMonitoringId != "" and Clients::checkIfTrainerHasClient($workout->trainerMonitoringId,$workout->userId,$workout->id))
					//Notifications::insertDynamicNotification("clientUpdatedWeight",$workout->trainerMonitoringId,$workout->userId,array("firstName"=>$workout->user->firstName,"lastName"=>$workout->user->lastName,"exercise"=>$workoutExercise->exercises->name,"workout"=>$workout->name));
				}
				//NOTIFY TRAINER IF REPS CHANGE
				if($request->get("reps") != $set->reps and $workout->trainerMonitoringId != "" and Clients::checkIfTrainerHasClient($workout->trainerMonitoringId,$workout->userId,$workout->id)) {
					Feeds::insertDynamicFeed("changedReps",$workout->userId,$workout->userId,array("firstName"=>$workout->user->firstName,"lastName"=>$workout->user->lastName,"exercise"=>$workoutExercise->exercises->name,"workout"=>$workout->name),"workoutChangedReps",$workout->getURL(),"workout");
					//if($workout->trainerMonitoringId != "" and Clients::checkIfTrainerHasClient($workout->trainerMonitoringId,$workout->userId,$workout->id))
					//Notifications::insertDynamicNotification("clientUpdatedWeight",$workout->trainerMonitoringId,$workout->userId,array("firstName"=>$workout->user->firstName,"lastName"=>$workout->user->lastName,"exercise"=>$workoutExercise->exercises->name,"workout"=>$workout->name));
				}
				$set->weight = $request->get("weight");
				$set->weightKG = Helper::formatWeight($set->weight/2.2);
				$set->reps = $request->get("reps");
				$set->completed = 1;
			//} else {
				//NOTIFY TRAINER IF DISTANCE CHANGE
				if($request->get("distance") != $set->distance and $workout->trainerMonitoringId != "" and Clients::checkIfTrainerHasClient($workout->trainerMonitoringId,$workout->userId,$workout->id)) {
					Feeds::insertDynamicFeed("changedDistance",$workout->userId,$workout->userId,array("firstName"=>$workout->user->firstName,"lastName"=>$workout->user->lastName,"exercise"=>$workoutExercise->exercises->name,"workout"=>$workout->name),"workoutChangedDistance",$workout->getURL(),"workout");
					//if($workout->trainerMonitoringId != "" and Clients::checkIfTrainerHasClient($workout->trainerMonitoringId,$workout->userId,$workout->id))
					//Notifications::insertDynamicNotification("clientUpdatedWeight",$workout->trainerMonitoringId,$workout->userId,array("firstName"=>$workout->user->firstName,"lastName"=>$workout->user->lastName,"exercise"=>$workoutExercise->exercises->name,"workout"=>$workout->name));
				}
				//NOTIFY TRAINER IF TIME CHANGE
				if($request->get("time") != $set->time and $workout->trainerMonitoringId != "" and Clients::checkIfTrainerHasClient($workout->trainerMonitoringId,$workout->userId,$workout->id)) {
					Feeds::insertDynamicFeed("changedTime",$workout->userId,$workout->userId,array("firstName"=>$workout->user->firstName,"lastName"=>$workout->user->lastName,"exercise"=>$workoutExercise->exercises->name,"workout"=>$workout->name),"workoutChangedTime",$workout->getURL(),"workout");
					//if($workout->trainerMonitoringId != "" and Clients::checkIfTrainerHasClient($workout->trainerMonitoringId,$workout->userId,$workout->id))
					//Notifications::insertDynamicNotification("clientUpdatedWeight",$workout->trainerMonitoringId,$workout->userId,array("firstName"=>$workout->user->firstName,"lastName"=>$workout->user->lastName,"exercise"=>$workoutExercise->exercises->name,"workout"=>$workout->name));
				}
				//NOTIFY TRAINER IF SPEED CHANGE
				if($request->get("speed") != $set->speed and $workout->trainerMonitoringId != "" and Clients::checkIfTrainerHasClient($workout->trainerMonitoringId,$workout->userId,$workout->id)) {
					Feeds::insertDynamicFeed("changedSpeed",$workout->userId,$workout->userId,array("firstName"=>$workout->user->firstName,"lastName"=>$workout->user->lastName,"exercise"=>$workoutExercise->exercises->name,"workout"=>$workout->name),"workoutChangedSpeed",$workout->getURL(),"workout");
					//if($workout->trainerMonitoringId != "" and Clients::checkIfTrainerHasClient($workout->trainerMonitoringId,$workout->userId,$workout->id))
					//Notifications::insertDynamicNotification("clientUpdatedWeight",$workout->trainerMonitoringId,$workout->userId,array("firstName"=>$workout->user->firstName,"lastName"=>$workout->user->lastName,"exercise"=>$workoutExercise->exercises->name,"workout"=>$workout->name));
				}
				$set->distance = $request->get("distance");
				$set->bpm = $request->get("bpm");
				$set->time = $request->get("time");
				$set->speed = $request->get("speed");
				$set->completed = 1;
			//}
			$set->save();


			$workoutExercise->updated_at = date("Y-m-d H:i:s");
			$workoutExercise->save();


			$workout->markAsCompleted();

			if($set->last == 1){
				$workout = Workouts::find($set->workoutId);
					if($workout){
						$workout->createNewSetsExercise($set->workoutsExercisesId);
					}
				return $this::responseJson(__("messages.NewSetsCreatedAndSaved"));
			}
			return $this::responseJson(__("messages.SetSaved"));
		} else {
			return $this::responseJsonError(__("messages.SetNotValid"));
		}
	}

	public function exerciseCompleted(Request $request){
		$user = Auth::user();
		$workoutsExercisesId = $request->get("workoutsExercisesId");
		$workoutExercise = WorkoutsExercises::find($workoutsExercisesId);
		$sets = $request->get("sets");
		foreach($sets as $set){
			$setFetched = Sets::find($set["idSet"]);
			if($setFetched){

				if(array_key_exists("weight",$set)) $setFetched->weight = $set["weight"];
				if(array_key_exists("weight",$set)) $setFetched->weightKG = Helper::formatWeight($set["weight"]/2.2);
				if(array_key_exists("reps",$set)) $setFetched->reps = $set["reps"];
				if(array_key_exists("rest",$set)) $setFetched->rest = $set["rest"];

				if(array_key_exists("speed",$set)) $setFetched->speed = $set["speed"];
				if(array_key_exists("time",$set)) $setFetched->time = $set["time"] ;
				if(array_key_exists("distance",$set)) $setFetched->distance = $set["distance"];
				//$set->hr = $set["hr"];
				$setFetched->save();

				$workoutExercise = WorkoutsExercises::find($setFetched->workoutsExercisesId);
				$workoutExercise->updated_at = date("Y-m-d H:i:s");
				$workoutExercise->save();


				$workout = Workouts::find($workoutExercise->workoutId);
				$workout->markAsCompleted();


				if($setFetched->last == 1 and $setFetched->completed == 1){
					$workout = Workouts::find($request->get("workoutId"));
					if($workout){
						$workout->createNewSetsExercise($setFetched->workoutsExercisesId);
					}
				}

			} else {
				return $this::responseJsonError(__("messages.NotFound"));
			}
		}
		$sets = Sets::where("workoutsExercisesId",$workoutsExercisesId)->take(TemplateSets::where("workoutsExercisesId",$workoutsExercisesId)->count())->orderBy("id","Desc")->get();
		foreach($sets as $set){
			$set->completed = 1;
			$set->save();
		}
		$workout = Workouts::find($workoutExercise->workoutId);
		if($workout){
			$workout->createNewSetsExercise($workoutExercise->id);
		}

		if(!Notifications::checkIfTrainerNotifiedTodayWorkout($user->id,null)){
			$trainers = Clients::where("userId",$user)->distinct()->pluck("trainerId");
			if(count($trainers > 0)){
				foreach($trainers as $trainer){
					Notifications::insertDynamicNotification(__("messages.TraineeCompleted"),$trainer,$user->id,array(),false);
				}
			}
		}


		return $this::responseJson(__("messages.ExerciseCompleted"));
	}

	public function workoutCompleted(Request $request){
		$workoutId = $request->get("workoutId");
		$workout = Workouts::find($workoutId);
		if($workout){
			$workoutExercises = WorkoutsExercises::where("workoutId",$workout->id)->get();
			foreach($workoutExercises as $workoutExercise){
				$sets = Sets::where("workoutsExercisesId",$workoutExercise->id)->take(TemplateSets::where("workoutsExercisesId",$workoutExercise->id)->count())->orderBy("id","Desc")->get();
				foreach($sets as $set){
					$set->completed = 1;
					$set->save();
				}
				$workout->createNewSetsExercise($workoutExercise->id);

			}


			$workout->markAsCompleted();



			return $this::responseJson(__("messages.WorkoutCompleted"));
		}
	}

	public function updateUnitExerciseGroup(Request $request){
		$id = $request->get("id");
		$units = $request->get("units");
		$exercise = WorkoutsExercises::find($id);
		if($exercise){
			$sets = Sets::where("workoutsExercisesId",$exercise->id)->get();
			foreach($sets as $set){

				if($exercise->units == "" and $units == "Imperial"){
						$set->units = "Imperial";
				} else if($exercise->units == "" and $units == "Metric"){
						$set->units = "Metric";
						$set->weight = number_format($set->weight / 2.2,1);
						$set->distance = number_format($set->distance / 1.609344,2);
						$set->speed = number_format($set->speed / 1.609344);
				} else {
					if($exercise->units != $units){

						if($units == "Imperial"){
							if($set->units == "Metric"){
									$set->weight = number_format($set->weight / 2.2,1);
									$set->distance = number_format($set->distance / 1.609344,2);
									$set->speed = number_format($set->speed / 1.609344,1);
							}
							$set->units = "Imperial";
						} else if($units == "Metric"){
							if($set->units == "Imperial"){
									$set->weight = number_format($set->weight * 2.2,1);
									$set->distance = number_format($set->distance * 1.609344,2);
									$set->speed = number_format($set->speed * 1.609344,1);
								}
								$set->units = "Metric";
						}

					}
				}
				$set->save();
			}
			$exercise->units = $units;
			$exercise->save();
		}
	}

	public function saveAllSets(Request $request){

		$sets = $request->get("sets");
		foreach($sets as $set){
			$setFetched = Sets::find($set["idSet"]);
			if($setFetched){

				if(array_key_exists("weight",$set)) $setFetched->weight = $set["weight"];
				if(array_key_exists("weight",$set)) $setFetched->weightKG = Helper::formatWeight($set["weight"]/2.2);
				if(array_key_exists("reps",$set)) $setFetched->reps = $set["reps"];
				if(array_key_exists("rest",$set)) $setFetched->rest = $set["rest"];

				if(array_key_exists("speed",$set)) $setFetched->speed = $set["speed"];
				if(array_key_exists("time",$set)) $setFetched->time = $set["time"];
				if(array_key_exists("distance",$set)) $setFetched->distance = $set["distance"];
				//$set->hr = $set["hr"];
				$setFetched->save();

				$workoutExercise = WorkoutsExercises::find($setFetched->workoutsExercisesId);
				$workoutExercise->updated_at = date("Y-m-d H:i:s");
				$workoutExercise->save();

				if($setFetched->last == 1 and $setFetched->completed == 1){
					$workout = Workouts::find($request->get("workoutId"));
					if($workout){
						$workout->createNewSetsExercise($setFetched->workoutsExercisesId);
					}
				}

			} else {
				return $this::responseJsonError(__("messages.NotFound"));
			}
		}
		return $this::responseJson(__("messages.AllSetsSaved"));
	}

	public function saveAllAddNewSets(Request $request){
		$workoutId = $request->get("workoutId");
		$workoutExerciseId = $request->get("exerciseId");
		$sets = $request->get("sets");
		foreach($sets as $set){
			$setFetched = Sets::find($set["idSet"]);
			if($setFetched){

				if(array_key_exists("weight",$set)) $setFetched->weight = $set["weight"];
				if(array_key_exists("weight",$set)) $setFetched->weightKG = Helper::formatWeight($set["weight"]/2.2);
				if(array_key_exists("reps",$set)) $setFetched->reps = $set["reps"];
				if(array_key_exists("rest",$set)) $setFetched->rest = $set["rest"];

				if(array_key_exists("speed",$set)) $setFetched->speed = $set["speed"];
				if(array_key_exists("time",$set)) $setFetched->time = $set["time"];
				if(array_key_exists("distance",$set)) $setFetched->distance = $set["distance"];
				//$set->hr = $set["hr"];
				$setFetched->save();

				$workoutExercise = WorkoutsExercises::find($setFetched->workoutsExercisesId);
				$workoutExercise->updated_at = date("Y-m-d H:i:s");
				$workoutExercise->save();

			} else {
				return $this::responseJsonError(__("messages.NotFound"));
			}
		}

		$workout = Workouts::find($workoutId);
		$workout->createNewSetsExercise($workoutExerciseId);

		$exercise = WorkoutsExercises::with("exercises")->find($workoutExerciseId);

		return view("widgets.full.workoutExercise")
			->with("exercise",$exercise)
			->with("workout",$workout);
	}

	public function createNewWorkoutAddEdit(Request $request){
		$userId = Auth::user()->id;
		$permissions = null;
		if($request->has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"));
			if($permissions["view"]){
				$userId = $request->get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		$workoutDetails = json_decode(stripslashes($request->get("program_details")),true);
		$workout = new Workouts();
		$workout->name = $workoutDetails["name"];
		$workout->shares = 0;
		$workout->views = 0;
		$workout->timesPerformed = 0;
		$workout->objectives = $request->get($workoutDetails["obj"]);
		$workout->userId = $userId;
		$workout->authorId = $userId;
		$workout->availability = "private";
		$workout->version = Config::get("constants.version");
		$workout->save();

		$workout->master = $workout->id;
		$workout->save();


		$exercises = json_decode(stripslashes($request->get("program")),true);
		$exercises = $exercises[1];
		$order = 1;
		foreach($exercises as $exercise){
			$workoutExercise = new WorkoutsExercises();

			$workoutExercise->workoutId = $workout->id;
			$workoutExercise->exerciseId = $exercise["exercise"];
			$ex = Exercises::find($workoutExercise->exerciseId);
			if($ex) $ex->incrementUsage();
			$workoutExercise->notes = $exercise["notes"];
			if(array_key_exists("equipmentId", $exercise)) $workoutExercise->equipmentId = $exercise["equipmentId"];
			$workoutExercise->order = $order;
			$workoutExercise->sets = count($exercise["reps"]);

			$workoutExercise->save();

			$counter = 1;
			$index = 0;
			foreach($exercise["reps"] as $rep){

				$templateSet = new TemplateSets();
				$templateSet->number = $counter;
				$templateSet->exerciseId = $exercise["exercise"];
				$templateSet->workoutsExercisesId = $workoutExercise->id;
				$type = "regular";

				if(array_key_exists($index,$exercise["time"]) and  $exercise["time"][$index] != ""){
					$type = "cardio";
					if(array_key_exists($index,$exercise["distance"])) $templateSet->distance = $exercise["distance"][$index] ;
					if(array_key_exists($index,$exercise["time"])) $templateSet->time = $exercise["time"][$index];
					if(array_key_exists($index,$exercise["speed"])) $templateSet->speed = $exercise["speed"][$index] ;
					if(array_key_exists($index,$exercise["reps"])) $templateSet->reps = $exercise["reps"][$index] ;
					if(array_key_exists($index,$exercise["hr"])) $templateSet->bpm = $exercise["hr"][$index] ;
				} else {
					if(array_key_exists($index,$exercise["reps"])) $templateSet->reps = $exercise["reps"][$index] ;
					if(array_key_exists($index,$exercise["templateSets"])) $templateSet->reps = $exercise["templateSets"][$index] ;

					if(array_key_exists($index,$exercise["weight"])) { $templateSet->weight = $exercise["weight"][$index] ; $templateSet->weightKG = Helper::formatWeight($exercise["weight"][$index]/2.2); };
					if(array_key_exists($index,$exercise["templateWeight"])) { $templateSet->weight = $exercise["templateWeight"][$index] ; $templateSet->weightKG = Helper::formatWeight($exercise["templateWeight"][$index]/2.2); };
					if(array_key_exists($index,$exercise["rest"])) $templateSet->rest = $exercise["rest"][$index] ;

				}

				//$templateSet->tempo = $exercise["tempo"];
				$templateSet->type = $type;
				$templateSet->notes = $exercise["notes"];
				$templateSet->tempo1 = (array_key_exists("tempo1", $exercise)) ? $exercise["tempo1"] : "";
				$templateSet->tempo2 = (array_key_exists("tempo2", $exercise)) ? $exercise["tempo2"] : "";
				$templateSet->tempo3 = (array_key_exists("tempo3", $exercise)) ? $exercise["tempo3"] : "";
				$templateSet->tempo4 = (array_key_exists("tempo4", $exercise)) ? $exercise["tempo4"] : "";
				$templateSet->workoutId = $workout->id;

				$templateSet->save();
				$index++;
				$counter++;
			}
			$order ++;

		}

		$workout->createSets();

		Event::dispatch('createAWorkout', array(Auth::user(),$workout->name));

		return redirect()->route("traineeWorkouts")
			->with("message",__("messages.WorkoutCreated"))
			->with("permissions",$permissions)
			->with("total",Workouts::where("userId","=",$userId)->count());
	}


	public function autoSaveWorkout(Request $request){

		$workout = null;
		$userId = Auth::user()->id;
		$flag = false;
		if($request->has("id") and $request->get("id") !=  ""){
			$workout = Workouts::find($request->get("id"));
		} else {
			$workout = new Workouts();
			$flag = true;
		}
		if($workout == null){
			$workout = new Workouts();
			$flag = true;
		}

		$workout->name = $request->get("workoutName");

		$workout->sale = 0;
		$workout->availability = "private";
		$workout->shares = 0;
		$workout->views = 0;
		$workout->version = Config::get("constants.version");
		$workout->timesPerformed = 0;
		$workout->userId = $userId;
		$workout->authorId = $userId;
		$workout->groupId = (Auth::user()->group) ? Auth::user()->group->id : null;
		if($workout->status == "") $workout->status = "Draft";
		$workout->exercises = $request->get("exercises");
		$workout->exerciseGroup = $request->get("exerciseGroup");
		$workout->exerciseGroupRest = $request->get("exerciseGroupsRest");
		$workout->save();

		if($flag){
			$workout->master = $workout->id;
			$workout->save();
		}
	}

	public function duplicateWorkout(Request $request){

		$workoutId = $request->get("workoutId");
		$workout = Workouts::find($workoutId);
		if($workout){
			$workoutNew = $workout->replicate();
			$workoutNew->name = $request->get("name");
			$workoutNew->save();
			$workoutNew->resetWorkout();

			$workoutNew->duplicateTemplateFrom($workout);

			$workoutNew->createSets();

			Event::dispatch('duplicateWorkout', array(Auth::user(),$workout->name));
            $username = strtolower(Auth::user()->firstName.Auth::user()->lastName);
			return redirect()->route("trainerWorkouts",['userName' => $username])->with("message",__("messages.WorkoutHasBeenDuplicated"));

		} else {

			return redirect()->route("trainerWorkouts")->withError(__("messages.WorkoutCannotBeDuplicated"));
		}
	}

	public function createNewWorkoutAddEditTrainer(Request $request){
        try {
            DB::beginTransaction();
            //DEFAULTS
            $DEFAULT_REST = 0;
            $DEFAULT_REPS = array("12","12","12");
            $DEFAULT_WEIGHTS = array("","","");
            $DEFAULT_ROUNDS = 1;
            $DEFAULT_RESTBETWEENROUNDS = 60;
            $DEFAULT_CIRCUIT_STYLE = "rounds";
            $DEFAULT_CIRCUIT_INTERVALS = 1;
            $DEFAULT_CIRCUIT_AMRAP = 1;
            $DEFAULT_CIRCUIT_EMOM = 1;

            $flag = false;

            $client = $request->get("clientId");
            $userId = Auth::user()->id;
            $permissions = null;
            if($request->has("userId")){
                $permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"));
                if($permissions["view"]){
                    $userId = $request->get("userId");
                }
            } else {
                $permissions = Helper::checkPremissions(Auth::user()->id,null);
            }
            $workout = null;
            if($request->has("id") and $request->get("id") !=  ""){
                $workout = Workouts::find($request->get("id"));
                $workout->deleteWorkoutContents();
            } else {

                $workout = new Workouts();
                $workout->version = Config::get("constants.version");
                $flag = true;
                $workout->sale = 0;
                $workout->availability = "private";
                $workout->shares = 0;
                $workout->groupId = (Auth::user()->group) ? Auth::user()->group->id : null;
                $workout->views = 0;
                $workout->timesPerformed = 0;
                $workout->userId = $userId;
                $workout->authorId = $userId;
            }
            $workout->name = $request->get("workoutName");
            //$workout->price = $workoutDetails["price"];


            $workout->notes = $request->get("notes");

            $workout->status = "Released";
            $workout->exercises = $request->get("exercises");
            $workout->exerciseGroup = $request->get("exerciseGroup");
            $workout->exerciseGroupRest = $request->get("exerciseGroupsRest");


            // $tags = json_decode($request->get("tags"),true);
            // $tagOutput = array();
            // foreach($tags as $tag){
            // 	if(!array_key_exists($tag,$tagOutput)) array_push($tagOutput,$tag);
            // 	if(Tags::where("name",$tag)->where("userId",Auth::user()->id)->count() == 0){
            // 		$tagNew = new Tags;
            // 		$tagNew->name = $tag;
            // 		$tagNew->userId = Auth::user()->id;
            // 		$tagNew->type = "tag";
            // 		$tagNew->save();
            // 	}
            // }

            //$workout->tags = implode(",",$tagOutput);
            $workout->save();

            if($flag){
                $workout->master = $workout->id;
                $workout->save();
            }


            $groups = json_decode($request->get("exerciseGroup"),true);
            $groupsRest = json_decode($request->get("exerciseGroupsRest"),true);



            $groupNumberCounter = 0;
            $order = 0;
            // Log::error("======================================================================================================");
            // Log::error(Auth::user()->getCompleteName());
            // Log::error($workout->name);
            // Log::error($workout->exercises);
            // Log::error($workout->exerciseGroup);
            // Log::error($workout->exerciseGroupRest);
            // Log::error("======================================================================================================");
            $groupCounter = 0;
            foreach($groups as $group){
                $groupObject = new WorkoutsGroups();
                $groupObject->groupNumber = $groupNumberCounter;
                $groupObject->workoutId = $workout->id;

                if((count($group) > 1 and is_array($groupsRest))  or (array_key_exists($groupNumberCounter, $groupsRest) and is_array($groupsRest[$groupNumberCounter]) and array_key_exists("type", $groupsRest[$groupNumberCounter]) and $groupsRest[$groupNumberCounter]["type"] == "circuit")){

                    $groupObject->type = "circuit";

                    $groupObject->circuitType = (is_array($groupsRest) and array_key_exists($groupNumberCounter,$groupsRest) and is_array($groupsRest[$groupNumberCounter]) and array_key_exists("circuitStyle", $groupsRest[$groupNumberCounter])) ? $groupsRest[$groupNumberCounter]["circuitStyle"] : $DEFAULT_CIRCUIT_STYLE;
                    $circuitStyle = (is_array($groupsRest) and array_key_exists($groupNumberCounter,$groupsRest) and is_array($groupsRest[$groupNumberCounter]) and array_key_exists("circuitStyle", $groupsRest[$groupNumberCounter])) ? $groupsRest[$groupNumberCounter]["circuitStyle"] : $DEFAULT_CIRCUIT_STYLE;

                    if($circuitStyle == "amrap"){
                        //IF THE CIRCUIT IS AMRAP
                        if (is_array($groupsRest) && array_key_exists($groupNumberCounter, $groupsRest) && is_array($groupsRest[$groupNumberCounter])) {
                            $currentGroup = $groupsRest[$groupNumberCounter]??[];
                            $groupObject->maxTime = (isset($currentGroup['circuitMaxTime']) && !empty($currentGroup['circuitMaxTime']) && is_numeric($currentGroup['circuitMaxTime'])) ? $currentGroup['circuitMaxTime'] : $DEFAULT_CIRCUIT_AMRAP;
                            $groupObject->rest = (isset($currentGroup['circuitRest']) && !empty($currentGroup['circuitRest']) && is_numeric($currentGroup['circuitRest'])) ? $currentGroup['circuitRest'] : $DEFAULT_RESTBETWEENROUNDS;
                            $groupObject->restBetweenCircuitExercises = (isset($currentGroup['restBetweenCircuitExercises']) && !empty($currentGroup['restBetweenCircuitExercises']) && is_array($currentGroup['restBetweenCircuitExercises'])) ? serialize($currentGroup['restBetweenCircuitExercises']) : serialize([]);
                        } else {
                            $groupObject->maxTime = $DEFAULT_CIRCUIT_AMRAP;
                            $groupObject->rest = $DEFAULT_RESTBETWEENROUNDS;
                            $groupObject->restBetweenCircuitExercises = serialize([]);
                        }
                    } else if($circuitStyle == "emom"){
                        //IF THE CIRCUIT IS EMOM
                        if (is_array($groupsRest) && array_key_exists($groupNumberCounter, $groupsRest) && is_array($groupsRest[$groupNumberCounter])) {
                            $currentGroup = $groupsRest[$groupNumberCounter];
                            $groupObject->emom = (isset($currentGroup['circuitEmom']) && !empty($currentGroup['circuitEmom']) && is_numeric($currentGroup['circuitEmom'])) ? $currentGroup['circuitEmom'] : $DEFAULT_CIRCUIT_EMOM;
                            $groupObject->rest = (isset($currentGroup['circuitRest']) && !empty($currentGroup['circuitRest']) && is_numeric($currentGroup['circuitRest'])) ? $currentGroup['circuitRest'] : 0;
                            $groupObject->restBetweenCircuitExercises = (isset($currentGroup['restBetweenCircuitExercises']) && !empty($currentGroup['restBetweenCircuitExercises']) && is_array($currentGroup['restBetweenCircuitExercises'])) ? serialize($currentGroup['restBetweenCircuitExercises']) : serialize([]);
                        } else {
                            $groupObject->emom = $DEFAULT_CIRCUIT_EMOM;
                            $groupObject->rest = $DEFAULT_RESTBETWEENROUNDS;
                            $groupObject->restBetweenCircuitExercises = serialize([]);
                        }

                    } else {
                        //IF THE CIRCUIT IS INTERVALS
                        if (is_array($groupsRest) && array_key_exists($groupNumberCounter, $groupsRest) && is_array($groupsRest[$groupNumberCounter])) {
                            $currentGroup = $groupsRest[$groupNumberCounter];
                            $groupObject->intervals = (isset($currentGroup['circuitRound']) && !empty($currentGroup['circuitRound']) && is_numeric($currentGroup['circuitRound'])) ? $currentGroup['circuitRound'] : $DEFAULT_CIRCUIT_INTERVALS;
                            $groupObject->rest = (isset($currentGroup['circuitRest']) && !empty($currentGroup['circuitRest']) && is_numeric($currentGroup['circuitRest'])) ? $currentGroup['circuitRest'] : 0;
                            $groupObject->restBetweenCircuitExercises = (isset($currentGroup['restBetweenCircuitExercises']) && !empty($currentGroup['restBetweenCircuitExercises']) && is_array($currentGroup['restBetweenCircuitExercises'])) ? serialize($currentGroup['restBetweenCircuitExercises']) : serialize([]);
                        } else {
                            $groupObject->intervals = $DEFAULT_ROUNDS;
                            $groupObject->rest = $DEFAULT_RESTBETWEENROUNDS;
                            $groupObject->restBetweenCircuitExercises = serialize([]);
                        }
                    }
                } else {
                    $groupObject->type = "regular";
                }

                if (is_array($groupsRest) && array_key_exists($groupNumberCounter, $groupsRest) && is_array($groupsRest[$groupNumberCounter])) {
                    $groupObject->restAfter = (isset($groupsRest[$groupNumberCounter]['restTime']) && !empty($groupsRest[$groupNumberCounter]['restTime']) && is_numeric($groupsRest[$groupNumberCounter]['restTime'])) ? $groupsRest[$groupNumberCounter]['restTime'] : 0;
                } else {
                    $groupObject->restAfter = 0;
                }
                $groupObject->save();



                foreach($group as $exercise){

                    //Log::error("=========================". $exercise["id"]."==========================");
                    //Log::error(print_r($exercise,true));
                    $exx = Exercises::find($exercise["exercise"]["id"]);
                    if($exx){
                        $exx->used = $exx->used+1;
                        $exx->save();
                    }

                    $workoutExercise = new WorkoutsExercises();

                    $workoutExercise->workoutId = $workout->id;
                    $workoutExercise->exerciseId = $exercise["exercise"]["id"]??0;
                    $workoutExercise->equipmentId = $exercise["exercise"]["equipmentId"]??0;
                    $workoutExercise->order = $order;
                    $workoutExercise->notes = $exercise["notes"]??null;
                    $workoutExercise->tempo1 = (isset($exercise["tempo1"]) && !empty($exercise["tempo1"]) && is_numeric($exercise["tempo1"])) ? (int)$exercise["tempo1"] : null;
                    $workoutExercise->tempo2 = (isset($exercise["tempo2"]) && !empty($exercise["tempo2"]) && is_numeric($exercise["tempo2"])) ? (int)$exercise["tempo2"] : null;
                    $workoutExercise->tempo3 = (isset($exercise["tempo3"]) && !empty($exercise["tempo3"]) && is_numeric($exercise["tempo3"])) ? (int)$exercise["tempo3"] : null;
                    $workoutExercise->tempo4 = (isset($exercise["tempo4"]) && !empty($exercise["tempo4"]) && is_numeric($exercise["tempo4"])) ? (int)$exercise["tempo4"] : null;
                    $workoutExercise->groupId = $groupObject->id;
                    $workoutExercise->metric = (isset($exercise["repType"]) && !empty($exercise["repType"])) ? $exercise["repType"] : "";
                    if(isset($exercise["exercise"]["bodygroupId"]) && $exercise["exercise"]["bodygroupId"] == 18){
                        if(isset($exercise["times"]) && !empty($exercise["times"]) && is_array($exercise["times"])){
                            $workoutExercise->sets = count($exercise["times"]);
                        } else {
                            $workoutExercise->sets = 1;
                            $exercise["times"] = array(array("dist"=>"","time"=>"","speedbpm"=>"","bpm"=>""));
                        }
                    } else {
                        $workoutExercise->sets = count($exercise["repArray"]??[]);
                    }

                    $typeEx = "";
                    if(isset($exercise["exercise"]["bodygroupId"]) && $exercise["exercise"]["bodygroupId"] == 18){
                        $typeEx = "intervals";
                    } else {
                        if(isset($exercise["type"]) && !empty($exercise["type"])){
                            $typeEx = $exercise["type"];
                        } else {
                            $typeEx = "rep";
                        }
                    }

                    $workoutExercise->units = ucfirst((array_key_exists("metric", $exercise)) ? $exercise["metric"] : "imperial");
                    $workoutExercise->save();



                    $counter = 1;
                    $index = 0;

                    if($exercise["exercise"]["bodygroupId"] == 18){
                        $metricVisual = "";
                        $isIntervalsEmpty = (isset($exercise['times']) && count($exercise['times']) === 0);
                        $entries = $isIntervalsEmpty ? $exercise['intervals'] : $exercise['times'];

                        foreach ($entries as $x => $entry) {
                            $templateSet = new TemplateSets();
                            $templateSet->number = $counter;
                            $templateSet->exerciseId = $exercise['exercise']['id'];
                            $templateSet->workoutsExercisesId = $workoutExercise->id;
                            $templateSet->type = "cardio";

                            if ($isIntervalsEmpty) {
                                // Handle intervals
                                $templateSet->distance = (isset($entry['dist']) && !empty($entry['dist']) && is_numeric($entry['dist'])) ? $entry['dist'] : null;
                                $templateSet->time = (isset($entry['time']) && !empty($entry['time']) && is_numeric($entry['time'])) ? $entry['time'] : null;
                                $templateSet->speed = (isset($entry['speed']) && !empty($entry['speed']) && is_numeric($entry['speed'])) ? $entry['speed'] : null;
                                $templateSet->bpm = (isset($entry['bpm']) && !empty($entry['bpm']) && is_numeric($entry['bpm'])) ? $entry['bpm'] : null;
                            } else {
                                // Handle times
                                $templateSet->time = (isset($exercise['times'][$x]) && !empty($exercise['times'][$x]) && is_numeric($exercise['times'][$x])) ? $exercise['times'][$x] : null;
                                $templateSet->bpm = (isset($exercise['hrs'][$x]) && !empty($exercise['hrs'][$x]) && is_numeric($exercise['hrs'][$x])) ? $exercise['hrs'][$x] : null;
                                $templateSet->distance = (isset($exercise['distances'][$x]) && !empty($exercise['distances'][$x]) && is_numeric($exercise['distances'][$x])) ? $exercise['distances'][$x] : null;
                                $templateSet->speed = (isset($exercise['speeds'][$x]) && !empty($exercise['speeds'][$x]) && is_numeric($exercise['speeds'][$x])) ? $exercise['speeds'][$x] : null;
                            }

                            // Handle rest
                            if (isset($exercise['restBetweenSets']) && is_array($exercise['restBetweenSets']) && isset($exercise['restBetweenSets'][$x]) && !empty($exercise['restBetweenSets'][$x])) {
                                $templateSet->rest = $exercise['restBetweenSets'][$x];
                            } else {
                                $templateSet->rest = null;
                            }

                            // Handle metric type
                            $templateSet->metric = (isset($exercise['repsType']) && is_array($exercise['repsType']) && isset($exercise['repsType'][$x]) && !empty($exercise['repsType'][$x])) ? $exercise['repsType'][$x] : ($isIntervalsEmpty ? $typeEx : null);

                            if (isset($exercise['repsType']) && isset($exercise['repsType'][$x]) && $exercise['repsType'][$x] == "max") {
                                $templateSet->bpm = "max";
                            }

                            // Set remaining properties
                            $templateSet->workoutId = $workout->id;
                            $templateSet->units = $workoutExercise->units;
                            $templateSet->save();

                            $index++;
                            $counter++;
                        }

                        // Determine metric visualization
                        $uniqueMetrics = array_unique($exercise['repsType']);
                        $workoutExercise->metricVisual = (count($uniqueMetrics) > 1) ? "Exercise Mode" : reset($uniqueMetrics);
                        $workoutExercise->save();
                    } else {
                        if(isset($exercise["repArray"]) && !empty($exercise["repArray"]) && is_array($exercise["repArray"])){
                            $metricVisual = "";
                            foreach($exercise["repArray"] as $rep){

                                $templateSet = new TemplateSets();
                                $templateSet->number = $counter;
                                $templateSet->exerciseId = $exercise["exercise"]["id"]??0;
                                $templateSet->workoutsExercisesId = $workoutExercise->id;
                                $type = "regular";

                                // Set reps
                                $templateSet->reps = $rep;

                                // Handle weights and weightKG
                                if (isset($exercise['weights']) && isset($exercise['weights'][$index]) && !empty($exercise['weights'][$index]) && is_numeric($exercise['weights'][$index])) {
                                    $templateSet->weight = $exercise['weights'][$index];
                                } else {
                                    $templateSet->weight = 0;
                                }

                                $templateSet->weightKG = (isset($templateSet->weight) && !empty($templateSet->weight)) ? Helper::formatWeight($templateSet->weight / 2.2) : 0;

                                // Set rest time
                                $templateSet->rest = (isset($exercise['rest']) && !empty($exercise['rest']) && is_numeric($exercise['rest'])) ? $exercise['rest'] : $DEFAULT_REST;

                                // Handle metric and time
                                if (isset($exercise['repsType']) && isset($exercise['repsType'][$index]) && !empty($exercise['repsType'][$index])) {
                                    $templateSet->metric = $exercise['repsType'][$index];

                                    if ($templateSet->metric == "time") {
                                        $templateSet->time = $rep;
                                    }
                                } else {
                                    $templateSet->metric = $typeEx;
                                }

                                // Set additional properties
                                $templateSet->type = $type;
                                $templateSet->workoutId = $workout->id;
                                $templateSet->units = $workoutExercise->units;

                                // Handle rest between sets
                                if (isset($exercise['restBetweenSets']) &&  is_array($exercise['restBetweenSets']) && isset($exercise['restBetweenSets'][$index]) && !empty($exercise['restBetweenSets'][$index])){
                                    $templateSet->rest = $exercise['restBetweenSets'][$index];
                                } else {
                                    $templateSet->rest = null;
                                }

                                // Save the template set
                                $templateSet->save();

                                // Manage the counter and index
                                $index++;
                                $counter++;

                                // Determine visual representation for the metric
                                $flag = true;
                                $metricVisual = "";
                                foreach ($exercise['repsType']??[] as $me) {
                                    if ($flag && $me != $metricVisual && $metricVisual != "") {
                                        $flag = false;
                                    } else {
                                        $metricVisual = $me;
                                    }
                                }

                                if (!$flag) {
                                    $metricVisual = "Exercise Mode";
                                }

                                // Save workout exercise metric visual
                                $workoutExercise->metricVisual = $metricVisual;
                                $workoutExercise->save();
                            }
                        }
                    }
                }
                $groupNumberCounter++;
                $order = 0;
            }

            //Log::error("|".$workout->groupsRest."|");
            if($workout->exerciseGroupRest == ""){
                //if($workout->exerciseGroupRest == ""){
                $controller = new SystemController();
                $controller->migrateWorkouts($workout->id);
            }


            $workout->createSets();

            Event::dispatch('createAWorkout', array(Auth::user(),$workout->name));

            Session::forget("workoutIdInProgress");

            //if($workoutDetails["user"] != ""){
            //	Notifications::insertDynamicNotification("WorkoutAddedByTrainer",$workoutDetails["user"],Auth::user()->id,array("firstName" => Auth::user()->firstName,"lastName" => Auth::user()->lastName ,"workoutName" => $workout->name,"workoutLink" => $workout->getURL()),true);
            //}

            //exit(0);



            $membershipCheck = Memberships::checkMembership(Auth::user());
            if( $membershipCheck == ""){
                $workout->status = "Released";
                $workout->save();
            } else {
                if ($request->filled('is_webview_request')){
                    return redirect()->route("webview.create-trainer-workout-success");
                }else{
                    $workout->status = "Draft";
                    $workout->save();
                    return redirect()->route("trainerWorkouts")
                        ->withError($membershipCheck)
                        ->with("permissions",$permissions);
                }
            }


            if($client != 0 and $client != ""){
                Sharings::shareWorkout(Auth::user()->id,$client,$workout,"Workout",null,true,true,true,true,true,true);
                $user = Users::find($client);
                $client = Clients::where("userId",$user->id)->where("trainerId",Auth::user()->id)->first();
                //Workouts::AddWorkoutToUser($workout->id,$client,null,true);
                $name = ($user) ? $user->getCompleteName() : "";
                if($client){
                    return redirect()->to("/Client/".$client->id."/".Helper::formatURLString($name))
                        ->with("message",__("messages.CreatedAndShared"))
                        ->with("permissions",$permissions);
                } else {
                    return redirect()->route("trainerClients")
                        ->with("message",__("messages.CreatedAndShared"))
                        ->with("permissions",$permissions);
                }
            }


            DB::commit();
            if ($request->filled('is_webview_request')){
                Auth::logout();
                return redirect()->route("webview.create-trainer-workout-success");
            }else{
                return redirect()->to($workout->getURL())
                    ->with("message",__("messages.WorkoutCreated"))
                    ->with("permissions",$permissions);
            }
//
//            return redirect()->route("trainerWorkouts")
//                ->with("message",__("messages.WorkoutCreated"))
//                ->with("permissions",$permissions)
//                ->with("total",Workouts::where("userId","=",$userId)->count());
        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->withErrors("Something went wrong !");
//            return $this::responseJsonError();
        }
	}

	public function assignWorkoutToClient($client,$workoutId,Request $request){
		$permissions = null;
		if($request->has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"));
			if($permissions["view"]){
				$userId = $request->get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		$user = null;
		//$clientObject = Clients::find($client);
		$workoutObject = Workouts::find($workoutId);
		$user = $client;
		Sharings::shareWorkout(Auth::user()->id,$user,$workoutObject,"Workout",null,true,true,true,true,true,true);
		$user = Users::find($user);
		//Workouts::AddWorkoutToUser($workout->id,$client,null,true);
		$name = ($user) ? $user->getCompleteName() : "";

		$clientObject = Clients::where("userId",$user->id)->where("trainerId",Auth::user()->id)->first();

		if($user){
				return redirect()->to("/Client/".$clientObject->id."/".Helper::formatURLString($name))
				->with("message",__("messages.CreatedAndShared"))
				->with("permissions",$permissions);
		} else {
				return redirect()->route("trainerClients")
				->with("message",__("messages.CreatedAndShared"))
				->with("permissions",$permissions);
		}
	}


	/**
	 * Show the form for creating a new resource.
	 * GET /objectives/create
	 *
	 * @return Response
	 */

	public function AddEdit(Request $request)
	{
		if($request->has("id") and $request->get("id") != ""){
			return $this->update($request->get("id"));
		} else {
			return $this->create();
		}
	}


	public function create(Request $request)
	{
		$validation = Workouts::validate($request->all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$objectives = new Workouts;
			$objectives->objective = $request->get("objective");
			$objectives->measureable = $request->get("measureable");
			$objectives->recordDate = $request->get("dateRecord");
			$objectives->userId = Auth::user()->id;
			$objectives->save();
			Feeds::insertFeed("NewObjective",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			return $this::responseJson(__("messages.ObjectiveAdded"));
		}
	}

	public function store()
	{
		//
	}

	public function show($id)
	{
		//
	}

	public function edit($id)
	{
		//
	}


	public function update($id)
	{
		//
	}


	public function destroy($id)
	{
		$obj = Workouts::find($id);
		if(!$obj) return $this::responseJsonError(__("messages.NotFound"));

		if($this->checkPermissions($obj->userId,Auth::user()->id)){
//			Feeds::insertFeed("DeletedWorkout",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			$obj->delete();

			Event::dispatch('deleteAWorkout', array(Auth::user()));

			return $this::responseJson(__("messages.WorkoutDeleted"));
		} else {
			return $this::responseJsonError(__("messages.Permissions"));
		}


	}


	public function archiveWorkout($id)
	{
		$obj = Workouts::find($id);
		if(!$obj) return $this::responseJsonError(__("messages.NotFound"));

		$obj->archive();

		Event::dispatch('archiveWorkout', array(Auth::user(),$obj->name));

		return $this::responseJson(__("messages.WorkoutArchived"));



	}

	public function unarchiveWorkout($id)
	{
		$obj = Workouts::find($id);
		if(!$obj) return $this::responseJsonError(__("messages.NotFound"));


		$obj->unArchive();

		Event::dispatch('unArchiveWorkout', array(Auth::user(),$obj->name));

		return $this::responseJson(__("messages.WorkoutUnArchived"));



	}

	public function deleteWorkout($id)
	{
		$obj = Workouts::find($id);

        $username = strtolower(Auth::user()->firstName.Auth::user()->lastName);
		if(!$obj) return redirect()->route("trainerWorkouts",['userName' => $username])->withError(__("messages.NotFound"));

		if($this->checkPermissions($obj->userId,Auth::user()->id)){
//			Feeds::insertFeed("DeletedWorkout",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			$obj->delete();

			Event::dispatch('deleteAWorkout', array(Auth::user()));
			return redirect()->route("trainerWorkouts",['userName' => $username])->with("message", __("messages.WorkoutDeleted"));
		} else {
			return redirect()->route("trainerWorkouts",['userName' => $username])->withError(__("messages.Permissions"));
		}


	}

	//=======================================================================================================================
	// API
	//======================================================================================================================


	//
	//	NIC CHANGES
	//

	public function API_IOS_CreateWorkout(){
        $user = Helper::generateUserSlug(Auth::user()->id);
        $response = [
            'create_workout_url' => route('webview.create-trainer-workout',['user' => $user]),
            'success_url' => route('webview.create-trainer-workout-success'),
            'failed_url' => route('webview.create-trainer-workout-failed'),
        ];

        $data["data"] = $response;
        $data["status"] = "ok";
        $data["message"] = "";
        return $this::responseJson($data);
	}

    public function API_ShareWorkout(Request $request)
    {
        $result = Helper::APIERROR();
        $validation = Validator::make($request->all(),[
            'email' => 'required',
            'workout_id' => 'required',
            'send_copy' => 'sometimes|boolean',
            'send_attachments' => 'sometimes|boolean',
            'subscribe_to_workout' => 'sometimes|boolean',
            'lock_workout' => 'sometimes|boolean',
        ]);
        if($validation->fails()){
            $result["message"] = $validation->messages()->first();
            return $this::responseJson($result,400);
        }

        $workoutId = $request->get("workout_id");
        $workout = Workouts::find($workoutId);
        if($workout){
            if($workout->canThisWorkoutBeShared(Auth::user())){
                $user = null;
                $stringOfUsersToShare = $request->get("email");
                $arrayOfUsersToShare = explode(",",$stringOfUsersToShare);
                foreach($arrayOfUsersToShare as $email){
                    $email = trim($email);
                    if($email != ""){
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            if(Users::where("email",$email)->count() > 0){
                                $user = Users::where("email",$email)->first();
                            } else {
                                $user = new Users();
                                $user->userType = "Trainee";
                                $user->email = $email;
                                $user->save();
                            }

                            Auth::user()->addClient($user);
                            $invite = Auth::user()->sendInvite($user);

                            $copyMe = false; $copyView = false; $copyPrint = false; $lock = false; $subscribe = false;
                            if($request->filled("subscribe_to_workout") && $request->get("subscribe_to_workout") == true) $subscribe = true;
                            if($request->filled("send_copy") && $request->get("send_copy") == true) $copyMe = true;
                            if($request->filled("send_attachments") && $request->get("send_attachments") == true) $copyView = true;
                            if($request->filled("lock_workout") && $request->get("lock_workout") == true) $lock = true;

                            Event::dispatch('shareAWorkout', array(Auth::user(),$user->id));
                            $comments = $request->get("comments");

                            Sharings::shareWorkout(Auth::user()->id,$user->id,$workout,"Workout",$comments,$invite,$copyMe,$copyView,$copyPrint,$subscribe,$lock);
                        } else {
                            $result["message"] = __("messages.WrongEmailAddress");
                            return $this->responseJsonError($result);
                        }
                    }
                }
            } else {
                $result["message"] = __("messages.WorkoutCannotBeShared");
                return $this->responseJsonError($result);
            }
        }else{
            $result["message"] = __("messages.WorkoutShared");
            return $this->responseJsonError($result);
        }
        $result = Helper::APIOK();
        $result['message'] = __("messages.WorkoutShared");
        return $this->responseJson($result);
    }

	public function API_Workouts_Basic(Request $request){
		$userId = Auth::user()->id;
		$permissions = null;
		if($request->has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"));
			if($permissions["view"]){
				$userId = $request->get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		// if($request->has("pageSize")) $this->pageSize = $request->get("pageSize") + $this->pageSize;


		$workouts = Workouts::where("userId",$userId)->orderBy("created_at","Desc")->get();

		$returnWorkouts = array();

		foreach($workouts as $workout){
			$workoutAPI = array();
			$workoutAPI["workout"] = $workout;
			$workoutAPI["images"] = $workout->getExercisesImagesWidget();
			array_push($returnWorkouts,$workoutAPI);
		}


		$data = array();
		$data["data"] = $returnWorkouts;
		$data["permissions"] = $permissions;
		$data["total"] = Workouts::where("userId","=",$userId)->count();
		$data["status"] = "ok";
		$data["message"] = "";

		return $this->responseJson($data);
	}


	public function APIIndex(Request $request){
		$userId = Auth::user()->id;
		$permissions = null;
		if($request->has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"));
			if($permissions["view"]){
				$userId = $request->get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		if($request->has("pageSize")) $this->pageSize = $request->get("pageSize") + $this->pageSize;


		$workouts = Workouts::where("userId",$userId)
            ->with(['exercises.exercises' => function($query) {
                $query->select("id", "bodygroupId", "userId",  "name", "description", "image as image_url", "image2 as image2_url", "thumb as thumb_url", "thumb2 as thumb2_url", "video as video_url", "youtube", "type", "equipment", "deleted_at", "created_at", "updated_at", "authorId", "bodyGroupSec", "views", "used", "nameEngine", "equipmentRequired", "exercisesTypesId", "secondsPerRep");
            }])
            ->with('exercises.templateSets')
            ->with(['exercises.workout_sets' => function($query) {
                $query->with('workoutsExercises');
            }])
            ->orderBy("created_at","Desc")
            ->take($this->pageSize)
            ->get();

        $returnWorkouts = array();
        foreach ($workouts as $workout) {
            $workoutAPI = array();
            $workout_images = array_filter($workout->getExercisesImagesWidget());
            $workout = $workout->toArray();
            $workout_data = $workout;
            $workout_data['exercises'] = "[]";
            $workoutAPI["workout"] = $workout_data;
            $workoutAPI["images"] = $workout_images;
            $workoutAPI["exercises"] = [];
            if (isset($workout['exercises']) && !empty($workout['exercises'])) {
                $exercises = $workout['exercises'];
                foreach($exercises as $exercise){
                    $ex = array();
                    $template_sets_data = $exercise['template_sets'];
                    $sets_data = $exercise['workout_sets'];
                    unset($exercise['template_sets']);
                    $ex["exercise"] = $exercise;
                    $ex["sets"] = array();
                    $ex["templateSets"] = $template_sets_data;
                    foreach($sets_data as $set){
                        array_push($ex["sets"],$set);
                    }
                    unset($ex["exercise"]['workout_sets']);
                    array_push($workoutAPI["exercises"],$ex);
                }
            }

            array_push($returnWorkouts,$workoutAPI);
        }




//        $workouts = Workouts::where("userId",$userId)->orderBy("created_at","Desc")->take($this->pageSize)->get();
//		$returnWorkouts = array();
//		foreach($workouts as $workout){
//			$workoutAPI = array();
//			$workoutAPI["workout"] = $workout;
//			$workoutAPI["images"] = array_filter($workout->getExercisesImagesWidget());
//			$exercises = $workout->getExercises()->get();
//			$workoutAPI["exercises"] = array();
//			foreach($exercises as $exercise){
//				$ex = array();
//				$ex["exercise"] = $exercise;
//				$ex["sets"] = array();
//				$ex["templateSets"] = TemplateSets::where("workoutsExercisesId",$exercise->id)->get();
//				$sets = $workout->getSets($exercise->id);
//				foreach($sets as $set){
//					array_push($ex["sets"],$set);
//				}
//				array_push($workoutAPI["exercises"],$ex);
//			}
//			array_push($returnWorkouts,$workoutAPI);
//		}


		$data = array();
		$data["data"] = $returnWorkouts;
		$data["permissions"] = $permissions;
		$data["total"] = Workouts::where("userId","=",$userId)->count();
		$data["status"] = "ok";
		$data["message"] = "";

		return $this->responseJson($data);
	}


	public function APIsaveSingleSet(Request $request){
		$set = Sets::find($request->get("id"));
		if($set){
			if($request->get("type") != "cardio"){
				$set->weight = $request->get("weight");
				$set->weightKG = Helper::formatWeight($set->weight/2.2);
				$set->reps = $request->get("reps");
				$set->completed = 1;
			} else {
				$set->distance = $request->get("distance");
				$set->time = $request->get("time");
				$set->speed = $request->get("speed");
				$set->completed = 1;
			}
			$set->save();

			$workoutExercise = WorkoutsExercises::find($set->workoutsExercisesId);
			$workoutExercise->updated_at = date("Y-m-d H:i:s");
			$workoutExercise->save();
			$workout = Workouts::find($workoutExercise->workoutId);
			$workout->markAsCompleted();
			if($set->last == 1){
				$workout = Workouts::find($set->workoutId);
					if($workout){
						$workout->createNewSetsExercise($set->workoutsExercisesId);
					}
				$result = Helper::APIOK();
				$result["message"] = __("messages.NewSetsCreatedAndSaved");
				return $result;

			}
			$result = Helper::APIOK();
			$result["message"] = __("messages.SetSaved");
			return $result;
		} else {
			$result = Helper::APIERROR();
			$result["message"] = __("messages.SetNotValid");
			return $result;
		}
	}

	public function APIcompleteSet(Request $request){
		$set = Sets::find($request->get("id"));

		if($set){

			$set->completed = 1;

			$set->save();

			$workoutExercise = WorkoutsExercises::find($set->workoutsExercisesId);
			$workoutExercise->updated_at = date("Y-m-d H:i:s");
			$workoutExercise->save();

			if($set->last == 1){
				$workout = Workouts::find($set->workoutId);
					if($workout){
						$workout->createNewSetsExercise($set->workoutsExercisesId);
					}
				$result = Helper::APIOK();
				$result["message"] = __("messages.NewSetsCreatedAndSaved");
				return $result;

			}
			$workout = Workouts::find($workoutExercise->workoutId);
			$workout->markAsCompleted();
			$result = Helper::APIOK();
			$result["message"] = __("messages.SetSaved");
			return $result;
		} else {
			$result = Helper::APIERROR();
			$result["message"] = __("messages.SetNotValid");
			return $result;
		}
	}


	public function APIExerciseCompleted(Request $request){
		$user = Auth::user();
		$workoutsExercisesId = $request->get("workoutsExercisesId");
		$workoutExercise = WorkoutsExercises::find($workoutsExercisesId);
		//$sets = $request->get("sets");
		$sets = Sets::where("workoutsExercisesId",$workoutsExercisesId)->get();
		foreach($sets as $set){
			$setFetched = $set;
			if($setFetched){

				if(isset($set["weight"])) $setFetched->weight = $set["weight"];
				if(isset($set["weight"])) $setFetched->weightKG = Helper::formatWeight($set["weight"]/2.2);
				if(isset($set["reps"])) $setFetched->reps = $set["reps"];
				if(isset($set["rest"])) $setFetched->rest = $set["rest"];
				if(isset($set["speed"])) $setFetched->speed = $set["speed"];
				if(isset($set["time"])) $setFetched->time = $set["time"];
				if(isset($set["distance"])) $setFetched->distance = $set["distance"];
				//$set->hr = $set["hr"];
				$setFetched->save();

				$workoutExercise = WorkoutsExercises::find($setFetched->workoutsExercisesId);
				$workoutExercise->updated_at = date("Y-m-d H:i:s");
				$workoutExercise->save();

				if($setFetched->last == 1 and $setFetched->completed == 1){
					$workout = Workouts::find($request->get("workoutId"));
					if($workout){
						$workout->createNewSetsExercise($setFetched->workoutsExercisesId);
					}
				}

			} else {
				$result = Helper::APIERROR();
				$result["message"] = __("messages.NotFound");
				return $result;

			}
		}
		$sets = Sets::where("workoutsExercisesId",$workoutsExercisesId)->take(TemplateSets::where("workoutsExercisesId",$workoutsExercisesId)->count())->orderBy("id","Desc")->get();
		foreach($sets as $set){
			$set->completed = 1;
			$set->save();
		}
		$workout = Workouts::find($workoutExercise->workoutId);
		$workout->markAsCompleted();
		if($workout){
			$workout->createNewSetsExercise($workoutExercise->id);
		}

		if(!Notifications::checkIfTrainerNotifiedTodayWorkout($user->id,null)){
			$trainers = Clients::where("userId",$user)->distinct()->pluck("trainerId")->toArray();
			if(!empty($trainers)){
				foreach($trainers as $trainer){
					Notifications::insertDynamicNotification(__("messages.TraineeCompleted"),$trainer,$user->id,array(),false);
				}
			}
		}

		$result = Helper::APIOK();
		$result["message"] = __("messages.ExerciseCompleted");
		return $result;
	}

	public function APIworkoutCompleted(Request $request){
		$user = Auth::user();
		$workoutId = $request->get("workoutId");
		$workout = Workouts::find($workoutId);
		if($workout){
			$workoutExercises = WorkoutsExercises::where("workoutId",$workout->id)->get();
			foreach($workoutExercises as $workoutExercise){
				$sets = Sets::where("workoutsExercisesId",$workoutExercise->id)->take(TemplateSets::where("workoutsExercisesId",$workoutExercise->id)->count())->orderBy("id","Desc")->get();
				foreach($sets as $set){
					$set->completed = 1;
					$set->save();
				}
				$workout->createNewSetsExercise($workoutExercise->id);

			}

			$workout->markAsCompleted();

			$result = Helper::APIOK();
			$result["message"] = __("messages.WorkoutCompleted");
			return $result;
		}
	}

	public function APIsaveAllSets(Request $request){

		$sets = $request->get("sets");

		foreach($sets as $set){
			$setFetched = Sets::find($set["id"]);
			if($setFetched){
				if(array_key_exists("weight",$set)) $setFetched->weight = $set["weight"];
				if(array_key_exists("weight",$set)) $setFetched->weightKG = Helper::formatWeight($set["weight"]/2.2);
				if(array_key_exists("reps",$set)) $setFetched->reps = $set["reps"];
				if(array_key_exists("rest",$set)) $setFetched->rest = $set["rest"];

				if(array_key_exists("speed",$set)) $setFetched->speed = $set["speed"];
				if(array_key_exists("time",$set)) $setFetched->time = $set["time"];
				if(array_key_exists("distance",$set)) $setFetched->distance = $set["distance"];
				//$set->hr = $set["hr"];
				$setFetched->save();

				$workoutExercise = WorkoutsExercises::find($setFetched->workoutsExercisesId);
				$workoutExercise->updated_at = date("Y-m-d H:i:s");
				$workoutExercise->save();

				if($setFetched->last == 1 and $setFetched->completed == 1){
					$workout = Workouts::find($request->get("workoutId"));
					if($workout){
						$workout->createNewSetsExercise($setFetched->workoutsExercisesId);
					}
				}

			} else {
				$result = Helper::APIERROR();
				$result["message"] = __("messages.NotFound");
				return $result;
			}
		}
		$result = Helper::APIOK();
		$result["message"] = __("messages.AllSetsSaved");
		return $result;

	}


	// public function API_Workouts_Basic(){
	// 	$userId = Auth::user()->id;
	// 	$permissions = null;
	// 	if($request->has("userId")){
	// 		$permissions = Helper::checkPremissions(Auth::user()->id,$request->get("userId"));
	// 		if($permissions["view"]){
	// 			$userId = $request->get("userId");
	// 		}
	// 	} else {
	// 		$permissions = Helper::checkPremissions(Auth::user()->id,null);
	// 	}

	// 	// if($request->has("pageSize")) $this->pageSize = $request->get("pageSize") + $this->pageSize;


	// 	$workouts = Workouts::where("userId",$userId)->orderBy("created_at","Desc")->get();

	// 	$returnWorkouts = array();

	// 	foreach($workouts as $workout){
	// 		$workoutAPI = array();
	// 		$workoutAPI["workout"] = $workout;
	// 		$workoutAPI["images"] = $workout->getExercisesImagesWidget();
	// 		array_push($returnWorkouts,$workoutAPI);
	// 	}


	// 	$data = array();
	// 	$data["data"] = $returnWorkouts;
	// 	$data["permissions"] = $permissions;
	// 	$data["total"] = Workouts::where("userId","=",$userId)->count();
	// 	$data["status"] = "ok";
	// 	$data["message"] = "";

	// 	return $this->responseJson($data);
	// }


	public function API_Workout_Groups(Request $request){
		$workoutId = $request->get("id");
		$workout = Workouts::find($workoutId);
		$user = Auth::user();
		if($workout){
			if($workout->status == "Draft"){
				// Handle after
			} {
				$workout->incrementViews();

			}

			$groups = $workout->getGroups()->get();
			$returnGroups = array();

			$exercises = array();
			foreach ($groups as $group) {
				$groupExercises = $group->getExercises()->get();
				if(count($groupExercises) > 0){
					foreach ($groupExercises as $groupExercise) {
						$ex = array();
						$ex["exercise"] = $groupExercise;

						$ex["sets"] = array();
						$ex["templateSets"] = TemplateSets::where("workoutsExercisesId",$groupExercise->id)->get();
						$sets = $workout->getSets($groupExercise->id);
						foreach($sets as $set){
							array_push($ex["sets"],$set);
						}



						array_push($exercises,$ex);
					}
					array_push($returnGroups, $group);
				}
			}


			/*
			foreach($workouts as $workout){
			$workoutAPI = array();
			$workoutAPI["workout"] = $workout;
			$workoutAPI["images"] = $workout->getExercisesImagesWidget();
			$exercises = $workout->getExercises()->get();
			$workoutAPI["exercises"] = array();
			foreach($exercises as $exercise){
				$ex = array();
				$ex["exercise"] = $exercise;
				$ex["sets"] = array();
				$ex["templateSets"] = TemplateSets::where("workoutsExercisesId",$exercise->id)->get();
				$sets = $workout->getSets($exercise->id);
				foreach($sets as $set){
					array_push($ex["sets"],$set);
				}
				array_push($workoutAPI["exercises"],$ex);
			}
			array_push($returnWorkouts,$workoutAPI);
		}
			*/




			$data["groups"] = $returnGroups;
			$data["exercises"] = $exercises;
			$data["status"] = "ok";
			$data["message"] = "";

			return $data;
		} else {
			$data["status"] = "not ok";
			return $data;
		}
	}


}


