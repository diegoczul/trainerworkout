<?php

class CalendarController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /calendar
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


		
		

		$dateEnd = date( 'Y-m-d', strtotime( 'today' ) );
		$dateStart = date( 'Y-m-d', strtotime($dateEnd.' - 1 month'));

		$activities = array();

		if(Input::has("arrayData")){
			$arrayData = json_decode(Input::get("arrayData"),true);
			if(is_array($arrayData)){
				if(array_key_exists("search", $arrayData) and $arrayData["search"] != "") $search = $arrayData["search"];
				if(array_key_exists("archive", $arrayData) and $arrayData["archive"] == "true") $archive = true;
			}
		}


		$days = $this->dateDifference($dateEnd,$dateStart);

		for($x = 0; $x < $days; $x++){
			$currentDate = date( 'Y-m-d', strtotime($dateEnd.' + 1 day'));
			$activities[$currentDate] = array();
			$activities[$currentDate]["performance"] =  array(); 
		}

		$performances = Workoutsperformances::where("forTrainer",Auth::user()->id)->where("userId",$clientUserId)->whereNotNull("dateCompleted")->get();

		foreach($performances as $performance){

			if($currentDate <= date($performance->dateCompleted) and date($performance->dateCompleted) <  date ("Y-m-d", strtotime("+1 day", strtotime($currentDate)))){
					$key = new DateTime($performance->dateCompleted);
					$key = $key->format('Y-m-d');
					array_push($activities[$currentDate]["performance"],$performance);
			}
		}




		return View::make("widgets.base.activityCalendar")
			->with("dateStart",$dateStart)
			->with("dateEnd",$dateEnd)
			->with("activities",$activities)
			->with("permissions",$permissions)
			->with("user",$user);
	}

	function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
	{
	    $datetime1 = date_create($date_1);
	    $datetime2 = date_create($date_2);
	   
	    $interval = date_diff($datetime1, $datetime2);


	   
	    return $interval->format($differenceFormat);
	   
	}

	public function indexFull()
	{

		$userId = Auth::user()->id;
		$user = Auth::user();
		$permissions = null;
		$default = true;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;


		
		

		$dateEnd = date( 'Y-m-d', strtotime( 'today' ) );
		$dateStart = date( 'Y-m-d', strtotime($dateEnd.' - 1 month'));

		$activities = array();



		if(Input::has("arrayData")){
			$arrayData = json_decode(Input::get("arrayData"),true);
			if(is_array($arrayData)){
				if(array_key_exists("search", $arrayData) and $arrayData["search"] != "") $search = $arrayData["search"];
				if(array_key_exists("archive", $arrayData) and $arrayData["archive"] == "true") $archive = true;
				if(array_key_exists("dateStart", $arrayData) and Helper::validateDate($arrayData["dateStart"])) { $dateStart = $arrayData["dateStart"]; $default = false; }
				if(array_key_exists("dateEnd", $arrayData) and Helper::validateDate($arrayData["dateEnd"])) $dateEnd = $arrayData["dateEnd"];
				if(array_key_exists("interval", $arrayData)){

					if($arrayData["interval"] == "last30Days"){
						$dateEnd = date( 'Y-m-d', strtotime( 'today' ) );
						$dateStart = date( 'Y-m-d', strtotime($dateEnd.' - 1 month'));
						$default = true;
						
					}

					if($arrayData["interval"] == "last3Months"){
						$dateEnd = date( 'Y-m-d', strtotime( 'today' ) );
						$dateStart = date( 'Y-m-d', strtotime($dateEnd.' - 3 month'));
					}

					


				} 
			}
		}


		$days = $this->dateDifference($dateEnd,$dateStart);


		for($x = 0; $x <= $days; $x++){
			$currentDate = date( 'Y-m-d', strtotime($dateStart.' + '.$x.' day'));
			$activities[$currentDate] = array();
			$activities[$currentDate]["performance"] =  array(); 
		}


		$performances = Workoutsperformances::where("forTrainer",Auth::user()->id)->where("userId",$userId)->whereNotNull("dateCompleted")->where("dateCompleted",">=",$dateStart." 00:00:00")->where("dateCompleted","<=",$dateEnd." 23:59:59")->get();

		foreach($performances as $performance){

		
					$key = new DateTime($performance->dateCompleted);
					$key = $key->format('Y-m-d');
					array_push($activities[$key]["performance"],$performance);
			
		}


		

		return View::make("widgets.base.activityCalendar")
			->with("activities",$activities)
			->with("dateStart",$dateStart)
			->with("dateEnd",$dateEnd)
			->with("permissions",$permissions)
			->with("default",$default)
			->with("currentEndDate",$dateEnd)
			->with("userId",$userId)
			->with("user",$user);
			
}

	/**
	 * Show the form for creating a new resource.
	 * GET /calendar/create
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
		$validation = Calendar::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$calendar = new Calendar;
			$calendar->objective = Input::get("objective");
			$calendar->measureable = Input::get("measureable");
			$calendar->recordDate = Input::get("dateRecord");
			$calendar->userId = Auth::user()->id;
			$calendar->save();
			Feeds::insertFeed("NewObjective",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			return $this::responseJson(Lang::get("messages.ObjectiveAdded"));	
		}
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /calendar
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /calendar/{id}
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
	 * GET /calendar/{id}/edit
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
	 * PUT /calendar/{id}
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
	 * DELETE /calendar/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$obj = Calendar::find($id);
		if(!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));

		if($this->checkPermissions($obj->userId,Auth::user()->id)){
//			Feeds::insertFeed("DeleteObjective",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			$obj->delete();
			return $this::responseJson(Lang::get("messages.ObjectiveDeleted"));
		} else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		}
		
	
	}

}