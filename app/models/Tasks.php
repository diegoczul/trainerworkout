<?php

class Tasks extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(
		"dateStart" => "required|date",
		//"timeStart" => array("required","regex:/^([01]?[0-9]|2[0-3]):([0-5][0-9])$/"),
		"value" => "required|max:500",
	);

	public function user(){
		return $this->hasOne("Users","id","targetId");
	}

	public static function validate($data){
		return Validator::make($data, static::$rules);
	}


	public static function dailyReminderChecker(){
		$date = new DateTime('today');	
		$user = Auth::user();
		$final = "";

		$tasks = self::where("userId","=",$user->id)->where("dateStart","<",$date->format("Y-m-d H:i:s"))->whereNull("reminded")->where("type","task")->get();
		$replace = array();
		foreach($tasks as $task){
			$friend = $user->id;
			if($task->targetId != ""){
				$final = "TaskDueFriend";
				$friend = $task->targetId;
				$friendObj = Users::find($friend);
				$replace = array();
				$replace = array("firstName" => $friendObj->firstName,"lastName" => $friendObj->lastName);
			} else {
				$final = "TaskDue";
			}
			

			//dd($final);
			Notifications::insertDynamicNotification($final,$user->id,$friend,$replace,true);
			$task->reminded = date("Y-m-d H:i:s");
			$task->save();
		}

		$final = "";
		$appointments = Appointments::where("userId","=",$user->id)->where("dateStart","<",$date->format("Y-m-d H:i:s"))->whereNull("reminded")->get();
		$friend = $user->id;
		$replace = array();
		foreach($appointments as $appointment){
			$friend = $user->id;
			$replace = array();

			if($appointment->targetId != ""){
				$final = "AppointmentDueFriend";
				$friend = $appointment->targetId;
				$friendObj = Users::find($friend);
				$replace = array("firstName" => $friendObj->firstName,"lastName" => $friendObj->lastName);
			} else {
				$final = "AppointmentDue";
			}
			
			Notifications::insertDynamicNotification($final,$user->id,$friend,$replace,true);
			$appointment->reminded = date("Y-m-d H:i:s");
			$appointment->save();
		}

		$final = "";
		$reminders = self::where("userId","=",$user->id)->where("dateStart","<",$date->format("Y-m-d H:i:s"))->whereNull("reminded")->where("type","reminder")->get();
		$friend = $user->id;
		$replace = array();
		foreach($reminders as $reminder){
			$friend = $user->id;
			if($reminder->targetId != ""){
				$final = "ReminderDueFriend";
				$friend = $reminder->targetId;
				$friendObj = Users::find($friend);
				$replace = array();
				$replace = array("firstName" => $friendObj->firstName,"lastName" => $friendObj->lastName);
			} else {
				$final = "ReminderDue";
			}
			
			Notifications::insertDynamicNotification($final,$user->id,$friend,$replace,true);
			$reminder->reminded = date("Y-m-d H:i:s");
			$reminder->save();
		}
	}

}