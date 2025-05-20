@php
    use App\Http\Libraries\Helper;
@endphp
@extends('layouts.frontEnd')
@section('content')
    <main class="accountPages">
        <div class="background"></div>
        <div class="wrapper accountRoot">
            <div class="topBlock">
                <h1>{{ __("content.frontEnd/forgotSomething") }} </h1>
                <h3>{{ __("content.frontEnd/forgotInstructions") }}</h3>
            </div>
            <div class="accountAction_container">
                {{ Form::open(array('route' => 'password.request',"class"=>"formholder","id"=>"password_reset")) }}
                <label for="email">{{ __("content.email") }}</label>
                <input type="text" placeholder="{{ __("content.email") }}" id="email" name="email" value="{{request()->old("email")}}" required/>
                <a href="javascript:void(0)" onclick="submitForm()" class="submit">{{ __("content.remind/get") }}</a>
                <a href="{{ __("routes./login") }}" class="forgot_password">{{ __("content.remind/back") }} </a>
{{--                <a href="{{ __('routes./login/facebook') }}" class="facebook">{{ __("content.frontEnd/facebooklogin") }}</a>--}}
                @if(!empty(Helper::getDeviceTypeCookie()) && Helper::getDeviceTypeCookie() == 'ios')
                    <a href="javascript:void(0);" onclick="console.log('LOGIN_WITH_APPLE=true');console.log('USER_TYPE=trainer')" class="login-with-apple-btn" style="margin-top: 15px;">Log In with Apple</a>
                @endif

                <p id="loaderGoogleButton"  style="margin:auto;padding: 6px;height: auto;width: 100%;align-items: center;justify-content: center;display: none">
                    <img src="{{ asset('assets/img/tw-gif.gif') }}" style="width: 40px;">
                </p>
                <a href="javascript:void(0);" onclick="redirectToGoogleLogin(this);" class="login-with-google-btn" style="margin-top: 15px;">Log In with Google</a>
                {{ Form::close() }}
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script type="text/javascript">
        function redirectToGoogleLogin(element) {
            $("#loaderGoogleButton").show().css('display', 'flex');
            window.location.href = "{{ route('auth.google',['role' => 'Trainer']) }}";
        }

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

