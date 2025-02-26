@extends('layouts.frontEnd')
@section('content')
    <!-- Header -->
    <main class="accountPages">
        <div class="background"></div>
        <div class="wrapper accountRoot">
            <div class="topBlock">
                <h1>{{ Lang::get("content.reset/title") }}</h1>
                <h3>{{ Lang::get("content.frontEnd/thankyouTW") }}</h3>
            </div>
            <div class="accountAction_container">
                {{ Form::open(array('route' => array('password.update', $token),"class"=>"formholder form-line-height", "id"=>"password_reset")) }}
                <label for="email">{{ Lang::get("content.reset/youremail") }}</label>{{ Form::hidden('token', $token) }}
                <input type="text" placeholder="{{ Lang::get("content.reset/youremail") }}" value="{{ $email or request()->old('email') }}" required name="email" id="email" />

                <label for="password">{{ Lang::get("content.reset/yourpassword") }}</label>
                <input placeholder="{{ Lang::get("content.reset/password") }}" required name="password" type="password" id="password" value="{{request()->old("password")}}"/>

                <label for="password_confirmation">{{ Lang::get("content.reset/yourpassword") }}</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="{{ Lang::get("content.reset/placeholder") }}" value="{{request()->old("password_confirmation")}}" />

                <a href="javascript:void(0)" onclick="submitForm()" class="submit">{{ Lang::get("content.reset/reset") }}</a>
                <a href="/" class="forgot_password">{{ Lang::get("content.reset/back") }}</a>

                {{ Form::close() }}
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script type="text/javascript">
        function submitForm(){
            var valid = true;
            var password = $("#password").val();
            var password_confirmation = $("#password_confirmation").val();
            var email    = $("#email").val();
            if (!validateEmail(email)) {
                valid = false;
                $("#email").addClass('error');
                $("#email_icon").css("color","rgba(255,0,0,0.5)");
            } else {
                $("#email").removeClass('error');
                $("#email_icon").css("color","#38AFDF");
            }
            if((password_confirmation !== password) || (password.length < 1) || (password_confirmation.length < 1)){
                valid = false;
                $("#password").addClass('error');
                $("#password_icon").css("color","rgba(255,0,0,0.5)");
                $("#password_confirmation").addClass('error');
                $("#password_confirmation_icon").css("color","rgba(255,0,0,0.5)");
                errorMessage("The passwords do not match.");
            } else {
                $("#password_confirmation").removeClass('error');
                $("#password_confirmation_icon").css("color","#38AFDF");
                $("#password").removeClass('error');
                $("#password_icon").css("color","#38AFDF");
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


