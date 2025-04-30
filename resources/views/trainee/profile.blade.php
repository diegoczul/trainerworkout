@use('App\Http\Libraries\Helper')

@extends('layouts.trainee')

 <!--------------------------     Profile Page of Users     ---------------------------->


@section("header")
    {!! Helper::seo("traineeProfile",array("firstName"=>$user->firstname,"lastName"=>$user->lastName)) !!}
@endsection


@section('content')
{{ Form::open(array('url' => Lang::get('routes./Trainee/EditProfile'), "class"=>"p_form", "files"=>true)) }}
<div class="content">
<div class="traineeBackgroundFilter"></div>
	<div class="contentContainer trainer">
		<div class="p_Container profile trainee-profile-form">
			<div class="p_img showRotate exercisesimages showDelete rotatingParent_bottom trainee">
				<img class="refreshImage" src="/{{{ Helper::image(Auth::user()->image) }}}">
                <div class="editImgContainer">
                    @if(Auth::user()->image != "")
                    <a href="javascript:void(0)" onClick="rotateLeft($(this)); arguments[0].stopPropagation(); return false;" class="rotate_left"><img src="{{asset('assets/img/rotate_left-white.png')}}"/></a>
                    @endif
                    <a class="editProfile" id="editImg" href="#" onclick="$(this).closest('.profile').find('.inputBoxFile').toggle()">edit</a>
                    @if(Auth::user()->image != "")
                    <a href="javascript:void(0)" onClick="rotateRight($(this)); arguments[0].stopPropagation(); return false;" class="rotate_right"><img src="{{asset('assets/img/rotate_right-white.png')}}"/></a>
                    @endif
                </div>
				<input class='inputBoxFile' type="file" name="image" style="display:none">
			</div>
			
				<input id="timezone" type="hidden" name="timezone" class="inputboxmid" value="{{request()->old("timezone")}}" />
				<label for="fname">{{ Lang::get("content.first name") }}</label>
				<input type="text" placeholder="first name" name="firstName" id="firstName" value="{{{ Auth::user()->firstName }}}">
				<label for="lname">{{ Lang::get("content.last name") }}</label>
				<input type="text" placeholder="first name" name="lastName" id="lastName" value="{{{ Auth::user()->lastName }}}">
				<label for="phone">{{ Lang::get("content.createAccountPhone") }}</label>
				<input type="tel" placeholder="1 (514) 555-4444" name="phone" id="phone" value="{{{ Auth::user()->phone }}}">	
				<label for="email">{{ Lang::get("content.email") }}</label>
				<input type="email" placeholder="name@domain.com" name="email" id="email" value="{{{ Auth::user()->email }}}">
				<label for="password">{{{ Lang::get("content.password") }}}</label>
				<input type="password" placeholder="" name="password" id="password">
				<label for="password_confirmation">{{{ Lang::get("content.reset/placeholder") }}}</label>
				<input type="password" placeholder="" name="password_confirmation" id="password_confirmation">	
				<div class="saveButton">    
                    <button>{{{ Lang::get("content.save") }}}</button>
                </div>	
			
		</div>
	</div>
</div>
{{ Form::close() }}
@endsection

@section("scripts")

<script>

function getTimezoneName() {
    tmSummer = new Date(Date.UTC(2005, 6, 30, 0, 0, 0, 0));
    so = -1 * tmSummer.getTimezoneOffset();
    tmWinter = new Date(Date.UTC(2005, 12, 30, 0, 0, 0, 0));
    wo = -1 * tmWinter.getTimezoneOffset();

    if (-660 == so && -660 == wo) return 'Pacific/Midway';
    if (-600 == so && -600 == wo) return 'Pacific/Tahiti';
    if (-570 == so && -570 == wo) return 'Pacific/Marquesas';
    if (-540 == so && -600 == wo) return 'America/Adak';
    if (-540 == so && -540 == wo) return 'Pacific/Gambier';
    if (-480 == so && -540 == wo) return 'US/Alaska';
    if (-480 == so && -480 == wo) return 'Pacific/Pitcairn';
    if (-420 == so && -480 == wo) return 'US/Pacific';
    if (-420 == so && -420 == wo) return 'US/Arizona';
    if (-360 == so && -420 == wo) return 'US/Mountain';
    if (-360 == so && -360 == wo) return 'America/Guatemala';
    if (-360 == so && -300 == wo) return 'Pacific/Easter';
    if (-300 == so && -360 == wo) return 'US/Central';
    if (-300 == so && -300 == wo) return 'America/Bogota';
    if (-240 == so && -300 == wo) return 'US/Eastern';
    if (-240 == so && -240 == wo) return 'America/Caracas';
    if (-240 == so && -180 == wo) return 'America/Santiago';
    if (-180 == so && -240 == wo) return 'Canada/Atlantic';
    if (-180 == so && -180 == wo) return 'America/Montevideo';
    if (-180 == so && -120 == wo) return 'America/Sao_Paulo';
    if (-150 == so && -210 == wo) return 'America/St_Johns';
    if (-120 == so && -180 == wo) return 'America/Godthab';
    if (-120 == so && -120 == wo) return 'America/Noronha';
    if (-60 == so && -60 == wo) return 'Atlantic/Cape_Verde';
    if (0 == so && -60 == wo) return 'Atlantic/Azores';
    if (0 == so && 0 == wo) return 'Africa/Casablanca';
    if (60 == so && 0 == wo) return 'Europe/London';
    if (60 == so && 60 == wo) return 'Africa/Algiers';
    if (60 == so && 120 == wo) return 'Africa/Windhoek';
    if (120 == so && 60 == wo) return 'Europe/Amsterdam';
    if (120 == so && 120 == wo) return 'Africa/Harare';
    if (180 == so && 120 == wo) return 'Europe/Athens';
    if (180 == so && 180 == wo) return 'Africa/Nairobi';
    if (240 == so && 180 == wo) return 'Europe/Moscow';
    if (240 == so && 240 == wo) return 'Asia/Dubai';
    if (270 == so && 210 == wo) return 'Asia/Tehran';
    if (270 == so && 270 == wo) return 'Asia/Kabul';
    if (300 == so && 240 == wo) return 'Asia/Baku';
    if (300 == so && 300 == wo) return 'Asia/Karachi';
    if (330 == so && 330 == wo) return 'Asia/Calcutta';
    if (345 == so && 345 == wo) return 'Asia/Katmandu';
    if (360 == so && 300 == wo) return 'Asia/Yekaterinburg';
    if (360 == so && 360 == wo) return 'Asia/Colombo';
    if (390 == so && 390 == wo) return 'Asia/Rangoon';
    if (420 == so && 360 == wo) return 'Asia/Almaty';
    if (420 == so && 420 == wo) return 'Asia/Bangkok';
    if (480 == so && 420 == wo) return 'Asia/Krasnoyarsk';
    if (480 == so && 480 == wo) return 'Australia/Perth';
    if (540 == so && 480 == wo) return 'Asia/Irkutsk';
    if (540 == so && 540 == wo) return 'Asia/Tokyo';
    if (570 == so && 570 == wo) return 'Australia/Darwin';
    if (570 == so && 630 == wo) return 'Australia/Adelaide';
    if (600 == so && 540 == wo) return 'Asia/Yakutsk';
    if (600 == so && 600 == wo) return 'Australia/Brisbane';
    if (600 == so && 660 == wo) return 'Australia/Sydney';
    if (630 == so && 660 == wo) return 'Australia/Lord_Howe';
    if (660 == so && 600 == wo) return 'Asia/Vladivostok';
    if (660 == so && 660 == wo) return 'Pacific/Guadalcanal';
    if (690 == so && 690 == wo) return 'Pacific/Norfolk';
    if (720 == so && 660 == wo) return 'Asia/Magadan';
    if (720 == so && 720 == wo) return 'Pacific/Fiji';
    if (720 == so && 780 == wo) return 'Pacific/Auckland';
    if (765 == so && 825 == wo) return 'Pacific/Chatham';
    if (780 == so && 780 == wo) return 'Pacific/Enderbury'
    if (840 == so && 840 == wo) return 'Pacific/Kiritimati';
    return 'US/Pacific';
}
$(document).ready(function(){
  var timezone = getTimezoneName();
  $("#timezone").val(timezone);
});


function rotateLeft(){
    $.ajax(
            {
                url :"{{ Lang::get("routes./Profile/Rotate/Left") }}",
                type: "POST",
              
                success:function(data, textStatus, jqXHR) 
                {
                    successMessage(data);
                    //callWidget("exercises_full");
                    refreshImages("refreshImage");
                    //location.reload();
                    //window.location = window.location;

                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    errorMessage(jqXHR.responseText);
                },
                statusCode: {
                    500: function() {
                        if(jqXHR.responseText != ""){
                            errorMessage(jqXHR.responseText);
                        }else {
                            
                        }
                        
                    }
                }
            });
}

function rotateRight(){
    $.ajax(
            {
                url :"{{ Lang::get("routes./Profile/Rotate/Right") }}",
                type: "POST",
                
                success:function(data, textStatus, jqXHR) 
                {
                    successMessage(data);
                    refreshImages("refreshImage");
                    location.reload();
                    //window.location = window.location;
                    //callWidget("exercises_full");
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    errorMessage(jqXHR.responseText);
                },
                statusCode: {
                    500: function() {
                        if(jqXHR.responseText != ""){
                            errorMessage(jqXHR.responseText);
                        }else {
                            
                        }
                        
                    }
                }
            });

}

</script>

@endsection