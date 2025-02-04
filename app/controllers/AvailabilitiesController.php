<?php

class AvailabilitiesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /tasks
	 *
	 * @return Response
	 */

	public $pageSize = 9;
	public $pageSizeFull = 9;


	public function index()
	{

		$userId = Auth::user()->id;
		$user = Auth::user();

		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;
		$date = new DateTime('today');	
		return View::make("widgets.base.tasks")
			->with("tasksOld",Tasks::where("userId","=",$userId)->where("dateStart","<",$date->format("Y-m-d ")." 00:00:00")->take($this->pageSize)->get())
			->with("tasksToday",Tasks::where("userId","=",$userId)->whereBetween("dateStart",array($date->format("Y-m-d")." 00:00:00",$date->format("Y-m-d")." 23:59:59"))->take($this->pageSize)->get())
			->with("tasksNew",Tasks::where("userId","=",$userId)->where("dateStart",">",$date->format("Y-m-d ")."23:59:59")->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("user",$user)
			->with("tasksOldTotal",Tasks::where("userId","=",$userId)->where("dateStart","<",$date->format("Y-m-d ")." 00:00:00")->count())
			->with("tasksTodayTotal",Tasks::where("userId","=",$userId)->whereBetween("dateStart",array($date->format("Y-m-d")." 00:00:00",$date->format("Y-m-d")." 23:59:59"))->count())
			->with("tasksNewTotal",Tasks::where("userId","=",$userId)->where("dateStart",">",$date->format("Y-m-d ")." 23:59:59")->count());
	}

	public function addEntry($start,$end){


		return View::make("popups.calendar")
			->with("start",$start)
			->with("end",$end);
	}

	public function indexFull()
	{

		$userId = Auth::user()->id;
		$user = Auth::user();
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}
		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.full.tasks")
			->with("tasks",Tasks::where("userId","=",$userId)->orderBy('recordDate', 'ASC')->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("user",$user)
			->with("total",Tasks::where("userId","=",$userId)->count());
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /tasks/create
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


	public function create()
	{
		$user = Auth::user();


			// if(Input::get("type") == "Task"){
			// 	$tasks = new Tasks;
			// 	$tasks->value = Input::get("task");
			// 	$tasks->dateStart = Helper::toDateTime(Input::get("dateStart")." ".Input::get("timeStart"));
			// 	$tasks->dateEnd = Helper::toDateTime(Input::get("dateEnd")." ".Input::get("timeEnd"));
			// 	$tasks->userId = Auth::user()->id;
			// 	if(Input::get("appointmentTarget") != ""){
			// 		$tasks->targetId = Input::get("appointmentTarget");
			// 	}
			// 	$tasks->name = Input::get("searchAppointmentTarget");
			// 	$tasks->userId = $user->id;
			// 	$tasks->value = Input::get("task");
			// 	$tasks->type = Input::get("type");
			// 	$tasks->save();
			// 	return $this::responseJson(Lang::get("messages.TaskAdded"));
			// }
			// if(Input::get("type") == "Reminder"){
			// 	$tasks = new Tasks;
			// 	$tasks->value = Input::get("task");
			// 	$tasks->dateStart = Helper::toDateTime(Input::get("dateStart")." ".Input::get("timeStart"));
			// 	$tasks->dateEnd = Helper::toDateTime(Input::get("dateEnd")." ".Input::get("timeEnd"));
			// 	$tasks->userId = Auth::user()->id;
			// 	if(Input::get("appointmentTarget") != ""){
			// 		$tasks->targetId = Input::get("appointmentTarget");
			// 	}
			// 	$tasks->name = Input::get("searchAppointmentTarget");
			// 	$tasks->userId = $user->id;
			// 	$tasks->value = Input::get("task");
			// 	$tasks->type = Input::get("type");
			// 	$tasks->save();
			// 	Feeds::insertFeed("NewReminder",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			// 	return $this::responseJson(Lang::get("messages.ReminderAdded"));
			// }
			if(Input::get("type") == "Appointment"){
				$appointments = new Appointments;
				$appointments->dateStart = Helper::toDateTime(Input::get("dateStart")." ".Input::get("timeStart"));
				$appointments->dateEnd = Helper::toDateTime(Input::get("dateEnd")." ".Input::get("timeEnd"));
				if(Input::get("appointmentTarget") != ""){
					$appointments->targetId = Input::get("appointmentTarget");
				}
				$appointments->name = Input::get("searchAppointmentTarget");
				$appointments->userId = $user->id;
				$appointments->save();
//				Feeds::insertFeed("NewAppointment",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
				return $this::responseJson(Lang::get("messages.AppointmentAdded"));
				
			}
			if(Input::get("type") == "Availability"){
				$availability = new Availabilities;
				$availability->title = Input::get("appointment");
				$availability->description = Input::get("task");
				$availability->dateStart = Helper::toDateTime(Input::get("dateStart")." ".Input::get("timeStart"));
				$availability->dateEnd = Helper::toDateTime(Input::get("dateEnd")." ".Input::get("timeEnd"));
				if(Input::get("appointmentTarget") != ""){
					$availability->targetId = Input::get("appointmentTarget");
				}
				$availability->name = Input::get("searchAppointmentTarget");
				$availability->userId = $user->id;
				$availability->save();
				Feeds::insertFeed("NewAvailailibility",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
				return $this::responseJson(Lang::get("messages.AvailabilityAdded"));
			}				
		
	}

	public function updateEvent(){

		if(Input::get("type") == "Appointment"){
			$appointment = Appointments::find(Input::get("eventId"));
			if($appointment){
				$appointment->dateStart = Helper::toDateTime(date("Y-m-d H:i:s",strtotime(Input::get("start"))));
				$appointment->dateEnd = Helper::toDateTime(date("Y-m-d H:i:s",strtotime(Input::get("end"))));
				$appointment->save();
				return $this::responseJson(Lang::get("messages.AppointmentUpdated"));
			}
			
		}
		if(Input::get("type") == "Reminder"){
			$task = Tasks::find(Input::get("eventId"));
			if($task){
				$task->dateStart = Helper::toDateTime(date("Y-m-d H:i:s",strtotime(Input::get("start"))));
				$task->dateEnd = Helper::toDateTime(date("Y-m-d H:i:s",strtotime(Input::get("end"))));
				$task->save();
				return $this::responseJson(Lang::get("messages.ReminderUpdated"));
			}
			
		}
		if(Input::get("type") == "Task"){
			$task = Tasks::find(Input::get("eventId"));
			if($task){
				$task->dateStart = Helper::toDateTime(date("Y-m-d H:i:s",strtotime(Input::get("start"))));
				$task->dateEnd = Helper::toDateTime(date("Y-m-d H:i:s",strtotime(Input::get("end"))));
				$task->save();
				return $this::responseJson(Lang::get("messages.TaskUpdated"));
			}
		}

		if(Input::get("type") == "Availability"){
			$availability = Availabilities::find(Input::get("eventId"));
			if($availability){
				$availability->dateStart = Helper::toDateTime(date("Y-m-d H:i:s",strtotime(Input::get("start"))));
				$availability->dateEnd = Helper::toDateTime(date("Y-m-d H:i:s",strtotime(Input::get("end"))));
				$availability->save();
				return $this::responseJson(Lang::get("messages.AvailabiltiyUpdated"));
			}
		}
	}

	public function getCalendar(){
		$user = Auth::user();

		$allEntries = array();

		$appointments = Appointments::where("userId",$user->id)->get();
		$tasks = Tasks::where("userId",$user->id)->where("type","Task")->get();
		$reminders = Tasks::where("userId",$user->id)->where("type","Reminder")->get();
		$availabilities = Availabilities::where("userId",$user->id)->get();

		foreach($appointments as $appointment){
			$name = "";
			if($appointment->name != "") $name = " ".$appointment->name;
			$entry = array(
					"start" => $appointment->dateStart,
					"end" => $appointment->dateEnd,
					"title" => $appointment->appointment.$name ,
					"color" => "#0066cc",
					"type" => "Appointment",
					"eventId" => $appointment->id
			);
			array_push($allEntries,$entry);
		}

		foreach($tasks as $task){
			$name = "";
			if($task->name != "") $name = " ".$task->name;
			$entry = array(
					"start" => $task->dateStart,
					"end" => $task->dateEnd,
					"title" => $task->value.$name,
					"color" => "#999999",
					"type" => "Task",
					"eventId" => $task->id
			);
			array_push($allEntries,$entry);
		}

		//ORANGE
		foreach($reminders as $reminder){
			$name = "";
			if($task->reminder != "") $name = " ".$task->reminder;
			$entry = array(
					"start" => $reminder->dateStart,
					"end" => $reminder->dateEnd,
					"title" => $reminder->value.$name,
					"color" => "#ffa000",
					"type" => "Reminder",
					"eventId" => $reminder->id
			);
			array_push($allEntries,$entry);
		}

		//ORANGE
		foreach($availabilities as $availability){
			$name = "";
			
			$entry = array(
					"start" => $availability->dateStart,
					"end" => $availability->dateEnd,
					"title" => $availability->description,
					"color" => "#551a8b",
					"type" => "Availability",
					"eventId" => $availability->id
			);
			array_push($allEntries,$entry);
		}

		return $this->responseJson($allEntries);
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /tasks
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /tasks/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /tasks/{id}/edit
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
	 * PUT /tasks/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	public function completeTask(){
		$task = Tasks::find(Input::get("task"));
		if($task){
			if($task->completed != ""){
				$task->completed = null;
			} else {
				$task->completed = date("Y-m-d H:i:s");
			}
			$task->save();
			if($task->type == "reminder") {
//				Feeds::insertFeed("UpdatedReminder",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
				return $this::responseJson(Lang::get("messages.ReminderCompleted"));
			}
//			Feeds::insertFeed("UpdatedTask",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			return $this::responseJson(Lang::get("messages.TaskCompleted"));
		}
		return $this::responseJsonError(Lang::get("messages.NotFound"));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /tasks/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$obj = Tasks::find($id);
		if(!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));

		if($this->checkPermissions($obj->userId,Auth::user()->id)){
			if($obj->type == "reminder"){
//				Feeds::insertFeed("DeleteReminder",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
				$obj->delete();
				return $this::responseJson(Lang::get("messages.ReminderDeleted"));
			} else {
//				Feeds::insertFeed("DeleteTask",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
				$obj->delete();
				return $this::responseJson(Lang::get("messages.TaskDeleted"));
			}
			
		} else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		}
		
	
	}

}