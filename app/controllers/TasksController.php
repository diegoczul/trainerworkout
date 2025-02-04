<?php

class TasksController extends \BaseController {

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

		if(Input::has("userId")){
			return View::make("widgets.base.tasks")
			->with("tasksOld",Tasks::where("userId","=",$user->id)->where("targetId",$userId)->where("dateStart","<",$date->format("Y-m-d ")." 00:00:00")->take($this->pageSize)->get())
			->with("tasksToday",Tasks::where("userId","=",$user->id)->where("targetId",$userId)->whereBetween("dateStart",array($date->format("Y-m-d")." 00:00:00",$date->format("Y-m-d")." 23:59:59"))->take($this->pageSize)->get())
			->with("tasksNew",Tasks::where("userId","=",$user->id)->where("targetId",$userId)->where("dateStart",">",$date->format("Y-m-d ")."23:59:59")->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("tasksOldTotal",Tasks::where("userId","=",$user->id)->where("targetId",$userId)->where("dateStart","<",$date->format("Y-m-d ")." 00:00:00")->count())
			->with("tasksTodayTotal",Tasks::where("userId","=",$user->id)->where("targetId",$userId)->whereBetween("dateStart",array($date->format("Y-m-d")." 00:00:00",$date->format("Y-m-d")." 23:59:59"))->count())
			->with("tasksNewTotal",Tasks::where("userId","=",$user->id)->where("targetId",$userId)->where("dateStart",">",$date->format("Y-m-d ")." 23:59:59")->count());
		} 


		
		return View::make("widgets.base.tasks")
			->with("tasksOld",Tasks::where("userId","=",$user->id)->where("dateStart","<",$date->format("Y-m-d ")." 00:00:00")->take($this->pageSize)->get())
			->with("tasksToday",Tasks::where("userId","=",$user->id)->whereBetween("dateStart",array($date->format("Y-m-d")." 00:00:00",$date->format("Y-m-d")." 23:59:59"))->take($this->pageSize)->get())
			->with("tasksNew",Tasks::where("userId","=",$user->id)->where("dateStart",">",$date->format("Y-m-d ")."23:59:59")->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("tasksOldTotal",Tasks::where("userId","=",$user->id)->where("dateStart","<",$date->format("Y-m-d ")." 00:00:00")->count())
			->with("tasksTodayTotal",Tasks::where("userId","=",$user->id)->whereBetween("dateStart",array($date->format("Y-m-d")." 00:00:00",$date->format("Y-m-d")." 23:59:59"))->count())
			->with("tasksNewTotal",Tasks::where("userId","=",$user->id)->where("dateStart",">",$date->format("Y-m-d ")." 23:59:59")->count());
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
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}
		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.full.tasks")
			->with("tasks",Tasks::where("userId","=",$userId)->orderBy('recordDate', 'ASC')->take($this->pageSize)->get())
			->with("permissions",$permissions)
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

		$user = Auth::user();
		$userId = $user->id;

		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]) $userId = Input::get("userId");
			if($permissions["add"])	$userId = Input::get("userId");
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		if($permissions["add"]){

			$validation = Tasks::validate(Input::all());
			if($validation->fails()){
				return $this::responseJsonErrorValidation($validation->messages());
			} else {
				$tasks = new Tasks;
				$tasks->value = Input::get("task");
				$tasks->dateStart = Helper::toDateTime(Input::get("dateStart")." ".Input::get("timeStart"));
				$tasks->userId = Auth::user()->id;
				if(Input::get("appointmentTarget") != ""){
					$appointments->targetId = Input::get("appointmentTarget");
				}
				$tasks->name = Input::get("searchAppointmentTarget");
				$tasks->userId = $user->id;
				$tasks->value = Input::get("value");
				$tasks->type = Input::get("type");
				$tasks->save();
				if($tasks->type == "reminder"){
					Feeds::insertFeed("NewReminder",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
					return $this::responseJson(Lang::get("messages.ReminderAdded"));
				} else {
					Feeds::insertFeed("NewTask",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
					return $this::responseJson(Lang::get("messages.TaskAdded"));
				}
					
			}
		} else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		}
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
				Feeds::insertFeed("DeleteReminder",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
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