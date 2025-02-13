

@extends('layouts.frontEnd')

@section('content')

<main class="accountPages">
    <div class="background"></div>
    <div class="wrapper accountRoot">
        <div class="topBlock">
            <h1>{{ Lang::get("content.frontEnd/forgotSomething") }} </h1>
            <h3>{{ Lang::get("content.frontEnd/forgotInstructions") }}</h3>
        </div>
        <div class="accountAction_container">
          {{ Form::open(array('route' => 'password.request',"class"=>"formholder","id"=>"password_reset")) }}
            <label for="email">{{ Lang::get("content.email") }}</label>
            <input type="text" placeholder="{{ Lang::get("content.email") }}" id="email" name="email" value="{{Input::old("email")}}" required/>
            <a href="javascript:void(0)" onclick="submitForm()" class="submit">{{ Lang::get("content.remind/get") }}</a>
            <a href="{{ Lang::get("routes./login") }}" class="forgot_password">{{ Lang::get("content.remind/back") }} </a>
            <a href="{{ Lang::get('routes./login/facebook') }}" class="facebook">{{ Lang::get("content.frontEnd/facebooklogin") }}</a>
          {{ Form::close() }}
        </div>
    </div>
</main> 

@endsection

@section('scripts')
<script type="text/javascript">

function submitForm(){
    var valid = true;
    var email    = $("#email").val();
    if (!validateEmail(email)) {
      valid = false;
      $("#email").addClass('error');
      $("#email_icon").css("color","rgba(255,0,0,0.5)");
    } else {
      $("#email").removeClass('error');
      $("#email_icon").css("color","#38AFDF");
    }
    if(valid){
      document.getElementById('password_reset').submit();
    }
}

function validateEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}


$(document).keypress(function(e) {
    if(e.which == 13) {
        submitForm();
    }
});
</script>
@endsection

