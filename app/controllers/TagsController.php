<?php

class TagsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /tags
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
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"),"w_tags");
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

    	$datay1 = array();
		$datay2  = array();
		$y1 = array();
		$tags = array();
		$workoutId = "";

		if(Input::has("arrayData") and array_key_exists("workoutId", json_decode(Input::get("arrayData"),true))){
			$arrayData = json_decode(Input::get("arrayData"),true);
			$workout = Workouts::find($arrayData["workoutId"]);
			$workoutId = $workout->id;
			if($workout){
				$workoutId = $workout->id;
				$tagsString = $workout->tags;
				$tagsArray = explode(",",$tagsString);
				$tags = Tags::where("userId","=",$userId)->whereIn("name",$tagsArray)->orderBy('name', 'ASC')->get();
			}
		} else {
			$tags = Tags::where("userId","=",$userId)->orderBy('name', 'ASC')->get();
		}
		

       	if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;
		
		return View::make("widgets.base.tags")
			->with("tags",$tags)
			->with("workoutId",$workoutId)
			->with("permissions",$permissions)
			->with("workoutId",$workoutId)
			->with("user",$user)
			->with("total",count($tags));
	}

	public function indexWorkout()
	{
		$user = Auth::user();
		$userId = Auth::user()->id;
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"),"w_tags");
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

    	$datay1 = array();
		$datay2  = array();
		$y1 = array();
		$tags = array();
		$workoutId = "";

		if(Input::has("arrayData") and array_key_exists("workoutId", json_decode(Input::get("arrayData"),true))){
			$arrayData = json_decode(Input::get("arrayData"),true);
			$workout = Workouts::find($arrayData["workoutId"]);
			if($workout){
				$workoutId = $workout->id;
				$tagsString = $workout->tags;
				$tagsArray = explode(",",$tagsString);
				$tags = Tags::where("userId","=",$userId)->whereIn("name",$tagsArray)->orderBy('name', 'ASC')->get();
			}
		} else {
			$tags = Tags::where("userId","=",$userId)->orderBy('name', 'ASC')->get();
		}
		

       	if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;
		
		return View::make("widgets.base.tagsWorkout")
			->with("tags",$tags)
			->with("workoutId",$workoutId)
			->with("permissions",$permissions)
			->with("user",$user)
			->with("total",count($tags));
	}

	public function indexFull()
	{
		$user = Auth::user();
		$userId = Auth::user()->id;
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"),"w_tags");
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
		}


    	$datay1 = array();
		$datay2  = array();
		$y1 = array();
		$tags = Tags::where("userId","=",$userId)->orderBy('name', 'ASC')->get();
		

       	if(Input::has("pageSize")) $this->pageSizeFull = Input::get("pageSize") + $this->pageSizeFull;
			          
		return View::make("widgets.full.tags")
			->with("tags",Tags::where("userId","=",$userId)->orderBy('name', 'ASC')->take($this->pageSizeFull)->get())
			->with("permissions",$permissions)
			->with("user",$user)
			->with("total",Tags::where("userId","=",$userId)->count());

	}

	/**
	 * Show the form for creating a new resource.
	 * GET /tags/create
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

			$validation = Tags::validate(Input::all());
			if($validation->fails()){
				return $this::responseJsonErrorValidation($validation->messages());
			} else {
				$tag = new Tags;
				$tag->userId = Auth::user()->id;
				if(Input::has("tagNameTag") and Input::get("tagNameTag") != ""){
					$tag->name = Input::get("tagNameTag");
					$tag->type = "tag";
				} else if (Input::has("tagNameClient") and Input::get("tagNameClient") != ""){
					$tag->name = Input::get("tagNameClient");
					$tag->type = "user";
				} else {
					$tag->name = Input::get("tagName");
					$tag->type = "tag";
				}

				

				if(Tags::where("userId",$userId)->where("name",$tag->name)->where("type",$tag->type)->count() == 0) $tag->save();
				if(Input::has("workoutId")){
					$workout = Workouts::find(Input::get("workoutId"));
					if($workout){
						Event::fire('createTag', array(Auth::user(),$workout->name,$tag->name));
						$tags = $workout->tags;
						$tagsArray = explode(",",$tags);
						array_push($tagsArray,$tag->name);
						$tags = implode(",",$tagsArray);
						$workout->tags = $tags;
						$workout->save();
					}
				}
				return $this::responseJson(Lang::get("messages.TagsAdded"));	
			}
		} else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		}
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /tags
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /tags/{id}
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
	 * GET /tags/{id}/edit
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
	 * PUT /tags/{id}
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
	 * DELETE /tags/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

		$obj = Tags::find($id);
		if(!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));
		
		Event::fire('destroyTag', array(Auth::user(),$obj->name));

		if($this->checkPermissions($obj->userId,Auth::user()->id)){
			$workouts = Workouts::where(function($query) use ($obj){
				$query->orWhere("tags","like","%".$obj->name.",%");
				$query->orWhere("tags","like","%".",".$obj->name."%");
				$query->orWhere("tags","like","%".",".$obj->name.",%");
			})->get();
			foreach($workouts as $workout){
				$workout->tags = str_replace(",".$obj->name.",", "", $workout->tags);
				$workout->tags = str_replace($obj->name.",", "", $workout->tags);
				$workout->tags = str_replace(",".$obj->name, "", $workout->tags);
				$workout->save();
			}
//			Feeds::insertFeed("DeletedTags",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			$obj->delete();
			return $this::responseJson(Lang::get("messages.TagsDeleted"));
		} else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		}
		
	}

	public function destroyTagWorkout()
	{
		$id = Input::get("tag");
		$workoutId = Input::get("workoutId");
		$workout = Workouts::find($workoutId);
		$tag = Tags::find($id);

		Event::fire('removeTagWorkout', array(Auth::user(),$workout->name,$tag->name));

		if($workout){
			$tags = $workout->tags;
			$tagsArray = explode(",",$tags);
			$newTags = array();
			foreach($tagsArray as $tagy){
				if(strtolower($tagy) != strtolower($tag->name)) array_push($newTags,$tagy);
			}
			$tags = implode(",",array_filter($newTags));
			$workout->tags = $tags;
			$workout->save();
			return $this::responseJson(Lang::get("messages.TagsDeleted"));
		} else {
			if(!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));
		}
	}
}