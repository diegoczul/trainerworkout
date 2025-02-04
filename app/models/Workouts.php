<?php

class Workouts extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(
		"price" => "numeric",
	);


	public function delete(){

			TemplateSets::where("workoutId",$this->id)->delete();
			WorkoutsExercises::where("workoutId",$this->id)->delete();
			WorkoutsGroups::where("workoutId",$this->id)->delete();
			Sets::where("workoutId",$this->id)->delete();
		
        return parent::delete();
	}

	public function restore(){

			TemplateSets::where("workoutId",$this->id)->restore();
			WorkoutsExercises::where("workoutId",$this->id)->restore();
			WorkoutsGroups::where("workoutId",$this->id)->restore();
			Sets::where("workoutId",$this->id)->restore();
		
        return parent::restore();
	}

	public function forceDelete(){

			TemplateSets::where("workoutId",$this->id)->forceDelete();
			WorkoutsExercises::where("workoutId",$this->id)->forceDelete();
			WorkoutsGroups::where("workoutId",$this->id)->forceDelete();
			Sets::where("workoutId",$this->id)->forceDelete();
		
        return parent::forceDelete();
	}

	public function archive(){

		$this->archived_at = date("Y-m-d h:i:s");
		$this->save();

	}

	public function unArchive(){

		$this->archived_at = null;
		$this->save();
		
	}

	public function subcribeToWorkout($trainerId,$subscribe=true){
		if($subscribe == "true"){
				
				$update = UserUpdates::where("trainerId",$trainerId)->Where("auxId", $this->id)->where("type","workout")->first();
				if(!$update){ 
					$update = new UserUpdates;
					$update->trainerId = $trainerId;
					$update->userId = $this->userId;
					$update->auxId = $this->id;
					$update->parentAuxId = $this->master;
					$update->type = "workout";
					$update->subscribe = 1;
					$update->save();
				} else {
					$update->subscribe = 1;
					$update->save();
				}

			} else{
				$update = UserUpdates::where("trainerId",$trainerId)->Where("auxId", $this->id)->where("type","workout")->first();
				if(!$update){ 
					$update = new UserUpdates;
					$update->trainerId = $trainerId;
					$update->userId = $this->userId;
					$update->auxId = $this->id;
					$update->parentAuxId = $this->master;
					$update->type = "workout";
					$update->subscribe = 0;
					$update->save();
				} else {
					$update->subscribe = 0;
					$update->save();
				}
			}
	}

	public function deleteWorkoutContents(){

			TemplateSets::where("workoutId",$this->id)->delete();
			WorkoutsExercises::where("workoutId",$this->id)->delete();
			WorkoutsGroups::where("workoutId",$this->id)->delete();
			Sets::where("workoutId",$this->id)->delete();
	}

	public function restoreWorkout($workoutId){
		TemplateSets::where("workoutId",$this->id)->restore();
			WorkoutsExercises::where("workoutId",$this->id)->restore();
			WorkoutsGroups::where("workoutId",$this->id)->restore();
			Sets::where("workoutId",$this->id)->restore();
	}



	public function scopeSearch($query,$search){
			if($search != ""){
				$result = null;
				$query = self::select(
							"workouts.*",
							DB::raw("MATCH(workouts.name) AGAINST(? in BOOLEAN MODE) AS scoreName"),
							DB::raw("MATCH(workouts.name,workouts.description) AGAINST(? in BOOLEAN MODE) AS scoreNameDescription"),
							DB::raw("Match (workouts.description) Against (? in BOOLEAN MODE) as scoreDescription"),
							DB::raw("Match (workouts.tags) Against (? in BOOLEAN MODE) as scoreTags"),
							DB::raw("CHAR_LENGTH(workouts.name) as multiplier")
							)
							->where(function($query) use ($search)
												 {
												       $query->orWhereRaw( "Match (workouts.name) Against (? in BOOLEAN MODE) > 0 ");
												       $query->orWhereRaw( "Match (workouts.name,workouts.description) Against (? in BOOLEAN MODE) > 0 ");
												       $query->orWhereRaw( "Match (workouts.description) Against (? in BOOLEAN MODE) > 0 ");
												       $query->orWhereRaw( "Match (workouts.tags) Against (? in BOOLEAN MODE) > 0 ");
												 })
							->orderBy("scoreName","DESC")
							->orderBy("scoreTags","DESC")
							->orderBy("scoreNameDescription","DESC")
							->orderBy("scoreDescription","DESC")
							->orderBy("multiplier","ASC")
							->orderBy("shares","DESC")
							->orderBy("views","DESC")
							->setBindings(array($search."*", $search."*", $search."*", $search."*", $search."*", $search."*", $search."*", $search."*"));
			}
			return $query;
	}   

	public function duplicateTemplateFrom($workoutFrom){

		$objects = WorkoutsGroups::where("workoutId",$workoutFrom->id)->get();
		foreach($objects as $obj){
			$newObj = $obj->replicate();
			$newObj->workoutId = $this->id;
			$newObj->save();

			$objects2 = WorkoutsExercises::where("workoutId",$workoutFrom->id)->where("groupId",$obj->id)->get();
			foreach($objects2 as $obj2){
				$newObj2 = $obj2->replicate();
				$newObj2->workoutId = $this->id;
				$newObj2->groupId = $newObj->id;
				$newObj2->save();

				$objects3 = TemplateSets::where("workoutId",$workoutFrom->id)->where("workoutsExercisesId",$obj2->id)->get();
				foreach($objects3 as $obj3){
					$newObj3 = $obj3->replicate();
					$newObj3->workoutId = $this->id;
					$newObj3->workoutsExercisesId = $newObj2->id;
					$newObj3->save();
				}
			}
		}

		

		

	}


	public function isOwner(){
		if($this->userId == Auth::user()->id) return true;

		return false;
	}


	public function isAuthor(){
		if($this->authorId == Auth::user()->id) return true;

		return false;
	}

	public function resetWorkout(){
		$this->shares = 0;
		$this->views = 0;
		$this->timesPerformed = 0;
		$this->timesPerWeek = 0;
		$this->averageCompleted = 0;
		$this->lastRevized = null;
		$this->timesPerformedRevized = 0;
		$this->save();
	}


	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

	public function templateSets(){
		return $this->hasMany("TemplateSets","workoutId","id");
	}

	public function workoutsExercises(){
		return $this->hasMany("WorkoutsExercises","workoutId","id");
	}

	public function workoutsGroups(){
		return $this->hasMany("WorkoutsGroups","workoutId","id");
	}


	public function sets(){
		return $this->hasMany("Sets","workoutId","id");
	}

	public function author(){
		return $this->hasOne("Users","id","authorId");
	}

	public function user(){
		return $this->hasOne("Users","id","userId");
	}

	public function authors(){
		return $this->hasOne("Users","id","authorId")->first();
	}

	public function trainer(){
		return $this->hasOne("Users","id","trainerMonitoringId");
	}

	public function users(){
		return $this->hasOne("Users","id","authorId")->first();
	}

	public function getExercises(){
		return WorkoutsExercises::with("exercises")->where("workoutId",$this->id)->orderBy("order");
	}

	public function getGroups(){
		return WorkoutsGroups::where("workoutId",$this->id)->orderBy("groupNumber");
	}

	public function getSets($workoutsExercisesId){
		return Sets::with("workoutsExercises")->where("workoutId",$this->id)->where("workoutsExercisesId",$workoutsExercisesId)->orderBy("number","ASC")->orderBy("id","ASC")->get();
	}

	public function getTemplateSets($workoutsExercisesId){
		return TemplateSets::where("workoutId",$this->id)->where("workoutsExercisesId",$workoutsExercisesId)->orderBy("id","ASC")->get();
	}

	public function getExercisesImagesWidget(){
		$images = array();
		$exercises = $this->getExercises()->get();
	
		$index = 0;
		$images = array_fill(0,5,"");

		foreach($exercises as $exercise){
			
			$images[$index] = $exercise->exercises->image;
			$index++;
			if($index > 5){
				break;
			}
		}
		
		return $images;
	}

	public function createSets(){
		$workoutExercises = WorkoutsExercises::where("workoutId",$this->id)->orderBy("id","ASC")->get();
		foreach($workoutExercises as $workoutExercise){
			$this->createNewSetsExercise($workoutExercise->id);
		}

	}

	public function scopeforSale($query){
		return $query->where("sale",1);
	}

	public function scopeReleased($query){
		return $query->where("status","Released");
	}

	public function scopeforSaleFree($query){
		return $query->where("sale",1)->where("price","=","0");
	}

	public function scopeforSalePaid($query){
		return $query->where("sale",1)->where("price",">","0");
	}


	public function createNewSetsExercise($workoutsExercise){
		$templateSets = TemplateSets::where("workoutId",$this->id)->where("workoutsExercisesId",$workoutsExercise)->orderBy("created_at","Desc")->orderBy("number","Asc")->get();
		$numberOfTemplateSets = count($templateSets);
		$sets = Sets::where("workoutId",$this->id)->where("workoutsExercisesId",$workoutsExercise)->orderBy("created_at","Desc")->orderBy("number","Asc")->get();
		Sets::where("workoutId",$this->id)->where("workoutsExercisesId",$workoutsExercise)->update(array("last"=>0));
		$index = 0;

		foreach($templateSets as $templateSet){
			$set = new Sets();
			$set->exerciseId = $templateSet->exerciseId;
			$set->number = count($sets)+$templateSet->number;
			$set->reps = $templateSet->reps;
			$set->metric = $templateSet->metric;
			if($index < count($sets)){
				$set->weight = $sets[$index]->weight;
				$set->weightKG = $sets[$index]->weightKG;
			} else {
				$set->weight = $templateSet->weight;
				$set->weightKG = $templateSet->weightKG;
			}
			$set->rest = $templateSet->rest;
			$set->tempo = $templateSet->tempo;
			$set->units = $templateSet->units;
			$set->type = $templateSet->type;
			$set->distance = $templateSet->distance;
			$set->speed = $templateSet->speed;
			$set->bpm = $templateSet->bpm;
			$set->time = $templateSet->time;
			$set->notes = $templateSet->notes;
			$set->workoutId = $templateSet->workoutId;
			$set->workoutsExercisesId = $templateSet->workoutsExercisesId;
			$set->completed = 0;
			$set->last = 0;
			if(count($templateSets) == $index+1) $set->last = 1;
			$set->save();
			$index++;
		}
	}

	public function getURL(){

		if($this->userId == Auth::user()->id or $this->authorId == Auth::user()->id){
			return Lang::get("routes.Workout/").$this->id."/".Helper::formatURLString($this->name)."/".(($this->author) ? Helper::formatURLString($this->author->firstName.$this->author->lastName) : "");
		} elseif($this->master != "") {
			$workoutMaster = Workouts::find($this->master);
			if($workoutMaster and ($workoutMaster->userId == Auth::user()->id or $workoutMaster->authorId == Auth::user()->id)){
				return Lang::get("routes.Workout/").$this->id."/".Helper::formatURLString($this->name)."/".(($this->author) ? Helper::formatURLString($this->author->firstName.$this->author->lastName) : "");
			}
		}
		return Lang::get("routes.Workout/").$this->id."/".Helper::formatURLString($this->name)."/".(($this->author) ? Helper::formatURLString($this->author->firstName.$this->author->lastName) : "");
	}

	public function getEditURL(){
		if($this->userId == Auth::user()->id or $this->authorId == Auth::user()->id)
			return Lang::get("routes./editWorkout/").$this->id."/".Helper::formatURLString($this->name)."/".(($this->author) ? Helper::formatURLString($this->author->firstName.$this->author->lastName) : "");
		return "#";
	}


	public function getURLImage(){


		return "/WorkoutInternal/".$this->id."/".App::getLocale()."/".Helper::formatURLString($this->name)."/".Helper::formatURLString($this->author->firstName.$this->author->lastName);

	}

	public function getURLPrint(){

		return "/Workout/PrintWorkoutInternal/".$this->id."/".App::getLocale();

	}

	public function getPDF(){

		$data["workout"] = $this;
		$data["user"] = Auth::user();
		$data["groups"] = $this->getGroups()->get();
		$data["exercises"] = $this->getExercises()->get();

		$this->incrementViews();
		$pdf = PDF::loadfile(URL::to("Workout/PrintWorkoutInternal/".$this->id));
		$pdf->setOptions(array(
				"orientation" => "landscape",			
			));
		//$name = GUID::generate();
		if(trim($this->name) != ""){
			$name = Config::get("constants.filePrefix").Helper::formatURLString($this->name);
		} else {
			$name = GUID::generate();
		}
        $name_temp = storage_path()."/temp/".$name."_grid.pdf";
        if(File::exists($name_temp)) File::delete($name_temp);
		$pdf->save($name_temp);
		Event::fire('pdfWorkout', array(Auth::user(),$this->name));
		return $name_temp;
	}

	public function getImagePDF(){

		$data["workout"] = $this;
		$data["user"] = Auth::user();
		$data["groups"] = $this->getGroups()->get();
		$data["exercises"] = $this->getExercises()->get();
		$image = Image2::loadFile(URL::to($this->getURLImage()));
		$pdf = PDF::loadfile(URL::to($this->getURLImage()));

		//$name = GUID::generate();
		if(trim($this->name) != ""){
			$name = Config::get("constants.filePrefix").Helper::formatURLString($this->name);
		} else {
			$name = GUID::generate();
		}
        $name_temp = storage_path()."/temp/".$name.".pdf";
        if(File::exists($name_temp)) File::delete($name_temp);
		$pdf->save($name_temp);
		//Event::fire('pdfWorkout', array(Auth::user(),$this->name));
		return $name_temp;
	}

	public function getPrintPDF(){

		$data["workout"] = $this;
		$data["user"] = Auth::user();
		$data["groups"] = $this->getGroups()->get();
		$data["exercises"] = $this->getExercises()->get();

		$pdf = PDF::loadfile(URL::to($this->getURLPrint()));
		$pdf->setOptions(array(
				"orientation" => "landscape",
			));

		//$name = GUID::generate();

		//$name = GUID::generate();
		if(trim($this->name) != ""){
			$name = Config::get("constants.filePrefix").Helper::formatURLString($this->name);
		} else {
			$name = GUID::generate();
		}

        $name_temp = storage_path()."/temp/".$name."_grid.pdf";
        if(File::exists($name_temp)) File::delete($name_temp);
		$pdf->save($name_temp);

		$merger = new LynX39\LaraPdfMerger\PdfManage;
		$merger->addPDF($name_temp);
		$merger->addPDF(Config::get("constants.gridPDF"));
		$merger->merge('file', $name_temp, 'L');

		//Event::fire('pdfWorkout', array(Auth::user(),$this->name));
		return $name_temp;
	}

	public function getImageScreenshot(){

		$data["workout"] = $this;
		$data["user"] = Auth::user();
		$data["groups"] = $this->getGroups()->get();
		$data["exercises"] = $this->getExercises()->get();
		$image = Image2::loadFile(URL::to($this->getURLImage()));
		//$name = GUID::generate();
		if(trim($this->name) != ""){
			$name = Config::get("constants.filePrefix").Helper::formatURLString($this->name);
		} else {
			$name = GUID::generate();
		}
        $name_temp = storage_path()."/temp/".$name.".jpg";
        if(File::exists($name_temp)) File::delete($name_temp);
		$image->save($name_temp);

		return $name_temp;
	}

	public function getURLPDF(){
		if($this->userId == Auth::user()->id or $this->authorId == Auth::user()->id)
			return "WorkoutPDF/".$this->id."/".Helper::formatURLString($this->name)."/".Helper::formatURLString($this->author->firstName.$this->author->lastName);
		return "Workout/Preview/".$this->id."/".Helper::formatURLString($this->name)."/".Helper::formatURLString($this->firstName.$this->lastName);
	}


	public function lastPerformed(){
		$lastDatePerformed = Sets::select("updated_at")->where("workoutId",$this->id)->where("completed",1)->orderBy("updated_at","Desc")->first();
		if($lastDatePerformed){
			return $lastDatePerformed->updated_at;
		} else{
			return date("Y-m-d h:i:s");
		}
	}

	public function getStartedDate(){
		$date = WorkoutLog::where("workoutId",$this->id)->where("userId",$this->userId)->min("datePerformed");
		if($date){
			return $date;
		} else {
			return $this->created_at;
		}
	}


	public function getCountPerformed($userId){
		$date = WorkoutsPerformances::where("workoutId",$this->id)->where("userId",$userId)->count();
		if($date){
			return $date;
		} else {
			return 0;
		}
	}

	public function getAveragePerWeek(){
		$dates = array();
		$date = WorkoutLog::where("workoutId",$this->id)->where("userId",$this->userId);
		foreach($date as $dat){
			$da = date_create($dat->datePerformed);
			if(array_key_exists(date_format($da,'Y-m-d'),$dates)){
				$dates[$da] += 1;
			} else {
				$dates[$da] = 1;
			}	
		}
		$average = array_sum($dates) / count($dates);
		return $average;
	}

	public function getAverageCompleted(){
		$totalSets = Sets::where("workoutId",$this->id)->count();
		$sets = Sets::where("workoutId",$this->id)->where("completed",1)->count();
		return number_format($sets/$totalSets*100,0);
		//return number_format($sets/$totalSets*100,10);
	}

	public function incrementViews(){
		$this->views = $this->views+1;
		$this->save();
	}

	public function incrementShares(){
		$this->shares = $this->shares+1;
		$this->save();
	}

	public function canThisWorkoutBeShared($userId){

		if($userId->id == $this->authorId or $this->lock == 0 or $this->id == 4652){
			return true;
		} else {
			return false;
		}
		
	}

	public function markAsCompleted(){
		$this->averageCompleted = $this->getAverageCompleted();
		//dd($this->averageCompleted);
		if(Sets::where("workoutId",$this->id)->where("created_at",">=",date("Y-m-d")." 00:00:00")->where("created_at","<=",date("Y-m-d")." 23:59:59")->where("completed",1)->count() <= 1){
			$this->setTimesPerWeek();
			$this->timesPerformed = $this->timesPerformed + 1;
			$this->timesPerformedRevized = $this->timesPerformedRevized + 1;
			$this->averageCompleted = $this->getAverageCompleted();
			$this->save();
			Feeds::insertDynamicFeed("WorkoutCompleted",Auth::user()->id,Auth::user()->id,array("firstName"=>Auth::user()->firstName,"lastName"=>Auth::user()->lastName,"workout"=>$this->name),"workoutPerformed",$this->getURL(),"workout");
			
			// if(!Notifications::checkIfTrainerNotifiedTodayWorkout($this->userId,null)){
			// 	$trainers = Clients::where("userId",$this->userId)->distinct()->lists("trainerId");
			// 	if(count($trainers > 0)){
			// 		foreach($trainers as $trainer){
			// 			Notifications::insertDynamicNotification(Lang::get("messagesTraineeCompleted"),$trainer,$this->userId,array(),false);
			// 		}
			// 	}
			// }


		}
	}

	public function setTimesPerWeek(){
		$check = DB::table("sets")->select(DB::raw(" distinct date(updated_at)"))->where("completed",1)->where("workoutId",$this->id)->where(DB::raw("str_to_date(concat(yearweek(updated_at), ' monday'), '%X%V %W')"),">=",DB::raw("str_to_date(concat(yearweek(curdate()), ' monday'), '%X%V %W')"))->count();
		$this->timesPerWeek = $check;
		$this->save();
	
	}

	public static function AddWorkoutToUser($workoutId,$userId,$author=false,$lock=true){

		$workout = Workouts::find($workoutId);
		$workoutNew = null;
		if($workout){
			$workoutNew = $workout->replicate();
			$workoutNew->userId = $userId;
			if($author) $workoutNew->authorId = $userId;
			$workoutNew->shares = 0;
			$workoutNew->views = 0;
			$workoutNew->lock = ($lock) ? 1 : 0;
			if(!$lock) $workoutNew->authorId = $userId;
			$workoutNew->availability = "private";
			$workoutNew->timesPerformed = 0;
			$workoutNew->save();
			$workoutNew->resetWorkout();

			$workoutNew->duplicateTemplateFrom($workout);

			$workoutNew->createSets();


			$workout->shares++;
			$workout->save();

			
		}

		return $workoutNew;

	}

	public static function copyWorkoutsFromTo($fromId,$toId){
		$workouts = Workouts::where("userId",$fromId)->get();
		foreach($workouts as $workout){
			if($workout->master != "" and $workout->master != 0){
				if(Workouts::where("userId",$toId)->where("master",$workout->master)->count() == 0){
					Workouts::AddWorkoutToUser($workout->id,$toId);
				}
			}
		}
	}



}