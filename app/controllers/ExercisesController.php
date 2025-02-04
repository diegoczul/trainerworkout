<?php

class ExercisesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /exercises
	 *
	 * @return Response
	 */

	public $pageSize = 40;
	public $searchSize = 40;
	public $pageSizeFull = 40;


	public function indexAdd()
	{

		$userId = Auth::user()->id;

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

        $tags = Tags::where("userId",Auth::user()->id)->get();
        
		return View::make(Helper::userTypeFolder(Auth::user()->userType).".addExercise")
			->with("bodygroups",Exercises::getBodyGroupsList())
			->with("equipments",Equipments::orderBy("name")->lists("name","id"))
			->with("tags",$tags)
			->with("bodyGroups",Bodygroups::select("id","name")->orderBy("name")->get())
			->with("equipments",EquipmentFs::select("id","name")->orderBy("name")->get())
			->with("exercisesTypes",Exercisestypes::select("id","name")->orderBy("name")->get())
			->with("exercisesTypes",Exercisestypes::select("id","name")->orderBy("name")->get())
			->with("total",Exercises::where("userId","=",$userId)->count());
	}

	public function editExercise($id)
	{

		$exercise = Exercises::find($id);
		$equipmentsSelected = ExercisesEquipments::where("exerciseId",$id)->where("type","required")->lists("equipmentId");
		$equipmentsSelectedOptional = ExercisesEquipments::where("exerciseId",$id)->where("type","optional")->lists("equipmentId");

		if($exercise){

		$userId = Auth::user()->id;

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

        $tags = Tags::where("userId",Auth::user()->id)->get();
        
		return View::make(Helper::userTypeFolder(Auth::user()->userType).".editExercise")
			->with("bodygroups",Exercises::getBodyGroupsList())
			->with("equipments",Equipments::orderBy("name")->lists("name","id"))
			->with("tags",$tags)
			->with("exercise",$exercise)
			->with("equipmentsSelected",$equipmentsSelected)
			->with("equipmentsSelectedOptional",$equipmentsSelectedOptional)
			->with("bodyGroups",Bodygroups::select("id","name")->orderBy("name")->get())
			->with("exercisesTypes",Exercisestypes::select("id","name")->orderBy("name")->get())
			->with("exercisesTypes",Exercisestypes::select("id","name")->orderBy("name")->get())
			->with("total",Exercises::where("userId","=",$userId)->count());


		} else {

			return Redirect::back()->withErrors(Lang::get("messages.Oops"));
		}
	}

	public function clearAttribute(){
		$id = Input::get("id");
		$attribute = Input::get("attribute");

		$exercise = Exercises::find($id);

		if($exercise){
			if($attribute == "image"){
				$exercise->removeFile("image");
				return $this::responseJson(Lang::get("content.Deleted"));
			}

			if($attribute == "image2"){
				$exercise->removeFile("image2");
				return $this::responseJson(Lang::get("content.Deleted"));
			}

			if($attribute == "image"){
				$exercise->removeFile("video");
				return $this::responseJson(Lang::get("content.Deleted"));
			}

		}
	}

	
	public function indexExercises()
	{
		$user = Auth::user();
		$userId = Auth::user()->id;
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
		}

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("trainee.exercises")
			->with("exercises",Exercises::where("userId","=",$userId)->orderBy('created_at', 'DESC')->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("user",$user)
			->with("total",Exercises::where("userId","=",$userId)->count());
	}

	public function addToFavorites(){
		$id = Input::get("id");
		$equipmentId = Input::get("equipmentId");

		$userId = Auth::user()->id;

		$action = "added";

		$ex = Exercises::find($id);

		$exercise = ExercisesUser::where("userId",$userId)->where("locale",App::getLocale())->where("exerciseId",$id)->where("equipmentId",$equipmentId)->first();

		if($id != "" and $id != 0 and $ex){
			if(!$exercise) { 
				$exercise = new ExercisesUser;
				$exercise->name = $ex->translate("en")->name;
				$exercise->locale = "en";
				$exercise->userId = $userId;
				$exercise->equipmentId = $equipmentId;
				$exercise->exerciseId = $id;
				if($exercise->favorite == 1){
					$exercise->favorite = null;
					$action = "removed";

				} else {
					$exercise->favorite = 1;
					$action = "added";
				}
				$exercise->save();

				$exercise = new ExercisesUser;
				$exercise->name = $ex->translate("fr")->name;
				$exercise->locale = "fr";
				$exercise->exerciseId = $id;
				$exercise->userId = $userId;
				$exercise->equipmentId = $equipmentId;
				if($exercise->favorite == 1){
					$exercise->favorite = null;
					$action = "removed";

				} else {
					$exercise->favorite = 1;
					$action = "added";
				}
				$exercise->save();
			} else {
				if($exercise->favorite == 1){
					$exercise->favorite = null;
					$action = "removed";

				} else {
					$exercise->favorite = 1;
					$action = "added";
				}
				$exercise->save();
			}	
		}

		if($action == "added") return $this::responseJson(Messages::showControlPanel("AddedToFavorites"));
		if($action == "removed") return $this::responseJson(Messages::showControlPanel("RemovedFromFavorites"));

	}

	//YURI'S FUNCTION
	public function indexAddInWorkout()
	{

		$userId = Auth::user()->id;

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

        
		return View::make("popups.addExerciseInWorkout")
			->with("bodygroups",Exercises::getBodyGroupsList())
			->with("equipments",Equipments::orderBy("name")->lists("name","id"))
			->with("total",Exercises::where("userId","=",$userId)->count());
	}


	public function indexExercisesTrainer()
	{

		$userId = Auth::user()->id;
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
		}

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("trainer.exercises")
			->with("exercises",Exercises::where("userId","=",$userId)->orderBy('created_at', 'DESC')->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("bodyGroups",Bodygroups::select("id","name")->orderBy("name")->get())
			->with("bodygroups",Bodygroups::orderBy("name")->lists("name","id"))
			->with("equipments",Equipments::select("id","name")->orderBy("name")->get())
			->with("equipmentsList",Equipments::select("id","name")->lists("name","id"))
			->with("exercisesTypes",Exercisestypes::select("id","name")->orderBy("name")->get())
			->with("total",Exercises::where("userId","=",$userId)->count());
	}

	public function index()
	{

		$userId = Auth::user()->id;
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
		}

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.base.exercises")
			->with("exercises",Exercises::where("userId","=",$userId)->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("total",Exercises::where("userId","=",$userId)->count());
	}

	public function managerExercises($search){
		//$userId = Auth::user()->id;

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("ControlPanel.manageExercises")
			->with("bodygroups",Exercises::getBodyGroupsList())
			->with("exercises",Exercises::searchExercises($search)->take(200)->get())
			->with("total",200);
	}



	public function searchExercise(){

		if(Input::has("pageSize")) $this->searchSize = Input::get("pageSize") + $this->searchSize;

		return $this->responseJson(array("data"=>Exercises::searchExercises(Input::get("search"), $this->searchSize,Input::get("filters")),"total"=>$this->searchSize+Input::get("pageSize")));

	}

	public function indexMail()
	{

		$userId = Auth::user()->id;

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.full.mail");
	
	}

	public function indexFull()
	{

		$userId = Auth::user()->id;
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
		}

		if(Input::get("search") == ""){
			$exercises = Exercises::where("userId","=",$userId)->orderBy('created_at', 'DESC')->take($this->pageSize)->get();
			$total = Exercises::where("userId","=",$userId)->count();
		} else {
			$exercises = Exercises::searchExercises(Input::get("search"),$this->searchSize,null,true);
			$total = $this->searchSize+Input::get("pageSize");
		}


		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.full.exercises")
			->with("permissions",$permissions)
			->with("exercises",$exercises)
			->with("total",$total);
	}

	public function indexFullTrainer()
	{

		$userId = Auth::user()->id;
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
		}

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.full.exercises")
			->with("permissions",$permissions)
			->with("exercises",Exercises::where("userId","=",$userId)->orderBy('created_at', 'DESC')->take($this->pageSize)->get())
			->with("total",Exercises::where("userId","=",$userId)->count());
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /exercises/create
	 *
	 * @return Response
	 */

	public function AddEdit()
	{
		if(Input::has("id") and Input::get("id") != ""){
			return $this->update(Input::get("id"));
		} else {
			return $this->create();
		}		
	}

	public function AddEditInWorkout()
	{
                	   
		if(Input::has("id") and Input::get("id") != ""){
			return $this->update(Input::get("id"),"async");
		} else {
			return $this->create("async");
		}		
	}


	public function create($requestType = "")
	{

		$user = Auth::user();


		$validation = Exercises::validate(Input::all());
		if($validation->fails()){
			//dd($validation->messages());
			if($requestType == ""){
				return Redirect::back()->withErrors($validation->messages())->withInput();
			} else {
				return $this::responseJsonError($validation->messages());
			}
		} else {

			$exercise = new Exercises;
			$exercise->name = ucfirst(Input::get("name"));

			$exercise->description = Input::get("description");
			$exercise->bodygroupId = Input::get("bodygroup");
			$exercise->youtube = Helper::extractYoutubeTag(Input::get("youtube"));
			$exercise->nameEngine = Input::get("nameEngine");
			if(Input::has("publicLicense")) { $exercise->type = "public"; } else { $exercise->type = "private"; }

			if(Input::has("equipmentRequired")){ $exercise->equipmentRequired = 1; } else { $exercise->equipmentRequired = 0; }

			//if(Auth::check()){
			//if(1==1){
				$exercise->userId = Auth::user()->id;
				$exercise->authorId = Auth::user()->id;
				Helper::checkUserFolder($user->id);

				if(Input::has("removeGreenScreen")){
					
					if(Input::hasFile("image1")) {
						$images = Helper::saveImageGreenScreen(Input::file("image1"),$user->getPath().Config::get("constants.exercisesPath")."/".$exercise->id);
						$exercise->image = $images["image"];
						$exercise->thumb = $images["thumb"];
					}
					if(Input::hasFile("image2")) {
							$images = Helper::saveImageGreenScreen(Input::file("image2"),$user->getPath().Config::get("constants.exercisesPath")."/".$exercise->id);
							$exercise->image2 = $images["image"];
							$exercise->thumb2 = $images["thumb"];
					}
				} else {
					if(Input::hasFile("image1")) {
						$images = Helper::saveImage(Input::file("image1"),$user->getPath().Config::get("constants.exercisesPath")."/".$exercise->id);
						$exercise->image = $images["image"];
						$exercise->thumb = $images["thumb"];
					}
					if(Input::hasFile("image2")) {
							$images = Helper::saveImage(Input::file("image2"),$user->getPath().Config::get("constants.exercisesPath")."/".$exercise->id);
							$exercise->image2 = $images["image"];
							$exercise->thumb2 = $images["thumb"];
					}	
				}
				
				if(Input::hasFile("video")) {
						$video = Helper::uploadFile(Input::file("video"),$user->getPath().Config::get("constants.videosExercisesPath")."/".$exercise->id);
						$exercise->video = $video;
				}
			/*} else{
				if(Input::hasFile("image1")) {
						$images = Helper::saveImage(Input::file("image1"),Config::get("constants.moreExercises"));
						$exercise->image = $images["image"];
						$exercise->thumb = $images["thumb"];
				}
				if(Input::hasFile("image2")) {
						$images = Helper::saveImage(Input::file("image2"),Config::get("constants.moreExercises"));
						$exercise->image2 = $images["image"];
						$exercise->thumb2 = $images["thumb"];
				}
				if(Input::hasFile("video")) {
						$video = Helper::uploadFile(Input::file("video"),Config::get("constants.moreExercises"));
						$exercise->video = $video;
				}
				$exercise->nameEngine = Input::get("nameEngine");
			}
			*/
			//dd("DONT");
			$exercise->save();
			

			if(Input::has("equipment")){

				if(is_array(Input::get("equipment"))){

					foreach(Input::get("equipment") as $equi){

						$eq = new ExercisesEquipments();
						$eq->exerciseId = $exercise->id;
						$eq->equipmentId = $equi;
						$eq->type = "required";
						$eq->save();
					}
				} else {
					$eq = new ExercisesEquipments();
					$eq->exerciseId = $exercise->id;
					$eq->equipmentId = Input::get("equipment");
					$eq->type = "required";
					$eq->save();
				}

				$exercise->equipmentRequired = 1;
			}

			if(Input::has("equipmentOptional")){

				if(is_array(Input::get("equipmentOptional"))){

					foreach(Input::get("equipmentOptional") as $equi){

						$eq = new ExercisesEquipments();
						$eq->exerciseId = $exercise->id;
						$eq->equipmentId = $equi;
						$eq->type = "optional";
						$eq->save();
					}
				} else {
					$eq = new ExercisesEquipments();
					$eq->exerciseId = $exercise->id;
					$eq->equipmentId = Input::get("equipmentOptional");
					$eq->type = "optional";
					$eq->save();
				}
			}

			if(Input::has("equipmentHidden")){

				if(is_array(Input::get("equipmentHidden"))){

					foreach(Input::get("equipmentHidden") as $equi){

						$eq = new ExercisesEquipments();
						$eq->exerciseId = $exercise->id;
						$eq->equipmentId = $equi;
						$eq->type = "hidden";
						$eq->save();
					}
				} else {
					$eq = new ExercisesEquipments();
					$eq->exerciseId = $exercise->id;
					$eq->equipmentId = Input::get("equipmentHidden");
					$eq->type = "hidden";
					$eq->save();
				}
			}
			
			if(Input::hasFile("image3")) {
					$exerciseImage = new ExercisesImages();
					$exerciseImage->userId = null;
					$exerciseImage->exerciseId = $exercise->id;
					$exerciseImage->availability = "public";
					$exerciseImage->save();

						
					$images = Helper::saveImage(Input::file("image3"),Config::get("constants.moreExercises"));
					$exerciseImage->image = $images["image"];
					$exerciseImage->thumb = $images["thumb"];
						
					$exerciseImage->save();

			}
			if(Input::hasFile("image4")) {
					$exerciseImage = new ExercisesImages();
					$exerciseImage->userId = null;
					$exerciseImage->exerciseId = $exercise->id;
					$exerciseImage->availability = "public";
					$exerciseImage->save();

						
					$images = Helper::saveImage(Input::file("image4"),Config::get("constants.moreExercises"));
					$exerciseImage->image = $images["image"];
					$exerciseImage->thumb = $images["thumb"];
						
					$exerciseImage->save();
			}
			if(Input::hasFile("image5")) {
					$exerciseImage = new ExercisesImages();
					$exerciseImage->userId = null;
					$exerciseImage->exerciseId = $exercise->id;
					$exerciseImage->availability = "public";
					$exerciseImage->save();

						
					$images = Helper::saveImage(Input::file("image5"),Config::get("constants.moreExercises"));
					$exerciseImage->image = $images["image"];
					$exerciseImage->thumb = $images["thumb"];
						
					$exerciseImage->save();
			}
			if(Input::hasFile("image6")) {
					$exerciseImage = new ExercisesImages();
					$exerciseImage->userId = null;
					$exerciseImage->exerciseId = $exercise->id;
					$exerciseImage->availability = "public";
					$exerciseImage->save();

						
					$images = Helper::saveImage(Input::file("image6"),Config::get("constants.moreExercises"));
					$exerciseImage->image = $images["image"];
					$exerciseImage->thumb = $images["thumb"];
						
					$exerciseImage->save();
			}

			$exercise->save();

			if($exercise->getTranslation("en",false) == ""){
				$ex = $exercise->translateOrNew("en");
				$ex->name = ucfirst(Input::get("name"));
				$ex->exercises_id = $exercise->id;
				$ex->created_at = date('Y-m-d H:i:s');
				$ex->save();
			}

			Event::fire('addedAnExercise', array(Auth::user(),$exercise->name));


			if(Auth::check()){
				Feeds::insertFeed("NewExercise",$user->id,$user->firstName,$user->lastName);
				//dd(Auth::user()->userType);
				if($requestType == ""){
					if(Auth::user()->userType == "Trainer") return Redirect::route("ExercisesHomeTrainer")->with("message",Lang::get("messages.ExerciseAdded"));
				}
				if($requestType == ""){
					return Redirect::route("ExercisesHomeTrainee")->with("message",Lang::get("messages.ExerciseAdded"));
				} else {
					return $this::responseJson(Lang::get("messages.ExerciseAdded"));
				}
			} else {
				return $this::responseJson(Lang::get("messages.ExerciseAdded"));
			}
		}
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /exercises
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /exercises/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id,$name="")
	{
		$exercise = Exercises::with("equipments")->with("equipmentsOptional")->with("exercisesTypes")->find($id);
		
		if($exercise){
			if(Auth::check()){
				return View::make(strtolower(Auth::user()->userType).".exercise")
					->with("exercise",$exercise);
			} else {
				return View::make("visitor.exercise")
					->with("exercise",$exercise);
			}
			
		} else {
			return Redirect::route("Trainee",Helper::userHome())->with("error",Lang::get("messages.NotFound"));
		}


	}

	public function APIShow($id)
	{
		$exercise = Exercises::find($id);
		
		
		return $this->responseJson($exercise);
		


	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /exercises/{id}/edit
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
	 * PUT /exercises/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$user = Auth::user();
					$requestType = "";

		

		$validation = Exercises::validate(Input::all());
		if($validation->fails()){
			//dd($validation->messages());
			if($requestType == ""){
				return Redirect::back()->withErrors($validation->messages())->withInput();
			} else {
				return $this::responseJsonError($validation->messages());
			}
		} else {

			$exercise = Exercises::find($id);
			$exercise->name = ucfirst(Input::get("name"));

			$exercise->description = Input::get("description");
			$exercise->bodygroupId = Input::get("bodygroup");
			$exercise->youtube = Helper::extractYoutubeTag(Input::get("youtube"));
			$exercise->nameEngine = Input::get("nameEngine");
			if(Input::has("publicLicense")) { $exercise->type = "public"; } else { $exercise->type = "private"; }

			if(Input::has("equipmentRequired")){ $exercise->equipmentRequired = 1; } else { $exercise->equipmentRequired = 0; }

			//if(Auth::check()){
			//if(1==1){
				$exercise->userId = Auth::user()->id;
				$exercise->authorId = Auth::user()->id;
				Helper::checkUserFolder($user->id);

				if(Input::has("removeGreenScreen")){
					
					if(Input::hasFile("image1")) {
						$images = Helper::saveImageGreenScreen(Input::file("image1"),$user->getPath().Config::get("constants.exercisesPath")."/".$exercise->id);
						$exercise->image = $images["image"];
						$exercise->thumb = $images["thumb"];
					}
					if(Input::hasFile("image2")) {
							$images = Helper::saveImageGreenScreen(Input::file("image2"),$user->getPath().Config::get("constants.exercisesPath")."/".$exercise->id);
							$exercise->image2 = $images["image"];
							$exercise->thumb2 = $images["thumb"];
					}
				} else {
					if(Input::hasFile("image1")) {
						$images = Helper::saveImage(Input::file("image1"),$user->getPath().Config::get("constants.exercisesPath")."/".$exercise->id);
						$exercise->image = $images["image"];
						$exercise->thumb = $images["thumb"];
					}
					if(Input::hasFile("image2")) {
							$images = Helper::saveImage(Input::file("image2"),$user->getPath().Config::get("constants.exercisesPath")."/".$exercise->id);
							$exercise->image2 = $images["image"];
							$exercise->thumb2 = $images["thumb"];
					}	
				}
				
				if(Input::hasFile("video")) {
						$video = Helper::uploadFile(Input::file("video"),$user->getPath().Config::get("constants.videosExercisesPath")."/".$exercise->id);
						$exercise->video = $video;
				}
			/*} else{
				if(Input::hasFile("image1")) {
						$images = Helper::saveImage(Input::file("image1"),Config::get("constants.moreExercises"));
						$exercise->image = $images["image"];
						$exercise->thumb = $images["thumb"];
				}
				if(Input::hasFile("image2")) {
						$images = Helper::saveImage(Input::file("image2"),Config::get("constants.moreExercises"));
						$exercise->image2 = $images["image"];
						$exercise->thumb2 = $images["thumb"];
				}
				if(Input::hasFile("video")) {
						$video = Helper::uploadFile(Input::file("video"),Config::get("constants.moreExercises"));
						$exercise->video = $video;
				}
				$exercise->nameEngine = Input::get("nameEngine");
			}
			*/
			//dd("DONT");
			$exercise->save();
			

			if(Input::has("equipment")){

				if(is_array(Input::get("equipment"))){

					foreach(Input::get("equipment") as $equi){

						$eq = new ExercisesEquipments();
						$eq->exerciseId = $exercise->id;
						$eq->equipmentId = $equi;
						$eq->type = "required";
						$eq->save();
					}
				} else {
					$eq = new ExercisesEquipments();
					$eq->exerciseId = $exercise->id;
					$eq->equipmentId = Input::get("equipment");
					$eq->type = "required";
					$eq->save();
				}

				$exercise->equipmentRequired = 1;
			}

			if(Input::has("equipmentOptional")){

				if(is_array(Input::get("equipmentOptional"))){

					foreach(Input::get("equipmentOptional") as $equi){

						$eq = new ExercisesEquipments();
						$eq->exerciseId = $exercise->id;
						$eq->equipmentId = $equi;
						$eq->type = "optional";
						$eq->save();
					}
				} else {
					$eq = new ExercisesEquipments();
					$eq->exerciseId = $exercise->id;
					$eq->equipmentId = Input::get("equipmentOptional");
					$eq->type = "optional";
					$eq->save();
				}
			}

			if(Input::has("equipmentHidden")){

				if(is_array(Input::get("equipmentHidden"))){

					foreach(Input::get("equipmentHidden") as $equi){

						$eq = new ExercisesEquipments();
						$eq->exerciseId = $exercise->id;
						$eq->equipmentId = $equi;
						$eq->type = "hidden";
						$eq->save();
					}
				} else {
					$eq = new ExercisesEquipments();
					$eq->exerciseId = $exercise->id;
					$eq->equipmentId = Input::get("equipmentHidden");
					$eq->type = "hidden";
					$eq->save();
				}
			}
			
			if(Input::hasFile("image3")) {
					$exerciseImage = new ExercisesImages();
					$exerciseImage->userId = null;
					$exerciseImage->exerciseId = $exercise->id;
					$exerciseImage->availability = "public";
					$exerciseImage->save();

						
					$images = Helper::saveImage(Input::file("image3"),Config::get("constants.moreExercises"));
					$exerciseImage->image = $images["image"];
					$exerciseImage->thumb = $images["thumb"];
						
					$exerciseImage->save();

			}
			if(Input::hasFile("image4")) {
					$exerciseImage = new ExercisesImages();
					$exerciseImage->userId = null;
					$exerciseImage->exerciseId = $exercise->id;
					$exerciseImage->availability = "public";
					$exerciseImage->save();

						
					$images = Helper::saveImage(Input::file("image4"),Config::get("constants.moreExercises"));
					$exerciseImage->image = $images["image"];
					$exerciseImage->thumb = $images["thumb"];
						
					$exerciseImage->save();
			}
			if(Input::hasFile("image5")) {
					$exerciseImage = new ExercisesImages();
					$exerciseImage->userId = null;
					$exerciseImage->exerciseId = $exercise->id;
					$exerciseImage->availability = "public";
					$exerciseImage->save();

						
					$images = Helper::saveImage(Input::file("image5"),Config::get("constants.moreExercises"));
					$exerciseImage->image = $images["image"];
					$exerciseImage->thumb = $images["thumb"];
						
					$exerciseImage->save();
			}
			if(Input::hasFile("image6")) {
					$exerciseImage = new ExercisesImages();
					$exerciseImage->userId = null;
					$exerciseImage->exerciseId = $exercise->id;
					$exerciseImage->availability = "public";
					$exerciseImage->save();

						
					$images = Helper::saveImage(Input::file("image6"),Config::get("constants.moreExercises"));
					$exerciseImage->image = $images["image"];
					$exerciseImage->thumb = $images["thumb"];
						
					$exerciseImage->save();
			}

			$exercise->save();

			if($exercise->getTranslation("en",false) == ""){
				$ex = $exercise->translateOrNew("en");
				$ex->name = ucfirst(Input::get("name"));
				$ex->exercises_id = $exercise->id;
				$ex->created_at = date('Y-m-d H:i:s');
				$ex->save();
			}

			Event::fire('addedAnExercise', array(Auth::user(),$exercise->name));


			if(Auth::check()){
				Feeds::insertFeed("NewExercise",$user->id,$user->firstName,$user->lastName);
				if($requestType == ""){
					if(Auth::user()->userType == "Trainer") return Redirect::route("ExercisesHomeTrainer")->with("message",Lang::get("messages.ExerciseAdded"));
				}
				if($requestType == ""){
					return Redirect::route("ExercisesHomeTrainee")->with("message",Lang::get("messages.ExerciseAdded"));
				} else {
					return $this::responseJson(Lang::get("messages.ExerciseAdded"));
				}
			} else {
				return $this::responseJson(Lang::get("messages.ExerciseAdded"));
			}
		}
		
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /exercises/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$obj = Exercises::find($id);
		if(!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));

        
		//if($this->checkPermissions($obj->userId,Auth::user()->id)){
		Event::fire('deletedAnExercise', array(Auth::user(),$obj->name));

			$obj->delete();
//			Feeds::insertFeed("DeleteExercise",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);

			

			return $this::responseJson(Lang::get("messages.ExerciseDeleted"));
		//} else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		//}
		
	
	}

	function switchPictures(){
		$id = Input::get("id");
		$obj = Exercises::find($id);
		
		if($obj){

			$image = $obj->image2;
			$image2 = $obj->image;
			$thumb = $obj->thumb2;
			$thumb2 = $obj->thumb;
			$obj->image = $image;
			$obj->image2 = $image2;
			$obj->thumb = $thumb;
			$obj->thumb2 = $thumb2;
			$obj->save();
		
			return $this::responseJson(Lang::get("messages.ImageSwitched"));
		}
	}

	function rotateRight(){
		$id = Input::get("id");
		$obj = Exercises::find($id);
		$images = [true,true];
		$imageNumber = Input::get("imageNumber");
		if($imageNumber != null and $imageNumber != ""){
			$images = [false,false];
			$images[$imageNumber-1] = true;
		}

		
		if($obj){
			if(file_exists($obj->image) and $images[0]){
				
				$image = Image::make($obj->image);
				$image->rotate(-90);
				$image->save();
				$image = Image::make($obj->thumb);
				$image->rotate(-90);
				$image->save();
			}
			if(file_exists($obj->image2) and $images[1]){
				$image = Image::make($obj->image2);
				$image->rotate(-90);
				$image->save();
				$image = Image::make($obj->thumb2);
				$image->rotate(-90);
				$image->save();
			}

			return $this::responseJson(Lang::get("messages.ImageRotated"));
		}
	}

	function rotateLeft(){
		$id = Input::get("id");
		$images = [true,true];
		$imageNumber = Input::get("imageNumber");
		if($imageNumber != null and $imageNumber != ""){
			$images = [false,false];
			$images[$imageNumber-1] = true;
		}
		$obj = Exercises::find($id);
	
		if($obj){
			if(file_exists($obj->image) and $images[0]){
				$image = Image::make($obj->image);
				$image->rotate(90);
				$image->save();
				$image = Image::make($obj->thumb);
				$image->rotate(90);
				$image->save();
			}
			if(file_exists($obj->image2) and $images[1]){
				$image = Image::make($obj->image2);
				$image->rotate(90);
				$image->save();
				$image = Image::make($obj->thumb2);
				$image->rotate(90);
				$image->save();
			}

			return $this::responseJson(Lang::get("messages.ImageRotated"));
		}
	}

	function rotateRight1(){
		$id = Input::get("id");
		$obj = Exercises::find($id);
		
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

	function rotateLeft1(){
		$id = Input::get("id");
		$obj = Exercises::find($id);
	
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

	function rotateRight2(){
		$id = Input::get("id");
		$obj = Exercises::find($id);
		
		if($obj){

			if(file_exists($obj->image2)){
				$image = Image::make($obj->image2);
				$image->rotate(-90);
				$image->save();
				$image = Image::make($obj->thumb2);
				$image->rotate(-90);
				$image->save();
			}

			return $this::responseJson(Lang::get("messages.ImageRotated"));
		}
	}

	function rotateLeft2(){
		$id = Input::get("id");
		$obj = Exercises::find($id);
	
		if($obj){

			if(file_exists($obj->image2)){
				$image = Image::make($obj->image2);
				$image->rotate(90);
				$image->save();
				$image = Image::make($obj->thumb2);
				$image->rotate(90);
				$image->save();
			}

			return $this::responseJson(Lang::get("messages.ImageRotated"));
		}
	}

	function removeImage(){
		$id = Input::get("id");
		$image = Exercises::find($id);
	
		if(Input::get("image") == 1){
			File::delete($image->image);
			File::delete($image->thumb);
			$image->image = null;
			$image->thumb = null;
		}
		if(Input::get("image") == 2){
			File::delete($image->image2);
			File::delete($image->thumb2);
			$image->image2 = null;
			$image->thumb2 = null;
		}

		$image->save();
		

		return $this::responseJson(Lang::get("messages.ImageRemoved"));
		
	}


	//=======================================================================================================================
	// CONTROL PANEL
	//=======================================================================================================================
	

	public function _index()
	{
		return View::make('ControlPanel/Exercises')
			->with("bodygroups",Exercises::getBodyGroupsList())
			->with("exercisesTypes",Exercisestypes::orderBy("name","ASC")->lists("name","id"))
			->with("equipments",Equipments::orderBy("name","ASC")->lists("name","id"))
			->with("users",Users::select(DB::raw("concat('id: ',id,' - ',firstName,' ',lastName) as name "),"id")->orderBy("firstName","ASC")->orderBy("lastName","ASC")->lists("name","id"));
	}

	public function _ApiList()
	{
		return $this::responseJson(array("data"=>Exercises::with("bodygroup")->with("exercisesTypes")->with("bodygroupsOptional")->with("equipments")->with("equipmentsOptional")->with("user")->with("author")->orderBy("name","ASC")->get()));
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
		ini_set('max_execution_time', 3000);
        set_time_limit(3000);

		$validation = Exercises::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$exercise = new Exercises;
			$exercise->name = Input::get("name");
			$exercise->nameEngine = Input::get("nameEngine");
			//$exercise->equipment = Input::get("equipment");
			$exercise->bodygroupId = Input::get("bodygroupId");
			//$exercise->exercisesTypesId = Input::get("exercisesTypesId");
			$exercise->userId = Input::get("userId");
			$exercise->authorId = Input::get("authorId");
			$exercise->views = Input::get("views");
			$exercise->video = Input::get("video");
			$exercise->type = Input::get("type");
			$exercise->youtube = Input::get("youtube");
			$exercise->description = Input::get("description");
			$exercise->used = Input::get("used");

			if(Input::has("equipmentRequired")){ $exercise->equipmentRequired = 1; } else { $exercise->equipmentRequired = 0; }


			if(Input::has("removeGreenScreen")){
				
				if(Input::hasFile("image1")) {
					
					$images = Helper::saveImageGreenScreen(Input::file("image1"),Config::get("constants.moreExercises"),Input::get("light"),Input::get("modulation"),Input::get("feather"),Input::get("algo"),Input::get("replacer"),Input::get("color1"),Input::get("color2"));
					$exercise->image = $images["image"];
					$exercise->thumb = $images["thumb"];
				}
				if(Input::hasFile("image2")) {
						$images = Helper::saveImageGreenScreen(Input::file("image2"),Config::get("constants.moreExercises"),Input::get("light"),Input::get("modulation"),Input::get("feather"),Input::get("algo"),Input::get("replacer"),Input::get("color1"),Input::get("color2"));
						$exercise->image2 = $images["image"];
						$exercise->thumb2 = $images["thumb"];
				}
			} else {
				
				if(Input::hasFile("image1")) {
					$images = Helper::saveImage(Input::file("image1"),Config::get("constants.moreExercises"));
					$exercise->image = $images["image"];
					$exercise->thumb = $images["thumb"];
				}
				if(Input::hasFile("image2")) {
						$images = Helper::saveImage(Input::file("image2"),Config::get("constants.moreExercises"));
						$exercise->image2 = $images["image"];
						$exercise->thumb2 = $images["thumb"];
				}	
			}
		
			
			$exercise->save();

			Exercises::where("id",$exercise->id)->update(array("name"=>Input::get("name"), "nameEngine"=>Input::get("nameEngine")));

			if($exercise->getTranslation("en",false) == ""){
				$ex = $exercise->translateOrNew("en");
				$ex->name = ucfirst(Input::get("name"));
				$ex->exercises_id = $exercise->id;
				$ex->created_at = date('Y-m-d H:i:s');
				$ex->save();
			}


			
			if(Input::has("equipment")){

				if(is_array(Input::get("equipment"))){

					foreach(Input::get("equipment") as $equi){
						if($equi != 0 and $equi != ""){
							$eq = new ExercisesEquipments();
							$eq->exerciseId = $exercise->id;
							$eq->equipmentId = $equi;
							$eq->type = "required";
							$eq->save();
						}
					}
				} else {
					if(Input::get("equipment") != 0 and Input::get("equipment") != ""){
						$eq = new ExercisesEquipments();
						$eq->exerciseId = $exercise->id;
						$eq->equipmentId = Input::get("equipment");
						$eq->type = "required";
						$eq->save();
					}
				}
			}

			if(Input::has("equipmentOptional")){

				if(is_array(Input::get("equipmentOptional"))){

					foreach(Input::get("equipmentOptional") as $equi){
						if($equi != 0 and $equi != ""){
							$eq = new ExercisesEquipments();
							$eq->exerciseId = $exercise->id;
							$eq->equipmentId = $equi;
							$eq->type = "optional";
							$eq->save();
						}
					}
				} else {
					if(Input::get("equipmentOptional") != 0 and Input::get("equipmentOptional") != ""){
						$eq = new ExercisesEquipments();
						$eq->exerciseId = $exercise->id;
						$eq->equipmentId = Input::get("equipmentOptional");
						$eq->type = "optional";
						$eq->save();
					}
				}
			}

			if(Input::has("equipmentHidden")){

				if(is_array(Input::get("equipmentHidden"))){

					foreach(Input::get("equipmentHidden") as $equi){
						if($equi != 0 and $equi != ""){
							$eq = new ExercisesEquipments();
							$eq->exerciseId = $exercise->id;
							$eq->equipmentId = $equi;
							$eq->type = "hidden";
							$eq->save();
						}
					}
				} else {
					if(Input::get("equipmentHidden") != 0 and Input::get("equipmentHidden") != ""){
						$eq = new ExercisesEquipments();
						$eq->exerciseId = $exercise->id;
						$eq->equipmentId = Input::get("equipmentHidden");
						$eq->type = "hidden";
						$eq->save();
					}
				}
			}

			if(Input::has("exercisesTypesId")){
				if(is_array(Input::get("exercisesTypesId"))){
					$equipments = ExercisesExercisestypes::where("exerciseId",$exercise->id)->lists("exercisestypesId");

					$toDelete = array_diff($equipments,Input::get("exercisesTypesId"));
					array_push($toDelete,-1);
					$toAdd = array_diff(Input::get("exercisesTypesId"),$equipments);

					ExercisesExercisestypes::where("exerciseId",$exercise->id)->whereIn("exercisestypesId",$toDelete)->delete();


					foreach(Input::get("exercisesTypesId") as $equi){
						if(in_array($equi,$toAdd) and $equi != 0 and $equi != ""){
							$eq = new ExercisesExercisestypes();
							$eq->exerciseId = $exercise->id;
							$eq->exercisestypesId = $equi;
							$eq->save();
						}
						
					}
				} else {

					if(ExercisesExercisestypes::where("exerciseId",$exercise->id)->where("type","optional")->where("exercisestypesId",Input::get("exercisesTypesId"))->count() == 0){
						if(Input::get("exercisesTypesId") != 0 and Input::get("exercisesTypesId") != ""){
							$eq = new ExercisesExercisestypes();
							$eq->exerciseId = $exercise->id;
							$eq->exercisestypesId = Input::get("exercisesTypesId");
							$eq->save();
						}
					}
				}
			} else {
				ExercisesExercisestypes::where("exerciseId",$exercise->id)->delete();
			}

			if(Input::has("bodygroupsOptional")){
				if(is_array(Input::get("bodygroupsOptional"))){
					$equipments = ExercisesBodygroups::where("exerciseId",$exercise->id)->lists("bodygroupId");

					$toDelete = array_diff($equipments,Input::get("bodygroupsOptional"));
					array_push($toDelete,-1);
					$toAdd = array_diff(Input::get("bodygroupsOptional"),$equipments);

					ExercisesBodygroups::where("exerciseId",$exercise->id)->whereIn("bodygroupId",$toDelete)->delete();


					foreach(Input::get("bodygroupsOptional") as $equi){
						if(in_array($equi,$toAdd) and $equi != 0 and $equi != ""){
							$eq = new ExercisesBodygroups();
							$eq->exerciseId = $exercise->id;
							$eq->bodygroupId = $equi;
							$eq->save();
						}
						
					}
				} else {

					if(ExercisesBodygroups::where("exerciseId",$exercise->id)->where("type","optional")->where("bodygroupId",Input::get("bodygroupsOptional"))->count() == 0){
						if(Input::get("bodygroupsOptional") != 0 and Input::get("bodygroupsOptional") != ""){
							$eq = new ExercisesBodygroups();
							$eq->exerciseId = $exercise->id;
							$eq->bodygroupId = Input::get("bodygroupsOptional");
							$eq->save();
						}
					}
				}
			} else {
				ExercisesBodygroups::where("exerciseId",$exercise->id)->delete();
			}



			return $this::responseJson(Messages::showControlPanel("ExerciseCreated"));	
		}
	}

	public function _show($exercise)
	{
		//
		return Exercises::with("equipments")->with("equipmentsOptional")->with("equipmentsHidden")->with("bodygroupsOptional")->with("exercisesTypes")->find($exercise);
	}

	public function _update($id)
	{
		ini_set('max_execution_time', 3000);
        set_time_limit(3000);
		$validation = Exercises::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$exercise = Exercises::find($id);
			$exercise->name = Input::get("name");
			$exercise->nameEngine = Input::get("nameEngine");
			//$exercise->equipment = Input::get("equipment");
			$exercise->bodygroupId = Input::get("bodygroupId");
			$exercise->userId = Input::get("userId");
			$exercise->authorId = Input::get("authorId");
			$exercise->views = Input::get("views");
			$exercise->video = Input::get("video");
			$exercise->type = Input::get("type");
			$exercise->youtube = Input::get("youtube");
			$exercise->description = Input::get("description");
			$exercise->used = Input::get("used");

			if(Input::has("equipmentRequired")){ $exercise->equipmentRequired = 1; } else { $exercise->equipmentRequired = 0; }

			
			if(Input::has("removeGreenScreen")){
					
				if(Input::hasFile("image1")) {
					$images = Helper::saveImageGreenScreen(Input::file("image1"),Config::get("constants.moreExercises"),Input::get("light"),Input::get("modulation"),Input::get("feather"),Input::get("algo"),Input::get("replacer"),Input::get("color1"),Input::get("color2"));
					$exercise->image = $images["image"];
					$exercise->thumb = $images["thumb"];
				}
				if(Input::hasFile("image2")) {
						$images = Helper::saveImageGreenScreen(Input::file("image2"),Config::get("constants.moreExercises"),Input::get("light"),Input::get("modulation"),Input::get("feather"),Input::get("algo"),Input::get("replacer"),Input::get("color1"),Input::get("color2"));
						$exercise->image2 = $images["image"];
						$exercise->thumb2 = $images["thumb"];
				}
			} else {
				
				if(Input::hasFile("image1")) {
					$images = Helper::saveImage(Input::file("image1"),Config::get("constants.moreExercises"));
					$exercise->image = $images["image"];
					$exercise->thumb = $images["thumb"];
				}
				if(Input::hasFile("image2")) {
						$images = Helper::saveImage(Input::file("image2"),Config::get("constants.moreExercises"));
						$exercise->image2 = $images["image"];
						$exercise->thumb2 = $images["thumb"];
				}	
			}
			if(Input::hasFile("video")) {
					$video = Helper::uploadFile(Input::file("video"),Config::get("constants.moreExercises"));
					$exercise->video = $video;
			}

			$exercise->save();

			if($exercise->getTranslation("en",false) == ""){
				$ex = $exercise->translateOrNew("en");
				$ex->name = ucfirst(Input::get("name"));
				$ex->exercises_id = $exercise->id;
				$ex->created_at = date('Y-m-d H:i:s');
				$ex->save();
			}

			

			

			if(Input::has("equipment")){
				if(is_array(Input::get("equipment"))){
					$equipments = ExercisesEquipments::where("exerciseId",$exercise->id)->where("type","required")->lists("equipmentId");

					$toDelete = array_diff($equipments,Input::get("equipment"));
					array_push($toDelete,-1);
					$toAdd = array_diff(Input::get("equipment"),$equipments);

					ExercisesEquipments::where("exerciseId",$exercise->id)->where("type","required")->whereIn("equipmentId",$toDelete)->delete();


					foreach(Input::get("equipment") as $equi){
						if(in_array($equi,$toAdd) and $equi != 0 and $equi != ""){
							$eq = new ExercisesEquipments();
							$eq->exerciseId = $exercise->id;
							$eq->equipmentId = $equi;
							$eq->type = "required";
							$eq->save();
						}
						
					}
				} else {

					if(ExercisesEquipments::where("exerciseId",$exercise->id)->where("type","required")->where("equipmentId",Input::get("equipment"))->count() == 0){
						if(Input::get("equipment") != 0 and Input::get("equipment") != ""){
							$eq = new ExercisesEquipments();
							$eq->exerciseId = $exercise->id;
							$eq->equipmentId = Input::get("equipment");
							$eq->type = "required";
							$eq->save();
						}
					}
				}
			} else {
				ExercisesEquipments::where("exerciseId",$exercise->id)->where("type","required")->delete();
			}

			if(Input::has("equipmentOptional")){
				if(is_array(Input::get("equipmentOptional"))){
					$equipments = ExercisesEquipments::where("exerciseId",$exercise->id)->where("type","optional")->lists("equipmentId");

					$toDelete = array_diff($equipments,Input::get("equipmentOptional"));
					array_push($toDelete,-1);
					$toAdd = array_diff(Input::get("equipmentOptional"),$equipments);

					ExercisesEquipments::where("exerciseId",$exercise->id)->where("type","optional")->whereIn("equipmentId",$toDelete)->delete();


					foreach(Input::get("equipmentOptional") as $equi){
						if(in_array($equi,$toAdd) and $equi != 0 and $equi != ""){
							$eq = new ExercisesEquipments();
							$eq->exerciseId = $exercise->id;
							$eq->equipmentId = $equi;
							$eq->type = "optional";
							$eq->save();
						}
						
					}
				} else {

					if(ExercisesEquipments::where("exerciseId",$exercise->id)->where("type","optional")->where("equipmentId",Input::get("equipmentOptional"))->count() == 0){
						if(Input::get("equipmentOptional") != 0 and Input::get("equipmentOptional") != ""){
							$eq = new ExercisesEquipments();
							$eq->exerciseId = $exercise->id;
							$eq->equipmentId = Input::get("equipmentOptional");
							$eq->type = "optional";
							$eq->save();
						}
					}
				}
			} else {
				ExercisesEquipments::where("exerciseId",$exercise->id)->where("type","optional")->delete();
			}


			if(Input::has("equipmentHidden")){
				if(is_array(Input::get("equipmentHidden"))){
					$equipments = ExercisesEquipments::where("exerciseId",$exercise->id)->where("type","hidden")->lists("equipmentId");

					$toDelete = array_diff($equipments,Input::get("equipmentHidden"));
					array_push($toDelete,-1);
					$toAdd = array_diff(Input::get("equipmentHidden"),$equipments);

					ExercisesEquipments::where("exerciseId",$exercise->id)->where("type","hidden")->whereIn("equipmentId",$toDelete)->delete();


					foreach(Input::get("equipmentHidden") as $equi){
						if(in_array($equi,$toAdd) and $equi != 0 and $equi != ""){
							$eq = new ExercisesEquipments();
							$eq->exerciseId = $exercise->id;
							$eq->equipmentId = $equi;
							$eq->type = "hidden";
							$eq->save();
						}
						
					}
				} else {

					if(ExercisesEquipments::where("exerciseId",$exercise->id)->where("type","hidden")->where("equipmentId",Input::get("equipmentHidden"))->count() == 0){
						if(Input::get("equipmentHidden") != 0 and Input::get("equipmentOptional") != ""){
							$eq = new ExercisesEquipments();
							$eq->exerciseId = $exercise->id;
							$eq->equipmentId = Input::get("equipmentHidden");
							$eq->type = "hidden";
							$eq->save();
						}
					}
				}
			} else {
				ExercisesEquipments::where("exerciseId",$exercise->id)->where("type","hidden")->delete();
			}

			if(Input::has("exercisesTypesId")){
				if(is_array(Input::get("exercisesTypesId"))){
					$equipments = ExercisesExercisestypes::where("exerciseId",$exercise->id)->lists("exercisestypesId");

					$toDelete = array_diff($equipments,Input::get("exercisesTypesId"));
					array_push($toDelete,-1);
					$toAdd = array_diff(Input::get("exercisesTypesId"),$equipments);

					ExercisesExercisestypes::where("exerciseId",$exercise->id)->whereIn("exercisestypesId",$toDelete)->delete();


					foreach(Input::get("exercisesTypesId") as $equi){
						if(in_array($equi,$toAdd) and $equi != 0 and $equi != ""){
							$eq = new ExercisesExercisestypes();
							$eq->exerciseId = $exercise->id;
							$eq->exercisestypesId = $equi;
							$eq->save();
						}
						
					}
				} else {

					if(ExercisesExercisestypes::where("exerciseId",$exercise->id)->where("type","optional")->where("exercisestypesId",Input::get("exercisesTypesId"))->count() == 0){
						if(Input::get("exercisesTypesId") != 0 and Input::get("exercisesTypesId") != ""){
							$eq = new ExercisesExercisestypes();
							$eq->exerciseId = $exercise->id;
							$eq->exercisestypesId = Input::get("exercisesTypesId");
							$eq->save();
						}
					}
				}
			} else {
				ExercisesExercisestypes::where("exerciseId",$exercise->id)->delete();
			}


			if(Input::has("bodygroupsOptional")){
				if(is_array(Input::get("bodygroupsOptional"))){
					$equipments = ExercisesBodygroups::where("exerciseId",$exercise->id)->lists("bodygroupId");

					$toDelete = array_diff($equipments,Input::get("bodygroupsOptional"));
					array_push($toDelete,-1);
					$toAdd = array_diff(Input::get("bodygroupsOptional"),$equipments);

					ExercisesBodygroups::where("exerciseId",$exercise->id)->whereIn("bodygroupId",$toDelete)->delete();


					foreach(Input::get("bodygroupsOptional") as $equi){
						if(in_array($equi,$toAdd) and $equi != 0 and $equi != ""){
							$eq = new ExercisesBodygroups();
							$eq->exerciseId = $exercise->id;
							$eq->bodygroupId = $equi;
							$eq->save();
						}
						
					}
				} else {

					if(ExercisesBodygroups::where("exerciseId",$exercise->id)->where("type","optional")->where("bodygroupId",Input::get("bodygroupsOptional"))->count() == 0){
						if(Input::get("bodygroupsOptional") != 0 and Input::get("bodygroupsOptional") != ""){
							$eq = new ExercisesBodygroups();
							$eq->exerciseId = $exercise->id;
							$eq->bodygroupId = Input::get("bodygroupsOptional");
							$eq->save();
						}
					}
				}
			} else {
				ExercisesBodygroups::where("exerciseId",$exercise->id)->delete();
			}

			return $this::responseJson(Messages::showControlPanel("ExerciseModified"));	
			
		}
	}

	public function _destroy($id)
	{
		//
		$exercise = Exercises::find($id);
		$exercise->delete();

		Event::fire('deletedAnExercise', array(Auth::user(),$exercise->name));

		return $this::responseJson(Messages::showControlPanel("ExerciseDeleted"));
	}



	//=======================================================================================================================
	// API
	//======================================================================================================================



	public function APIsearchExercise(){

		$userId = Auth::user()->id;
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		if(Input::has("pageSize")) $this->searchSize = Input::get("pageSize") + $this->searchSize;
		$search = Exercises::searchExercises(Input::get("search"), $this->searchSize);
		$data = array();
		$data["data"] = $search;
		$data["permissions"] = $permissions;
		$data["total"] = count($search);
		$data["status"] = "ok";
		$data["message"] = "";

		return $this->responseJson($data);


	}

	public function API_Exercise_Model() {
		$userId = Auth::user()->id;
		$exerciseId = -1;
		$permissions = null;
		if(Input::has("exerciseId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("exerciseId"));
			if($permissions["view"]){
				$userId 	= Input::get("userId");
				$exerciseId = Input::get("exerciseId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		$exercise = Exercises::where("id",$exerciseId)->get();
		$exercise["templateSets"]	= TemplateSets::where("exerciseId", $exerciseId)->get();
		$exercise["sets"] 			= array();

		$data = array();
		$data["data"] = $exercise;
		$data["permissions"] = $permissions;
		$data["total"] = 1;
		$data["status"] = "ok";
		$data["message"] = "";

		return $this->responseJson($data);
	}


	// public function API_Exercise_Model() {
	// 	$userId = Auth::user()->id;
	// 	$exerciseId = -1;
	// 	$permissions = null;
	// 	if(Input::has("exerciseId")){
	// 		$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("exerciseId"));
	// 		if($permissions["view"]){
	// 			$userId 	= Input::get("userId");
	// 			$exerciseId = Input::get("exerciseId");
	// 		}
	// 	} else {
	// 		$permissions = Helper::checkPremissions(Auth::user()->id,null);
	// 	}

	// 	$exercise = Exercises::where("id",$exerciseId)->get();
	// 	$exercise["templateSets"]	= TemplateSets::where("exerciseId", $exerciseId)->get();
	// 	$exercise["sets"] 			= array();

	// 	$data = array();
	// 	$data["data"] = $exercise;
	// 	$data["permissions"] = $permissions;
	// 	$data["total"] = 1;
	// 	$data["status"] = "ok";
	// 	$data["message"] = "";

	// 	return $this->responseJson($data);
	// }


}