<?php

class ClientsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /clients
	 *
	 * @return Response
	 */

	public $pageSize = 9;
	public $pageSizeFull = 9;


	public function index()
	{

		$userId = Auth::user()->id;
		$user = Auth::user();
		$total = 0;

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

		if(Input::get("search") != ""){
			$search = Input::get("search");
			$clients = Clients::whereHas('user',function($query) use ($search) {
				$query->where(function($query2) use ($search){
					$query2->orwhere('firstName', 'LIKE', "%".$search."%");
					$query2->orwhere('lastName', 'LIKE', "%".$search."%");
					$query2->orwhere('email', 'LIKE', "%".$search."%");
					$query2->orwhere('phone', 'LIKE', "%".$search."%");
				});
				
			})->where("trainerId","=",$userId)->orderBy('updated_at', 'DESC')->take($this->pageSize)->get();
			$total = Clients::whereHas('user',function($query) use ($search) {
				$query->where(function($query2) use ($search){
					$query2->orwhere('firstName', 'LIKE', "%".$search."%");
					$query2->orwhere('lastName', 'LIKE', "%".$search."%");
					$query2->orwhere('email', 'LIKE', "%".$search."%");
					$query2->orwhere('phone', 'LIKE', "%".$search."%");
				});
				
			})->where("trainerId","=",$userId)->orderBy('updated_at', 'DESC')->count();
		} else {
			$clients = Clients::with("user")->where("trainerId","=",$userId)->orderBy('updated_at', 'DESC')->take($this->pageSize)->get();
			$total = Clients::where("trainerId","=",$userId)->count();
		}

		return View::make("widgets.base.clients")
			->with("clients",$clients)
			->with("permissions",$permissions)
			->with("user",$user)
			->with("total",$total);
	}

	public function showClients(){
		return View::make("trainer.clients");
			
	}

	public function indexFull()
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

		return View::make("widgets.full.clients")
			->with("clients",Users::where("userId","=",$userId)->orderBy('recordDate', 'ASC')->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("user",$user)
			->with("total",Users::where("userId","=",$userId)->count());
	}

	public function showClientList(){
		$emails = Clients::select("email")->distinct()->where("trainerId",Auth::user()->id)->whereNotNull("email")->leftJoin("users","users.id","=","userId")->lists("email");
		return $this->responseJson($emails);
	}


	public function modifyClient(){
		$user = Input::get("client");
		$user = Clients::find($user);
		if($user){
			if($user->user->virtual == 1 or $user->user->lastLogin == null){
				$trainee = $user->user;
				$trainee->firstName = Input::get("firstName");
				$trainee->lastName = Input::get("lastName");
				$trainee->email = Input::get("email");
				$trainee->phone = Helper::formatPhone(Input::get("phone"));
				$trainee->save();
				return $this->responseJson(Lang::get("messages.ProfileSaved"));
			} else {
				return $this->responseJsonError(Lang::get("messages.NotControlAccount"));
			}
		}
	}

	public function clientProfile($clientId,$clientName="")
	{

		$userId = Auth::user()->id;


	
		//$user = Clients::find($clientId);
		$user = Clients::find($clientId);


		
		if($user){
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
			$performances = Workoutsperformances::where("forTrainer",Auth::user()->id)->where("userId",$user->userId)->whereNotNull("dateCompleted")->count();

			return View::make("trainer.client")
			->with("performances",$performances)
			->with("user",$user)
			->with("client",$user);

		}

		return Redirect::route('Trainer', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->withError(Lang::get("messages.UserNotFound"));

	}

	public function subscribeClient(){
		$id = Input::get("clientId");
		$client = Clients::find($id);
		if($client){
			if(Input::get("subscribeToClient") == "true"){
				$client->subscribeClient = 1;
				$client->save();
				$clientName = "";
				if($client->user) $clientName = $client->user->getCompleteName();
				return $this->responseJson(Lang::get("messages.SubscribedToClient",array("client"=>$clientName)));
			} else{
				$client->subscribeClient = 0;
				$client->save();
				$clientName = "";
				if($client->user) $clientName = $client->user->getCompleteName();
				return $this->responseJson(Lang::get("messages.NotSubscribedToClient",array("client"=>$clientName)));
			}
			
		} else{

			return $this->responseJsonError(Lang::get("messages.NotSubscribedToClient"));
		}
	}


	/**
	 * Show the form for creating a new resource.
	 * GET /clients/create
	 *
	 * @return Response
	 */

	public function confirmClientByInvitation($invite){

		$invite = Invites::where("key",$invite)->first();
		if($invite){
			$user = Users::find($invite->fakeId);
			$trainer = Users::find($invite->userId);
			if($user and $trainer){
				$client = Clients::where("trainerId",$invite->userId)->where("userId",$user->id)->first();
				$client->approvedClient = 1;
				$client->save();
				$invite->viewed = 1;
				$invite->completed = 1;
				$invite->save();
				if(Friends::where("followingId",$user->id)->where("userId",$trainer->id)->count() == 0){
					$friends = new Friends;
					$friends->followingId = $user->id;
					$friends->userId = $trainer->id;
					$friends->chat = 1;
					$friends->save();
					if($user){
						Notifications::insertDynamicNotification("Following",$user->id,$trainer->id,array("firstName"=>$trainer->firstName,"lastName"=>$trainer->lastName)); 
					}
					Feeds::insertDynamicFeed("Following",$trainer->id,$user->id,array("firstName"=>$trainer->firstName,"lastName"=>$trainer->lastName,"friendFirstName"=>$user->firstName,"friendLastName"=>$user->lastName)); 
				}
				if(Friends::where("userId",$trainer->id)->where("followingId",$user->id)->count() == 0){
					$friends = new Friends;
					$friends->followingId = $trainer->id;
					$friends->userId = $user->id;
					$friends->chat = 1;
					$friends->save();
					if($user){
						Notifications::insertDynamicNotification("Following",$trainer->id,$user->id,array("firstName"=>$user->firstName,"lastName"=>$user->lastName)); 
					}
					Feeds::insertDynamicFeed("Following",$user->id,$trainer->id,array("firstName"=>$user->firstName,"lastName"=>$user->lastName,"friendFirstName"=>$trainer->firstName,"friendLastName"=>$trainer->lastName));
				}
				Feeds::insertDynamicFeed("NewTrainer",$user->id,$trainer,array("userName"=>$user->getCompleteName(),"firstName"=>$user->firstName,"lastName"=>$user->lastName, "trainerName"=>$trainer->getCompleteName(), "link"=>$trainer->getURL()));
				Notifications::insertDynamicNotification("ClientAccepted",$client->trainer,$client->user,array("userName"=>$user->getCompleteName(),"firstName"=>$user->firstName,"lastName"=>$user->lastName));
//				Notifications::where("link",$invite->key)->update(array("message"=>Messages::showNotification("TrainerAccepted",array("firstName"=>$client->trainer->firstName,"lastName"=>$client->trainer->lastName))));

				if(Auth::check()){
					if(array_key_exists("HTTP_REFERER", $_SERVER) and $_SERVER['HTTP_REFERER'] != ""){
						return $this->responseJson(Lang::get("messages.TrainerConfirmed"));
					} else {
						return Redirect::route(Auth::user()->userType, array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->with("message",Lang::get("messages.TrainerConfirmed"));
					}
				} else {
					return Redirect::route('home')->with("message",Lang::get("messages.TrainerConfirmed"));
				}
	
			} 

		}
		return $this->responseJsonError(Lang::get("messages.NotFound"));
		
	}

	public function addClientTrainer(){

		$validation = Validator::make(Input::all(), array("firstName"=>"required"));
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$user = Users::where("email",Input::get("email"))->first();
			if(!$user){
				$user = new Users();
				$user->userType = "Trainee";
				$user->firstName = Input::get("firstName");
				$user->lastName = Input::get("lastName");
				$user->email = Input::get("email");
				$user->phone = Helper::formatPhone(Input::get("phone"));
				if(Input::get("clientLink") == "Yes"){
					$user->virtual = 0;
				} else {
					$user->virtual = 1;
				}
				$user->save();
			}

			if(Input::get("subscribe") == "Yes"){
				$subscribe = true;
			} else {
				$subscribe = false;
			}

			$message = Input::get("personalizedTxt");


			if(Clients::where("userId",$user->id)->where("trainerId",Auth::user()->id)->count() == 0){
				$client = Auth::user()->addClient($user,null,$subscribe,$message);
				return $this::responseJson(Lang::get("messages.ClientInvitation"));
			} else{
				return $this::responseJsonError(Lang::get("messages.ClientAlreadyInvited"));
			}
		}
	}

	public function addClient(){
		$addType = "New";
		$response = Memberships::checkMembership(Auth::user());
		
		if($response == ""){

			if(Input::has("user") and Input::get("user") != ""){
				$user = Users::find(Input::get("user"));

				if(Clients::where("userId",$user->id)->where("trainerId",Auth::user()->id)->count() > 0){
					return $this::responseJsonError(Lang::get("messages.ClientAlreadyInvited"));
				}

				
				$type = "ClientRequest";
				$client = new Clients;
				$client->userId = $user->id;
				$client->trainerId = Auth::user()->id;
				$client->approvedClient = 0;
				$client->approvedTrainer = 1;
				$client->save();
				$invite = new Invites;
				$invite->userId = Auth::user()->id;
				$invite->fakeId = $user->id;
				$invite->firstName = $user->firstName;
				$invite->lastName = $user->lastName;
				$invite->email = $user->email;
				$invite->key = GUID::generate();
				$invite->type = "ClientRequest";
				$invite->save();
				//dd($invite);
				//$invite->sendInviteClient();
				if(Friends::where("followingId",$user->id)->where("userId",Auth::user()->id)->count() == 0){
					$friends = new Friends;
					$friends->followingId = $user->id;
					$friends->userId = Auth::user()->id;
					$friends->chat = 1;
					$friends->save();
					if($user){
						Notifications::insertDynamicNotification("Following",$user->id,Auth::user()->id,array("firstName"=>Auth::user()->firstName,"lastName"=>Auth::user()->lastName)); 
						Feeds::insertDynamicFeed("Following",Auth::user()->id,$user->id,array("firstName"=>Auth::user()->firstName,"lastName"=>Auth::user()->lastName,"friendFirstName"=>$user->firstName,"friendLastName"=>$user->lastName));  
					}
					
				}
				if(Friends::where("followingId",Auth::user()->id)->where("userId",$user->id)->count() == 0){
					$friends = new Friends;
					$friends->followingId = Auth::user()->id;
					$friends->userId = $user->id;
					$friends->chat = 1;
					$friends->save();
					if($user){
						Notifications::insertDynamicNotification("Following",Auth::user()->id,$user->id,array("firstName"=>$user->firstName,"lastName"=>$user->lastName)); 
						Feeds::insertDynamicFeed("Following",$user->id,Auth::user()->id,array("firstName"=>$user->firstName,"lastName"=>$user->lastName,"friendFirstName"=>Auth::user()->firstName,"friendLastName"=>Auth::user()->lastName)); 
					}
					
				}

				Feeds::insertDynamicFeed("ClientRequest",Auth::user()->id,$user->id,array("firstName"=>Auth::user()->firstName,"lastName"=>Auth::user()->lastName,"friendFirstName"=>$user->firstName,"friendLastName"=>$user->lastName)); 
				Notifications::insertDynamicNotification("ClientInvitation",$user,Auth::user(),array("link"=>URL::to("/Clients/Invitation/".$invite->key."/"),"firstName"=>Auth::user()->firstName,"lastName"=>Auth::user()->lastName),true,$invite->key);

				Event::fire('sendInviteToClient', array(Auth::user(),$user->id));

				return $this::responseJson(Lang::get("messages.ClientInvited"));	
			} else {

				$rules = array("firstName => required","lastName"=>"required","email"=>"required|email|unique:users");
				$validation = Validator::make(Input::all(), $rules);
			
				//if($validation and Users::where("email",Input::get("email"))->count() == 0){
				if($validation->passes()){
					$user = new Users;
					$user->firstName = ucfirst(Input::get("firstName"));
					$user->lastName = ucfirst(Input::get("lastName"));
					$user->email = (Input::get("email"));
					$user->userType = "TempTrainee";
					$user->save();
					$client = new Clients;
					$client->userId = $user->id;
					$client->trainerId = Auth::user()->id;
					$client->approvedClient = 1;
					$client->approvedTrainer = 1;
					$client->save();
					if(Friends::where("followingId",$user->id)->where("userId",Auth::user()->id)->count() == 0){
						$friends = new Friends;
						$friends->followingId = $user->id;
						$friends->userId = Auth::user()->id;
						$friends->chat = 1;
						$friends->save();
						if($user){
							Notifications::insertDynamicNotification("Following",$user->id,Auth::user()->id,array("firstName"=>Auth::user()->firstName,"lastName"=>Auth::user()->lastName)); 
						}
						Feeds::insertDynamicFeed("Following",Auth::user()->id,$user->id,array("firstName"=>Auth::user()->firstName,"lastName"=>Auth::user()->lastName,"friendFirstName"=>$user->firstName,"friendLastName"=>$user->lastName)); 
					}
					if(Friends::where("followingId",Auth::user()->id)->where("userId",$user->id)->count() == 0){
						$friends = new Friends;
						$friends->followingId = Auth::user()->id;
						$friends->userId = $user->id;
						$friends->chat = 1;
						$friends->save();
						if($user){
							Notifications::insertDynamicNotification("Following",Auth::user()->id,$user->id,array("firstName"=>$user->firstName,"lastName"=>$user->lastName)); 
						}
						Feeds::insertDynamicFeed("Following",$user->id,Auth::user()->id,array("firstName"=>$user->firstName,"lastName"=>$user->lastName,"friendFirstName"=>Auth::user()->firstName,"friendLastName"=>Auth::user()->lastName)); 
					}
					$invite = new Invites;
					$invite->userId = Auth::user()->id;
					$invite->email = Input::get("email");
					$invite->fakeId = $user->id;
					$invite->firstName = $user->firstName;
					$invite->lastName = $user->lastName;
					$invite->key = GUID::generate();
					$invite->type = "ClientRequest";
					$invite->save();
					$invite->sendInviteClient();
//					Feeds::insertFeed("InvitedClient",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);

					Event::fire('sendInviteToNotClient', array(Auth::user(),$user->email));

					return $this::responseJson(Lang::get("messages.ClientInvitation"));
				} else {
					return $this::responseJsonErrorValidation($validation->messages());
				}
			}
		} else {
			return $this::responseJsonError($response);
		}
		
	}


	public function addClientWithId(){
		$addType = "New";
		$user = "";
		if(Input::has("user") and Input::get("user") != ""){
			$user = Input::get("user");
		} else if(Users::where("email",Input::get("email"))->count() > 0 and Input::get("email") != ""){
			$userObject = Users::where("email",Input::get("email"))->first();
			$user = $userObject->id;
		}

		if($user != ""){
			$user = Users::find($user);
			if(Clients::where("userId",$user->id)->where("trainerId",Auth::user()->id)->count() > 0){
				return $this::responseJsonError(Lang::get("messages.ClientAlreadyInvited"));
			}
			
			$type = "ClientRequest";
			$client = new Clients;
			$client->userId = $user->id;
			$client->trainerId = Auth::user()->id;
			$client->approvedClient = 0;
			$client->approvedTrainer = 1;
			$client->save();
			$invite = new Invites;
			$invite->userId = Auth::user()->id;
			$invite->fakeId = $user->id;
			$invite->firstName = $user->firstName;
			$invite->lastName = $user->lastName;
			$invite->email = $user->email;
			$invite->key = GUID::generate();
			$invite->type = "ClientRequest";
			$invite->save();

			if(Friends::where("followingId",$user->id)->where("userId",Auth::user()->id)->count() == 0){
				$friends = new Friends;
				$friends->followingId = $user->id;
				$friends->userId = Auth::user()->id;
				$friends->chat = 1;
				$friends->save();
				if($user){
					Notifications::insertDynamicNotification("Following",$user->id,Auth::user()->id,array("firstName"=>Auth::user()->firstName,"lastName"=>Auth::user()->lastName)); 
				}
				Feeds::insertDynamicFeed("Following",$user->id,Auth::user()->id,array("firstName"=>$user->firstName,"lastName"=>$user->lastName,"friendFirstName"=>Auth::user()->firstName,"friendLastName"=>Auth::user()->lastName));  
			}
			if(Friends::where("followingId",Auth::user()->id)->where("userId",$user->id)->count() == 0){
				$friends = new Friends;
				$friends->followingId = Auth::user()->id;
				$friends->userId = $user->id;
				$friends->chat = 1;
				$friends->save();
				if($user){
					Notifications::insertDynamicNotification("Following",Auth::user()->id,$user->id,array("firstName"=>$user->firstName,"lastName"=>$user->lastName)); 
				}
				Feeds::insertDynamicFeed("Following",$user->id,Auth::user()->id,array("firstName"=>$user->firstName,"lastName"=>$user->lastName,"friendFirstName"=>Auth::user()->firstName,"friendLastName"=>Auth::user()->lastName)); 
			}

			Feeds::insertDynamicFeed("ClientRequest",Auth::user()->id,$user->followingId,array("firstName"=>Auth::user()->firstName,"lastName"=>Auth::user()->lastName)); 
			Notifications::insertDynamicNotification("ClientInvitation",$user,Auth::user(),array("link"=>URL::to("/Clients/Invitation/".$invite->key."/")),true,$invite->key);

			Event::fire('sendInviteToClient', array(Auth::user(),$user->id));

			return $this::responseJson(array("message" => Lang::get("messages.ClientInvited"),"id"=>$user->id));	
		} else {
			$rules = array("firstName => required","lastName"=>"required","email"=>"required|email|unique:Users");
			$validation = Validator::make(Input::all(), $rules);
			if(Users::where("email",Input::get("email"))->count() > 0 || Input::get("firstName") == "" || Input::get("lastName") == ""){
				return $this::responseJsonError(Lang::get("messages.Oops"));
			}

			if($validation){
				
				$user = new Users;
				$user->firstName = ucfirst(Input::get("firstName"));
				$user->lastName = ucfirst(Input::get("lastName"));
				$user->email = (Input::get("email"));
				$user->userType = "TempTrainee";
				$user->save();
				$client = new Clients;
				$client->userId = $user->id;
				$client->trainerId = Auth::user()->id;
				$client->approvedClient = 1;
				$client->approvedTrainer = 1;
				$client->save();

				if(Friends::where("followingId",$user->id)->where("userId",Auth::user()->id)->count() == 0){
					$friends = new Friends;
					$friends->followingId = $user->id;
					$friends->userId = Auth::user()->id;
					$friends->chat = 1;
					$friends->save();
					if($user){
						Notifications::insertDynamicNotification("Following",$user->id,Auth::user()->id,array("firstName"=>Auth::user()->firstName,"lastName"=>Auth::user()->lastName)); 
					}
					Feeds::insertDynamicFeed("Following",Auth::user()->id,$user->id,array("firstName"=>Auth::user()->firstName,"lastName"=>Auth::user()->lastName,"friendFirstName"=>$user->firstName,"friendLastName"=>$user->lastName)); 
				}
				if(Friends::where("followingId",Auth::user()->id)->where("userId",$user->id)->count() == 0){
					$friends = new Friends;
					$friends->followingId = Auth::user()->id;
					$friends->userId = $user->id;
					$friends->chat = 1;
					$friends->save();
					if($user){
						Notifications::insertDynamicNotification("Following",Auth::user()->id,$user->id,array("firstName"=>$user->firstName,"lastName"=>$user->lastName)); 
					}
					Feeds::insertDynamicFeed("Following",$user->id,Auth::user()->id,array("firstName"=>$user->firstName,"lastName"=>$user->lastName,"friendFirstName"=>Auth::user()->firstName,"friendLastName"=>Auth::user()->lastName)); 
				}


				$invite = new Invites;
				$invite->userId = Auth::user()->id;
				$invite->email = Input::get("email");
				$invite->fakeId = $user->id;
				$invite->firstName = $user->firstName;
				$invite->lastName = $user->lastName;
				$invite->key = GUID::generate();
				$invite->type = "ClientRequest";
				$invite->save();
				$invite->sendInviteClient();
//				Feeds::insertFeed("InvitedClient",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);

				Event::fire('sendInviteToNotClient', array(Auth::user(),Input::get("email")));
				
				return $this::responseJson(array("message" => Lang::get("messages.ClientInvitation"),"id"=>$user->id));	

			} else {
				return $this::responseJsonErrorValidation($validation->messages());
			}
		}
	}



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
		$validation = Users::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$clients = new Users;
			$clients->client = Input::get("client");
			$clients->measureable = Input::get("measureable");
			$clients->recordDate = Input::get("dateRecord");
			$clients->userId = Auth::user()->id;
			$clients->save();
			Feeds::insertFeed("NewObjective",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			return $this::responseJson(Lang::get("messages.ObjectiveAdded"));	
		}
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /clients
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /clients/{id}
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
	 * GET /clients/{id}/edit
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
	 * PUT /clients/{id}
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
	 * DELETE /clients/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$obj = Clients::find($id);
		if(!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));

		if($this->checkPermissions($obj->trainerId,Auth::user()->id)){
			$obj->delete();
			return $this::responseJson(Lang::get("messages.ClientDeleted"));
		} else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		}
		
	
	}

}