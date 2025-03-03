@extends('layouts.frontEnd')
@php
    use App\Http\Libraries\Helper;
@endphp
@section("header")
    {!! Helper::seo("login") !!}
@endsection
@section('content')
    <!-- Main -->
    <main class="accountPages">
        <div class="background"></div>
        <div class="wrapper accountRoot">
            <div class="topBlock">
                <h1>{{ __("content.frontEnd/welcomeBack") }} </h1>
                <h3>{{ __("content.frontEnd/thankyouTW") }}</h3>
            </div>
            <div class="accountAction_container">
                <form action="{{ __("routes./login") }}" method="post" id="login_form">
                    @csrf
                    <a href="{{ __('routes./login/facebook') }}" class="facebook">{{ __("content.frontEnd/facebooklogin") }}</a>
                    <a href="{{ route('auth.google',['agent' => \Jenssegers\Agent\Facades\Agent::deviceType()]) }}" class="login-with-google-btn" style="margin-top: 15px">Log In with Google</a>
                    <div class="accountOr">
                        <hr><span>or</span><hr>
                    </div>
                    <label for="email">{{ __("content.email") }}</label>
                    <input type="text" placeholder="{{ __('content.email') }}" value="{{ request()->old('email') }}" required name="email" id="email"/>
                    <label for="password">{{ __("content.password") }}</label>
                    <input placeholder="{{ __('content.password') }}" required name="password" type="password" id="password"/>
                    <a href="javascript:void(0)" onclick="submitForm()" class="submit">{{ __("content.Login") }}</a>
                    <a href="{{ __("/password/reset") }}" class="forgot_password">{{ __("content.forgot") }}</a>
                </form>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script type="text/javascript">
        function submitForm() {
            var valid = true;
            var password = $("#password").val();
            var email = $("#email").val();
            if (!validateEmail(email)) {
                valid = false;
                $("#email").addClass('error');
                $("#email_icon").css("color", "rgba(255,0,0,0.5)");
            } else {
                $("#email").removeClass('error');
                $("#email_icon").css("color", "#38AFDF");
            }
            if (password.length < 1) {
                valid = false;
                $("#password").addClass('error');
                $("#password_icon").css("color", "rgba(255,0,0,0.5)");
            } else {
                $("#password").removeClass('error');
                $("#password_icon").css("color", "#38AFDF");
            }
            if (valid) {
                document.getElementById('login_form').submit();
            }
        }

        function validateEmail(email) {
            var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            return re.test(email);
        }

        $(document).keypress(function (e) {
            if (e.which == 13) {
                submitForm();
            }
        });
    </script>
@endsection
