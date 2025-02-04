
@extends('layouts.whitelabels.'.strtolower(Config::get("app.whitelabel")).".frontEnd")

@section('content')


<!-- Main -->

<main class="accountPages">
    <div class="background"></div>
    <div class="wrapper accountRoot">
        <div class="topBlock">
            <h1>{{ Lang::get("content.whitelabelTrainerWelcome") }}</h1>
            <h3></h3>
        </div>
        <div class="accountAction_container">
            <form action="{{ Lang::get("routes./login") }}" method="post" id="login_form">
                <label for="email">{{ Lang::get("content.email") }}</label>
                <input type="text" placeholder="{{ Lang::get('content.email') }}" value="{{ Input::old('email') }}" required name="email" id="email" />
                <label for="password">{{ Lang::get("content.password") }}</label>
                <input placeholder="{{ Lang::get('content.password') }}" required name="password" type="password" id="password"/>
                <a href="javascript:void(0)" onclick="submitForm()" class="submit">{{ Lang::get("content.Login") }}</a>
                <a href="{{ Lang::get("/password/reset") }}" class="forgot_password">{{ Lang::get("content.forgot") }}</a>
                <a href="{{ Lang::get('routes./login/facebook') }}" class="facebook">{{ Lang::get("content.frontEnd/facebooklogin") }}</a>
            </form>
        </div>  
    </div>
</main>

@endsection

@section('scripts')
<script type="text/javascript">

function submitForm(){
    var valid = true;
    var password = $("#password").val();
    var email    = $("#email").val();
    if (!validateEmail(email)) {
      valid = false;
      $("#email").addClass('error');
      $("#email_icon").css("color","rgba(255,0,0,0.5)");
    } else {
      $("#email").removeClass('error');
      $("#email_icon").css("color","#38AFDF");
    }
    if(password.length < 1){
      valid = false;
      $("#password").addClass('error');
      $("#password_icon").css("color","rgba(255,0,0,0.5)");
    } else {
      $("#password").removeClass('error');
      $("#password_icon").css("color","#38AFDF");
    }
    if(valid){
      document.getElementById('login_form').submit();
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
