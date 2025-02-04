<?php

class Clients extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public function user(){
		return $this->hasOne("Users","id","userId");
	}

	public function trainer(){
		return $this->hasOne("Users","id","trainerId");
	}


	public static function checkIfTrainerHasClient($trainerId,$userId){
		$clients = self::where("trainerId",$trainerId)->where("userId",$userId)->count();
		if($clients > 0){
			return true;
		}
		return false;
	}

	public static function checkIfTraineeHasTrainer($trainee,$trainer){
		$clients = self::where("trainerId",$trainer)->where("userId",$trainee)->count();
		if($clients > 0){
			return true;
		}
		return false;
	}

	public function numberOfWorkoutsSharedFromTrainerToClient($trainerId){
		return Workouts::where("userId",$this->userId)->where("authorId",$trainerId)->count();
	}

	public function lastWorkoutPerformedFromTrainer($trainerId){
		 $workoutPerformance = Workoutsperformances::where("userId",$this->userId)->where("forTrainer",$trainerId)->orderBy("created_at","Desc")->first();
		 if($workoutPerformance){
		 	$now = time();
		 	$your_date = strtotime($workoutPerformance->created_at);
			$datediff = $now - $your_date;
			return floor($datediff / (60 * 60 * 24));
		 } else {
		 	return -1;
		 }
	}

	public function latestWorkoutSharedName($trainerId){
		$workout = Workouts::where("userId",$this->userId)->where("authorId",$trainerId)->orderBy("created_at","DESC")->first();
		if($workout){
			return $workout->name;
		} else {
			return Lang::get("messages.NoWorkoutShared");
		}
	}


	public static function returnAllTrainersOfClient($clientId){
		$trainers = Clients::select("trainerId")->with("trainer")->where("userId",$clientId)->distinct()->get();
		return $trainers;
	}

	public static function trainerAddWorkoutToClient($trainerId,$userId,$workoutId){
		$workout = Workouts::find($workoutId);
				if($workout){
					$workoutNew = new Workouts();
					$workoutNew->name = $workout->name;
					$workoutNew->shares = 0;
					$workoutNew->views = 0;
					$workoutNew->timesPerformed = 0;
					$workoutNew->objectives = $workout->objectives;
					$workoutNew->userId = $userId;
					$workoutNew->authorId = $trainerId;
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
				}

	}

	public function subscribeToClient($trainerId,$subscribe=true){
		if($subscribe == "true"){
				
				$update = UserUpdates::where("trainerId",$trainerId)->Where("auxId", $this->id)->where("type","client")->first();
				if(!$update){ 
					$update = new UserUpdates;
					$update->trainerId = $trainerId;
					$update->userId = $this->userId;
					$update->auxId = $this->id;
					$update->parentAuxId = $this->master;
					$update->type = "client";
					$update->subscribe = 1;
					$update->save();
				} else {
					$update->subscribe = 1;
					$update->save();
				}

			} else{
				$update = UserUpdates::where("trainerId",$trainerId)->Where("auxId", $this->id)->where("type","client")->first();
				if(!$update){ 
					$update = new UserUpdates;
					$update->trainerId = $trainerId;
					$update->userId = $this->userId;
					$update->auxId = $this->id;
					$update->parentAuxId = $this->master;
					$update->type = "client";
					$update->subscribe = 0;
					$update->save();
				} else {
					$update->subscribe = 0;
					$update->save();
				}
			}
	}

	public function link(){
		return "/Client/".$this->user->id."/".Helper::formatURLString($this->user->getCompleteName());
	}
}