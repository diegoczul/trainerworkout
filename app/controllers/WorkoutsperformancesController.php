<?php

class WorkoutsperformanceController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /workoutlog
	 *
	 * @return Response
	 */

	public function workoutsPerformanceClientsIndex(){

			$dateStart = date( 'Y-m-d', strtotime( 'previous sunday' ) );

			$dateEnd = date( 'Y-m-d', strtotime( 'today' ) );

		return View::make("trainer.reports.workoutsPerformanceClients")
		->with("dateStart",$dateStart)
		->with("dateEnd",$dateEnd);
	}

	public function workoutsPerformance(){
		$workoutsPeformance = array();

		$performances = Workoutsperformances::where("forTrainer",Auth::user()->id)->whereNotNull("dateCompleted")->get();
		$clients = Clients::where("trainerId",Auth::user()->id)->get();
		
		$arrayData = array();
		if(Input::get("arrayData") != "") $arrayData = json_decode(Input::get("arrayData"),true);

		if(is_array($arrayData) and array_key_exists("dateEnd", $arrayData) and $arrayData["dateEnd"] != ""){
			$dateEnd = date($arrayData["dateEnd"]);
		} else {
			$dateEnd = date( 'Y-m-d', strtotime( 'today' ) );
		}

		if(is_array($arrayData) and array_key_exists("dateStart", $arrayData) and $arrayData["dateStart"] != ""){
			$dateStart =date($arrayData["dateStart"]);
		} else {
			$dateStart = date ("Y-m-d", strtotime("-7 day", strtotime($dateEnd)));
		}

		$days = array();
		$date = $dateStart;

		while(strtotime($date) <= strtotime($dateEnd)){
			array_push($days,$date);
			$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
		}

		foreach($clients as $client){
				$workoutsPeformance[(string)$client->userId] = array();

				$date = $dateStart;

				while(strtotime($date) <= strtotime($dateEnd)){
				
					
					$workoutsPeformance[(string)$client->userId][$date] = array();
					$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
					
				}
		}

		if(!array_key_exists(Auth::user()->id, $workoutsPeformance)){
			$date = $dateStart;

			while(strtotime($date) <= strtotime($dateEnd)){
				
				
				$workoutsPeformance[(string)Auth::user()->id][$date] = array();
				$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
				
			}
		}


		

		foreach($performances as $performance){

			if($dateStart <= date($performance->dateCompleted) and date($performance->dateCompleted) <  date ("Y-m-d", strtotime("+1 day", strtotime($dateEnd)))){
					$key = new DateTime($performance->dateCompleted);
					$key = $key->format('Y-m-d');
					// echo "<pre>";
					// print_r($key);
					// print_r($workoutsPeformance);
					// echo "</pre>";
					if (array_key_exists((string)$performance->userId, $workoutsPeformance)) array_push($workoutsPeformance[(string)$performance->userId][$key],$performance);
		

			}
		}



		return View::make("widgets.reports.workoutsPerformance")
				->with("performances",$workoutsPeformance)
				->with("clients",$clients)
				->with("dates",$days);
	}

	public function workoutsPerformanceDetail($id = ""){
		if($id != "") $performance = Workoutsperformances::find($id);
		if($performance){
			return View::make("widgets.reports.workoutsPerformanceDetail")
			->with("performance",$performance);
		}

		
	}

	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /workoutlog/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /workoutlog
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /workoutlog/{id}
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
	 * GET /workoutlog/{id}/edit
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
	 * PUT /workoutlog/{id}
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
	 * DELETE /workoutlog/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}