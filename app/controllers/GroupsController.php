<?php

class GroupsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /equipments
	 *
	 * @return Response
	 */

	public $pageSize = 6;
	public $searchSize = 8;
	public $pageSizeFull = 10;


	public function showGroup(){
		$user = Auth::user();
		$groupUser = $user->group;
		if($groupUser){

		$group = Groups::find($groupUser->groupId);

			if($group){
				$userGroups = UserGroups::with("user")->where("groupId",$group->id)->get();
				return View::make('trainer.employeeManagement')
					->with("userGroups",$userGroups)
					->with("groupUser",$groupUser)
					->with("user",$user)
					->with("group",$group);
			}
		}
	}

	

	public function resendGroupInvitation($userId){
		$user = Users::find($userId);
		$author = Auth::user();
		$authorFirstName = $author->firstName;
		$authorLastName = $author->lastName;
		$authorEmail = $author->email;

		if($user){
			$user->sendInviteGroup("",$authorFirstName,$authorLastName,$authorEmail);
			return $this::responseJson(Lang::get("messages.InvitationSent"));
		} else {
			return $this::responseJsonError(Lang::get("messages.Oops"));
		}
	}

	public function changeRole(){
		$userId = Input::get("user");
		$role = Input::get("user");
		$user = Auth::user();
		$groupUser = $user->group;
		$group = Groups::find($groupUser->groupId);
		$userGroup = UserGroups::with("user")->where("groupId",$group->id)->where("userId",$userId)->first();
		if($userGroup){
			$userGroup->role = Input::get("role");
			$userGroup->save();
		}
		return $this::responseJson(Lang::get("messages.EmployeeUpdated"));
	}

	public function removeAccess($userId){

		$user = Auth::user();
		$groupUser = $user->group;
		$group = Groups::find($groupUser->groupId);
		$userGroups = UserGroups::with("user")->where("groupId",$group->id)->where("userId",$userId)->delete();
		return $this::responseJson(Lang::get("messages.EmployeesRemoved"));
	}


	public function addEmployees(){
		$counter = 0;
		$elements = count(Input::get("firstName"));

		$owner = Auth::user();
		$user = null;
		$group = $owner->group;
		for($x = 0; $x < $elements; $x++){


			if(Users::where("email",Input::get("email")[$counter])->count() == 0){
				$newuser = new Users;
				$newuser->firstName = ucfirst(Input::get("firstName")[$counter]);
				$newuser->lastName = ucfirst(Input::get("lastName")[$counter]);
				$newuser->email = ucfirst(Input::get("email")[$counter]);
				$newuser->userType = 'Trainer';
				$newuser->save();
				$newuser->freebesTrainer();

				$newuser->sendInviteGroup($owner->firstName,$owner->lastName,$owner->email);
				$user = $newuser;
			} else {
				$user = Users::where("email",Input::get("email")[$counter])->first();
				$user->sendInviteGroup($owner->firstName,$owner->lastName,$owner->email);
			}

			if(UserGroups::where("groupId",$group->groupId)->where("userId",$user->id)->count() == 0){
				$userGroup = new UserGroups;
				$userGroup->userId = $user->id;
				$userGroup->role = Input::get("role")[$counter];
				$userGroup->groupId = $group->groupId;
				if($userGroup->groupId != 0) $userGroup->save();
			}
			$counter++;

		}

		return Redirect::route("employeeManagement")->with("message",Lang::get("messages.EmployeesInvited"));
	}


	//=======================================================================================================================
	// CONTROL PANEL
	//=======================================================================================================================
	

	public function _index()
	{
		return View::make('ControlPanel/Groups')
		->with("users",Users::select(DB::raw("concat(firstname,' ',lastName,' ',email) as fullName"),"id")->orderBy("firstName")->orderBy("lastName")->lists("fullName","id"));
	}

	public function _ApiList()
	{
		return $this::responseJson(array("data"=>Groups::orderBy("name","ASC")->get()));
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


		$validation = Groups::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$group = new Groups;
			$group->name = Input::get("name");

			$group->save();
		

			return $this::responseJson(Messages::showControlPanel("GroupCreated"));	
		}
	}

	public function _show($equipment)
	{
		//
		return Groups::find($equipment);
	}

	public function _update($id)
	{

		$validation = Groups::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$group = Groups::find($id);
			$group->name = Input::get("name");

			$group->save();

			return $this::responseJson(Messages::showControlPanel("UserLogoModified"));	
			
		}
	}

	

	public function _destroy($id)
	{
		//
		$group = Groups::find($id);
		if($group->countUsers() == 0){
			$group->delete();
			return $this::responseJson(Messages::showControlPanel("GroupDeleted"));
		} else {
			return $this::responseJsonError(Messages::showControlPanel("GroupEmpty"));
		}
		
	}

}