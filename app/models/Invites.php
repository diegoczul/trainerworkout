<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Invites extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static $rules = array(
		"userId" => "required",
		"followerId" => "required",
	);

	public function users(){
		return $this->belongsTo("Users");
	}

	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

	public function sendInvite(){
		$user = Users::find($this->userId);
		$name = $user->firstName;
		$this->lastSent = new DateTime;
		$this->save();
		$subject = Lang::get("content.InviteClient",array("firstName"=>$user->firstName,"lastName"=>$user->lastName));
		Mail::queueOn(App::environment(),'emails.'.Config::get("app.whitelabel").'.user.'.App::getLocale().'.inviteFriend', array("invite"=>$this,"user"=>$user,"name"=>$name), function($message) use ($user,$subject)
		{
		  $message->to($this->email)
          			->subject($subject);
		});
		
	}

	public function sendInviteClient($comments = ""){
		$user = Users::find($this->userId);
		$fake = Users::find($this->fakeId);
		$this->lastSent = new DateTime;
		$this->save();
		$email = $this->email;
		
		$subject = Lang::get("content.InviteClient",array("firstName"=>$user->firstName,"lastName"=>$user->lastName));
		Mail::queueOn(App::environment(),'emails.'.Config::get("app.whitelabel").'.user.'.App::getLocale().'.inviteClient', array("comments"=>$comments,"password"=>null,"invite"=>serialize($this),"user"=>serialize($user),"fake"=>serialize($fake)), function($message) use ($user,$email,$subject)
		{
		  $message->to($email)
          			->subject($subject);
		});
		
	}

	public function completeInvite(){
		$this->completed = 1;
		$this->save();
		$friend = new Friends;
		$friend->userId = $this->userId;
		$friend->followingId = $this->fakeId;
		$friend->save();

		$friendBack = new Friends;
		$friendBack->userId = $this->fakeId;
		$friendBack->followingId = $this->userId;
		$friendBack->save();
	}


}