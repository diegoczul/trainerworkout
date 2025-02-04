<?php

class WeightsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /weights
	 *
	 * @return Response
	 */

	public $pageSize = 2;
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
				$user = Users::find($userId);
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}


    	$datay1 = array();
		$datay2  = array();
		$y1 = array();
		$weights = Weights::where("userId","=",$userId)->orderBy('recordDate', 'DESC')->get();
		$offset = 0;
		$offset = floor($weights->count()/8);
		$x = 0;
		
		if ($weights->count() > 1){
           	foreach ($weights as $weight){
           		if($x >= $offset){
						array_push($datay1,$weight->weightPounds);
						array_push($datay2,$weight->weightKilograms);
						array_push($y1,Helper::date($weight->recordDate));
						$x = 0;
				}
				$x++; 
           	}	
       	}	

       	if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;
			          

			          
		return View::make("widgets.base.weight")
			->with("weights",Weights::where("userId","=",$userId)->orderBy('recordDate', 'DESC')->take($this->pageSize)->get())
			->with("total",Weights::where("userId","=",$userId)->count())
			->with("permissions",$permissions)
			->with("user",$user)
			->with("datay1",array_reverse($datay1))
			->with("datay2",array_reverse($datay2))
			->with("y1",array_reverse($y1));
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
		

    	$datay1 = array();
		$datay2  = array();
		$y1 = array();
		$weights = Weights::where("userId","=",$userId)->orderBy('recordDate', 'DESC')->get();
		$offset = 0;
		$offset = floor($weights->count()/16);
		$x = 0;
		
		if ($weights->count() > 1){
           	foreach ($weights as $weight){
           		if($x >= $offset){
						array_push($datay2,$weight->weightKilograms);
						array_push($datay1,$weight->weightPounds);
						array_push($y1,Helper::date($weight->recordDate));
						$x = 0;
				}
				$x++; 
           	}	
       	}



       	if(Input::has("pageSize")) $this->pageSizeFull = Input::get("pageSize") + $this->pageSizeFull;
			          
		return View::make("widgets.full.weight")
			->with("weights",Weights::where("userId","=",$userId)->orderBy('recordDate', 'DESC')->take($this->pageSizeFull)->get())
			->with("total",Weights::where("userId","=",$userId)->count())
			->with("permissions",$permissions)
			->with("user",$user )
			->with("datay1",array_reverse($datay1))
			->with("datay2",array_reverse($datay2))
			->with("y1",array_reverse($y1));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /weights/create
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
		
		$validation = Weights::validate(Input::all());
		
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
			if($validation->fails()){
				return $this::responseJsonErrorValidation($validation->messages());
			} else {
				$weights = new Weights;
				if(Input::get("type") == "pounds"){
					$weights->weightPounds = Input::get("weight");
					$weights->weightKilograms = number_format(Input::get("weight")/2.2,2);
				} else {
					$weights->weightPounds = number_format(Input::get("weight")*2.2,2);
					$weights->weightKilograms = Input::get("weight");
				}
				
				$weights->type = Input::get("type");
				$weights->recordDate = Input::get("dateRecord");
				$weights->userId = $userId;
				$weights->save();
				Feeds::insertFeed("NewWeight",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName,"weightAdded");
				return $this::responseJson(Lang::get("messages.WeightAdded"));	
			}
		}else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		}
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /weights
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /weights/{id}
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
	 * GET /weights/{id}/edit
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
	 * PUT /weights/{id}
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
	 * DELETE /weights/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

		$obj = Weights::find($id);
		if(!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));

		if($this->checkPermissions($obj->userId,Auth::user()->id)){
//			Feeds::insertFeed("DeletedPictures",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			$obj->delete();
			return $this::responseJson(Lang::get("messages.WeightDeleted"));
		} else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		}
		
	}

	//=======================================================================================================================
	// API
	//=======================================================================================================================

	public function APIAddEdit()
	{
		if(Input::has("id") and Input::get("id") != ""){
			return $this->APIupdate(Input::get("id"));
		} else {
			return $this->APIcreate();
		}		
	}


	public function APIUpdate(){
		$validation = Weights::validate(Input::all());
		
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

		$type = "";

		if($permissions["add"]){
			if($validation->fails()){
				$return = Helper::APIERROR();
				$return["messages"] = $validation->messages();
				return $return;
			} else {
				$weights = Weights::find(Input::get("id"));
				if($weights){
					if(!Input::has($type)) $type = "pounds";
					if($type == "pounds"){
						$weights->weightPounds = Input::get("weight");
						$weights->weightKilograms = number_format(Input::get("weight")/2.2,2);
					} else {
						$weights->weightPounds = number_format(Input::get("weight")*2.2,2);
						$weights->weightKilograms = Input::get("weight");
					}
					
					$weights->type = $type;
					$weights->recordDate = Input::get("recordDate");
					$weights->userId = $userId;
					$weights->save();
					Feeds::insertFeed("NewWeight",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
					$return = Helper::APIOK();
					$return["message"] = Lang::get("messages.WeightUpdated");	
					return $return;
				}
			}
		}else {
			$return["messages"] = Lang::get("messages.Permissions");
			return $return;
			
		}
	}


	public function APIcreate()
	{
		
		$validation = Weights::validate(Input::all());
		
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

		$type = "";

		if($permissions["add"]){
			if($validation->fails()){
				$return = Helper::APIERROR();
				$return["messages"] = $validation->messages();
				return $return;
			} else {
				$weights = new Weights;
				if(!Input::has($type)) $type = "pounds";
				if($type == "pounds"){
					$weights->weightPounds = Input::get("weight");
					$weights->weightKilograms = number_format(Input::get("weight")/2.2,2);
				} else {
					$weights->weightPounds = number_format(Input::get("weight")*2.2,2);
					$weights->weightKilograms = Input::get("weight");
				}
				
				$weights->type = $type;
				$weights->recordDate = Input::get("dateRecord");
				$weights->userId = $userId;
				$weights->save();
				Feeds::insertFeed("NewWeight",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
				$return = Helper::APIOK();
				$return["message"] = Lang::get("messages.WeightAdded");	
				return $return;
			}
		}else {
			$return["messages"] = Lang::get("messages.Permissions");
			return $return;
			
		}
	}

}