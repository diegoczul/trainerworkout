<?php

class UserGroupsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /equipments
	 *
	 * @return Response
	 */

	public $pageSize = 6;
	public $searchSize = 8;
	public $pageSizeFull = 10;



	//=======================================================================================================================
	// CONTROL PANEL
	//=======================================================================================================================
	

	public function _index()
	{
		return View::make('ControlPanel/UserGroups');
	}

	public function _ApiList()
	{
		$response = UserGroups::with("user")->orderBy("updated_at","DESC");
		if(Input::get("groupId") != "") { $response->where("groupId",Input::get("groupId")); }

		return $this::responseJson(array("data"=>$response->get()));
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


		$validation = UserGroups::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			if(UserGroups::where("groupId",Input::get("hiddenGroupId"))->where("userId",Input::get("userId"))->count() == 0){
			$group = new UserGroups;
			$group->userId = Input::get("userId");
			$group->groupId = Input::get("hiddenGroupId");
			$group->role = Input::get("role");
			if($group->role == "Owner" or $group->role == "Admin"){
				$user = Users::find($group->userId);
				$user->admin = 1;
				$user->save();
			}
			if($group->groupId != 0) $group->save();
			
			}
		

			return $this::responseJson(Messages::showControlPanel("GroupCreated"));	
		}
	}

	public function _show($equipment)
	{
		//
		return UserGroups::find($equipment);
	}

	public function _update($id)
	{

		$validation = UserGroups::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$group = UserGroups::find($id);
			$group->userId = Input::get("userId");
			$group->groupId = Input::get("hiddenGroupId");
			$group->role = Input::get("role");
			if($group->role == "Owner" or $group->role == "Admin"){
				$user = Users::find($group->userId);
				$user->admin = 1;
				$user->save();
			}
			if($group->groupId != 0) $group->save();

			return $this::responseJson(Messages::showControlPanel("UserGroupModified"));	
			
		}
	}

	

	public function _destroy($id)
	{
		//
		$group = UserGroups::find($id);

			$group->delete();
			return $this::responseJson(Messages::showControlPanel("UserGroupDeleted"));

		
	}

}