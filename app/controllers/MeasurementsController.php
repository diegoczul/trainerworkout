<?php

class MeasurementsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /measurements
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
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"),"w_measurements");
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

    	$datay1 = array();
		$datay2  = array();
		$y1 = array();
		$measurements = Measurements::where("userId","=",$userId)->orderBy('recordDate', 'DESC')->get();
		

       	if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;
			          
		return View::make("widgets.base.measurements")
			->with("measurements",Measurements::where("userId","=",$userId)->orderBy('recordDate', 'DESC')->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("user",$user)
			->with("total",Measurements::where("userId","=",$userId)->count());
	}

	public function indexFull()
	{
		$user = Auth::user();
		$userId = Auth::user()->id;
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"),"w_measurements");
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
		}


    	$datay1 = array();
		$datay2  = array();
		$y1 = array();
		$measurements = Measurements::where("userId","=",$userId)->orderBy('recordDate', 'DESC')->get();
		

       	if(Input::has("pageSize")) $this->pageSizeFull = Input::get("pageSize") + $this->pageSizeFull;
			          
		return View::make("widgets.full.measurements")
			->with("measurements",Measurements::where("userId","=",$userId)->orderBy('recordDate', 'DESC')->take($this->pageSizeFull)->get())
			->with("permissions",$permissions)
			->with("user",$user)
			->with("total",Measurements::where("userId","=",$userId)->count());

	}

	/**
	 * Show the form for creating a new resource.
	 * GET /measurements/create
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
		$userId = $user->id;
		
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]) $userId = Input::get("userId");
			if($permissions["add"])	$userId = Input::get("userId");
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		//dd( Input::get("userId"));
		if($permissions["add"]){

			$validation = Measurements::validate(Input::all());
			if($validation->fails()){
				return $this::responseJsonErrorValidation($validation->messages());
			} else {
				$measurements = new Measurements;
				$measurements->chest = Input::get("chest");
				$measurements->recordDate = Input::get("recordDate");
				$measurements->abdominals = Input::get("abdominals");
				$measurements->bicepsLeft = Input::get("bicepsLeft");
				$measurements->bicepsRight = Input::get("bicepsRight");
				$measurements->legsLeft = Input::get("legsLeft");
				$measurements->legsRight = Input::get("legsRight");
				$measurements->forearmLeft = Input::get("forearmLeft");
				$measurements->forearmRight = Input::get("forearmRight");
				$measurements->calfLeft = Input::get("calfLeft");
				$measurements->calfRight = Input::get("calfRight");
				$measurements->waist = Input::get("waist");
				$measurements->userId = $userId;
				$measurements->save();
				Feeds::insertFeed("NewMeasurement",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName,"measurementsAdded");
				$trainers = Clients::returnAllTrainersOfClient($user->id);
				foreach($trainers as $trainer){
					Notifications::insertDynamicNotification("NewMeasurementClient",$trainer->trainerId,$user->id,array("clientFirstName"=>$user->firstName,"clientLastName"=>$user->lastName,"clientLink"=>$user->clientLink()),true,null,"message","measurements","feed");
				}
				return $this::responseJson(Lang::get("messages.MeasurementAdded"));	
			}
		} else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		}
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /measurements
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /measurements/{id}
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
	 * GET /measurements/{id}/edit
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
	 * PUT /measurements/{id}
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
	 * DELETE /measurements/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

		$obj = Measurements::find($id);
		if(!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));

		if($this->checkPermissions($obj->userId,Auth::user()->id)){
//			Feeds::insertFeed("DeletedMeasurement",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			$obj->delete();
			return $this::responseJson(Lang::get("messages.MeasurementDeleted"));
		} else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		}
		
	}

}