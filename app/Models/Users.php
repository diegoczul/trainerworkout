<?php
namespace App\Models;

use App\Http\Libraries\Helper;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Stripe\Stripe;
use Stripe\Subscription;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Users extends Authenticatable implements JWTSubject
{
    use SoftDeletes, Notifiable;

    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'address',
        'phone',
        'street',
        'suite',
        'city',
        'province',
        'country',
        'userType',
        'password',
        'fbUsername',
        'timezone',
        'birthday',
        'fbUsername',
        'biography',
        'certifications',
        'specialities',
        'past_experience',
        'word',
        'updated_at',
        'lastLogin',
        'virtual',
    ];
    protected $dates = ['deleted_at'];
    protected $hidden = array('password');
    protected $softDelete = true;

    public static $rules = array(
        "firstName" => "required|min:2",
        "lastName" => "required|min:2",
        "password" => "required|min:6",
        //"password" => "required|min:6|confirmed",
        // "password_confirmation " => "same:password",
        "email" => "required|email|unique:users",
        "certifications" => "max:5000",
        "past_experience" => "max:5000",
        "biography" => "max:5000",
        "specialities" => "max:5000"
    );
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public static function validate($data,$extra = array()){
        foreach($extra as $ex =>$val){
            static::$rules[$ex] = $val;
        }
        return Validator::make($data, static::$rules);
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getCompleteName(){
        return trim(ucfirst($this->firstName)." ".ucfirst($this->lastName));
    }

    public function getInitials(){
        $string = "";
        $string .= substr($this->firstName,0,1);
        $string .= substr($this->lastName,0,1);
        return $string;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function activeLogo(){
        return $this->hasOne(UserLogos::class,"userId","id")->where("active",1);
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getURL(){
        return Helper::userType($this->userType)."/".$this->id."/".Helper::formatURLString($this->firstName.$this->lastName );
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function getReminderEmail()
    {
        return $this->email;
    }

    public function getPath(){
        return Config::get("constants.userPath")."/".$this->id;
    }

    public function friends(){
        return $this->hasMany(Friends::class);
    }

    public function workouts(){
        return $this->hasMany(Workouts::class,"userId","id");
    }

    public function workoutsReleased(){
        return $this->hasMany(Workouts::class,"userId","id")->where("status","Released");
    }

    public function group(){
        return $this->hasOne(UserGroups::class,"userId","id");
    }

    public function membership(){
        return $this->hasOne(MembershipsUsers::class,"userId","id");
    }

    public function doubleCheckOnboardingClient(){
        $ALAIN = 24;
        $Corinne = 15;

        if(Clients::where("userId",$ALAIN)->where("trainerId",Auth::user()->id)->count() == 0){
            $client = new Clients();
            $client->userId = $ALAIN; // ALAIN TRAINEE
            $client->trainerId = $this->id;
            $client->approvedTrainer = 1;
            $client->approvedClient = 1;
            $client->save();
        }

        if(Friends::where("userId",$Corinne)->where("followingId",Auth::user()->id)->count() == 0){
            $friend = new Friends();
            $friend->userId = $Corinne; // ALAIN TRAINEE
            $friend->followingId = Auth::user()->id;
            $friend->save();
        }

        if(Friends::where("userId",Auth::user()->id)->where("followingId",$Corinne)->count() == 0){
            $friend = new Friends();
            $friend->userId = Auth::user()->id; // ALAIN TRAINEE
            $friend->followingId = $Corinne;
            $friend->save();
        }
    }

    public function freebesTrainer(){
        $ALAIN = 24;
        Workouts::AddWorkoutToUser(4652,$this->id,false,false);
    }

    public function membershipValid(){
        $membershipUser = MembershipsUsers::where("userId",Auth::user()->id)->first();
        if($membershipUser){
            $membership = $membershipUser->membership;
            if(date($membershipUser->expiry) >= Helper::nowDate() and count($this->workoutsReleased) <= $membership->workoutsAllowed){
                return true;
            }
        } else {
            if(count($this->workoutsReleased) <= Config::get("constants.maxFreeWorkouts")) return true;
        }
        return false;
    }

    public function membershipValidButAtLimit(){
        $membershipUser = MembershipsUsers::where("userId",Auth::user()->id)->first();
        if($membershipUser){
            $membership = $membershipUser->membership;
            if(date($membershipUser->expiry) >= Helper::nowDate() and count($this->workoutsReleased) < $membership->workoutsAllowed){
                return true;
            }
        } else {
            if(count($this->workoutsReleased) < Config::get("constants.maxFreeWorkouts")) return true;
        }
        return false;
    }

    public function freebesTrainee(){
        Workouts::AddWorkoutToUser(4652,$this->id,false,false);
    }

    public function sendActivationEmail(){
        $guid = Uuid::uuid4()->toString();
        $this->token = $guid;
        $this->save();

        $user = Users::find($this->id);

//        Mail::queueOn(App::environment(),'emails.'.Config::get("app.whitelabel").'.user.'.App::getLocale().'.activateEmail', array("user"=>serialize($user)), function($message) use ($user)
//        {
//            $message->to($user->email)
//                ->cc(Config::get("constants.activityEmail"))
//                ->subject(Messages::showEmailMessage("TrainerWorkoutEmailConfirmation"));
//        });
    }

    public function sendInviteGroup($authorFirstName, $authorLastName, $authorEmail){
        $guid = Uuid::uuid4()->toString();
        $this->token = $guid;
        $password = "";
        if($this->activated == "" and $this->created_at == $this->updated_at) {
            $password = str_random(8);
            $this->password = Hash::make($password);
        }

        $this->save();
        $subject = Lang::get("messages.TrainerWorkoutGroupInvitation");

        $user = Users::find($this->id);

        Mail::queueOn(App::environment(),'emails.'.Config::get("app.whitelabel").'.user.'.App::getLocale().'.inviteGroup', array("user"=>serialize($user),"password"=>$password,"authorFirstName"=>$authorFirstName,"authorLastName"=>$authorLastName,"authorEmail"=>$authorEmail), function($message) use ($user,$subject)
        {
            $message->to($user->email)
                ->cc(Config::get("constants.activityEmail"))
                ->subject($subject);
        });
    }

    public function postFBTimeline($userObject,$message,$url,$replaceArray=array(),$forceFacebook = false) {
        if($userObject == null){
            $userObject = Auth::user();
        }

        if($userObject->fbUsername != ""){
            foreach($replaceArray as $key => $value){
                $message = str_replace("{".$key."}",$value,$message);
            }
            $config = array(
                'appId' => '430853867021763',
                'secret' => '3f8d4530282a97ca54fcb2e8a091d2d2',
                'cookie' => true ,
                'scope' => 'publish_stream',
            );

            $fb = new Facebook($config);

            try {
                $ret_obj = $fb->api('/'.Auth::user()->fbUsername.'/feed', 'POST', array( 'link' => $url, 'message' => $message ));
                return array("error"=>false,"message"=>Lang::get("messages.FacebookPosted"));
            } catch(FacebookApiException $e) {
                return array("error"=>false,"message"=>Lang::get("messages.FacebookError"));
            }
        } else {
            if($forceFacebook) return array("error"=>false,"message"=>Lang::get("messages.NoFacebookUser"));
        }
    }

    public function clientLink(){
        return "/Client/".$this->id."/".Helper::formatURLString($this->getCompleteName());
    }

    public function addClient($user,$approved=true,$notify=true,$message=""){
        if(Clients::where("userId",$user->id)->where("trainerId",$this->id)->count() == 0){
            $client = new Clients;
            $client->userId = $user->id;
            $client->trainerId = $this->id;
            $client->approvedClient = ($approved) ? 1 : 0;
            $client->approvedTrainer = 1;
            if($notify){
                $client->subscribeClient = 1;
            } else {
                $client->subscribeClient = 0;
            }
            $client->save();

            if($user->virtual == 0){
                $invite = Auth::user()->sendInvite($user,"client",$message);
            } else {
                //$invite = Auth::user()->sendInvite($user,"client");
            }

            return $client;
        } else {
            return Clients::where("userId",$user->id)->where("trainerId",$this->id)->first();
        }
    }

    public function getNumberOfClients(){
        $number = Clients::leftJoin("users",function($join){
            $join->on("clients.userId","=","users.id");
        })->whereNotNull("users.id")->where("trainerId",$this->id)->count();
        return $number;
    }

    public function getNumberOfWorkouts(){
        $number = Workouts::where("userId",$this->id)->count();
        return $number;
    }

    public function getNumberOfExercises(){
        $number = Exercises::where("userId",$this->id)->count();
        return $number;
    }

    public function sendInvite($user,$type="client",$message = ""){
        $invited = Users::where("id",$user->id)->whereNotNull("lastLogin")->first();
        if(!$invited){
            $sentInvite = Invites::where("userId",$this->id)->where("fakeId",$user->id)->where("completed",0)->first();
            if($sentInvite){
                if($type=="client"){
                    $sentInvite->sendInviteClient($message);
                } else {
                    $sentInvite->sendInvite();
                }
                return $sentInvite;
            } else {
                $invite = new Invites;
                $invite->userId = $this->id;
                $invite->fakeId = $user->id;
                $invite->firstName = $user->firstName;
                $invite->lastName = $user->lastName;
                $invite->email = $user->email;
                $invite->key = Uuid::uuid4()->toString();
                if($type=="client"){
                    $invite->type = "ClientRequest";
                } else {
                    $invite->type = "FriendRequest";
                }
                $invite->save();

                if($type=="client"){
                    $invite->sendInviteClient($message);
                } else {
                    $invite->sendInvite();
                }

                return $invite;
            }
        } else {
            return null;
        }
    }

    public function getStripeSubscription(){
        $debug = Config::get('app.debug');
        if($debug){
            Stripe::setApiKey(Config::get('constants.debugApiKey'));
        } else {
            Stripe::setApiKey(Config::get('constants.apiKey'));
        }
        try {
            $subscription = Stripe::subscriptions()->all([
                'customer' => $this->stripeCustomerId,
                'limit' => 1
            ]);
            return $subscription;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function subscriptions(){
        return $this->hasMany("Subscriptions","userId","id");
    }

    public function scopeNonSoftDeleted($query){
        return $query->whereNull("deleted_at");
    }

    public function scopeSoftDeleted($query){
        return $query->whereNotNull("deleted_at");
    }

    public function subscriptionsAvailable()
    {
        $subscriptions = [];
        if (Subscription::count() > 0) {
            $subscriptions = Subscription::all();
        }
        return $subscriptions;
    }

    public function getUserSubscriptionPlan($user)
    {
        return $this->subscriptions()->where('userId', $user->id)->first();
    }

    public function updateToMembership($membershipId){
        $stripeMemberhsip = null;
        $twMembership = null;
        $today = date("Y-m-d");
        $interval = "months";
        $quantity = 1;


        //Check to what membership should I update;
        $membership = Memberships::find($membershipId);

        if($membership){
            if($membership->durationType == "yearly"){
                $interval = "years";
            }elseif ($membership->durationType == "monthly"){
                $interval = "months";
            } else {
                $interval = "days";
            }
            //Check if I am upgrading to Stripe or to Fre
            if($membership->free == 1){
                //Check if there is a strip membership IN PLACE and CANCEL IT
                $mem = MembershipsUsers::where("userId",$this->id)->first();

                if($mem){
                    $stripeMemberhsip = $this->getStripeSubscription();
                    if($stripeMemberhsip){
                        //$sub->cancel();
                        Log::error("CANCEL MEMBERSHIP STRIPE");
                    }

                    if(date($mem->expiry) < date("Y-m-d")){
                        $mem->expiry = date('Y-m-d', strtotime($today." + ".$quantity." ".$interval));
                        $mem->renewedTimes = $mem->renewedTimes+1;
                        $mem->save();
                    }
                } else {
                    $mem = new MembershipsUsers();
                    $mem->membershipId = $membershipId;
                    $mem->expiry = date('Y-m-d', strtotime($today." + ".$quantity." ".$interval));
                    $mem->registrationDate = date("Y-m-d");
                    $mem->userId = $this->id;
                    $mem->save();
                }

            } else {
                $stripeMemberhsip = $this->getStripeSubscription();
                $mem = MembershipUsers::where("userId",$this->id)->first();

                if($this->stripeCheckoutToken == "" or $stripeMemberhsip == null){
                    if(date($mem->expiry) < date("Y-m-d")){
                        $mem->expiry = date('Y-m-d', strtotime($today." + ".$quantity." ".$interval));
                        $mem->renewedTimes = $mem->renewedTimes+1;
                        $mem->save();
                    }
                } else {

                    if($membership->idAPI == $stripeMemberhsip->plan->id){
                        //UPDATE THE EXPIRY DATE;
                        $mem->membershipId = $membershipId;
                        $mem->expiry = date('Y-m-d', $stripeMemberhsip->current_period_end);
                        $mem->renewedTimes = $mem->renewedTimes+1;
                        $mem->save();
                    } else {
                        //CHANGE STRIPE PLANS
                        $stripeMemberhsip->plan->id = $membership->idAPI;
                        $subscription->save();

                        $mem->membershipId = $membershipId;
                        $mem->expiry = date('Y-m-d', $stripeMemberhsip->current_period_end);
                        $mem->save();
                    }

                }
            }

        }
    }
}
