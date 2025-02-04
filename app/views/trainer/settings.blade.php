

@extends('layouts.trainer')

@section('content')
 
 <section id="content" class="contenttoptouch clearfix">
    	<div class="bannerholder">
          <div class="wrapper clearfix">
              <div class="profileimage">
                  <img src="/{{ Helper::image(Auth::user()->thumb) }}" alt="profile image">
                </div>
                <div class="profieldetails">
                  <h1>{{ $user->firstName }}</h1>
                     <h3>
                      <a class="editicon fltright" href="/Trainee/EditProfile/">edit</a>
                      Training with TrainerWorkout since: {{ Helper::date($user->created_at) }}
                     </h3>
                     <ul class="clearfix">
                      <li>{{ $user->lastName }}</li>
                        <li>
                          @if($user->birthday != "")
                            {{ Helper::getAge($user->birthday)}} years old
                          @endif
                        </li>
                        <li>{{ $user->email }}</li>
                        <li>{{ $user->phone }}</li>
                     </ul>
                </div>
            </div>
          
        </div>

		<div class="wrapper">
        	<div class="widgets fullwidthwidget shadow clearfix">
               <?php //dd($userPermissions); ?>
     {{ Form::open(array('url' => Lang::get("routes./Trainer/Settings"), 'class'=>"bootstrap-frm")); }}
         <!-- <fieldset>
            <label>Bench</label>
            <select name="bench" class="chosen-select">
              <option value="0">Everybody</option>
              <option value="friends">Only Friends</option>
              <option value="private">Only Me</option>
            </select>
          </fieldset>-->
          <h2 style='margin-top:20px'> General Settings </h2>
          <fieldset>
            <label>Objectives </label>
            <select name="w_objectives" class="chosen-select">
              <option {{ (array_key_exists("w_objectives",$userPermissions) && $userPermissions["w_objectives"] == "public") ? "selected='selected'" : "" }} value="public">Everybody</option>
              <option {{ (array_key_exists("w_objectives",$userPermissions) && $userPermissions["w_objectives"] == "friends") ? "selected='selected'" : "" }} value="friends">Only Friends</option>
              <option {{ (array_key_exists("w_objectives",$userPermissions) && $userPermissions["w_objectives"] == "private") ? "selected='selected'" : "" }} value="private">Only Me</option>
            </select>
          </fieldset>
          <fieldset>
            <label>Pictures</label>
            <select name="w_pictures" class="chosen-select">
              <option {{ (array_key_exists("w_pictures",$userPermissions) && $userPermissions["w_pictures"] == "public") ? "selected='selected'" : "" }} value="public">Everybody</option>
              <option {{ (array_key_exists("w_pictures",$userPermissions) && $userPermissions["w_pictures"] == "friends") ? "selected='selected'" : "" }} value="friends">Only Friends</option>
              <option {{ (array_key_exists("w_pictures",$userPermissions) && $userPermissions["w_pictures"] == "private") ? "selected='selected'" : "" }} value="private">Only Me</option>
            </select>
          </fieldset>
          <fieldset>
            <label>Measurements</label>
            <select name="w_measurements" class="chosen-select">
              <option {{ (array_key_exists("w_measurements",$userPermissions) && $userPermissions["w_measurements"] == "public") ? "selected='selected'" : "" }} value="public">Everybody</option>
              <option {{ (array_key_exists("w_measurements",$userPermissions) && $userPermissions["w_measurements"] == "friends") ? "selected='selected'" : "" }} value="friends">Only Friends</option>
              <option {{ (array_key_exists("w_measurements",$userPermissions) && $userPermissions["w_measurements"] == "private") ? "selected='selected'" : "" }} value="private">Only Me</option>
            </select>
          </fieldset>
          <fieldset>
            <label>Workouts</label>
            <select name="w_workouts" class="chosen-select">
              <option {{ (array_key_exists("w_workouts",$userPermissions) && $userPermissions["w_workouts"] == "public") ? "selected='selected'" : "" }} value="public">Everybody</option>
              <option {{ (array_key_exists("w_workouts",$userPermissions) && $userPermissions["w_workouts"] == "friends") ? "selected='selected'" : "" }} value="friends">Only Friends</option>
              <option {{ (array_key_exists("w_workouts",$userPermissions) && $userPermissions["w_workouts"] == "private") ? "selected='selected'" : "" }} value="private">Only Me</option>
            </select>
          </fieldset>
          <!--<fieldset>
            <label>Nutritions</label>
            <select name="nutritions" class="chosen-select">
              <option value="public">Everybody</option>
              <option value="friends">Only Friends</option>
              <option value="private">Only Me</option>
            </select>-->
          </fieldset>
          <fieldset>
            <label>Information </label>
            <select name="w_information" class="chosen-select">
              <option {{ (array_key_exists("w_information",$userPermissions) && $userPermissions["w_information"] == "public") ? "selected='selected'" : "" }} value="public">Everybody</option>
              <option {{ (array_key_exists("w_information",$userPermissions) && $userPermissions["w_information"] == "friends") ? "selected='selected'" : "" }} value="friends">Only Friends</option>
              <option {{ (array_key_exists("w_information",$userPermissions) && $userPermissions["w_information"] == "private") ? "selected='selected'" : "" }} value="private">Only Me</option>
            </select>
          </fieldset>
          <fieldset>
            <label>Send me messages</label>
            <select name="w_userMessages" class="chosen-select">
              <option {{ (array_key_exists("w_userMessages",$userPermissions) && $userPermissions["w_userMessages"] == "public") ? "selected='selected'" : "" }} value="public">Everybody</option>
              <option {{ (array_key_exists("w_userMessages",$userPermissions) && $userPermissions["w_userMessages"] == "friends") ? "selected='selected'" : "" }} value="friends">Only Friends</option>
              <option {{ (array_key_exists("w_userMessages",$userPermissions) && $userPermissions["w_userMessages"] == "private") ? "selected='selected'" : "" }} value="private">Only Me</option>
            </select>
          </fieldset>
          <fieldset>
                <label>Public profile</label>
                <select name="w_publicProfile" class="chosen-select">
                  <option {{ (array_key_exists("w_publicProfile",$userPermissions) && $userPermissions["w_publicProfile"] == "yes") ? "selected='selected'" : "" }} value="yes">Yes</option>
                  <option {{ (array_key_exists("w_publicProfile",$userPermissions) && $userPermissions["w_publicProfile"] == "no") ? "selected='selected'" : "" }} value="no">No</option>
                </select>
          </fieldset>  
          <h2 style='margin-top:20px'> Emails </h2>
          <fieldset>
            <label>Any Email Notifications</label>
            <select name="email_notifications" class="chosen-select">
              <option {{ (array_key_exists("email_notifications",$userPermissions) && $userPermissions["email_notifications"] == "yes") ? "selected='selected'" : "" }} value="yes">Yes</option>
              <option {{ (array_key_exists("email_notifications",$userPermissions) && $userPermissions["email_notifications"] == "no") ? "selected='selected'" : "" }} value="no">No</option>
           
            </select>
          </fieldset>
          <fieldset>
            <label>Workout Notifications</label>
            <select name="email_notifications_workout" class="chosen-select">
              <option {{ (array_key_exists("email_notifications_workout",$userPermissions) && $userPermissions["email_notifications_workout"] == "yes") ? "selected='selected'" : "" }} value="yes">Yes</option>
              <option {{ (array_key_exists("email_notifications_workout",$userPermissions) && $userPermissions["email_notifications_workout"] == "no") ? "selected='selected'" : "" }} value="no">No</option>
           
            </select>
          </fieldset>
          <fieldset>
            <label>Client Notifications</label>
            <select name="email_notifications_client" class="chosen-select">
              <option {{ (array_key_exists("email_notifications_client",$userPermissions) && $userPermissions["email_notifications_client"] == "yes") ? "selected='selected'" : "" }} value="yes">Yes</option>
              <option {{ (array_key_exists("email_notifications_client",$userPermissions) && $userPermissions["email_notifications_client"] == "no") ? "selected='selected'" : "" }} value="no">No</option>
           
            </select>
          </fieldset>
          <fieldset>
            <label>People Notifications</label>
            <select name="email_notifications_people" class="chosen-select">
              <option {{ (array_key_exists("email_notifications_people",$userPermissions) && $userPermissions["email_notifications_people"] == "yes") ? "selected='selected'" : "" }} value="yes">Yes</option>
              <option {{ (array_key_exists("email_notifications_people",$userPermissions) && $userPermissions["email_notifications_people"] == "no") ? "selected='selected'" : "" }} value="no">No</option>
           
            </select>
          </fieldset>
          <fieldset>
            <label>Trainer Notifications</label>
            <select name="email_notifications_trainer" class="chosen-select">
              <option {{ (array_key_exists("email_notifications_trainer",$userPermissions) && $userPermissions["email_notifications_trainer"] == "yes") ? "selected='selected'" : "" }} value="yes">Yes</option>
              <option {{ (array_key_exists("email_notifications_trainer",$userPermissions) && $userPermissions["email_notifications_trainer"] == "no") ? "selected='selected'" : "" }} value="no">No</option>
           
            </select>
          </fieldset>
          <fieldset>
                <label>Newsletter</label>
                <select name="newsletter" class="chosen-select">
                  <option {{ (array_key_exists("newsletter",$userPermissions) && $userPermissions["newsletter"] == "yes") ? "selected='selected'" : "" }} value="yes">Yes</option>
                  <option {{ (array_key_exists("newsletter",$userPermissions) && $userPermissions["newsletter"] == "no") ? "selected='selected'" : "" }} value="no">No</option>
                </select>
          </fieldset>
          <h2 style='margin-top:20px'> Trainer </h2>  
          <fieldset>
                <label>Remind me to revize my client's workouts every</label>
                <select name="setting_workout_reminder" class="chosen-select">
                  @for($x = 1; $x < 91; $x++)
                    <option {{ (array_key_exists("setting_workout_reminder",$userPermissions) && $userPermissions["setting_workout_reminder"] == $x) ? "selected='selected'" : "" }} value="{{{ $x }}}">{{{ $x }}} days</option>
                  @endfor
                </select>
          </fieldset>
          <fieldset>
                <label>Remind me of my client's goal progress every:</label>
                <select name="setting_workout_reminder_number" class="chosen-select">
                  @for($x = 1; $x < 91; $x++)
                    <option {{ (array_key_exists("setting_workout_reminder_number",$userPermissions) && $userPermissions["setting_workout_reminder_number"] == $x) ? "selected='selected'" : "" }} value="{{{ $x }}}">{{{ $x }}} workouts</option>
                  @endfor
                </select>
          </fieldset>
          <fieldset>
                <label>Remind me to update my clients weight every</label>
                <select name="setting_weight_reminder_number" class="chosen-select">
                  @for($x = 1; $x < 91; $x++)
                    <option {{ (array_key_exists("setting_weight_reminder_number",$userPermissions) && $userPermissions["setting_weight_reminder_number"] == $x) ? "selected='selected'" : "" }} value="{{{ $x }}}">{{{ $x }}} workouts</option>
                  @endfor
                </select>
          </fieldset>
          <fieldset>
                <label>Remind me to update my clients measurements every</label>
                <select name="setting_measurements_reminder_number" class="chosen-select">
                  @for($x = 1; $x < 91; $x++)
                    <option {{ (array_key_exists("setting_measurements_reminder_number",$userPermissions) && $userPermissions["setting_measurements_reminder_number"] == $x) ? "selected='selected'" : "" }} value="{{{ $x }}}">{{{ $x }}} workouts</option>
                  @endfor
                </select>
          </fieldset>
          <fieldset>
                <label>Remind me to update my client's pictures every</label>
                <select name="setting_pictures_reminder_number" class="chosen-select">
                  @for($x = 1; $x < 91; $x++)
                    <option {{ (array_key_exists("setting_pictures_reminder_number",$userPermissions) && $userPermissions["setting_pictures_reminder_number"] == $x) ? "selected='selected'" : "" }} value="{{{ $x }}}">{{{ $x }}} workouts</option>
                  @endfor
                </select>
          </fieldset>
          <fieldset>
                <label>Notify me when a client has been inactive for more than</label>
                <select name="setting_inactive_reminder_number" class="chosen-select">
                  @for($x = 1; $x < 91; $x++)
                    <option {{ (array_key_exists("setting_inactive_reminder_number",$userPermissions) && $userPermissions["setting_inactive_reminder_number"] == $x) ? "selected='selected'" : "" }} value="{{{ $x }}}">{{{ $x }}} days</option>
                  @endfor
                </select>
          </fieldset>
          <fieldset>
            <input type="submit" value="Save Settings" class="bluebtn ajaxSaveSubmit" widget="" style="margin-top:20px;">
          </fieldset>
      {{ Form::close() }}
          </div>
        	
          
          
        
         
            
            
        </div>
    </section>
 
    @endsection

@section('scripts')
<script>
$(document).ready(function(){
      $(".chosen-select").trigger("chosen:updated");
});
</script>
@endsection