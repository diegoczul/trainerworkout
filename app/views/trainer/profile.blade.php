@extends('layouts.trainer')

@section("header")
    {{ Helper::seo("trainerProfile",array("firstName"=>$user->firstname,"lastName"=>$user->lastName)) }}
@endsection

@section('content')
<section id="content" class="profile">
    <div class="wrapper">
        {{ Form::open(array('url' => Lang::get('routes./Trainer/EditProfile'), "class"=>"p_form", "files"=>true)); }}
        <input id="timezone" type="hidden" name="timezone" class="inputboxmid" value="{{Input::old("timezone")}}" />
        <div class="profileContainer">
            <div class="profileImagesCol">
                <label class="profilePictureContainer" for="image">{{ Lang::get("content.ProfileImage") }}
                    <div class="img rotatingParent_bottom">
                        <img src="/{{ Helper::image(Auth::user()->thumb) }}" alt="profile image" class="refreshImage profileImage"/>
                        <div class="editImgContainer">
                            <a href="javascript:void(0)" onClick="rotateLeft($(this)); arguments[0].stopPropagation(); return false;" class="rotate_left"><img src="/img/rotate_left.png"/></a>
                            <p id="editImg">{{ Lang::get("content.TEditImg") }}</p>
                            <a href="javascript:void(0)" onClick="rotateRight($(this)); arguments[0].stopPropagation(); return false;" class="rotate_right"><img src="/img/rotate_right.png"/></a>                    
                        </div>

                        <input type="file" class="profileImg profileImg1" name="image"></input>
                    </div>
                </label>
                <label for="logo">{{ Lang::get("content.logo") }}
                    <div class="img logoProfileContainer">
                        <img class="logoProfile" src="/{{ Helper::image(((isset($logo) and $logo) ? $logo->thumb : "")) }}" alt="profile image">
                        <p id="editImgLogo">{{ Lang::get("content.TEditImgLogo") }}</p>
                        <input type="file" class="profileImg profileImgLogo" name="logo"></input>
                    </div>
                </label>
            </div>
            <div class="profileInputsCol">
                <label for="fname">{{ Lang::get("content.TFirstName") }}<!-- <img src="/img/editIcon.svg" alt="edit first name"> --></label>
                <input type="text" placeholder="{{ $user->firstName }}" name="firstName" id="fname" value="{{ $user->firstName }}" class="showInput">
                <label for="lname">{{ Lang::get("content.TLastName") }}<!-- <img src="/img/editIcon.svg" alt="edit last name"> --></label>
                <input type="text" placeholder="{{ $user->lastName }}" name="lastName" id="lname" value="{{ $user->lastName }}" class="showInput">
                <label for="phone">{{ Lang::get("content.TphoneNumber") }}<!-- <img src="/img/editIcon.svg" alt="edit phone number"> --></label>
                <input type="tel" placeholder="1 (514) 555-4444" name="phone" id="phone" value="{{ $user->phone }}" class="showInput">   
                <label for="email">{{ Lang::get("content.Temail") }}<!-- <img src="/img/editIcon.svg" alt="edit email"> --></label>
                <input type="email" placeholder="{{ $user->email }}" name="email" id="email" value="{{ $user->email }}" class="showInput">

               <!--  When Changing the password the user is first prompt to reenter current password, once the password is verified the user must enter a new password and then verify it.  -->
                <label for="password">{{ Lang::get("content.Tpassword") }}<!-- <img src="/img/editIcon.svg" alt="edit password"> --></label>
                <input type="password" placeholder="******" name="password" id="password" class="showInput">
            </div>
        </div>
        <div class="saveButton">  
            <button>{{ Lang::get("content.TSave") }}</button>  
        </div> 

        </form>
    </div>




</section>
@endsection

@section('scripts')


<script type="text/javascript">

$(document).ready(function(){
    $(".menu_profile").addClass("selected");
});

$(document).ready(function(){ $("#m_profile").addClass('active'); });

$("#editImg").click(function showInput(){
    $(this).hide();
    $(".profileImg1").css("position", "initial");
});

$("#editImgLogo").click(function showInput(){
    $(this).hide();
    $(".profileImgLogo").css("position", "initial");
});


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
                    //location.reload();
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