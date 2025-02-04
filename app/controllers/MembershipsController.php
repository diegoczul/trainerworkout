<?php

class MembershipsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /memberships
	 *
	 * @return Response
	 */
	public function indexMembershipManagement()
	{
	
		if(!Auth::user()->membership) Auth::user()->updateToMembership(Config::get("constants.freeTrialMembershipId"));
		
		return View::make("MembershipManagement");
	}

	public function indexMembershipManagementOld()
	{
	
		return View::make("MembershipManagementOld");
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /memberships/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /memberships
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /memberships/{id}
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
	 * GET /memberships/{id}/edit
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
	 * PUT /memberships/{id}
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
	 * DELETE /memberships/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}



	//=======================================================================================================================
	// CONTROL PANEL
	//=======================================================================================================================
	

	public function _indexUsers()
	{
		return View::make('ControlPanel/Memberships')
			->with("users",Users::select(DB::raw("concat('id: ',id,' - ',firstName,' ',lastName,' - ',email) as name "),"id")->orderBy("firstName","ASC")->orderBy("lastName","ASC")->lists("name","id"))
			->with("memberships",Memberships::orderBy("name","ASC")->lists("name","id"));
	}

	public function _ApiListUsers()
	{
		return $this::responseJson(array("data"=>MembershipsUsers::with("users")->with("membership")->orderBy("expiry","ASC")->get()));
	}


	public function _AddEditUsers()
	{
		if(Input::has("hiddenId") and Input::get("hiddenId") != ""){
			return $this->_updateUsers(Input::get("hiddenId"));
		} else {
			return $this->_createUsers();
		}		
	}

	public function _createUsers()
	{


		$validation = MembershipsUsers::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$object = new MembershipsUsers;
			$object->userId = Input::get("userId");
			$object->membershipId = Input::get("membershipId");
			$object->expiry = Input::get("expiry");
			$object->save();
			
	
			return $this::responseJson(Messages::showControlPanel("FieldCreated"));	
		}
	}

	public function _showUsers($object)
	{
		//
		return MembershipsUsers::find($object);
	}

	public function _updateUsers($id)
	{

		$validation = MembershipsUsers::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$object = MembershipsUsers::find($id);
			$object->userId = Input::get("userId");
			$object->membershipId = Input::get("membershipId");
			$object->expiry = Input::get("expiry");
			$object->save();

			return $this::responseJson(Messages::showControlPanel("FieldModified"));	
			
		}
	}

	public function _destroyUsers($id)
	{
		//
		$object = MembershipsUsers::find($id);
		$object->delete();

		return $this::responseJson(Messages::showControlPanel("FieldDeleted"));
	}

	public function _index()
	{
		return View::make('ControlPanel/MembershipsTypes');
	}

	public function _ApiList()
	{
		return $this::responseJson(array("data"=>Memberships::orderBy("name","ASC")->get()));
	}


	public function _AddEdit()
	{
		if(Input::has("hiddenId") and Input::get("hiddenId") != ""){
			return $this->_update(Input::get("hiddenId"));
		} else {
			return $this->_create();
		}		
	}

	public function _create()
	{


		$validation = Memberships::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$object = new Memberships;
			$object->name = Input::get("name");
			$object->description = Input::get("description");
			$object->features = Input::get("features");
			$object->save();
			
	
			return $this::responseJson(Messages::showControlPanel("FieldCreated"));	
		}
	}

	public function _show($object)
	{
		//
		return Memberships::find($object);
	}

	public function _update($id)
	{

		$validation = Memberships::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$object = Memberships::find($id);
			$object->name = Input::get("name");
			$object->description = Input::get("description");
			$object->features = Input::get("features");
			$object->save();

			return $this::responseJson(Messages::showControlPanel("FieldModified"));	
			
		}
	}

	public function _destroy($id)
	{
		//
		$object = Memberships::find($id);
		$object->delete();

		return $this::responseJson(Messages::showControlPanel("FieldDeleted"));
	}

}