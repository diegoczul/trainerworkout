<?php

class UserMessagesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /messages
	 *
	 * @return Response
	 */

	public $pageSize = 9;
	public $pageSizeFull = 9;


	public function index()
	{

		$userId = Auth::user()->id;
		$total = UserMessages::where("to","=",$userId)->whereNull("read")->orderBy('sent', 'DESC')->count();
		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;
		$data = json_encode(array(
		    'total' => $total
		));
		return $data;
	}

	public function indexOld()
	{

		$userId = Auth::user()->id;

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("notifications.notifications");
	}

	public function indexMail()
	{

		$userId = Auth::user()->id;

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.full.mail")
			->with("messages",UserMessages::getInbox());
	}

	public function dialog($user)
	{

		return View::make("widgets.full.dialog")
			->with("messages",UserMessages::where(
						function($query){
							$query->orWhere("from",Auth::user()->id);
							$query->orWhere("to",Auth::user()->id);
						}
				)->orderBy("sent","ASC")->get()
			)
			->with("friend",Users::find($user));
	}

	public function composeMail()
	{

		$userId = Auth::user()->id;

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.full.composeMail");
	}

	public function composeMailToUser($user)
	{
		$client = Users::find($user);
		$userId = Auth::user()->id;

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.full.composeMail")
			->with("client",$client);
	}



	public function AddEdit()
	{

		if(Input::has("id") and Input::get("id") != ""){
			return $this->update(Input::get("id"));
		} else {
			return $this->create();
		}		
	}

	public function readUserMessages(){
		UserMessages::readUserMessages(Input::get("user"),Input::get("user"));
	}


	public function create()
	{
		if(!Input::has("friend")){
			return $this::responseJsonError(Lang::get("messages.NoFriendChosen"));
		}
		$validation = UserMessages::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$messages = new UserMessages;
			$messages->message = Input::get("message");
			$messages->from = Auth::user()->id;
			$messages->to = Input::get("friend");
			$messages->sent = Helper::dateToUnix(date("Y-m-d H:i:s"));
			$messages->read = 1;
			$messages->user_read = 0;
			$messages->save();
			return $this::responseJson(Lang::get("messages.MessageSent"));	
		}
	}

	public function eventMessageClient($userId,$userId2){
		
		$userTo = Users::find($userId2);

		if($userTo){
			if(Clients::checkIfTrainerHasClient($userId,$userId2)){
				Event::fire('messageClient', array(Auth::user(),$userTo->firstName." ".$userTo->lastName));
			} else if(Clients::checkIfTraineeHasTrainer($userId,$userId2)){
				Event::fire('messagePersonalTrainer', array(Auth::user(),$userTo->firstName." ".$userTo->lastName));
			} else {
				Event::fire('messageNoneClient', array(Auth::user(),$userTo->firstName." ".$userTo->lastName));
			}

		}
	}

	public function eventTest(){
		//http_get("http://www.trainerworkout.com/Events/eventMessageClient/".$userId."/".$to);
		// create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, "http://www.trainerworkout.com");

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch); 
	}



	public function destroy($id)
	{
		$obj = UserMessages::find($id);
		if(!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));

		if($this->checkPermissions($obj->userId,Auth::user()->id)){
			$obj->delete();
			return $this::responseJson(Lang::get("messages.MessageDeleted"));
		} else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		}
		
	
	}

}		