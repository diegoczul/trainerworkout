<?php

class AppointmentsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /appointments
	 *
	 * @return Response
	 */

	public $pageSize = 9;
	public $pageSizeFull = 9;


	public function index()
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
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;
		$date = new DateTime('today');
		return View::make("widgets.base.appointments")
			->with("appointmentsOld",Appointments::where("userId","=",$userId)->where("dateStart","<",$date->format("Y-m-d ")." 00:00:00")->take($this->pageSize)->get())
			->with("appointmentsToday",Appointments::where("userId","=",$userId)->whereBetween("dateStart",array($date->format("Y-m-d")." 00:00:00",$date->format("Y-m-d")." 23:59:59"))->take($this->pageSize)->get())
			->with("appointmentsNew",Appointments::where("userId","=",$userId)->where("dateStart",">",$date->format("Y-m-d ")."23:59:59")->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("user",$user)
			->with("appointmentsOldTotal",Appointments::where("userId","=",$userId)->where("dateStart","<",$date->format("Y-m-d ")." 00:00:00")->count())
			->with("appointmentsTodayTotal",Appointments::where("userId","=",$userId)->whereBetween("dateStart",array($date->format("Y-m-d")." 00:00:00",$date->format("Y-m-d")." 23:59:59"))->count())
			->with("appointmentsNewTotal",Appointments::where("userId","=",$userId)->where("dateStart",">",$date->format("Y-m-d ")." 23:59:59")->count());
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /appointments/create
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

		$validation = Appointments::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonError(Lang::get("messages.CreateAppointmentError"));
		} else {
			$appointments = new Appointments;
			$appointments->appointment = Input::get("appointment");
			$appointments->dateStart = Helper::toDateTime(Input::get("dateStart"));
			$appointments->dateEnd = Helper::toDateTime(Input::get("dateEnd"));
			if(Input::get("appointmentTarget") != ""){
				$appointments->targetId = Input::get("appointmentTarget");
			}
			$appointments->name = Input::get("searchAppointmentTarget");
			$appointments->userId = $user->id;
			$appointments->save();
			return $this::responseJson(Lang::get("messages.AppointmentAdded"));	
		}
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /appointments
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /appointments/{id}
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
	 * GET /appointments/{id}/edit
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
	 * PUT /appointments/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /appointments/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy()
	{

		if(Input::has("id") and Input::get("id") != ""){
			$obj = Appointments::find(Input::get("id"));
			if(!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));

			if($this->checkPermissions($obj->userId,Auth::user()->id)){
//				Feeds::insertFeed("DeleteAppointment",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
				$obj->delete();
				return $this::responseJson(Lang::get("messages.DeleteAppointment"));
			} else {
				return $this::responseJsonError(Lang::get("messages.Permissions"));
			}
		} else {
			return $this::responseJsonError(Lang::get("messages.AppointmentDeleteError"));
		}
	}
}