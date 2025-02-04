@extends('layouts.trainer')

@section("header")
    {{ Helper::seo("employeeManagement") }}
@endsection


@section('content')

 <!-- content area starts here -->


<!-- Widget Window -->
<div class="wrapper">
    <div class="widget employee">

<!--    Widget Header    -->

        <div class="employee--header">
            <div>
                <h1>{{ Lang::get("content.team/h1") }}</h1>
                <p>{{ Lang::get("content.team/h3") }}</p>
            </div>
                <button id="addUser">
                    <svg width="9" height="9" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                        <title>
                            Add
                        </title>
                        <g stroke-linecap="square" stroke="#fff" fill="none" fill-rule="evenodd">
                            <path d="M.5 4.5h8M4.5 8.5v-8"/>
                        </g>
                    </svg>
                    {{ Lang::get("content.team/add") }}
                </button>
        </div>

<!--  Widget Content  -->
        <table id="employee_table" class="employee_table">

        <caption>{{ Lang::get("content.team/caption") }}</caption>
<!-- Table Header -->
            <thead>
                <tr class="header_row">
                    <th scope="col" class="user_id">{{ Lang::get("content.team/headerUser") }}</th>
                    <th scope="col" class="user_role">{{ Lang::get("content.team/headerRole") }}</th>
                    <th scope="col" class="user_status">{{ Lang::get("content.team/headerStatus") }}</th>
                    <th scope="col" class="user_options">{{ Lang::get("content.team/headerOptions") }}</th>
                    <th scope="col" class="user_actions">{{ Lang::get("content.team/headerActions") }}</th>

                </tr>
            </thead>
            <tbody>
<!-- Table Row 1 (owner of account) -->
            @foreach($userGroups as $userGroup)
                @if($userGroup->user)
                <tr class="membership_row">

                    <th scope="row" class="user_id">

                        <div class="user_id--content">
                            @if($userGroup->user->thumb != "")
<!-- If we have the image-->    <img src="{{ Helper::image($userGroup->user->thumb) }}" alt="profile image">
                            @else
                                <div class="user_icon"><p>{{ $userGroup->user->getInitials() }}</p></div>
                            @endif
                            <div class="user_name">
                                <h5>{{ $userGroup->user->getCompleteName() }}</h5>
                                <p>{{ $userGroup->user->email }}</p>
                            </div>
                        </div>
                    </th>

                    <td class="user_role">
                    @if($userGroup->role == "Owner")
                            <div class="user_role--owner">
                            {{ Lang::get("content.team/roleOwner") }}
                            </div>
                    @else

                        <div class="user_role--content">
                            <div class="user_role--content--select">
                                <select onChange="changeRole({{ $userGroup->user->id }},this.value)" {{ ($groupUser->role != "Owner" and $userGroup->role == "Owner") ? "disabled='disabled'" : "" }}>

                                    @if($groupUser->role == "Owner")
                                    <option value="Owner" {{ ($userGroup->role == "Owner") ? "selected='selected'" : "" }}>{{ Lang::get("content.team/roleOwner") }}</option> <!-- Cannot be selected and is only attributed to the owner of this account -->
                                    @endif

                                    <option value="Admin" {{ ($userGroup->role == "Admin") ? "selected='selected'" : "" }}>{{ Lang::get("content.team/roleAdmin") }}</option> 
                                    <option value="Member" {{ ($userGroup->role == "Member") ? "selected='selected'" : "" }}>{{ Lang::get("content.team/roleTrainer") }}</option>
                                </select>
                            </div>
                        </div>

                    @endif

                    </td>
                    <td class="user_status">
                        @if($userGroup->user->activated == "")
                        <h5>{{ Lang::get("content.team/statusPending") }}</h5>
                        @else
                        <h5>{{ Lang::get("content.team/statusActive") }}</h5>
                        @endif
                    </td>

                    <td class="user_options">
                    @if($userGroup->user->activated == "")
                        <button>
                        <a href="javascript:void(0);" onClick="resendInvite({{ $userGroup->user->id }},$(this))">{{ Lang::get("content.team/btnResend") }}</a></button>
                        
                    @else
                        @if($userGroup->user->id != Auth::user()->id and $userGroup->role != "Owner")
                            <button><a href="javascript:void(0)" onClick="removeAccessUser({{ $userGroup->user->id }},$(this))">{{ Lang::get("content.team/btnRemove") }}</a></button>
                        @endif
                    @endif
                        
                    </td>
                    <td class="user_actions">
                    @if($userGroup->user->id != Auth::user()->id)
                        <button><a href="{{ Lang::get("routes./Trainer/EmployeeManagement/Personify") }}/{{ $userGroup->user->id }}">{{ Lang::get("content.team/btnAccess") }}</a></button>
                    @endif

                    </td>

                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
   
        <!-- End Table -->

<!-- End of Widget Window -->
    </div>
    <div class="not_on_small_device">
        <div class="widget">
            <h1>Employee management is not available on <br> devices smaller then 700px.</h1>
            <h1>Use a tablet or a computer</h1>
        </div>
    </div>


</div>




<div id="user_overlay" class="">
    <div class="emp_invite widget">
        <img id="exitLightbox" src="/img/exitLightbox.svg">
        <h1>{{ Lang::get("content.team/inviteh1") }}</h1>
        <p>{{ Lang::get("content.team/invitep") }}</p>
        <div class="emp_invite--wrapper">
           {{ Form::open(array('url' => Lang::get("routes./Trainer/EmployeeManagement/addEmployees"))); }}
           <div id="employee_invitation">
                <div class="emp_individual delete_employee_hover">
                    <div class="emp_info emp_fName">
                        <label for="firstName">{{ Lang::get("content.team/fname") }}</label>
                        <input type="text"  name="firstName[]" placeholder="{{ Lang::get("content.team/fnamep") }}" required>
                    </div>
                    <div class="emp_info emp_lName">
                        <label for="lastName">{{ Lang::get("content.team/lname") }}</label>
                        <input type="text"  name="lastName[]" placeholder="{{ Lang::get("content.team/lnamep") }}" required>
                    </div>
                    <div class="emp_info emp_email">
                        <label for="email">{{ Lang::get("content.team/email") }}</label>
                        <input type="email" name="email[]" placeholder="{{ Lang::get("content.team/emailp") }}" required>
                    </div>
                    <div class="emp_info emp_access">
                        <label for="role">{{ Lang::get("content.team/access") }}</label>
                        <select id="role" name="role[]" required>
                            <option value="" disabled selected>{{ Lang::get("content.team/role") }}<img src="/img/dropdownArrowGreen.svg"></option>
                            <option value="Admin">{{ Lang::get("content.team/roleAdmin") }}</option>
                            <option value="Member">{{ Lang::get("content.team/roleTrainer") }}</option>
                        </select>
                    </div>
                </div>


                <a href="javascript:void(0)" id="emp_individual_add">{{ Lang::get("content.team/addUserBtn") }}</a>

                <div id="errorMessageDiv">{{ Lang::get("content.team/errMessage") }}</div>

                <button type="submit" onCLick="lightBoxLoadingTwSpinner();">{{ Lang::get("content.team/sendInvite") }}</button>
            </div>
         {{Form::close() }}
        </div>

        <p>{{ Lang::get("content.team/inviteNote") }}</p>

    </div>
</div>






@endsection



@section('scripts')
<script>  // Data table plugin
$(document).ready( function () {
    //$('#employee_table').DataTable();
} );
</script>



<script>  // Error messages if not all fields used, if it Safari!! 
var ua = navigator.userAgent.toLowerCase(); 
  if (ua.indexOf('safari') != -1) { 
    if (ua.indexOf('chrome') > -1) {
      console.log("chrome") // Chrome
    } else {
        // If Safari
        var form = document.getElementById('employee_invitation');
        form.noValidate = true;
        form.addEventListener('submit', function(event) { // listen for form submitting
                if (!event.target.checkValidity()) {
                    event.preventDefault(); // dismiss the default functionality
                    document.getElementById('errorMessageDiv').style.display = 'block'; // error message
                }
            }, false); 
    }
  }
</script>


<script> // Adding employee and removing employee from the form. 
var nbEmployee = $("div.emp_individual").length;

//Clone the employee row and add another. 
$("#emp_individual_add").click(function() {
    $("#emp_individual_add").before($("#employee_invitation div.emp_individual:first-child").clone());
    $(".emp_individual").last().find("input[type='text']").val('');
    $(".emp_individual").last().find("input[type='email']").val('');
    nbEmployee = $("div.emp_individual").length;
    
    // If multiple row employee, adds the option to remove employees.
    if(nbEmployee == 2) {
        $(".emp_individual").append('<div class="delete_employee">X</div>');
        nbEmployee = $("div.emp_individual").length;
    };

        // Action of removing the employee row.   
        $(".delete_employee").click(function() {
        nbEmployee = $("div.emp_individual").length;
        if (nbEmployee > 1) {
            $(this).parent().remove();
            nbEmployee = $("div.emp_individual").length;
        };
        //Remove the option to remove an employee row if there is only one row left. 
        nbEmployee = $("div.emp_individual").length;
        if (nbEmployee == 1) {
            $(".delete_employee").remove();
            };
        });
});

$("#addUser").click(function() {
    $("#user_overlay").addClass("showOverlay");
});

// Hides the overlay if clicked on x. 
$("#exitLightbox").click(function() {
    $("#user_overlay").removeClass("showOverlay");
});


function resendInvite(user,object){
    //showLoader(object);
     showTopLoader();
     $.ajax(
        {
            url : "{{ Lang::get("routes./Trainer/EmployeeManagement/resendInvite") }}"+"/"+user,
            type: "GET",
            success:function(data, textStatus, jqXHR) 
            {
                successMessage(data);
                //hideLoader(object);
                hideTopLoader();
            },
            error: function(jqXHR, textStatus, errorThrown) 
            {
                errorMessage(jqXHR.responseText);
                //hideLoader(object);
                hideTopLoader();
                
                
            },
        });
}

function removeAccessUser(userId,object){
     //showLoader(object);
     showTopLoader();
     $.ajax(
        {
            url : "{{ Lang::get("routes./Trainer/EmployeeManagement/RemoveAccess") }}"+"/"+userId,
            type: "GET",
            success:function(data, textStatus, jqXHR) 
            {
                successMessage(data);
                $(object).closest("tr").slideUp();
                //hideLoader(object);
                hideTopLoader();
            },
            error: function(jqXHR, textStatus, errorThrown) 
            {
                errorMessage(jqXHR.responseText);
                //hideLoader(object);
                hideTopLoader();
                
                
            },
        });
}

function changeRole(userId,value){
    $.ajax(
        {
            url : "{{ Lang::get("routes./Trainer/EmployeeManagement/ChangeRole") }}",
            type: "POST",
            data: {user:userId,role:value},
            success:function(data, textStatus, jqXHR) 
            {
                successMessage(data);
            },
            error: function(jqXHR, textStatus, errorThrown) 
            {
                errorMessage(jqXHR.responseText);
            },
        });    
}

$(document).ready(function(){
    $(".menu_employee").addClass("selected");
});


</script>


@endsection