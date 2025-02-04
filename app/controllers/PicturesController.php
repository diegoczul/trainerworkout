<?php

class PicturesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /pictures
	 *
	 * @return Response
	 */
	public $pageSize = 2;
	public $pageSizeFull = 9;

	public function index()
	{

		$userId = Auth::user()->id;
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"),"w_pictures");
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}
       	if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;
			          
		return View::make("widgets.full.pictures")
			->with("pictures",Pictures::where("userId","=",$userId)->orderBy('recordDate', 'DESC')->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("total",Pictures::where("userId","=",$userId)->count());
	}

	public function indexFull()
	{
		
		$userId = Auth::user()->id;
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"),"w_pictures");
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

       	if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;
			          
			

		return View::make("widgets.full.pictures")
			->with("pictures",Pictures::where("userId","=",$userId)->orderBy('recordDate', 'DESC')->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("total",Pictures::where("userId","=",$userId)->count());
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /pictures/create
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

		$user =  Auth::user();

		$validation = Pictures::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {

			$pictures = new Pictures;
			$pictures->recordDate = Input::get("recordDate");
			$pictures->userId = Auth::user()->id;
			Feeds::insertFeed("NewPictures",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName,"picturesAdded");
			$pictures->save();
			// open an image file

			Helper::checkUserFolder($user->id);

			if(Input::hasFile("front")) {
					$images = Helper::saveImage(Input::file("front"),$user->getPath().Config::get("constants.picturesPath")."/".$pictures->id);
					$pictures->front = $images["image"];
					$pictures->thumbFront = $images["thumb"];
				}
			if(Input::hasFile("back")) {
					$images = Helper::saveImage(Input::file("back"),$user->getPath().Config::get("constants.picturesPath")."/".$pictures->id);
					$pictures->back = $images["image"];
					$pictures->thumbBack = $images["thumb"];
			}
			if(Input::hasFile("left")) {
					$images = Helper::saveImage(Input::file("left"),$user->getPath().Config::get("constants.picturesPath")."/".$pictures->id);
					$pictures->left = $images["image"];
					$pictures->thumbLeft = $images["thumb"];
			}
			if(Input::hasFile("right")) {
					$images = Helper::saveImage(Input::file("right"),$user->getPath().Config::get("constants.picturesPath")."/".$pictures->id);
					$pictures->right = $images["image"];
					$pictures->thumbRight = $images["thumb"];
			}

			$pictures->save();
			
			return $this::responseJson(Lang::get("messages.PicturesAdded"));	
		}
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /pictures
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /pictures/{id}
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
	 * GET /pictures/{id}/edit
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
	 * PUT /pictures/{id}
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
	 * DELETE /pictures/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$obj = Pictures::find($id);
		if(!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));

		if($this->checkPermissions($obj->userId,Auth::user()->id)){
//			Feeds::insertFeed("DeletedPictures",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			$obj->delete();
			return $this::responseJson(Lang::get("messages.PicturesDeleted"));
		} else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		}
		
	}

	//=======================================================================================================================
	// API
	//=======================================================================================================================

	public function APIindex()
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
		
       	$this->pageSize = 999;

		$result = Helper::APIOK();
		$result["data"] = Pictures::where("userId","=",$userId)->orderBy('recordDate', 'DESC')->take($this->pageSize)->get();
		$result["permissions"] = $permissions;
		$result["total"] = Pictures::where("userId","=",$userId)->count();

		return $result;

	}


	public function APIAddEdit()
	{
		if(Input::has("id") and Input::get("id") != ""){
			return $this->APIupdate(Input::get("id"));
		} else {
			return $this->APIcreate();
		}		
	}

	public function APIcreate()
	{

		$user =  Auth::user();

		$validation = Pictures::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
			$result = Helper::APIERROR();
			$result["message"] = $validation->messages();
			return $result;
			
		} else {

			$pictures = new Pictures;
			$pictures->recordDate = Input::get("recordDate");
			$pictures->userId = Auth::user()->id;
			Feeds::insertFeed("NewPictures",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			$pictures->save();
			// open an image file

			Helper::checkUserFolder($user->id);

			if(Input::hasFile("image0")) {
					$images = Helper::saveImage(Input::file("image0"),$user->getPath().Config::get("constants.picturesPath")."/".$pictures->id);
					$pictures->front = $images["image"];
					$pictures->thumbFront = $images["thumb"];
				}
			if(Input::hasFile("image1")) {
					$images = Helper::saveImage(Input::file("image1"),$user->getPath().Config::get("constants.picturesPath")."/".$pictures->id);
					$pictures->back = $images["image"];
					$pictures->thumbBack = $images["thumb"];
			}
			if(Input::hasFile("image2")) {
					$images = Helper::saveImage(Input::file("image2"),$user->getPath().Config::get("constants.picturesPath")."/".$pictures->id);
					$pictures->left = $images["image"];
					$pictures->thumbLeft = $images["thumb"];
			}
			if(Input::hasFile("image3")) {
					$images = Helper::saveImage(Input::file("image3"),$user->getPath().Config::get("constants.picturesPath")."/".$pictures->id);
					$pictures->right = $images["image"];
					$pictures->thumbRight = $images["thumb"];
			}

			$pictures->save();
			
			$result = Helper::APIOK();
			$result["message"] = Lang::get("messages.PicturesAdded");
			return $result;	
		}
	}


}