<?php


class UserMessages extends \Eloquent {

	protected $fillable = [];
	public $table = "arrowchat";
	public $timestamps = false;

	public static $rules = array(
		"message" => "required"
	);

	public function toUser(){
		return $this->belongsTo("Users","to","id");
	}

	public function fromUser(){
		return $this->belongsTo("Users","from","id");
	}

	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

	public static function insertMessage($message,$fromId,$toId=""){
		self::insert( array(
						"message"		=>	$message,
						"from"			=>	$user,
						"to"			=>	$user,
						"sent"			=>	Helper::dateToUnix(date('Y-m-d H:i:s')),
						"read"			=>	0,
						"user_read"		=>	0,
						"warup"			=> 	0
						)
			);
	}

	public static function readUserMessages($fromUser,$toUser){
		$results = self::whereNull("read")->where(
						function($query) use($fromUser,$toUser){
							$query->orWhere("from",$fromUser);
							$query->orWhere("to",$toUser);
						}
				)->get();
		foreach($results as $result){
			$result->viewed = date("Y-m-d H:i:s");
			$result->save();
		}
	}

	public static function getInbox(){

		return
						DB::select("select * from (
										select * from (
												(select `to` as user, message, sent from arrowchat where `from` = ".Auth::user()->id." order by sent DESC)  
												UNION 
												(select `from` as user, message, sent from arrowchat where `to` = ".Auth::user()->id."  order by sent DESC)) 
										as tempTable order by sent desc ) 
								ordered left join users on users.id = ordered.user group by user ");
	}
}